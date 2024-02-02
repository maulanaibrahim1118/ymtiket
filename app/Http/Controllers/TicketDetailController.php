<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;
use App\Comment;
use App\Agent;
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
        $ticket         = Ticket::where('id', $id)->first();
        $ticket_details = Ticket_detail::where('ticket_id', $id)->get();
        $comments       = Comment::where('ticket_id', $id)->get();
        $checkComment   = Comment::where('ticket_id', $id)->count();
        $progress_tickets   = Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get();

        return view('contents.ticket_detail.index', [
            "title"             => "Ticket Detail",
            "path"              => "Ticket",
            "path2"             => "Detail",
            "ticket"            => $ticket,
            "comments"          => $comments,
            "checkComment"      => $checkComment,
            "progress_tickets"  => $progress_tickets,
            "ticket_details"    => $ticket_details
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

        return view('contents.ticket_detail.create', [
            "title"             => "Proses Ticket",
            "path"              => "Ticket",
            "path2"             => "Proses",
            "ticket"            => $ticket,
            "category_tickets"  => Category_ticket::all(),
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
            'category_ticket_id'        => 'required',
            'sub_category_ticket_id'    => 'required',
            'biaya'                     => 'max:20',
        ],
        // Create custom notification for the validation request
        [
            'category_ticket_id.required'       => 'Kategori Ticket harus dipilih!',
            'sub_category_ticket_id.required'   => 'Sub Kategori Ticket harus dipilih!',
            'biaya.max'                         => 'Ketik maksimal 20 digit!',
        ]);

        if($request['biaya'] == NULL){
            $biaya = 0;
        }else{
            $biaya = str_replace(',','',$request['biaya']);
        }
        $pending_at = '-';

        // Saving data to sub_category_ticket table
        $ticket_detail                          = new Ticket_detail;
        $ticket_detail->ticket_id               = $request['ticket_id'];
        $ticket_detail->sub_category_ticket_id  = $request['sub_category_ticket_id'];
        $ticket_detail->agent_id                = $request['agent_id'];
        $ticket_detail->pending_at              = $pending_at;
        $ticket_detail->resolved_time           = 0;
        $ticket_detail->pending_time            = 0;
        $ticket_detail->biaya                   = $biaya;
        $ticket_detail->note                    = $request['note'];
        $ticket_detail->updated_by              = $request['updated_by'];
        $ticket_detail->save();

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $request['ticket_id'];
        $progress_ticket->tindakan      = "Ticket di proses oleh";
        $progress_ticket->lama_tindakan = 0;
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        // Updating data to ticket table
        Ticket::where('id', $request['ticket_id'])->update([
            'status'    => $request['status']
        ]);

        // Redirect to the Category Asset view if create data succeded
        $no_ticket = $request['no_ticket'];
        return redirect('/ticket-details'.'/'.$request['url'])->with('success', $no_ticket.' sedang diproses!');
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
        $id     = decrypt($id);
        $ticket = Ticket::where('id', $id)->first();
        $ticket_detail  = Ticket_detail::where('ticket_id', $id)->first();

        return view('contents.ticket_detail.edit', [
            "title"                 => "Edit Detail Tindakan",
            "path"                  => "Ticket",
            "path2"                 => "Edit",
            "category_tickets"      => Category_ticket::all(),
            "sub_category_tickets"  => Sub_category_ticket::all(),
            "ticket"                => $ticket,
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
        // Validating data request
        $validatedData = $request->validate([
            'category_ticket_id'        => 'required',
            'sub_category_ticket_id'    => 'required',
            'biaya'                     => 'max:20',
        ],
        // Create custom notification for the validation request
        [
            'category_ticket_id.required'       => 'Kategori Ticket harus dipilih!',
            'sub_category_ticket_id.required'   => 'Sub Kategori Ticket harus dipilih!',
            'biaya.max'                         => 'Ketik maksimal 20 digit!',
        ]);

        if($request['biaya'] == NULL){
            $biaya = 0;
        }else{
            $biaya = str_replace(',','',$request['biaya']);
        }
        
        // Updating data to ticket table
        Ticket_detail::where('ticket_id', $request['ticket_id'])->update([
            'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
            'biaya'                     => $biaya,
            'note'                      => $request['note'],
        ]);

        // Redirect to the Category Asset view if create data succeded
        $no_ticket = $request['no_ticket'];
        return redirect('/ticket-details'.'/'.$request['url'])->with('success', 'Detail tindakan ticket '.$no_ticket.' telah diedit!');
    }

    public function pending($id = 0, Request $request)
    {
        $pending_at = date('d-m-Y H:i:s');
        $status     = "pending";
        
        // Updating data to ticket table
        Ticket_detail::where('ticket_id', $id)->update([
            'pending_at'    => $pending_at,
        ]);

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'status'    => $status
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di pending oleh";
        $progress_ticket->lama_tindakan = 0;
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        return back()->with('success', 'Ticket berhasil di pending!');
    }

    public function reProcess($id = 0, Request $request)
    {
        $getTicketDetail    = Ticket_detail::where('ticket_id', $id)->first();
        $now                = date('d-m-Y H:i:s');
        $reProcess_at       = Carbon::parse($now);
        $pending_at1        = Carbon::parse($getTicketDetail->pending_at);
        $pending_at2        = '-';
        $status             = "onprocess";

        // Mencari lama nya waktu ticket di pending
        $pending_time = $pending_at1->diffInMinutes($reProcess_at);
        
        // Updating data to ticket table
        Ticket_detail::where('ticket_id', $id)->update([
            'pending_at'    => $pending_at2,
            'pending_time'  => $pending_time
        ]);

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'status'    => $status
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di proses ulang oleh";
        $progress_ticket->lama_tindakan = $pending_time;
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        return back()->with('success', 'Ticket berhasil di proses ulang!');
    }

    public function assign($id = 0, Request $request)
    {
        $locationId = $request->location_id;

        // Menghitung jumlah agent yang sedang tidak mengerjakan ticket (idle)
        $countIdle  = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->count();
        if($countIdle == 0){ // Jika tidak ada Agent yang sedang menganggur
            return back()->with('assignError', 'Semua Agent sedang sibuk!');
        }else{
            // Get data Agent yang dipilih selanjutnya untuk menerima ticket yang di assign
            $getAgent   = Agent::where([['location_id', $locationId],['status', 'idle']])->orderBy('total_ticket', 'asc')->first();
            $agentId    = $getAgent->id;
            $agentName  = $getAgent->nama_agent;

            $getTicketDetail    = Ticket_detail::where('ticket_id', $id)->first();
            $now                = date('Y-m-d H:i:s');
            $status             = "pending";
            
            // Updating data to ticket table
            Ticket_detail::where('ticket_id', $id)->update([
                'agent_id'      => $agentId,
                'pending_at'    => $now,
            ]);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'status'    => $status,
                'agent_id'  => $agentId
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di pending oleh";
            $progress_ticket->lama_tindakan = 0;
            $progress_ticket->updated_by    = "sistem";
            $progress_ticket->save();

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di terima oleh";
            $progress_ticket->lama_tindakan = 0;
            $progress_ticket->updated_by    = $agentName;
            $progress_ticket->save();

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di assign oleh";
            $progress_ticket->lama_tindakan = 0;
            $progress_ticket->updated_by    = $request['updated_by'];
            $progress_ticket->save();

            return back()->with('success', 'Ticket berhasil di assign ke '.$agentName);
        }
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
