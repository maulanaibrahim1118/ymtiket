<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Ticket_detail;
use Illuminate\Http\Request;

class SearchTicketController extends Controller
{
    public function show(Request $request)
    {
        // Get No. Ticket dari input request
        $noTicket   = $request['no_ticket'];

        // Jika Input No. Ticket tidak diisi (mecegah input validasi html tidak berjalan) 
        if($noTicket == NULL){
            return back()->with('error', 'Nomor Ticket belum diisi!');

        // Jika Input No. Ticket diisi
        }else {
            // Mencari data ticket by No. Ticket
            $ticket     = Ticket::where('no_ticket', $noTicket)->whereNotIn('status', ['deleted'])->first();
            
            // Jika No. Ticket tidak ditemukan
            if($ticket == NULL){
                return back()->with('error', 'Nomor Ticket tidak ditemukan!');

            // Jika No. Ticket ditemukan
            }else{
                // Get ID Ticket
                $ticketId   = $ticket->id;

                // Mencari Detail Ticket
                $countDetail    = Ticket_detail::where('ticket_id', $ticketId)->count();
                $ticketDetails  = Ticket_detail::where('ticket_id', $ticketId)->get();

                return view('contents.search_ticket.index', [
                    "title"             => "Search Ticket",
                    "path"              => "Ticket",
                    "path2"             => "Detail",
                    "ticket"            => $ticket,
                    "countDetail"       => $countDetail,
                    "ticket_details"    => $ticketDetails
                ]);
            }
        }
    }
}