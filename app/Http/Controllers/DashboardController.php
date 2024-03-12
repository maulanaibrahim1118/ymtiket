<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Agent;
use App\Asset;
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
                $unProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'created']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved']])
                                    ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1   = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $total      = Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'created']])->count();
                $onProcess  = Ticket::where([['ticket_area', $ticketKorwil],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])
                                    ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1   = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $total      = Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'created']])->count();
                $onProcess  = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'pending']])->count();
                $finished   = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])
                                    ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $total      = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created']])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'resolved']])
                                    ->orWhere([['location_id', $locationId],['status', 'finished']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }

            $dataArray      = [$total, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", ""];
        }else{ 
            if($role == "service desk"){
                // Menghitung Total Ticket Service Desk
                $total      = Ticket::where('ticket_for', $location)->whereNotIn('status', ['deleted'])->count();
                $unProcess  = Ticket::where([['ticket_for', $location],['status', 'created']])->count();
                $onProcess  = Ticket::where([['ticket_for', $location],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['ticket_for', $location],['status', 'pending']])->count();
                $resolved   = Ticket::where([['ticket_for', $location],['status', 'resolved']])
                    ->orWhere([['ticket_for', $location],['status', 'finished']])
                    ->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();

                // Menghitung Ticket Jam Kerja dan Diluar Jam Kerja
                $workTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'ya']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $location],['jam_kerja', 'tidak']])->whereNotIn('status', ['deleted'])->count();

                // Menghitung Total Ticket by Asset
                $asset  = Ticket::where('ticket_for', $location)->whereNotIn('status', ['deleted'])->distinct()->count('asset_id');

                // Menghitung Total Kategori Kendala
                $category   = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
                ->where('tickets.ticket_for', $location)->distinct()->count('ticket_details.sub_category_ticket_id');

                $dataArray  = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 

                $data1 = Agent::where('location_id', $locationId)
                                ->withCount('ticket_detail')
                                ->select(
                                    'agents.*', 
                                    DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted")) as total_ticket'),
                                    DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created") as ticket_unprocessed'),
                                    DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created")) as ticket_onprocess'),
                                    DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","onprocess","pending","created")) as ticket_finish'),
                                    DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as processed_time'),
                                    DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
                                )
                                ->orderBy('sub_divisi', 'ASC')
                                ->get();

                $data2 = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak']])->get();
                $data3 = Ticket::where([['ticket_for', $location],['status','created'],['is_queue', 'ya']])->get();
                $filterArray = ["", ""];
            }else{
                // Menghitung Total Ticket Agent
                $total          = Ticket::where('agent_id', $agentId)->whereNotIn('status', ['deleted'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved']])
                    ->orWhere([['agent_id', $agentId],['status', 'finished']])
                    ->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();

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

                $dataArray  = [$total, $resolved, $assigned, $workload, $roundedAvg];

                $data1  = Ticket::where([['agent_id', $agentId],['status', 'created']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya']])->get();
                $data2  = Ticket::where([['agent_id', $agentId],['status', 'onprocess']])
                                    ->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak']])->get();
                $data3  = 0;
                $filterArray = [$agentId, ""];
            }
        }

        return view('contents.dashboard.index', [
            "title"         => "Dashboard",
            "path"          => "Dashboard",
            "path2"         => "Dashboard",
            "pathFilter"    => "Semua",
            "dataArray"     => $dataArray,
            "filterArray"   => $filterArray,
            "data1"         => $data1,
            "data2"         => $data2,
            "data3"         => $data3,
            "agents"        => Agent::where('location_id', $locationId)->get()
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
