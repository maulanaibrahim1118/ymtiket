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
            return back()->with('error', 'Ticket Number required!');

        // Jika Input No. Ticket diisi
        }else {
            // Mencari data ticket by No. Ticket
            $ticket     = Ticket::where('no_ticket', $noTicket)->first();
            
            // Jika No. Ticket tidak ditemukan
            if($ticket == NULL){
                return back()->with('error', 'Ticket not found!');
                
                // Jika No. Ticket ditemukan
            }else{
                $ticket     = Ticket::where('no_ticket', $noTicket)->whereIn('status', ['resolved', 'finished'])->first();

                if($ticket == NULL){
                    return back()->with('error', 'Ticket is being processed by the agent!');
                }else{
                    // Get ID Ticket
                    $ticketId   = $ticket->id;

                    // Mencari Detail Ticket
                    $countDetail    = Ticket_detail::where('ticket_id', $ticketId)->count();
                    $ticketDetails  = Ticket_detail::where('ticket_id', $ticketId)->get();

                    return view('contents.search_ticket.index', [
                        "title"             => "Search Ticket",
                        "path"              => "Ticket",
                        "path2"             => "Search",
                        "ticket"            => $ticket,
                        "countDetail"       => $countDetail,
                        "ticket_details"    => $ticketDetails
                    ]);
                }
            }
        }
    }

    public function shared(Request $request)
    {
        // Get No. Ticket dari input request
        $ticketId   = decrypt($request['id']);

        // Mencari Detail Ticket
        $countDetail    = Ticket_detail::where('ticket_id', $ticketId)->count();
        $ticketDetails  = Ticket_detail::where('ticket_id', $ticketId)->get();
        $ticket         = Ticket::where('id', $ticketId)->first();

        // Mencari extension file
        $ext = substr($ticket->file, -4);

        return view('contents.search_ticket.shared', [
            "title"             => "Ticket Details",
            "path"              => "Ticket",
            "path2"             => "Details",
            "ext"               => $ext,
            "ticket"            => $ticket,
            "countDetail"       => $countDetail,
            "ticket_details"    => $ticketDetails
        ]);
    }
}