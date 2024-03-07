<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Agent;
use App\Ticket;
use App\Ticket_detail;
use App\Location;
use App\Sub_category_ticket;

class FilterController extends Controller
{
    public function filterDashboard($id = 0, $role = 0, Request $request)
    {
        if($request['filter1'] == NULL AND $request['filter2'] == NULL){
            return redirect('/dashboard'.'/'.$id.'-'.$role);
        }

        $id         = decrypt($id);
        $role       = decrypt($role);
        $agent      = $request['filter1'];
        $periode    = $request['filter2'];

        // Menentukan Filter Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter Waktu
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

        // Mengidentifikasi user yang login
        $getUser    = User::where('id', $id)->first();
        $nik        = $getUser['nik'];
        $location   = $getUser->location->nama_lokasi;
        $locationId = $getUser->location_id;
        $positionId = $getUser['position_id'];
        $getAgent   = Agent::where('nik', $nik)->first();
        $agentId    = $getAgent['id'];

        if($role == "client"){ // Jika role Client
            // Mencari nama lokasi user untuk parameter ticket_for atau menampilkan halaman ticket Service Desk
            $getLocation    = Location::where('id', $locationId)->first();
            $namaLokasi     = $getLocation['nama_lokasi'];

            // Mencari area, regional, wilayah user untuk parameter menampilkan halaman ticket bagi Manager, Chief, Korwil
            $area           = substr($getLocation['area'], -1);
            $regional       = substr($getLocation['regional'], -1);
            $wilayah        = substr($getLocation['wilayah'], -2);
            $ticketKorwil   = $area.$regional.$wilayah;
            $ticketChief    = $area.$regional;

            if($positionId == "2"){ // Jika jabatan Chief
                $total      = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $total      = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $total      = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $total      = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }

            $dataArray      = [$total, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", ""];
        }else{ // Jika role Service Desk / Agent
            if($role == "service desk"){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";

                // Menghitung Total Ticket Service Desk
                $total      = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_for', $location],['status', 'created'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $onProcess  = Ticket::where([['ticket_for', $location],['status', 'onprocess'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_for', $location],['status', 'pending'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->count();
                $resolved   = Ticket::where([['ticket_for', $location],['status', 'resolved'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['ticket_for', $location],['status', 'finished'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                    ->count();

                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();

                // Menghitung Total Ticket by Asset
                $asset  = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->distinct()->count('asset_id');

                // Menghitung Total Kategori Kendala
                $category   = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
                                        ->where([['tickets.ticket_for', $location],['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.created_at', 'like', $filter2.'%']])->distinct()->count(['ticket_details.sub_category_ticket_id']);

                $dataArray  = [$total, $unProcess, $onProcess, $pending, $resolved, $workTimeTicket, $freeTimeTicket, $asset, $category]; 

                $data1 = Agent::where([['location_id', $locationId],['id', 'like', '%'.$filter1]])
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
                $data2 = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                $data3 = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                $filterArray = [$agent, $periode];
            }else{ // Role Agent
                $pathFilter = "[".$pathFilter."]";

                // Menghitung Total Ticket Agent
                $total          = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                    ->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned'],['created_at', 'like', $filter2.'%']])->count();

                // Menghitung Workload Agent
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

                $dataArray  = [$total, $resolved, $assigned, $workload, $roundedAvg];

                $data1  = Ticket::where([['agent_id', $agentId],['status', 'created'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya'],['created_at', 'like', $filter2.'%']])->get();
                $data2  = Ticket::where([['agent_id', $agentId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak'],['created_at', 'like', $filter2.'%']])->get();
                $data3  = 0;
                $filterArray = [$agentId, $periode];
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

    // Menampilkan Data Ticket sesuai menu Dashboard yang di klik
    public function filterTicket($status = 0, $filter1 = 0, $filter2 = 0, $id = 0, $role = 0)
    {
        $status     = decrypt($status);
        $agent      = decrypt($filter1);
        $periode    = decrypt($filter2);
        $id         = decrypt($id);
        $role       = decrypt($role);

        // Menentukan Filter Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter Waktu
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

        $getUser    = User::where('id', $id)->first();
        $nik        = $getUser['nik'];
        $location   = $getUser->location->nama_lokasi;
        $locationId = $getUser->location_id;
        $positionId = $getUser['position_id'];
        $getAgent   = Agent::where('nik', $nik)->first();
        $agentId    = $getAgent['id'];

        if($role == "client"){ // Jika role Client
            // Mencari nama lokasi user untuk parameter ticket_for atau menampilkan halaman ticket Service Desk
            $getLocation    = Location::where('id', $locationId)->first();
            $namaLokasi     = $getLocation['nama_lokasi'];

            // Mencari area, regional, wilayah user untuk parameter menampilkan halaman ticket bagi Manager, Chief, Korwil
            $area           = substr($getLocation['area'], -1);
            $regional       = substr($getLocation['regional'], -1);
            $wilayah        = substr($getLocation['wilayah'], -2);
            $ticketKorwil   = $area.$regional.$wilayah;
            $ticketChief    = $area.$regional;

            if($positionId == "2"){ // Jika jabatan Chief
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = $ticket     = Ticket::where([['ticket_area', $ticketKorwil],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $ticket     = Ticket::where([['ticket_area', $ticketKorwil],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                        ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }
            }elseif($positionId == "7"){ // Jika jabatan Manager
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }
        }else{ // Jika role Service Desk / Agent
            if($role == "service desk"){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";
                // Menghitung Total Ticket Service Desk
                if($status == "all"){
                    $title      = "Total Ticket Masuk";
                    $tickets    = Ticket::where([['ticket_for', $location],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['ticket_for', $location],['status', 'created'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['ticket_for', $location],['status', 'onprocess'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_for', $location],['status', 'pending'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "selesai"){
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_for', $location],['status', 'resolved'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                                        ->orWhere([['ticket_for', $location],['status', 'finished'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                                        ->get();
                }elseif($status == "workday"){
                    $title      = "Ticket Masuk Di Jam Kerja";
                    $tickets    = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }else{
                    $title      = "Ticket Masuk Di Luar Jam Kerja";
                    $tickets    = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }
            }else{
                $pathFilter = "[".$pathFilter."]";
                // Menghitung Total Ticket Agent
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "selesai"){
                    $title      = "Ticket Telah Selesai";
                    $tickets    = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                        ->get();
                }else{
                    $title      = "Ticket Telah Di Assign";
                    $tickets    = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                        ->where([['ticket_details.agent_id', $agentId],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                        ->get();
                }
            }
        }

        return view('contents.ticket.filter.index', [
            "title"         => $title,
            "path"          => "Ticket",
            "path2"         => $title,
            "pathFilter"    => $pathFilter,
            "hoAgents"      => Agent::where([['location_id', $locationId],['pic_ticket', 'ho'],['status', 'present']])->whereNotIn('id', [$agentId])->get(),
            "storeAgents"   => Agent::where([['location_id', $locationId],['pic_ticket', 'store'],['status', 'present']])->whereNotIn('id', [$agentId])->get(),
            "tickets"       => $tickets
        ]);
    }

    // Menampilkan Asset Berkendala yang telah/sedang ditangani
    public function filterAsset($status = 0, $filter1 = 0, $filter2 = 0, $id = 0, $role = 0)
    {
        $status     = decrypt($status);
        $agent      = decrypt($filter1);
        $periode    = decrypt($filter2);
        $id         = decrypt($id);
        $role       = decrypt($role);
        $title      = "Asset Berkendala";

        // Menentukan Filter Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter Waktu
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

        $getUser    = User::where('id', $id)->first();
        $location   = $getUser->location->nama_lokasi;
        
        $tickets  = Ticket::where([['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%'],['ticket_for', $location]])->whereNotIn('status', ['deleted'])
            ->groupBy('asset_id')
            ->select('asset_id')
            ->orderBy('asset_id', 'ASC')
            ->get();

        return view('contents.asset.filter.index', [
            "url"           => "",
            "title"         => $title,
            "path"          => "Asset",
            "path2"         => $title,
            "pathFilter"    => "[".$namaAgent."] - [".$pathFilter."]",
            "tickets"       => $tickets
        ]);
    }

    // Menampilkan Kendala yang ditangani oleh agent
    public function filterKendala($status = 0, $filter1 = 0, $filter2 = 0, $location = 0)
    {
        $status     = decrypt($status);
        $agent      = decrypt($filter1);
        $periode    = decrypt($filter2);
        $locationId = decrypt($location);
        $title      = "Kategori Kendala";

        // Menentukan Filter Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter Waktu
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

        // Mendapatkan Lokasi User
        $getLocation    = Location::where('id', $locationId)->first();
        $location       = $getLocation->nama_lokasi;

        $data   = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
        ->where([['tickets.ticket_for', $location],['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.created_at', 'like', $filter2.'%']])->get();

        return view('contents.sub_category_ticket.filter.index', [
            "url"           => "",
            "title"         => $title,
            "path"          => "Sub Category Ticket",
            "path2"         => $title,
            "pathFilter"    => "[".$namaAgent."] - [".$pathFilter."]",
            "data"          => $data
        ]);
    }

    public function filterAgent($status = 0, $filter = 0, $location = 0)
    {
        // $status         = decrypt($status);
        // $filter         = decrypt($filter);
        // $location_id    = decrypt($location);
        // $url            = $status;

        // if($filter == "today"){
        //     $filter2    = date('Y-m-d');
        //     $path2      = "Hari Ini";
        // }elseif($filter == "monthly"){
        //     $filter2    = date('Y-m');
        //     $path2      = "Bulan Ini";
        // }elseif($filter == "yearly"){
        //     $filter2    = date('Y');
        //     $path2      = "Tahun Ini";
        // }else{
        //     $filter2    = "";
        //     $path2      = "Semua";
        // }

        // $data = Agent::where('location_id', $location_id)
        //     ->withCount('ticket_detail')
        //     ->select(
        //         'agents.*', 
        //         DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.created_at LIKE "' . $filter2 . '%") as total_ticket'),
        //         DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $filter2 . '%") as processed_time'),
        //         DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.created_at LIKE "' . $filter2 . '%") as avg')
        //     )
        //     ->get();

        //     return view('contents.agent.index', [
        //     "url"       => $url,
        //     "title"     => "Agent List",
        //     "path"      => "Agent",
        //     "path2"     => $path2,
        //     "data"      => $data
        // ]);
    }
}
