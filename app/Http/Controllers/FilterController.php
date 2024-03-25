<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Ticket;
use App\Location;
use App\Ticket_detail;
use App\Sub_category_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FilterController extends Controller
{
    public function filterDashboard(Request $request)
    {
        // Get data User
        $id         = Auth::user()->id;
        $role       = Auth::user()->role;
        $location   = Auth::user()->location->nama_lokasi;
        $locationId = Auth::user()->location_id;
        $positionId = Auth::user()->position_id;
        
        // Memasukkan request kedalam variabel
        $agent      = $request['filter1'];
        $periode    = $request['filter2'];

        // Jika pilihan filter tidak ada yang dipilih
        if($agent == NULL AND $periode == NULL){
            return redirect('/dashboard');
        }

        // Menentukan Filter by Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter by Periode
        if($periode == "today"){
            $filter2    = date('Y-m-d');
            $pathFilter = date('d F Y');
        }elseif($periode == "monthly"){
            $filter2    = date('Y-m');
            $pathFilter = date('F Y');
        }elseif($periode == "yearly"){
            $filter2    = date('Y');
            $pathFilter = date('Y');
        }else{
            $filter2    = "";
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

            // Jika jabatan Chief
            if($positionId == "2"){
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();

            // Jika jabatan Koordinator Wilayah
            }elseif($positionId == "6"){
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['ticket_area', $ticketKorwil],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->count();
                $unProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();

            // Jika jabatan Manager
            }elseif($positionId == "7"){
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['ticket_area', 'like', $area.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();

            // Jika jabatan selain Korwil, Chief dan Manager
            }else{
                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total      = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->count();
                $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }

            // Mengembalikan data untuk di tampilkan di view
            $dataArray      = [$total, $approval, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", $periode];

        }else{
            // Jika role Service Desk
            if($role == "service desk"){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";

                // Get total data yang ingin di tampilkan di dashboard sesuai filter
                $total          = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess      = Ticket::where([['ticket_for', $location],['status', 'created'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $onProcess      = Ticket::where([['ticket_for', $location],['status', 'onprocess'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $pending        = Ticket::where([['ticket_for', $location],['status', 'pending'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $resolved       = Ticket::where([['ticket_for', $location],['status', 'resolved'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->orWhere([['ticket_for', $location],['status', 'finished'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $assigned       = Ticket_detail::where([['agent_id', 'like', '%'.$filter1],['status', 'assigned'],['created_at', 'like', $filter2.'%']])->count();
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $asset          = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->distinct()->count('asset_id');
                $category       = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')->where([['tickets.ticket_for', $location],['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.created_at', 'like', $filter2.'%']])->distinct()->count(['ticket_details.sub_category_ticket_id']);

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 
                $data1          = Agent::where([['location_id', $locationId],['id', 'like', '%'.$filter1]])
                    ->withCount('ticket_detail')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted") AND tickets.created_at LIKE "' . $filter2 . '%") as total_ticket'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created" AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_unprocessed'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created") AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","onprocess","pending","created") AND tickets.created_at LIKE "' . $filter2 . '%") as ticket_finish'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $filter2 . '%") as processed_time'),
                        DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $filter2 . '%") as avg')
                    )
                    ->get();
                $data2          = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                $data3          = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                $filterArray    = [$agent, $periode];

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
}