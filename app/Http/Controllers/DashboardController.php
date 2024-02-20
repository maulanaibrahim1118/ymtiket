<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Agent;
use App\Ticket;
use App\Ticket_detail;
use App\Location;

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
                $unClosed   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved']])->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $total      = Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])
                                    ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished']])->count();
                $unClosed   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $total      = Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])
                                    ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished']])->count();
                $unClosed   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $total      = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved']])
                                    ->orWhere([['location_id', $locationId],['status', 'finished']])->count();
                $unClosed   = Ticket::where([['location_id', $locationId],['status', 'resolved']])->get();
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
            // Menghitung Total Ticket Agent
            $ticketAgent    = Ticket::where('agent_id', $agentId)->whereNotIn('status', ['deleted'])->count();
            $resolved       = Ticket_detail::where([['agent_id', $agentId],['status', 'resolved']])->count();
            $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();
            $total          = $ticketAgent+$assigned;

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

            return view('contents.dashboard.index', [
                "url"           => "",
                "title"         => "Dashboard",
                "path"          => "Dashboard",
                "path2"         => "Dashboard",
                "agent"         => $agent,
                "onProcess"     => $onProcess,
                "newTicket"     => $newTicket,
                "total"         => $total,
                "resolved"      => $resolved,
                "assigned"      => $assigned,
                "workload"      => $workload,
                "roundedAvg"    => $roundedAvg
            ]);
        }
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
