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

        // Melakukan beberapa perhitungan agregat
        $agents->map(function($agent) {
            // Report 1
            $agent->ticket_unprocessed = $agent->ticket->where('status', 'created')->count();
            $agent->ticket_pending = $agent->ticket->where('status', 'pending')->count();
            $agent->ticket_onprocess = $agent->ticket->where('status', 'onprocess')->count();
            $agent->ticket_finish = $agent->ticket->whereIn('status', ['resolved', 'finished'])->count();
            $agent->ticket_assigned = $agent->ticket_details->where('status', 'assigned')->count();

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
        $totalAssigned      = $agents->sum('ticket_assigned');

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
        
        //              0               1               2               3                4               5                  6                7                8
        $total = [$totalPending, $totalOnprocess, $totalFinish, $totalAvgPending, $totalAvgFinish, $totalAssigned /* $totalTicketPerDay, $totalHourPerDay, $totalPermintaan, $totalKendala */];
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

    public function showTicket(Request $request)
    {
        $agentId = decrypt($request['agent_id']);
        $status = $request['status'];
        $startDate = $request['start_date'];
        $endDate = $request['end_date'];

        // Membuat query tiket
        $query = Ticket::query();

        // Menambahkan kondisi filter berdasarkan tanggal
        if ($request->filled('start_date') && !$request->filled('end_date')) {
            // Jika hanya start_date yang diisi, filter berdasarkan tanggal itu saja
            $query->whereDate('created_at', '=', $startDate);
        } elseif (!$request->filled('start_date') && $request->filled('end_date')) {
            // Jika hanya end_date yang diisi, filter berdasarkan tanggal itu saja
            $query->whereDate('created_at', '=', $endDate);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika kedua tanggal diisi, filter antara dua tanggal tersebut
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        }

        // Mengambil hasil query
        if($status != 'finished'){
            $tickets = $query->where([['agent_id', $agentId],['status', $status]])->get();
        }else{
            $tickets = $query->where('agent_id', $agentId)->whereIn('status', ['resolved', 'finished'])->get();
        }

        $agent = Agent::find($agentId);

        $subtitle = "Ticket ".ucfirst($status)." by ".ucwords($agent->nama_agent);

        return view('contents.report.agent.show', [
            "title"     => "Detail Report Agent",
            "path"      => "Report",
            "path2"     => "Agent",
            "reqPage"   => "show_ticket",
            "tickets"   => $tickets,
            "subtitle"  => $subtitle
        ]);
    }

    public function showDetailTicket(Request $request)
    {
        $agentId = decrypt($request['agent_id']);
        $type = $request['type'];
        $startDate = $request['start_date'];
        $endDate = $request['end_date'];

        // Membuat query tiket
        $query = Ticket_detail::query();

        // Menambahkan kondisi filter berdasarkan tanggal
        if ($request->filled('start_date') && !$request->filled('end_date')) {
            // Jika hanya start_date yang diisi, filter berdasarkan tanggal itu saja
            $query->whereDate('created_at', '=', $startDate);
        } elseif (!$request->filled('start_date') && $request->filled('end_date')) {
            // Jika hanya end_date yang diisi, filter berdasarkan tanggal itu saja
            $query->whereDate('created_at', '=', $endDate);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika kedua tanggal diisi, filter antara dua tanggal tersebut
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        }

        // Mengambil hasil query
        if($type == "assigned"){
            $tickets = $query->where([['agent_id', $agentId],['status', $type]])->orderBy('ticket_id', 'ASC')->get();
        }
        
        if($type == "kendala" || $type == "permintaan"){
            $tickets = $query->where([['agent_id', $agentId],['jenis_ticket', $type]])->whereIn('status', ['assigned', 'resolved'])->get();
        }

        if($type == "hourperday"){
            $tickets = $query->where('agent_id', $agentId)->orderBy('processed_time', 'DESC')->get();
        }

        $agent = Agent::find($agentId);

        if($type == "kendala"){
            $type = "accident";
        }
        
        if($type == "permintaan"){
            $type = "request";
        }
        
        if($type == "assigned"){
            $type = "participant";
        }

        if($type == "hourperday"){
            $type = "Average Hour Per Day";
        }
        
        $subtitle = "Ticket ".ucfirst($type)." by ".ucwords($agent->nama_agent);

        return view('contents.report.agent.show', [
            "title"     => "Detail Report Agent",
            "path"      => "Report",
            "path2"     => "Agent",
            "reqPage"   => "show_detail_ticket",
            "tickets"   => $tickets,
            "subtitle"  => $subtitle
        ]);
    }

    public function export(Request $request)
    {
        $locationId = Auth::user()->location_id;
        $locationIds = $locationId == 10 ? [10, 359, 360] : [$locationId];
        $startDate = $request['startDate'];
        $endDate = $request['endDate'];
        
        if($startDate && $endDate){
            $filter = "(".$startDate."_".$endDate.")";
        }
        if($startDate && !$endDate){
            $filter = "(".$startDate.")";
        }
        if(!$startDate && $endDate){
            $filter = "(".$endDate.")";
        }
        if(!$startDate && !$endDate){
            $filter = "";
        }

        if($request['report1']){
            $report = $request['report1'];
            return Excel::download(new AgentsExport($locationIds, $startDate, $endDate, $report), 'agents_report1'.$filter.'.xlsx');
        }
        if($request['report2']){
            $report = $request['report2'];
            return Excel::download(new AgentsExport($locationIds, $startDate, $endDate, $report), 'agents_report2'.$filter.'.xlsx');
        }
        if($request['report3']){
            $report = $request['report3'];
            return Excel::download(new AgentsExport($locationIds, $startDate, $endDate, $report), 'agents_report3'.$filter.'.xlsx');
        }
        if($request['report4']){
            $report = $request['report4'];
            return Excel::download(new AgentsExport($locationIds, $startDate, $endDate, $report), 'agents_report4'.$filter.'.xlsx');
        }
        if($request['report5']){
            $report = $request['report5'];
            return Excel::download(new AgentsExport($locationIds, $startDate, $endDate, $report), 'agents_report5'.$filter.'.xlsx');
        }
    }
}