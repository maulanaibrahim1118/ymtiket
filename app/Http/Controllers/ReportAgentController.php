<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Ticket;
use App\Location;
use App\Ticket_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ReportAgentController extends Controller
{
    public function index(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $location   = Auth::user()->location->nama_lokasi;
        $pathFilter = "Semua";

        $agents = Agent::where([['location_id', $locationId],['is_active', '1']])
            ->withCount(['ticket', 'ticket_details'])
            ->with([
                'ticket' => function($query) {
                    $query->whereNotIn('status', ['deleted'])->with('ticket_detail');
                },
                'ticket_details'
            ])
            ->orderBy('sub_divisi', 'ASC')
            ->get();

        // Melakukan beberapa perhitungan agregat
        $agents->map(function($agent) {
            // Report 1
            $agent->ticket_unprocessed = $agent->ticket->where('status', 'created')->count();
            $agent->ticket_pending = $agent->ticket->where('status', 'pending')->count();
            $agent->ticket_onprocess = $agent->ticket->where('status', 'onprocess')->count();
            $agent->ticket_finish = $agent->ticket->whereIn('status', ['resolved', 'finished'])->count();

            // Report 2
            $agent->avg_pending = $agent->ticket->pluck('ticket_detail.pending_time')->average();
            $agent->avg_finish = $agent->ticket->pluck('ticket_detail.processed_time')->average();
            
            // Report 3
            $totalTicket = $agent->ticket_details_count;
            $workHour = $agent->ticket_details->sum('processed_time');
            $uniqueDates = $agent->ticket_details->pluck('created_at')
                ->map(function($date) {
                    return $date ? $date->format('Y-m-d') : null;
                })
                ->unique()
                ->filter()
                ->count();

            if ($totalTicket == 0 || $workHour == 0 || $uniqueDates == 0) {
                $agent->ticket_per_day = 0;
                $agent->hour_per_day = 0;
            } else {
                $agent->ticket_per_day = round($totalTicket/$uniqueDates);
                $agent->hour_per_day = round($workHour/$uniqueDates);
            }

            $agent->percentage = round(($agent->hour_per_day/28800)*100);
            
            // Report 4
            $agent->avg_permintaan = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->average('processed_time');
            $agent->avg_kendala = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->average('processed_time');

            // Report 5
            $agent->permintaan = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->count();
            $agent->kendala = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->count();

            // Report 6
            $agent->jml_ticket = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->count();
            $agent->jml_process = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->sum('processed_time');
            
            return $agent;
        });
        
        // Menghitung total untuk semua report
        $totalPending       = $agents->sum('ticket_pending');
        $totalOnprocess     = $agents->sum('ticket_onprocess');
        $totalFinish        = $agents->sum('ticket_finish');
        $totalAvgPending    = $agents->sum('avg_pending');
        $totalAvgFinish     = $agents->sum('avg_finish');

        // Pengolahan data untuk ECharts
        $data = [];
        foreach ($agents as $agent) {
            $subDivision = $agent->sub_divisi ?: 'No Sub Division';

            if (!isset($data[$subDivision])) {
                $data[$subDivision] = ['tickets' => [], 'process' => []];
            }

            $data[$subDivision]['tickets'][] = [
                'name' => $agent->nama_agent,
                'value' => $agent->jml_ticket
            ];

            $data[$subDivision]['process'][] = [
                'name' => $agent->nama_agent,
                'value' => $agent->jml_process
            ];
        }

        $jsonData = json_encode($data);
        
        //              0               1               2               3                4                     5                  6                7                8
        $total = [$totalPending, $totalOnprocess, $totalFinish, $totalAvgPending, $totalAvgFinish, /* $totalTicketPerDay, $totalHourPerDay, $totalPermintaan, $totalKendala */];
        $filterArray = ["", ""];

        return view('contents.report.agent.index', [
            "title"         => "Agent Report",
            "path"          => "Report",
            "path2"         => "Agent",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "agents"        => $agents,
            "jsonData"      => $jsonData,
            "total"         => $total
        ]);
    }
}