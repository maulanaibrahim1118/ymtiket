<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;
use App\Comment;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
