<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;
use App\Comment;
use App\Agent;
use App\User;
use App\Progress_ticket;
use App\Category_ticket;
use App\Sub_category_ticket;

class TicketDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $id = decrypt($id);
        $ticket             = Ticket::where('id', $id)->first();
        $agentId            = $ticket->agent_id;
        $getAgent           = Agent::where('id', $agentId)->first();
        $locationId         = $getAgent->location_id;

        // Cek apakah dia agent atau service desk
        $nik        = $getAgent->nik;
        $getUser    = User::where('nik', $nik)->first();
        $role       = $getUser->role;

        // Mendapatkan id service desk
        $getUserSD  = User::where([['location_id', $locationId],['role', 'service desk']])->first();
        $nikSD      = $getUserSD->nik;
        $getAgentSD = Agent::where('nik', $nikSD)->first();
        $sdId       = $getAgentSD->id;

        // Mencari extension file
        $ext = substr($ticket->file, -4);

        if($role == "service desk"){
            $agents = Agent::where('location_id', $locationId)->whereNotIn('id', [$agentId])->get();
        }else{
            $agents = Agent::where([['location_id', $locationId],['id', $sdId]])->get();
        }

        return view('contents.ticket_detail.index', [
            "title"             => "Ticket Detail",
            "path"              => "Ticket",
            "path2"             => "Detail",
            "ticket"            => $ticket,
            "comments"          => Comment::where('ticket_id', $id)->get(),
            "checkComment"      => Comment::where('ticket_id', $id)->count(),
            "progress_tickets"  => Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get(),
            "ticket_details"    => Ticket_detail::where('ticket_id', $id)->get(),
            "agents"            => $agents,
            "ext"               => $ext
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = 0)
    {
        $id     = decrypt($id);
        $now    = date('d-m-Y H:i:s');
        $types  = ["kendala", "permintaan"];

        return view('contents.ticket_detail.create', [
            "title"                 => "Tangani Ticket",
            "path"                  => "Ticket",
            "path2"                 => "Tangani",
            "ticket"                => Ticket::where('id', $id)->first(),
            'now'                   => $now,
            'types'                 => $types,
            "category_tickets"      => Category_ticket::all(),
            "sub_category_tickets"  => Sub_category_ticket::all()
        ]);
    }

    public function getSubCategoryTicket($id = 0)
    {
        $data = Sub_category_ticket::where('category_ticket_id', $id)->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validating data request
        $validatedData = $request->validate([
            'jenis_ticket'              => 'required',
            'category_ticket_id'        => 'required',
            'sub_category_ticket_id'    => 'required',
            'biaya'                     => 'max:20',
            'note'                      => 'required|min:10',
        ],
        // Create custom notification for the validation request
        [
            'jenis_ticket.required'             => 'Jenis Ticket harus dipilih!',
            'category_ticket_id.required'       => 'Kategori Ticket harus dipilih!',
            'sub_category_ticket_id.required'   => 'Sub Kategori Ticket harus dipilih!',
            'biaya.max'                         => 'Ketik maksimal 20 digit!',
            'note.required'                     => 'Saran Tindakan harus diisi!',
            'note.min'                          => 'Ketik minimal 10 karakter!',
        ]);

        if($request['biaya'] == NULL){
            $biaya = 0;
        }else{
            $biaya = str_replace(',','',$request['biaya']);
        }

        $pending_at = '-';
        $updatedBy  = $request['updated_by'];

        // Saving data to ticket_detail table
        $ticket_detail                          = new Ticket_detail;
        $ticket_detail->ticket_id               = $request['ticket_id'];
        $ticket_detail->jenis_ticket            = $request['jenis_ticket'];
        $ticket_detail->sub_category_ticket_id  = $request['sub_category_ticket_id'];
        $ticket_detail->agent_id                = $request['agent_id'];
        $ticket_detail->process_at              = $request['process_at'];
        $ticket_detail->pending_at              = $pending_at;
        $ticket_detail->biaya                   = $biaya;
        $ticket_detail->note                    = $request['note'];
        $ticket_detail->updated_by              = $request['updated_by'];
        $ticket_detail->save();

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $request['ticket_id'];
        $progress_ticket->tindakan      = "Ticket di proses oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $request['process_at'];
        $progress_ticket->status        = "onprocess";
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        // Updating data to ticket table
        Ticket::where('id', $request['ticket_id'])->update([
            'status'    => $request['status']
        ]);

        // Redirect to the Category Asset view if create data succeded
        $no_ticket = $request['no_ticket'];
        return redirect('/ticket-details'.'/'.$request['url'])->with('success', 'Data telah disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = 0)
    {
        $id             = decrypt($id);
        $ticket         = Ticket::where('id', $id)->first();
        $agentId        = $ticket->agent_id;
        $ticket_detail  = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->first();
        $subCategoryId  = $ticket_detail->sub_category_ticket_id;
        $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
        $categoryId     = $subCategory->category_ticket_id;
        $types          = ["kendala", "permintaan"];

        return view('contents.ticket_detail.edit', [
            "title"                 => "Edit Detail Tindakan",
            "path"                  => "Ticket",
            "path2"                 => "Edit",
            "category_tickets"      => Category_ticket::all(),
            "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
            "ticket"                => $ticket,
            'types'                 => $types,
            "td"                    => $ticket_detail
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket_detail $ticket_detail)
    {
        $ticketId       = $request['ticket_id'];
        $agentId        = $request['agent_id'];
        $getDetail      = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->first();
        $processAt      = $getDetail->process_at;
        $updatedBy      = $request['updated_by'];

        // Validating data request
        $validatedData = $request->validate([
            'jenis_ticket'              => 'required',
            'category_ticket_id'        => 'required',
            'sub_category_ticket_id'    => 'required',
            'biaya'                     => 'max:20',
            'note'                      => 'required|min:10'
        ],
        // Create custom notification for the validation request
        [
            'jenis_ticket.required'             => 'Jenis Ticket harus dipilih!',
            'category_ticket_id.required'       => 'Kategori Ticket harus dipilih!',
            'sub_category_ticket_id.required'   => 'Sub Kategori Ticket harus dipilih!',
            'biaya.max'                         => 'Ketik maksimal 20 digit!',
            'note.required'                     => 'Saran Tindakan harus diisi!',
            'note.min'                          => 'Ketik minimal 10 karakter!'
        ]);

        if($request['biaya'] == NULL){
            $biaya = 0;
        }else{
            $biaya = str_replace(',','',$request['biaya']);
        }
        
        if($processAt == NULL) {
            // Updating data to ticket detail table
            Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->update([
                'jenis_ticket'              => $request['jenis_ticket'],
                'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
                'biaya'                     => $biaya,
                'process_at'                => $request['process_at'],
                'note'                      => $request['note'],
                'status'                    => "onprocess",
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket di proses oleh ".ucwords($updatedBy);
            $progress_ticket->process_at    = $request['process_at'];
            $progress_ticket->status        = "onprocess";
            $progress_ticket->updated_by    = $request['updated_by'];
            $progress_ticket->save();

            // Redirect to the Category Asset view if create data succeded
            $no_ticket = $request['no_ticket'];
            return redirect('/ticket-details'.'/'.$request['url'])->with('success', $no_ticket.' sedang diproses!');
        }else{
            // Updating data to ticket detail table
            Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->update([
                'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
                'biaya'                     => $biaya,
                'note'                      => $request['note'],
            ]);

            // Redirect to the Category Asset view if create data succeded
            $no_ticket = $request['no_ticket'];
            return redirect('/ticket-details'.'/'.$request['url'])->with('success', 'Detail tindakan ticket '.$no_ticket.' telah diedit!');
        }
    }

    // // Assign jika kondisi belum pernah diproses
    // public function assign1($id = 0, Request $request)
    // {
    //     $locationId = $request->location_id;
    //     $namaSD = $request->updated_by;

    //     // Menghitung jumlah agent yang sedang tidak mengerjakan ticket (idle)
    //     $countIdle  = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->count();
    //     if($countIdle == 0){ // Jika tidak ada Agent yang sedang menganggur
    //         return back()->with('assignError', 'Semua Agent sedang sibuk!');
    //     }else{
    //         // Get Service Desk, untuk mengurangi total ticket di tabel agent
    //         $getSD              = Agent::where('nama_agent', $namaSD)->first();
    //         $ticketAssignSD     = $getSD->ticket_assigned;
    //         $ticket_assigned_SD = $ticketAssignSD+1;

    //         Agent::where('nama_agent', $namaSD)->update([
    //             'ticket_assigned'   => $ticket_assigned_SD,
    //             'status'            => 'working'
    //         ]);

    //         // Get data Agent yang dipilih selanjutnya untuk menerima ticket yang di assign
    //         $getAgent       = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->first();
    //         $agentId        = $getAgent->id;
    //         $agentName      = $getAgent->nama_agent;
    //         $totalTicket    = $getAgent->total_ticket;
    //         $total_ticket   = $totalTicket+1;

    //         $getTicketDetail    = Ticket_detail::where('ticket_id', $id)->first();
    //         $now                = date('Y-m-d H:i:s');
    //         $status             = "assigned";
            
    //         // Updating data to ticket table
    //         Ticket::where('id', $id)->update([
    //             'assigned'  => "ya",
    //             'agent_id'  => $agentId,
    //             'role'      => "agent"
    //         ]);

    //         Agent::where('id', $agentId)->update([
    //             'total_ticket'  => $total_ticket,
    //             'status'        => "working",
    //         ]);

    //         // Saving data to progress ticket table
    //         $progress_ticket                = new Progress_ticket;
    //         $progress_ticket->ticket_id     = $id;
    //         $progress_ticket->tindakan      = "Ticket di terima oleh";
    //         $progress_ticket->status        = $status;
    //         $progress_ticket->process_at    = $now;
    //         $progress_ticket->updated_by    = $agentName;
    //         $progress_ticket->save();

    //         // Saving data to progress ticket table
    //         $progress_ticket                = new Progress_ticket;
    //         $progress_ticket->ticket_id     = $id;
    //         $progress_ticket->tindakan      = "Ticket di assign oleh";
    //         $progress_ticket->status        = $status;
    //         $progress_ticket->process_at    = $now;
    //         $progress_ticket->updated_by    = $request['updated_by'];
    //         $progress_ticket->save();

    //         return back()->with('success', 'Ticket berhasil di assign ke '.$agentName);
    //     }
    // }

    // // Assign jika kondisi sudah pernah diproses
    // public function assign2($id = 0, Request $request)
    // {
    //     $locationId = $request->location_id;
    //     $now        = date('Y-m-d H:i:s');

    //     // Menghitung jumlah agent yang sedang tidak mengerjakan ticket (idle)
    //     $countIdle  = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->count();

    //     if($countIdle == 0){ // Jika tidak ada Agent yang sedang menganggur
    //         return back()->with('assignError', 'Semua Agent sedang sibuk!');
    //     }else{
    //         $nikAgent1  = $request['nik'];

    //         // Mendapatkan data agent yang pertama / yang meng assign
    //         $getAgent1          = Agent::where('nik', $nikAgent1)->first();
    //         $agentId1           = $getAgent1->id;
    //         $ticketAssigned1    = $getAgent1->ticket_assigned;
    //         $ticket_assigned1   = $ticketAssigned1+1;
    //         $assignedTime1      = $getAgent1->assigned_time;

    //         // Mendapatkan lama waktu penanganan ticket yang akhirnya di assign / tidak resolved oleh agent pertama
    //         $getTicketDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId1]])->first();
    //         $processAt          = $getTicketDetail->process_at;
    //         $pendingTime        = $getTicketDetail->pending_time;
    //         $processAt          = Carbon::parse($processAt);
    //         $now                = Carbon::parse($now);
    //         $runningTime        = $processAt->diffInSeconds($now);
    //         $processedTime      = $runningTime-$pendingTime;
    //         $assigned_time1     = $assignedTime1+$processedTime;

    //         // Updating data to ticket detail table (agent pertama)
    //         Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId1]])->update([
    //             'processed_time'    => $processedTime,
    //             'status'            => "assigned",
    //             'updated_by'        => $request['updated_by']
    //         ]);

    //         // Updating data to agent table (agent pertama)
    //         Agent::where('id', $agentId1)->update([
    //             'ticket_assigned'       => $ticket_assigned1,
    //             'total_assigned_time'   => $assigned_time1,
    //             'updated_by'            => $request['updated_by'],
    //             'status'                => "idle"
    //         ]);

    //         // Get data Agent yang dipilih selanjutnya untuk menerima ticket yang di assign
    //         $getAgent       = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->first();
    //         $agentId        = $getAgent->id;
    //         $agentName      = $getAgent->nama_agent;
            
    //         // Mendapatkan total tiket +1
    //         $totalTicket    = $getAgent->total_ticket;
    //         $total_ticket   = $totalTicket+1;

    //         // Mendapatkan total ticket yang di assign +1
    //         $ticketAssigned     = $getAgent->ticket_assigned;
    //         $ticket_assigned    = $ticketAssigned+1;

    //         $getTicketDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId1]])->first();
    //         $subCategoryId      = $getTicketDetail->sub_category_ticket_id;
    //         $biaya              = $getTicketDetail->biaya;
    //         $note               = $getTicketDetail->note;
    //         $pending            = "pending";
    //         $assign             = "assigned";
            
    //         // Updating data to ticket table
    //         Ticket::where('id', $id)->update([
    //             'status'        => $pending,
    //             'pending_at'    => $now,
    //             'assigned'      => "ya",
    //             'agent_id'      => $agentId,
    //             'updated_by'    => $request['updated_by'],
    //             'role'          => "agent"
    //         ]);

    //         Agent::where('id', $agentId)->update([
    //             'total_ticket'  => $total_ticket,
    //             'status'        => "working",
    //             'updated_by'    => $request['updated_by']
    //         ]);

    //         // Saving data to ticket detail table
    //         $ticket_detail                          = new Ticket_detail;
    //         $ticket_detail->ticket_id               = $id;
    //         $ticket_detail->sub_category_ticket_id  = $subCategoryId;
    //         $ticket_detail->agent_id                = $agentId;
    //         $ticket_detail->status                  = $pending;
    //         $ticket_detail->updated_by              = $request['updated_by'];
    //         $ticket_detail->save();

    //         // Saving data to progress ticket table
    //         $progress_ticket                = new Progress_ticket;
    //         $progress_ticket->ticket_id     = $id;
    //         $progress_ticket->tindakan      = "Ticket di pending oleh";
    //         $progress_ticket->status        = $pending;
    //         $progress_ticket->process_at    = $now;
    //         $progress_ticket->updated_by    = "sistem";
    //         $progress_ticket->save();

    //         // Saving data to progress ticket table
    //         $progress_ticket                = new Progress_ticket;
    //         $progress_ticket->ticket_id     = $id;
    //         $progress_ticket->tindakan      = "Ticket di terima oleh";
    //         $progress_ticket->status        = $assign;
    //         $progress_ticket->process_at    = $now;
    //         $progress_ticket->updated_by    = $agentName;
    //         $progress_ticket->save();

    //         // Saving data to progress ticket table
    //         $progress_ticket                = new Progress_ticket;
    //         $progress_ticket->ticket_id     = $id;
    //         $progress_ticket->tindakan      = "Ticket di assign oleh";
    //         $progress_ticket->status        = $assign;
    //         $progress_ticket->process_at    = $now;
    //         $progress_ticket->updated_by    = $request['updated_by'];
    //         $progress_ticket->save();

    //         return back()->with('success', 'Ticket berhasil di assign ke '.$agentName);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
