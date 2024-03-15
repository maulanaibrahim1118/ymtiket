<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;
use App\Ticket_approval;
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

        $ticket     = Ticket::where('id', $id)->first();
        $agentId    = $ticket->agent_id;
        $ticketArea = $ticket->ticket_area;
        $getAgent   = Agent::where('id', $agentId)->first();
        $locationId = $getAgent->location_id;

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
            if($ticketArea == "ho"){
                $agents = Agent::where([['location_id', $locationId],['sub_divisi', 'hardware maintenance'],['status', 'present']])
                                ->orWhere([['location_id', $locationId],['pic_ticket', 'ho'],['status', 'present']])
                                ->whereNotIn('id', [$agentId])
                                ->get();
            }else{
                $agents = Agent::where([['location_id', $locationId],['sub_divisi', 'hardware maintenance'],['status', 'present']])
                                ->orWhere([['location_id', $locationId],['pic_ticket', 'store'],['status', 'present']])
                                ->whereNotIn('id', [$agentId])
                                ->get();
            }
        }else{
            $agents = Agent::where([['location_id', $locationId],['id', $sdId]])->get();
        }

        $ticketApproval = Ticket_approval::where('ticket_id', $id)->first();
        if($ticketApproval == NULL)
        {
            $ticketApproval = Ticket_approval::latest()->first();
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
            "ticket_approval"   => $ticketApproval,
            "countDetail"       => Ticket_detail::where('ticket_id', $id)->count(),
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
        $ticket = Ticket::where('id', $id)->first();
        $now    = date('d-m-Y H:i:s');
        $types  = ["kendala", "permintaan"];

        // Mencari extension file
        $ext = substr($ticket->file, -4);

        return view('contents.ticket_detail.create', [
            "title"                 => "Tangani Ticket",
            "path"                  => "Ticket",
            "path2"                 => "Tangani",
            "ticket"                => $ticket,
            'now'                   => $now,
            'types'                 => $types,
            'ext'                   => $ext,
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
        /** 
         * Cek status ticket (jika onprocess atau pending)
         * Menghindari perubahan setelah agent memproses ticket lalu klik kembali (ke form tangani) di browser
         */
        $ticket = Ticket::where('id', $request['ticket_id'])->first();
        $statusTicket = $ticket->status;
        $countDetail = Ticket_detail::where([['ticket_id', $request['ticket_id']],['agent_id', $request['agent_id']],['status', 'onprocess']])->count();
        if($statusTicket == "pending" || $countDetail == 1){
            return redirect('/ticket-details'.'/'.$request['url'])->with('error', 'Ticket sudah anda tangani sebelumnya!');
        }

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

        $updatedBy  = $request['updated_by'];

        // Saving data to ticket_detail table
        $ticket_detail                          = new Ticket_detail;
        $ticket_detail->ticket_id               = $request['ticket_id'];
        $ticket_detail->jenis_ticket            = $request['jenis_ticket'];
        $ticket_detail->sub_category_ticket_id  = $request['sub_category_ticket_id'];
        $ticket_detail->agent_id                = $request['agent_id'];
        $ticket_detail->process_at              = $request['process_at'];
        $ticket_detail->pending_at              = "-";
        $ticket_detail->biaya                   = $biaya;
        $ticket_detail->note                    = $request['note'];
        $ticket_detail->status                  = "onprocess";
        $ticket_detail->updated_by              = $updatedBy;
        $ticket_detail->save();

        // Jika penanganan memerlukan biaya
        if($biaya != 0){
            $now = date('d-m-Y H:i:s');

            // Updating data to ticket table
            Ticket::where('id', $request['ticket_id'])->update([
                'status' => "pending",
                'need_approval' => "ya",
                'pending_at' => $now
            ]);

            Ticket_detail::where([['ticket_id', $request['ticket_id']],['agent_id', $request['agent_id']]])->update([
                'status' => "pending",
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $request['ticket_id'];
            $progress_ticket->tindakan      = "Ticket di pending oleh sistem (Alasan: memerlukan persetujuan biaya)";
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = "pending";
            $progress_ticket->updated_by    = "Sistem";
            $progress_ticket->save();

            $agent = Agent::where('id', $request['agent_id'])->first();
            // Saving data to ticket approval table
            $ticket_approval                = new Ticket_approval;
            $ticket_approval->ticket_id     = $request['ticket_id'];
            $ticket_approval->status        = "null";
            $ticket_approval->updated_by    = $agent->nama_agent;
            $ticket_approval->save();
        }

        // Redirect to the Category Asset view if create data succeded
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
        $ticket_detail  = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
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
        
        $getDetail = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->latest()->first();
        $detail_id = $getDetail->id;

        // Updating data to ticket detail table
        Ticket_detail::where('id', $detail_id)->update([
            'jenis_ticket'              => $request['jenis_ticket'],
            'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
            'biaya'                     => $biaya,
            'note'                      => $request['note'],
        ]);

        $ticket = Ticket::where('id', $ticketId)->first();
        $approved = $ticket->approved;

        if($biaya != 0 AND $approved == "null"){
            $now = date('d-m-Y H:i:s');

            // Updating data to ticket table
            Ticket::where('id', $ticketId)->update([
                'status' => "pending",
                'need_approval' => "ya",
                'pending_at' => $now
            ]);

            Ticket_detail::where('id', $detail_id)->update([
                'status' => "pending",
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket di pending oleh sistem (Alasan: Memerlukan persetujuan biaya)";
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = "pending";
            $progress_ticket->updated_by    = "Sistem";
            $progress_ticket->save();

            $agent = Agent::where('id', $agentId)->first();
            // Saving data to ticket approval table
            $ticket_approval                = new Ticket_approval;
            $ticket_approval->ticket_id     = $ticketId;
            $ticket_approval->status        = "null";
            $ticket_approval->updated_by    = $agent->nama_agent;
            $ticket_approval->save();
        }

        // Redirect to the Category Asset view if create data succeded
        $no_ticket = $request['no_ticket'];
        return redirect('/ticket-details'.'/'.$request['url'])->with('success', 'Detail tindakan ticket '.$no_ticket.' telah diedit!');
    }

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
