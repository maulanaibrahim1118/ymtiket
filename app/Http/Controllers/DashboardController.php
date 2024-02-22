<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Agent;
use App\Ticket;
use App\Ticket_detail;
use App\Location;
use App\Category_ticket;
use App\Sub_category_ticket;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0, $role = 0)
    {
        $id         = decrypt($id);
        $role       = decrypt($role);
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
                $total      = Ticket::where('ticket_area', 'like', $ticketChief.'%')->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved']])
                    ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $total      = Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])
                    ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $total      = Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])
                    ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $total      = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved']])
                    ->orWhere([['location_id', $locationId],['status', 'finished']])->count();
                // Menampilkan Data Ticket yang belum di Close                
                $unClosed   = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }

            return view('contents.dashboard.index', [
                "url"           => "",
                "title"         => "Dashboard",
                "path"          => "Dashboard",
                "path2"         => "Dashboard",
                "total"         => $total,
                "onProcess"     => $onProcess,
                "pending"       => $pending,
                "finished"      => $finished,
                "unClosed"      => $unClosed
            ]);
        }else{ // Jika role Agent / Service Desk
            if($role == "agent"){
                // Menghitung Total Ticket Agent
                $total          = Ticket::where('agent_id', $agentId)->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved']])
                    ->orWhere([['agent_id', $agentId],['status', 'finished']])
                    ->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();

                // Menghitung Total Kategori Kendala
                $category       = 0;
                
                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = 0;
                $freeTimeTicket = 0;

                // Menghitung Workload Agent
                $processedTime  = Ticket_detail::where('agent_id', $agentId)->sum('processed_time');
                $pendingTime    = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->sum('pending_time');
                $workload       = $processedTime-$pendingTime;

                // Menghitung Waktu Rata-rata Ticket Resolved
                $resolvedCount  = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->count();
                $resolvedTime   = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->sum('processed_time');

                if($resolvedCount == 0){
                    $resolvedAvg    = 0;
                    $roundedAvg     = 0;
                }else {
                    $resolvedAvg    = ($resolvedTime-$pendingTime)/$resolvedCount;
                    $roundedAvg     = round($resolvedAvg);
                }

                $agent      = Agent::where('nik', $nik)->first();
                $onProcess  = Ticket::where([['agent_id', $agentId],['status', 'onprocess']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak']])->get();
                $newTicket  = Ticket::where([['agent_id', $agentId],['status', 'created']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya']])->get();
            }else{
                // Menghitung Total Ticket Service Desk
                $total          = Ticket::where('ticket_for', $location)->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['ticket_for', $location],['status', 'resolved']])
                    ->orWhere([['ticket_for', $location],['status', 'finished']])
                    ->count();
                $assigned       = 0;

                // Menghitung Total Kategori Kendala
                $category       = Sub_category_ticket::join('category_tickets', 'sub_category_tickets.category_ticket_id', '=', 'category_tickets.id')
                    ->where('category_tickets.location_id', $locationId)
                    ->count();
                
                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak']])->whereNotIn('status', ['deleted'])->count();

                // Menghitung Workload Service Desk
                $processedTime  = Ticket_detail::where('agent_id', $agentId)->sum('processed_time');
                $pendingTime    = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->sum('pending_time');
                $workload       = $processedTime-$pendingTime;

                // Menghitung Waktu Rata-rata Ticket Resolved
                $resolvedCount  = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->count();
                $resolvedTime   = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->sum('processed_time');

                if($resolvedCount == 0){
                    $resolvedAvg    = 0;
                    $roundedAvg     = 0;
                }else {
                    $resolvedAvg    = ($resolvedTime-$pendingTime)/$resolvedCount;
                    $roundedAvg     = round($resolvedAvg);
                }

                $agent      = Agent::where('nik', $nik)->first();
                $onProcess  = Ticket::where([['ticket_for', $location],['status', 'onprocess']])
                                    ->orWhere([['ticket_for', $location],['status', 'pending'],['assigned', 'tidak']])->orderBy('created_at', 'DESC')->get();
                $newTicket  = Ticket::where([['ticket_for', $location],['status', 'created']])
                                    ->orWhere([['ticket_for', $location],['status', 'pending'],['assigned', 'ya']])->orderBy('created_at', 'DESC')->get();
            }

            return view('contents.dashboard.index', [
                "url"               => "",
                "title"             => "Dashboard",
                "path"              => "Dashboard",
                "path2"             => "Dashboard",
                "agent"             => $agent,
                "onProcess"         => $onProcess,
                "newTicket"         => $newTicket,
                "total"             => $total,
                "resolved"          => $resolved,
                "assigned"          => $assigned,
                "category"          => $category,
                "workTimeTicket"    => $workTimeTicket,
                "freeTimeTicket"    => $freeTimeTicket,
                "workload"          => $workload,
                "roundedAvg"        => $roundedAvg
            ]);
        }
    }

    public function filter($filter = 0, $id = 0, $role = 0)
    {
        $id         = decrypt($id);
        $role       = decrypt($role);
        $filter     = decrypt($filter);

        if($filter == "today"){
            $filter2    = date('Y-m-d');
            $path2      = "Hari Ini";
        }elseif($filter == "monthly"){
            $filter2    = date('Y-m');
            $path2      = "Bulan Ini";
        }else{
            $filter2    = date('Y');
            $path2      = "Tahun Ini";
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
                $total      = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $total      = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $total      = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $total      = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending'],['created_at', 'like', $filter2.'%']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                // Menampilkan Data Ticket yang belum di Close
                $unClosed   = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])->get();
            }

            return view('contents.dashboard.index', [
                "url"           => "",
                "title"         => "Dashboard",
                "path"          => "Dashboard",
                "path2"         => $path2,
                "total"         => $total,
                "onProcess"     => $onProcess,
                "pending"       => $pending,
                "finished"      => $finished,
                "unClosed"      => $unClosed
            ]);
        }else{ // Jika role Agent / Service Desk
            if($role == "agent"){
                // Menghitung Total Ticket Agent
                $ticketAgent    = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                    ->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned'],['created_at', 'like', $filter2.'%']])->count();
                $total          = $ticketAgent+$assigned;

                // Menghitung Total Kategori Kendala
                $category       = 0;
                
                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = 0;
                $freeTimeTicket = 0;

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

                $agent      = Agent::where('nik', $nik)->first();
                $onProcess  = Ticket::where([['agent_id', $agentId],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak'],['created_at', 'like', $filter2.'%']])->get();
                $newTicket  = Ticket::where([['agent_id', $agentId],['status', 'created'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya'],['created_at', 'like', $filter2.'%']])->get();
            }else{
                // Menghitung Total Ticket Service Desk
                $total      = Ticket::where([['ticket_for', $location],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $resolved   = Ticket::where([['ticket_for', $location],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                    ->orWhere([['ticket_for', $location],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                    ->count();
                $assigned   = 0;

                // Menghitung Total Kategori Kendala
                $category       = Sub_category_ticket::join('category_tickets', 'sub_category_tickets.category_ticket_id', '=', 'category_tickets.id')
                    ->where('category_tickets.location_id', $locationId)
                    ->count();
                
                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();

                // Menghitung Workload Service Desk
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

                $agent      = Agent::where('nik', $nik)->first();
                $onProcess  = Ticket::where([['ticket_for', $location],['status', 'onprocess'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['ticket_for', $location],['status', 'pending'],['assigned', 'tidak'],['created_at', 'like', $filter2.'%']])->get();
                $newTicket  = Ticket::where([['ticket_for', $location],['status', 'created'],['created_at', 'like', $filter2.'%']])
                                    ->orWhere([['ticket_for', $location],['status', 'pending'],['assigned', 'ya'],['created_at', 'like', $filter2.'%']])->get();
            }

            return view('contents.dashboard.index', [
                "url"               => "",
                "title"             => "Dashboard",
                "path"              => "Dashboard",
                "path2"             => $path2,
                "agent"             => $agent,
                "onProcess"         => $onProcess,
                "newTicket"         => $newTicket,
                "total"             => $total,
                "resolved"          => $resolved,
                "assigned"          => $assigned,
                "category"          => $category,
                "workTimeTicket"    => $workTimeTicket,
                "freeTimeTicket"    => $freeTimeTicket,
                "workload"          => $workload,
                "roundedAvg"        => $roundedAvg
            ]);
        }
    }

    public function ticket($status = 0, $filter = 0, $id = 0, $role = 0)
    {
        $status     = decrypt($status);
        $filter     = decrypt($filter);
        $id         = decrypt($id);
        $role       = decrypt($role);
        $url        = $status;

        if($filter == "today"){
            $filter2    = date('Y-m-d');
            $path2      = "Hari Ini";
        }elseif($filter == "monthly"){
            $filter2    = date('Y-m');
            $path2      = "Bulan Ini";
        }elseif($filter == "yearly"){
            $filter2    = date('Y');
            $path2      = "Tahun Ini";
        }else{
            $filter2    = "";
            $path2      = "All";
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
                if($status == "All"){
                    $url        = "";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "Selesai"){
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', strtolower($status)],['created_at', 'like', $filter2.'%']])->count();
                }
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                if($status == "All"){
                    $url        = "";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "Selesai"){
                    $tickets   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                        ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $ticket    = Ticket::where([['ticket_area', $ticketKorwil],['status', strtolower($status)],['created_at', 'like', $filter2.'%']])->count();
                }
            }elseif($positionId == "7"){ // Jika jabatan Manager
                if($status == "All"){
                    $url        = "";
                    $tickets      = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->count();
                }elseif($status == "Selesai"){
                    $tickets   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->count();
                }else{
                    $tickets  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', strtolower($status)],['created_at', 'like', $filter2.'%']])->count();
                }
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                if($status == "All"){
                    $url        = "";
                    $tickets    = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "Selesai"){
                    $tickets    = Ticket::where([['location_id', $locationId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $tickets    = Ticket::where([['location_id', $locationId],['status', strtolower($status)],['created_at', 'like', $filter2.'%']])->get();
                }
            }
        }else{ // Jika role Agent / Service Desk
            if($role == "agent"){
                // Menghitung Total Ticket Agent
                if($status == "All"){
                    $url        = "";
                    $tickets    = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "Selesai"){
                    $tickets       = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                        ->get();
                }else{
                    $tickets       = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                        ->where([['ticket_details.agent_id', $agentId],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                        ->get();
                }
            }else{
                // Menghitung Total Ticket Service Desk
                if($status == "All"){
                    $url        = "";
                    $tickets    = Ticket::where([['ticket_for', $location],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "Selesai"){
                    $tickets   = Ticket::where([['ticket_for', $location],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_for', $location],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                        ->get();
                }elseif($status == "Jam Kerja"){
                    $tickets    = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }else{
                    $tickets    = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }
            }
        }

        return view('contents.ticket.index', [
            "url"       => $url,
            "title"     => "Ticket",
            "path"      => "Ticket",
            "path2"     => $status." - ".$path2,
            "agents"    => Agent::where('location_id', $locationId)->whereNotIn('id', [$agentId])->get(),
            "tickets"   => $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
