<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

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
        $ticketId = $request['ticket_id'];

        // Validating data request
        $validatedData = $request->validate([
            'user_id'       => 'required',
            'ticket_id'     => 'required',
            'komentar'      => 'required',
            'updated_by'    => 'required'
        ],

        // Create custom notification for the validation request
        [
            'komentar.required' => 'Anda belum mengetikkan apapun!',
        ]);

        // Simpan data Comment sesuai request yang telah di validasi
        Comment::create($validatedData);

        // Redirect ke halaman ticket detail beserta notifikasi sukses
        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($ticketId)])->with('commentSuccess', 'Komentar telah dikirim!');
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