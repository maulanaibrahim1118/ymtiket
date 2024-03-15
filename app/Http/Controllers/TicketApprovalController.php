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
        $id = $request['id'];
        $approved = $request['status'];
        $ticketId = $request['ticket_id'];
        $agentId = $request['agent_id'];
        $reason = $request['reason'];
        $updatedBy = $request['updated_by'];
        $now = date('d-m-Y H:i:s');
        
        Ticket_approval::where('id', $id)->update([
            'status' => $approved,
            'reason' => $reason,
            'approved_by' => $updatedBy,
            'updated_by' => $updatedBy
        ]);

        Ticket::where('id', $ticketId)->update([
            'approved' => $approved,
            'updated_by' => $updatedBy
        ]);

        if($approved == "rejected"){
            $statusApproval = "tidak disetujui";
            $statusTicket = "finished";

            // Mencari lamanya ticket di pending
            $getTicket      = Ticket::where('id', $ticketId)->first();
            $pendingAt      = Carbon::parse($getTicket->pending_at);
            $processAt      = Carbon::parse($getTicket->process_at);
            $now            = Carbon::parse($now);
            $pendingTime    = $pendingAt->diffInSeconds($now);
            $processedTime  = $processAt->diffInSeconds($now);

            // Mencari ticket detail id
            $getDetail = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->latest()->first();
            $detail_id = $getDetail->id;

            Ticket::where('id', $ticketId)->update([
                'status' => $statusTicket,
                'pending_time' => $pendingTime,
                'processed_time' => $processedTime,
                'updated_by' => $updatedBy
            ]);

            Ticket_detail::where('id', $detail_id)->update([
                'status' => "resolved",
                'pending_time' => 0,
                'processed_time' => 0,
                'updated_by' => $updatedBy
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
            $progress_ticket->tindakan      = "Ticket ".$statusApproval." oleh ".ucwords($request['updated_by']);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = $statusTicket;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
        }else{
            $statusApproval = "disetujui";
            $statusTicket = "pending";

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket ".$statusApproval." oleh ".ucwords($request['updated_by']);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = $statusTicket;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
        }

        return back()->with('success', 'Ticket '.$statusApproval);
    }
}
