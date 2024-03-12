<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Agent;
use App\Ticket;
use App\Ticket_detail;
use App\Location;

class ReportAgentController extends Controller
{
    public function index(Request $request)
    {
        $userId     = auth()->user()->id;
        $userRole   = auth()->user()->role;
        $locationId = auth()->user()->location_id;
        $location   = auth()->user()->location->nama_lokasi;

        $data1  = Agent::where('location_id', $locationId)
                    ->withCount('ticket_detail')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted")) as total_ticket'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created") as ticket_unprocessed'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "pending") as ticket_pending'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created")) as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","onprocess","pending","created")) as ticket_finish'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as processed_time'),
                        DB::raw('(SELECT AVG(pending_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg_pending'),
                        DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg_resolved')
                    )
                    ->orderBy('sub_divisi', 'ASC')
                    ->get();
        $totalPending   = Ticket::where([['ticket_for', $location],['status', 'pending']])->count();
        $totalOnprocess = Ticket::where([['ticket_for', $location],['status', 'onprocess']])->count();
        $totalResolved  = Ticket::where([['ticket_for', $location],['status', 'resolved']])
                                ->orWhere([['ticket_for', $location],['status', 'finished']])
                                ->count();
        $totalAvgPending    = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
                                            ->where('tickets.ticket_for', $location)
                                            ->avg('ticket_details.pending_time');
        $totalAvgResolved    = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
                                            ->where('tickets.ticket_for', $location)
                                            ->avg('ticket_details.processed_time');

        $total1         = [$totalPending, $totalOnprocess, $totalResolved, $totalAvgPending, $totalAvgResolved];

        $data2 = Agent::where('location_id', $locationId)
                    ->withCount('ticket_detail')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted")) as total_ticket'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as processed_time'),
                        DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
                    )
                    ->get();

        return view('contents.report.agent.index', [
            "url"       => "",
            "title"     => "Report Agent",
            "path"      => "Report",
            "path2"     => "Agent",
            "data1"     => $data1,
            "total1"    => $total1
        ]);
    }
}
