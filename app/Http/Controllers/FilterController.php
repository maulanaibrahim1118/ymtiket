<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Ticket;
use App\Location;
use Carbon\Carbon;
use App\Ticket_detail;
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
        $role       = Auth::user()->role;
        $location   = Auth::user()->location->nama_lokasi;
        $locationId = Auth::user()->location_id;
        $positionId = Auth::user()->position_id;
        
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
        if($role != "client"){
            $nik        = Auth::user()->nik;
            $getAgent   = Agent::where('nik', $nik)->first();
            $agentId    = $getAgent['id'];
        }

        // Jika role Client
        if($role == "client"){
            // Get data lokasi user untuk menyesuaikan tampilan data ticket Korwil, Chief dan Manager (sesuai ticket area)
            $getLocation    = Location::where('id', $locationId)->first();
            $namaLokasi     = $getLocation['nama_lokasi'];
            $area           = substr($getLocation['area'], -1);
            $regional       = substr($getLocation['regional'], -1);
            $wilayah        = substr($getLocation['wilayah'], -2);

            // Get parameter ticket area untuk Korwil dan Chief
            $ticketKorwil   = $area.$regional.$wilayah;
            $ticketChief    = $area.$regional;

            if($positionId == "2"){ // Jika jabatan Chief
                $ticketArea = $ticketChief;
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $ticketArea = $ticketKorwil;
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $ticketArea = $area;
            }

            if($positionId == "2" || $positionId == "6" || $positionId == "7"){
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $periodeFilter.'%']])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['status', 'created'],['created_at', 'like', $periodeFilter.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['status', 'onprocess'],['created_at', 'like', $periodeFilter.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['status', 'pending'],['created_at', 'like', $periodeFilter.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $ticketArea.'%'],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();

            // Jika jabatan selain Korwil, Chief dan Manager
            }else{
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['location_id', $locationId],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $periodeFilter.'%']])->count();
                $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created'],['created_at', 'like', $periodeFilter.'%']])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess'],['created_at', 'like', $periodeFilter.'%']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending'],['created_at', 'like', $periodeFilter.'%']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $periodeFilter.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $periodeFilter.'%']])->get();
            }

            // Mengembalikan data untuk di tampilkan di view
            $dataArray      = [$total, $approval, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", $filter2];

        }else{
            // Jika role Service Desk
            if($role == "service desk"){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";

                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total          = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess      = Ticket::where([['ticket_for', $location],['status', 'created'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->count();
                $onProcess      = Ticket::where([['ticket_for', $location],['status', 'onprocess'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->count();
                $pending        = Ticket::where([['ticket_for', $location],['status', 'pending'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->count();
                $resolved       = Ticket::where([['ticket_for', $location],['status', 'resolved'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->orWhere([['ticket_for', $location],['status', 'finished'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->count();
                $assigned       = Ticket_detail::where([['agent_id', 'like', '%'.$agentFilter],['status', 'assigned'],['created_at', 'like', $periodeFilter.'%']])->count();
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->count();
                $asset          = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->whereNotIn('status', ['deleted'])->distinct()->count('asset_id');
                $category       = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')->where([['tickets.ticket_for', $location],['ticket_details.agent_id', 'like', '%'.$agentFilter],['ticket_details.created_at', 'like', $periodeFilter.'%']])->distinct()->count(['ticket_details.sub_category_ticket_id']);

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 
                $data1          = Agent::where('location_id', $locationId)
                    ->when($agentFilter, function ($query, $agentFilter) {
                        return $query->where('id', $agentFilter);
                    })
                    ->withCount('ticket_details')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted") AND tickets.created_at LIKE "' . $periodeFilter . '%") as total_ticket'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created") AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","onprocess","pending","created") AND tickets.created_at LIKE "' . $periodeFilter . '%") as ticket_finish'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "assigned" AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as assigned'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created" AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_unprocessed'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as sum'),
                        DB::raw('(SELECT COUNT(DISTINCT CASE WHEN DAYOFWEEK(created_at) NOT IN (1, 7) THEN DATE(created_at) END) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as working_days'),
                        DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $periodeFilter . '%") as avg')
                    )
                    ->orderBy('sub_divisi', 'ASC')
                    ->get();
                $data2          = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->get();
                $data3          = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'ya'],['agent_id', 'like', '%'.$agentFilter],['created_at', 'like', $periodeFilter.'%']])->get();
                $filterArray    = [$filter1, $filter2];

            // Jika role Agent
            }else{
                $pathFilter = "[".$pathFilter."]";

                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total          = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned'],['created_at', 'like', $filter2.'%']])->count();
                $processedTime  = Ticket_detail::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->sum('processed_time');
                $pendingTime    = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->sum('pending_time');
                $workload       = $processedTime-$pendingTime;

                // Menghitung Waktu Rata-rata Ticket Resolved
                $resolvedCount  = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->count();
                $resolvedTime   = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->sum('processed_time');

                if($resolvedCount == 0){
                    $resolvedAvg    = 0;
                    $roundedAvg     = 0;
                }else {
                    $resolvedAvg    = ($resolvedTime-$pendingTime)/$resolvedCount;
                    $roundedAvg     = round($resolvedAvg);
                }

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $resolved, $assigned, $workload, $roundedAvg];
                $data1          = Ticket::where([['agent_id', $agentId],['status', 'created'],['created_at', 'like', $filter2.'%']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya'],['created_at', 'like', $filter2.'%']])->get();
                $data2          = Ticket::where([['agent_id', $agentId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak'],['created_at', 'like', $filter2.'%']])->get();
                $data3          = 0;
                $filterArray    = [$agentId, $periode];
            }
        }

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
            "agents"        => Agent::where('location_id', $locationId)->get()
        ]);
    }

    public function reportAgent(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
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

        $agents = Agent::where('location_id', $locationId)
            ->withCount(['ticket', 'ticket_details'])
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
            
            // Report 4
            $agent->permintaan = $agent->ticket_details->where('status', 'resolved')->where('jenis_ticket', 'permintaan')->average('processed_time');
            $agent->kendala = $agent->ticket_details->where('status', 'resolved')->where('jenis_ticket', 'kendala')->average('processed_time');

            return $agent;
        });
        
        // Menghitung total untuk semua report
        $totalPending       = $agents->sum('ticket_pending');
        $totalOnprocess     = $agents->sum('ticket_onprocess');
        $totalFinish        = $agents->sum('ticket_finish');
        $totalAvgPending    = $agents->sum('avg_pending');
        $totalAvgFinish     = $agents->sum('avg_finish');
        // $totalTicketPerDay  = $agents->sum('ticket_per_day');
        // $totalHourPerDay    = $agents->sum('hour_per_day');
        // $totalPermintaan    = $agents->sum('permintaan');
        // $totalKendala       = $agents->sum('kendala');

        //              0               1               2               3                4                    5                   6                7                8
        $total = [$totalPending, $totalOnprocess, $totalFinish, $totalAvgPending, $totalAvgFinish, /* $totalTicketPerDay, $totalHourPerDay, $totalPermintaan, $totalKendala */];

        return view('contents.report.agent.index', [
            "url"           => "",
            "title"         => "Report Agent",
            "path"          => "Report",
            "path2"         => "Agent",
            "pathFilter"    => $pathFilter,
            "agents"        => $agents,
            "total"         => $total
        ]);
    }
}