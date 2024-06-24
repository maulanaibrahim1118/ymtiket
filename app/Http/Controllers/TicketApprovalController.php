<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Ticket_approval;
use App\Ticket_detail;
use App\Progress_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TicketApprovalController extends Controller
{
    public function update(Request $request)
    {
        // Get input request
        $approved   = decrypt($request['status']);
        $reason     = decrypt($request['reason']);
        $ticketId   = decrypt($request['ticket_id']);
        $updatedBy  = Auth::user()->nama;

        // Get waktu saat ini
        $now = date('d-m-Y H:i:s');
        
        // Updating data Ticket Approval sesuai input request
        Ticket_approval::where('ticket_id', $ticketId)->update([
            'status'        => $approved,
            'reason'        => $reason,
            'approved_by'   => $updatedBy,
            'updated_by'    => $updatedBy
        ]);

        // Updating status approval pada tabel ticket
        Ticket::where('id', $ticketId)->update([
            'approved'      => $approved,
            'updated_by'    => $updatedBy
        ]);

        // Jika biaya ticket tidak disetujui
        if($approved == "rejected"){
            $statusTicket   = "finished";
            $statusApproval = "tidak disetujui";

            // Mencari lamanya ticket di pending
            $getTicket      = Ticket::where('id', $ticketId)->first();
            $pendingAt      = Carbon::parse($getTicket->pending_at);
            $processAt      = Carbon::parse($getTicket->process_at);
            $now            = Carbon::parse($now);
            $pendingTime    = $pendingAt->diffInSeconds($now);
            $processedTime  = $processAt->diffInSeconds($now);

            // Update status ticket, waktu pending dan waktu proses pada tabel ticket
            Ticket::where('id', $ticketId)->update([
                'status'            => $statusTicket,
                'pending_time'      => $pendingTime,
                'processed_time'    => $processedTime,
                'updated_by'        => $updatedBy
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket di tutup oleh sistem";
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = $statusTicket;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket ".$statusApproval." oleh ".ucwords($updatedBy);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = $statusTicket;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
        }else{
            $statusTicket = "pending";
            $statusApproval = "disetujui";

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket ".$statusApproval." oleh ".ucwords($updatedBy);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = $statusTicket;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
        }

        // Redirect kembali ke halaman detail ticket beserta notifikasi sukses
        return back()->with('success', 'Ticket '.$statusApproval);
    }
}