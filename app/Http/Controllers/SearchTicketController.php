<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;

class SearchTicketController extends Controller
{
    public function show(Request $request)
    {
        $noTicket   = $request['no_ticket'];

        if($noTicket == NULL){
            return back()->with('error', 'Nomor Ticket belum diisi!');
        }else {
            $ticket     = Ticket::where('no_ticket', $noTicket)->whereNotIn('status', ['deleted'])->first();
            if($ticket == NULL){
                return back()->with('error', 'Nomor Ticket tidak ditemukan!');
            }else{
                $ticketId   = $ticket->id;
                return view('contents.search_ticket.index', [
                    "title"             => "Ticket Detail",
                    "path"              => "Ticket",
                    "path2"             => "Detail",
                    "ticket"            => $ticket,
                    "ticket_details"    => Ticket_detail::where('ticket_id', $ticketId)->get()
                ]);
            }
        }
    }
}
