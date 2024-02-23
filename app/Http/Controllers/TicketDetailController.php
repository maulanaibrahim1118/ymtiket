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
        $ticket_detail->updated_by              = $updatedBy;
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
        $countDetail    = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->count();
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
        
        if($countDetail == NULL) {
            // Updating data to ticket detail table
            Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->update([
                'jenis_ticket'              => $request['jenis_ticket'],
                'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
                'biaya'                     => $biaya,
                'process_at'                => $request['process_at'],
                'note'                      => $request['note'],
                'status'                    => "onprocess",
            ]);
            // Saving data to ticket_detail table
            $ticket_detail                          = new Ticket_detail;
            $ticket_detail->ticket_id               = $ticketId;
            $ticket_detail->jenis_ticket            = $request['jenis_ticket'];
            $ticket_detail->sub_category_ticket_id  = $request['sub_category_ticket_id'];
            $ticket_detail->agent_id                = $request['agent_id'];
            $ticket_detail->process_at              = $request['process_at'];
            $ticket_detail->pending_at              = "-";
            $ticket_detail->biaya                   = $biaya;
            $ticket_detail->note                    = $request['note'];
            $ticket_detail->updated_by              = $request['updated_by'];
            $ticket_detail->save();

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
                'jenis_ticket'              => $request['jenis_ticket'],
                'sub_category_ticket_id'    => $request['sub_category_ticket_id'],
                'biaya'                     => $biaya,
                'note'                      => $request['note'],
            ]);

            // Redirect to the Category Asset view if create data succeded
            $no_ticket = $request['no_ticket'];
            return redirect('/ticket-details'.'/'.$request['url'])->with('success', 'Detail tindakan ticket '.$no_ticket.' telah diedit!');
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
