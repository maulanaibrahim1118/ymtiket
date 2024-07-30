<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Ticket;
use App\Wilayah;
use App\Location;
use Carbon\Carbon;
use App\Ticket_detail;
use App\Category_ticket;
use App\Sub_category_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FilterController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get data User
        $id         = Auth::user()->id;
        $role       = Auth::user()->role_id;
        $locationId = Auth::user()->location_id;
        $positionId = Auth::user()->position_id;
        $codeAccess = Auth::user()->code_access;
        
        // Memasukkan request kedalam variabel
        $filter1    = $request['filter1'];
        $filter2    = $request['filter2'];

        // Jika pilihan filter tidak ada yang dipilih
        if($filter1 == NULL AND $filter2 == NULL){
            return redirect('/dashboard');
        }

        // Menentukan Filter by Agent
        if($filter1 == NULL){
            $agentFilter    = "";
            $namaAgent      = "Semua Agent";
        }else{
            $agentFilter    = $filter1;
            $agent          = Agent::where('id', $agentFilter)->first();
            $namaAgent      = ucwords($agent->nama_agent);
        }

        // Menentukan Filter by Periode
        if($filter2 == "today"){
            $periodeFilter  = date('Y-m-d');
            $pathFilter = date('d F Y');
        }elseif($filter2 == "monthly"){
            $periodeFilter  = date('Y-m');
            $pathFilter = date('F Y');
        }elseif($filter2 == "yearly"){
            $periodeFilter  = date('Y');
            $pathFilter = date('Y');
        }else{
            $periodeFilter  = "";
            $pathFilter = "Semua Periode";
        }

        // Get data Agent (jika user bukan sebagai client)
        if($role != 3){
            $nik        = Auth::user()->nik;
            $getAgent   = Agent::where('nik', $nik)->first();
            $agentId    = $getAgent['id'];
        }

        // Jika role Client
        if($role == 3){
            if($locationId == 17){
                if($positionId == 2){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where([['code_access', 'like', '%'.$codeAccess.'%'],['created_at', 'like', $periodeFilter.'%']])
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
                }elseif($positionId == 6){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where([['code_access', 'like', '%'.$codeAccess],['created_at', 'like', $periodeFilter.'%']])
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
                }elseif($positionId == 7){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where([['code_access', 'like', $codeAccess.'%'],['created_at', 'like', $periodeFilter.'%']])
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
                }else{
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where([['location_id', $locationId],['created_at', 'like', $periodeFilter.'%']])
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
                }

            // Jika bukan Divisi Operational
            }else{
                $ticketCounts = Ticket::select(
                    DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                    DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                    DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                    DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                    DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                    DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                )
                ->where([['location_id', $locationId],['created_at', 'like', $periodeFilter.'%']])
                ->first();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
            }

            // Mengembalikan data untuk di tampilkan di view
            $total          = $ticketCounts->total;
            $approval       = $ticketCounts->approval;
            $unProcess      = $ticketCounts->unprocess;
            $onProcess      = $ticketCounts->onprocess;
            $pending        = $ticketCounts->pending;
            $finished       = $ticketCounts->finished;
            $dataArray      = [$total, $approval, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", $filter2];

        }else{
            // Jika role Service Desk
            if($role == 1){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";

                $ticketCounts = Ticket::select(
                    DB::raw('COUNT(CASE WHEN status NOT IN ("deleted", "resolved", "finished") THEN id END) as total'),
                    DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                    DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                    DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                    DB::raw('COUNT(CASE WHEN status = "finished" OR status = "resolved" THEN id END) as resolved'),
                    DB::raw('COUNT(CASE WHEN jam_kerja = "ya" AND status NOT IN ("deleted") THEN id END) as worktime'),
                    DB::raw('COUNT(CASE WHEN jam_kerja = "tidak" AND status NOT IN ("deleted") THEN id END) as freetime'),
                    DB::raw('COUNT(DISTINCT CASE WHEN status NOT IN ("deleted") THEN asset_id END) as asset')
                )
                ->where([['ticket_for', $locationId],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])
                ->first();

                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total          = $ticketCounts->total;
                $unProcess      = $ticketCounts->unprocess;
                $onProcess      = $ticketCounts->onprocess;
                $pending        = $ticketCounts->pending;
                $resolved       = $ticketCounts->resolved;
                $workTimeTicket = $ticketCounts->worktime;
                $freeTimeTicket = $ticketCounts->freetime;
                $asset          = $ticketCounts->asset;
                $assigned       = Ticket_detail::where([['agent_id', 'like', '%'.$agentFilter],['status', 'assigned'],['created_at', 'like', $periodeFilter.'%']])->count();
                $category       = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')->where([['tickets.ticket_for', $locationId],['ticket_details.agent_id', 'like', '%'.$agentFilter],['ticket_details.created_at', 'like', $periodeFilter.'%']])->distinct()->count(['ticket_details.sub_category_ticket_id']);

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 
                $data1          = Agent::where('location_id', $locationId)
                    ->when($agentFilter, function ($query, $agentFilter) {
                        return $query->where('id', $agentFilter);
                    })
                    ->withCount('ticket_details')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.process_at LIKE "' . $periodeFilter . '%") as total_ticket'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created") AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "resolved" AND ticket_details.process_at LIKE "' . $periodeFilter . '%") as ticket_finish'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "assigned" AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as assigned'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created" AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_unprocessed'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as sum'),
                        DB::raw('(SELECT COUNT(DISTINCT CASE WHEN DAYOFWEEK(created_at) NOT IN (1, 7) THEN DATE(created_at) END) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as working_days'),
                        DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as avg')
                    )
                    ->orderBy('sub_divisi', 'ASC')
                    ->get();
                $data2          = Ticket::where([['ticket_for', $locationId],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->get();
                $data3          = Ticket::where([['ticket_for', $locationId],['is_queue', 'ya'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->whereIn('status',['created', 'pending'])->get();
                $filterArray    = [$filter1, $filter2];

            // Jika role Agent
            }else{
                if($filter2 == NULL){
                    return redirect('/dashboard');
                }
                $ticketCounts = Ticket::select(
                    DB::raw('COUNT(CASE WHEN status NOT IN ("deleted", "resolved", "finished") THEN id END) as total'),
                    DB::raw('COUNT(CASE WHEN status = "finished" OR status = "resolved" THEN id END) as resolved')
                )
                ->where([['agent_id', $agentId],['created_at', 'like', $periodeFilter.'%']])
                ->first();

                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total          = $ticketCounts->total;
                $resolved       = $ticketCounts->resolved;
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned'],['created_at', 'like', $periodeFilter.'%']])->count();
                $processedTime  = Ticket_detail::where([['agent_id', $agentId],['created_at', 'like', $periodeFilter.'%']])->sum('processed_time');
                $workload       = $processedTime-0;

                // Menghitung Waktu Rata-rata Ticket Resolved
                $processedCount  = Ticket_detail::where([['agent_id', $agentId],['created_at', 'like', $periodeFilter.'%']])->whereIn('status', ['resolved', 'assigned'])->count();

                if($processedCount == 0){
                    $resolvedAvg    = 0;
                    $roundedAvg     = 0;
                }else {
                    $processedAvg    = $processedTime/$processedCount;
                    $roundedAvg     = round($processedAvg);
                }

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $resolved, $assigned, $workload, $roundedAvg];
                $data1          = Ticket::where([['agent_id', $agentId],['status', 'created'],['created_at', 'like', $periodeFilter.'%']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya'],['created_at', 'like', $periodeFilter.'%']])->get();
                $data2          = Ticket::where([['agent_id', $agentId],['status', 'onprocess'],['created_at', 'like', $periodeFilter.'%']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak'],['created_at', 'like', $periodeFilter.'%']])->get();
                $data3          = 0;
                $filterArray    = [$agentId, $filter2];
            }
        }

        $agents = Agent::where([['location_id', $locationId],['is_active', '1']])->get();

        return view('contents.dashboard.index', [
            "url"           => "",
            "title"         => "Dashboard",
            "path"          => "Dashboard",
            "path2"         => "Dashboard",
            "pathFilter"    => $pathFilter,
            "dataArray"     => $dataArray,
            "filterArray"   => $filterArray,
            "data1"         => $data1,
            "data2"         => $data2,
            "data3"         => $data3,
            "agents"        => $agents
        ]);
    }

    public function reportAgent(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $locationId = Auth::user()->location_id;
        $location   = Auth::user()->location->nama_lokasi;

        if ($request->filled('start_date') && !$request->filled('end_date')) {
            $date = Carbon::parse($request->start_date);
            $pathFilter = $date->format('d-M-Y');
        } elseif (!$request->filled('start_date') && $request->filled('end_date')) {
            $date = Carbon::parse($request->end_date);
            $pathFilter = $date->format('d-M-Y');
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $date1 = Carbon::parse($request->start_date);
            $date2 = Carbon::parse($request->end_date);
            if ($request->start_date == $request->end_date) {
                $pathFilter = $date1->format('d-M-Y');
            } else {
                $pathFilter = $date1->format('d-M-Y')." s/d ".$date2->format('d-M-Y');
            }
        } else {
            return redirect('/report-agents');
        }

        $locationIds = $locationId == 10 ? [10, 359, 360] : [$locationId];
        
        $agents = Agent::whereIn('location_id', $locationIds)
            ->where('is_active', '1')
            ->with([
                'ticket' => function($query) use ($request) {
                    $query->whereNotIn('status', ['deleted']);
    
                    // Menentukan jenis filter berdasarkan input tanggal
                    if ($request->filled('start_date') && !$request->filled('end_date')) {
                        // Jika hanya start_date yang diisi, filter berdasarkan tanggal itu saja
                        $query->whereDate('created_at', '=', $request->start_date);
                    } elseif (!$request->filled('start_date') && $request->filled('end_date')) {
                        // Jika hanya end_date yang diisi, filter berdasarkan tanggal itu saja
                        $query->whereDate('created_at', '=', $request->end_date);
                    } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                        // Jika kedua tanggal diisi, filter antara dua tanggal tersebut
                        $query->whereDate('created_at', '>=', $request->start_date)
                              ->whereDate('created_at', '<=', $request->end_date);
                    }
    
                    $query->with('ticket_detail');
                },
                'ticket_details' => function($query) use ($request) {
                    if ($request->filled('start_date') && !$request->filled('end_date')) {
                        $query->whereDate('created_at', '=', $request->start_date);
                    } elseif (!$request->filled('start_date') && $request->filled('end_date')) {
                        $query->whereDate('created_at', '=', $request->end_date);
                    } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                        $query->whereDate('created_at', '>=', $request->start_date)
                              ->whereDate('created_at', '<=', $request->end_date);
                    }
                }
            ])
            ->withCount(['ticket', 'ticket_details'])
            ->orderBy('sub_divisi', 'ASC')
            ->get();

        // Melakukan beberapa perhitungan agregat
        $agents->map(function($agent) {
            // Report 1
            $agent->total_ticket = $agent->ticket->count();
            $agent->ticket_unprocessed = $agent->ticket->where('status', 'created')->count();
            $agent->ticket_pending = $agent->ticket->where('status', 'pending')->count();
            $agent->ticket_onprocess = $agent->ticket->where('status', 'onprocess')->count();
            $agent->ticket_finish = $agent->ticket->whereIn('status', ['resolved', 'finished'])->count();
            $agent->ticket_assigned = $agent->ticket_details->where('status', 'assigned')->count();

            // Report 2
            $agent->avg_pending = $agent->ticket->pluck('ticket_detail.pending_time')->average();
            $agent->avg_finish = $agent->ticket->pluck('ticket_detail.processed_time')->average();
            
            // Report 3
            $totalTicket = $agent->ticket_details->count();
            $workHour = $agent->ticket_details->sum('processed_time');
            $uniqueDates = $agent->ticket_details->pluck('created_at')
                                        ->map(function($date) {
                                            return $date ? $date->format('Y-m-d') : null;
                                        })
                                        ->unique()
                                        ->filter()
                                        ->count();
            // $totalTicket = $agent->ticket_count;
            // $workHour = $agent->ticket->pluck('ticket_detail.processed_time')->sum();
            // $uniqueDates = $agent->ticket->pluck('ticket_detail.created_at')
            //                             ->map(function($date) {
            //                                 return $date ? $date->format('Y-m-d') : null;
            //                             })
            //                             ->unique()
            //                             ->filter()
            //                             ->count();

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
        // $totalTicketPerDay  = $agents->sum('ticket_per_day');
        // $totalHourPerDay    = $agents->sum('hour_per_day');
        // $totalPermintaan    = $agents->sum('permintaan');
        // $totalKendala       = $agents->sum('kendala');

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

        //              0               1               2               3                4               5                   6                7                8
        $total = [$totalPending, $totalOnprocess, $totalFinish, $totalAvgPending, $totalAvgFinish, $totalAssigned /* $totalTicketPerDay, $totalHourPerDay, $totalPermintaan, $totalKendala */];
        $filterArray = [$request->start_date, $request->end_date];

        return view('contents.report.agent.index', [
            "url"           => "",
            "title"         => "Report Agent",
            "path"          => "Report",
            "path2"         => "Agent",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "agents"        => $agents,
            "jsonData"      => $jsonData,
            "total"         => $total
        ]);
    }

    public function reportSubCategory(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $locationId = Auth::user()->location_id;
        $location   = Auth::user()->location->nama_lokasi;

        $category = $request->category;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $date1 = Carbon::parse($start_date);
        $date2 = Carbon::parse($end_date);

        $filterArray = [$category, $start_date, $end_date];

        if ($request->filled('category') && $request->filled('start_date') && !$request->filled('end_date')) {
            $pathFilter = [$category, $date1->format('d-M-Y')." s/d sekarang"];
        } elseif ($request->filled('category') && !$request->filled('start_date') && $request->filled('end_date')) {
            $pathFilter = [$category, "Periode Awal s/d ".$date2->format('d-M-Y')];
        } elseif ($request->filled('category') && !$request->filled('start_date') && !$request->filled('end_date')) {
            $pathFilter = [$category, ""]; 
        } elseif (!$request->filled('category') && $request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date == $request->end_date) {
                $pathFilter = ["", $date1->format('d-M-Y')];
            } else {
                $pathFilter = ["", $date1->format('d-M-Y')." s/d ".$date2->format('d-M-Y')];
            }
        } elseif ($request->filled('category') && $request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date == $request->end_date) {
                $pathFilter = [$category, $date1->format('d-M-Y')];
            } else {
                $pathFilter = [$category, $date1->format('d-M-Y')." s/d ".$date2->format('d-M-Y')];
            }
        } elseif (!$request->filled('category') && $request->filled('start_date') && !$request->filled('end_date')) {
            $pathFilter = ["", $date1->format('d-M-Y')." s/d sekarang"];
        } elseif (!$request->filled('category') && !$request->filled('start_date') && $request->filled('end_date')) {
                $pathFilter = ["", "Periode Awal s/d ".$date2->format('d-M-Y')];
        } else {
            return redirect('/report-sub-categories');
        }

        $dCategories = Category_ticket::where('location_id', $locationId)->get();
        
        $query = Category_ticket::where('location_id', $locationId);

        if (!empty($category)) {
            $query->where('nama_kategori', $category);
        }

        $categories = $query->with(['sub_category_tickets.ticket_details' => function($query) use ($start_date, $end_date) {
            if (!empty($start_date) && empty($end_date)) {
                $query->whereDate('created_at', '>=', $start_date);
            }
        
            if (!empty($end_date) && empty($start_date)) {
                $query->whereDate('created_at', '<=', $end_date);
            }
        
            if (!empty($start_date) && !empty($end_date)) {
                if ($start_date == $end_date) {
                    $query->whereDate('created_at', '=', $start_date);
                } else {
                    $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                }
            }
        
            $query->whereIn('status', ['resolved', 'assigned']);
            $query->with('agent');
        }])->get();
        
        $agents = Agent::where([['location_id', $locationId],['is_active', '1']])->get();
        $totalAgents = Agent::where([['location_id', $locationId],['is_active', '1']])->count();
        $data = [];
        
        foreach ($categories as $category) {
            foreach ($category->sub_category_tickets as $subCategory) {
                foreach ($agents as $agent) {
                    $avgTime = $subCategory->ticket_details->where('agent_id', $agent->id)->avg('processed_time');
                    $data[$category->nama_kategori][$subCategory->nama_sub_kategori][$agent->id] = $avgTime;
                }
                $data[$category->nama_kategori][$subCategory->nama_sub_kategori]['totalAverage'] = $subCategory->ticket_details->avg('processed_time');
            }
        }

        return view('contents.report.sub_category.index', [
            "url"           => "",
            "title"         => "Report Sub Category",
            "path"          => "Report",
            "path2"         => "Sub Category",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "dCategories"   => $dCategories,
            "totalAgents"   => $totalAgents,
            "agents"        => $agents,
            "data"          => $data
        ]);
    }

    public function reportLocation(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;

        $wilayahId = $request->wil;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $date1 = Carbon::parse($startDate);
        $date2 = Carbon::parse($endDate);

        if($wilayahId != NULL){
            $getWilayah = Wilayah::find($wilayahId);
            $namaWilayah = $getWilayah->name;
        }else{
            $namaWilayah = '';
        }

        $filterArray = [$wilayahId, $startDate, $endDate];

        if ($request->filled('wil') && $request->filled('start_date') && !$request->filled('end_date')) {
            $pathFilter = [$namaWilayah, $date1->format('d-M-Y')." s/d sekarang"];
        } elseif ($request->filled('wil') && !$request->filled('start_date') && $request->filled('end_date')) {
            $pathFilter = [$namaWilayah, "Periode Awal s/d ".$date2->format('d-M-Y')];
        } elseif ($request->filled('wil') && !$request->filled('start_date') && !$request->filled('end_date')) {
            $pathFilter = [$namaWilayah, ""]; 
        } elseif (!$request->filled('wil') && $request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date == $request->end_date) {
                $pathFilter = ["", $date1->format('d-M-Y')];
            } else {
                $pathFilter = ["", $date1->format('d-M-Y')." s/d ".$date2->format('d-M-Y')];
            }
        } elseif ($request->filled('wil') && $request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date == $request->end_date) {
                $pathFilter = [$namaWilayah, $date1->format('d-M-Y')];
            } else {
                $pathFilter = [$namaWilayah, $date1->format('d-M-Y')." s/d ".$date2->format('d-M-Y')];
            }
        } elseif (!$request->filled('wil') && $request->filled('start_date') && !$request->filled('end_date')) {
                $pathFilter = ["", $date1->format('d-M-Y')." s/d sekarang"];
        } elseif (!$request->filled('wil') && !$request->filled('start_date') && $request->filled('end_date')) {
                $pathFilter = ["", "Periode Awal s/d ".$date2->format('d-M-Y')];
        } else {
            return redirect('/report-locations');
        }

        $wilayahs = Wilayah::all();

        $query = Location::orderBy('nama_lokasi');
        
        if (!empty($wilayahId)) {
            $query->where('wilayah_id', $wilayahId);
        }

        $locations = $query->withCount(['tickets', 'user'])
            ->with(['tickets' => function($query) use ($locationId, $startDate, $endDate) { 
                $query->where('ticket_for', $locationId)
                      ->whereNotIn('status', ['deleted']);
                
                // Filter berdasarkan created_at atau updated_at jika startDate dan endDate diisi
                if (!empty($startDate) && empty($endDate)) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
            
                if (!empty($endDate) && empty($startDate)) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
            
                if (!empty($startDate) && !empty($endDate)) {
                    if ($startDate == $endDate) {
                        $query->whereDate('created_at', '=', $startDate);
                    } else {
                        $query->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate);
                    }
                }

                $query->with('ticket_detail');
            }, 'user']) 
            ->orderBy('nama_lokasi', 'ASC')
            ->get();

        $locations = $locations->map(function($location) { 
            // Report 1 
            $location->permintaan = $location->tickets->filter(function($ticket) {
                    return isset($ticket->ticket_detail) && $ticket->ticket_detail->jenis_ticket == "permintaan";
                })->count();
            $location->kendala = $location->tickets->filter(function($ticket) {
                    return isset($ticket->ticket_detail) && $ticket->ticket_detail->jenis_ticket == "kendala";
                })->count();
            $location->total = $location->permintaan + $location->kendala;

            return $location;
        });

        
        return view('contents.report.location.index', [
            "url"           => "",
            "title"         => "Report Store & Division",
            "path"          => "Report",
            "path2"         => "Store & Division",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "locations"     => $locations,
            "wilayahs"      => $wilayahs,
        ]);
    }
}