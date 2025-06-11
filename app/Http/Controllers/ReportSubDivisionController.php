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
use App\Exports\AgentsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportSubDivisionController extends Controller
{
    public function index(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $location   = Auth::user()->location->nama_lokasi;
        $pathFilter = "Semua";

        $locationIds = $locationId == 10 ? [10, 359, 360] : [$locationId];

        $agents = Agent::whereIn('location_id', $locationIds)
            ->where('is_active', '1')
            ->withCount(['ticket', 'ticket_details'])
            ->with([
                'ticket' => function($query) {
                    $query->whereNotIn('status', ['deleted'])->with('ticket_detail');
                },
                'ticket_details'
            ])
            ->orderBy('sub_divisi', 'ASC')
            ->get();

        // Group agents by sub_divisi
        $groupedBySubDivisi = $agents->groupBy('sub_divisi');

        // Initialize data structure
        $subDivisiReports = $groupedBySubDivisi->map(function($agentsGroup, $subDivisi) {
            $subDivisiData = new \stdClass();
            $subDivisiData->sub_divisi = $subDivisi;
            
            // Initialize counters
            $totalTickets = $totalWorkHours = $uniqueDatesCount = 0;
            
            // Aggregated reports
            foreach ($agentsGroup as $agent) {
                $totalTickets += $agent->ticket_details_count;
                $totalWorkHours += $agent->ticket_details->sum('processed_time');
                $uniqueDatesCount += $agent->ticket_details->pluck('created_at')
                    ->map(function($date) {
                        return $date ? $date->format('Y-m-d') : null;
                    })
                    ->unique()
                    ->filter()
                    ->count();

                // Status-specific counts
                $subDivisiData->ticket_unprocessed = ($subDivisiData->ticket_unprocessed ?? 0) + $agent->ticket->where('status', 'created')->count();
                $subDivisiData->ticket_pending = ($subDivisiData->ticket_pending ?? 0) + $agent->ticket->where('status', 'pending')->count();
                $subDivisiData->ticket_onprocess = ($subDivisiData->ticket_onprocess ?? 0) + $agent->ticket->where('status', 'onprocess')->count();
                $subDivisiData->ticket_finish = ($subDivisiData->ticket_finish ?? 0) + $agent->ticket->whereIn('status', ['resolved', 'finished'])->count();
                $subDivisiData->ticket_assigned = ($subDivisiData->ticket_assigned ?? 0) + $agent->ticket_details->where('status', 'assigned')->count();
                
                // Average calculations
                $subDivisiData->avg_pending = ($subDivisiData->avg_pending ?? 0) + $agent->ticket->pluck('ticket_detail.pending_time')->average();
                $subDivisiData->avg_finish = ($subDivisiData->avg_finish ?? 0) + $agent->ticket->pluck('ticket_detail.processed_time')->average();

                $subDivisiData->avg_permintaan = ($subDivisiData->avg_pending ?? 0) + $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->average('processed_time');
                $subDivisiData->avg_kendala = ($subDivisiData->avg_pending ?? 0) + $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->average('processed_time');

                // Type-specific counts
                $subDivisiData->permintaan = ($subDivisiData->permintaan ?? 0) + $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->count();
                $subDivisiData->kendala = ($subDivisiData->kendala ?? 0) + $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->count();
            }

            // Final calculations for each sub-divisi
            $subDivisiData->totalHour = $totalWorkHours;
            $subDivisiData->totalDay = $uniqueDatesCount;
            $subDivisiData->ticket_per_day = $uniqueDatesCount > 0 ? round($totalTickets / $uniqueDatesCount) : 0;
            $subDivisiData->hour_per_day = $uniqueDatesCount > 0 ? round($totalWorkHours / $uniqueDatesCount) : 0;
            $subDivisiData->percentage = $subDivisiData->hour_per_day > 0 ? round(($subDivisiData->hour_per_day / 28800) * 100) : 0;

            return $subDivisiData;
        });

        // Convert to JSON for use in ECharts
        $data = [];
        foreach ($subDivisiReports as $report) {
            $data[$report->sub_divisi] = [
                'tickets' => [
                    ['name' => $report->sub_divisi, 'value' => $report->ticket_finish]
                ],
                'process' => [
                    ['name' => $report->sub_divisi, 'value' => $report->totalHour]
                ]
            ];
        }

        $jsonData = json_encode($data);
        
        // Aggregate total values for the summary section
        $totalPending = $subDivisiReports->sum('ticket_pending');
        $totalOnprocess = $subDivisiReports->sum('ticket_onprocess');
        $totalFinish = $subDivisiReports->sum('ticket_finish');
        $totalAvgPending = $subDivisiReports->sum('avg_pending');
        $totalAvgFinish = $subDivisiReports->sum('avg_finish');
        $totalAssigned = $subDivisiReports->sum('ticket_assigned');

        $total = [$totalPending, $totalOnprocess, $totalFinish, $totalAvgPending, $totalAvgFinish, $totalAssigned];

        $filterArray = ["", ""];

        return view('contents.report.sub_division.index', [
            "title"         => "Sub Division Report",
            "path"          => "Report",
            "path2"         => "Sub Division",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "agents"        => $agents,
            "jsonData"      => $jsonData,
            "total"         => $total,
            "subDivisiReports" => $subDivisiReports
        ]);
    }
}