<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Ticket;
use App\Comment;
use Illuminate\Http\Request;
use App\Jobs\SendFonnteNotification;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get ID Ticket
        $ticketId = decrypt($request['ticket_id']);

        // Validating data request
        $validatedData = $request->validate([
            'ticket_id'     => 'required',
            'komentar'      => 'required',
        ],

        // Create custom notification for the validation request
        [
            'komentar.required' => 'Comment required!',
        ]);

        // Menambahkan data statis langsung ke array $validatedData
        $validatedData['ticket_id'] = $ticketId;
        $validatedData['updated_by'] = Auth::user()->nama;
        $validatedData['user_id'] = Auth::user()->id;

        // Mencari data no. hp untuk agent atau client yang akan di kirimi komentar
        $ticket = Ticket::find($ticketId);

        if ($ticket->user_id == Auth::user()->id) {
            $getAgent = Agent::where([['is_active', '1'],['id', $ticket->agent_id]])->first();
            $userAgent = User::where('nik', $getAgent->nik)->first();
            $userPhone = $userAgent->telp;
        } else {
            $userClient = User::where('id', $ticket->user_id)->first();
            $userPhone = $userClient->telp;
        }

        $noTiket = $ticket->no_ticket;
                    
        if($ticket->location->wilayah_id == 1 || $ticket->location->wilayah_id == 2){
            $cabang = ucwords($ticket->user->nama)." (".ucwords($ticket->location_name).")";
        } else {
            $cabang = ucwords($ticket->location_name);
        }

        $kendala = $ticket->kendala;

        // Kirim notifikasi ke WhatsApp via job/helper
        if (!empty($userPhone) && strlen(preg_replace('/\D/', '', $userPhone)) >= 8) {
            SendFonnteNotification::dispatch("+$userPhone", "Ada komentar baru pada tiket!\n\nNo Tiket: $noTiket\nClient: $cabang\nKendala: $kendala\n\nKomentar: $request->komentar");
        }

        // Simpan data Comment sesuai request yang telah di validasi
        Comment::create($validatedData);

        // Redirect ke halaman ticket detail beserta notifikasi sukses
        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($ticketId)])->with('commentSuccess', 'Comment has been sent!');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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