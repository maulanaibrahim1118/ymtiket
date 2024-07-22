<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Asset;
use App\Ticket;
use App\Location;
use App\Ticket_detail;
use App\Category_ticket;
use App\Sub_category_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data User
        $id         = Auth::user()->id;
        $role       = Auth::user()->role_id;
        $locationId = Auth::user()->location_id;
        $positionId = Auth::user()->position_id;
        $codeAccess = Auth::user()->code_access;
        
        // Get data Agent (jika user bukan sebagai client)
        if($role != 3){
            $nik        = Auth::user()->nik;
            $getAgent   = Agent::where('nik', $nik)->first();
            $agentId    = $getAgent->id;
        }

        // Jika role Client
        if($role == 3){ 
            // Jika Divisi Operational
            if($locationId == 17){
                // Jika jabatan Chief
                if($positionId == 2){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where('code_access', 'like', '%'.$codeAccess.'%')
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1   = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
                    
                // Jika jabatan Koordinator Wilayah
                }elseif($positionId == 6){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where('code_access', 'like', '%'.$codeAccess)
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1   = Ticket::where([['code_access', '%'.$codeAccess],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();

                // Jika jabatan Manager
                }elseif($positionId == 7){
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where('code_access', 'like', $codeAccess.'%')
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
                // Jika jabatan selain Korwil, Chief dan Manager
                }else{
                    $ticketCounts = Ticket::select(
                        DB::raw('COUNT(CASE WHEN status NOT IN ("deleted") THEN id END) as total'),
                        DB::raw('COUNT(CASE WHEN need_approval = "ya" AND approved IS NULL THEN id END) as approval'),
                        DB::raw('COUNT(CASE WHEN status = "created" THEN id END) as unprocess'),
                        DB::raw('COUNT(CASE WHEN status = "onprocess" THEN id END) as onprocess'),
                        DB::raw('COUNT(CASE WHEN status = "pending" THEN id END) as pending'),
                        DB::raw('COUNT(CASE WHEN status = "finished" THEN id END) as finished')
                    )
                    ->where('location_id', $locationId)
                    ->first();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
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
                ->where('location_id', $locationId)
                ->first();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
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
            $filterArray    = ["", ""];

        }else{
            // Jika Role Service Desk
            if($role == "1"){
                $ticketCounts = Ticket::select(
                    DB::raw('SUM(CASE WHEN status NOT IN ("deleted", "resolved", "finished") THEN 1 ELSE 0 END) as total'),
                    DB::raw('SUM(CASE WHEN status = "created" THEN 1 ELSE 0 END) as unprocess'),
                    DB::raw('SUM(CASE WHEN status = "onprocess" THEN 1 ELSE 0 END) as onprocess'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = "finished" AND  status = "resolved" THEN 1 ELSE 0 END) as resolved'),
                    DB::raw('SUM(CASE WHEN jam_kerja = "ya" AND  status NOT IN ("deleted") THEN 1 ELSE 0 END) as worktime'),
                    DB::raw('SUM(CASE WHEN jam_kerja = "tidak" AND  status NOT IN ("deleted") THEN 1 ELSE 0 END) as freetime'),
                    DB::raw('COUNT(DISTINCT CASE WHEN status NOT IN ("deleted") THEN asset_id END) as asset')
                )
                ->where('ticket_for', $locationId)
                ->first();

                // Get total data yang ingin di tampilkan di dashboard
                $total          = $ticketCounts->total;
                $unProcess      = $ticketCounts->unprocess;
                $onProcess      = $ticketCounts->onprocess;
                $pending        = $ticketCounts->pending;
                $resolved       = $ticketCounts->resolved;
                $workTimeTicket = $ticketCounts->worktime;
                $freeTimeTicket = $ticketCounts->freetime;
                $asset          = $ticketCounts->asset;
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();
                $category       = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')->where('tickets.ticket_for', $locationId)->distinct()->count('ticket_details.sub_category_ticket_id');

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 
                $data1          = Agent::where([['location_id', $locationId],['is_active', '1']])
                    ->withCount('ticket_details')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as total_ticket'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created")) as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created") as created'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "onprocess") as onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "pending") as pending'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "assigned") as assigned'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "resolved") as ticket_finish'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created") as ticket_unprocessed'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as sum'),
                        DB::raw('(SELECT COUNT(DISTINCT CASE WHEN DAYOFWEEK(created_at) NOT IN (1, 7) THEN DATE(created_at) END) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as working_days')
                        // DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
                    )
                    ->orderBy('sub_divisi', 'ASC')
                    ->get();
                $data2          = 0;
                $data3          = Ticket::where([['ticket_for', $locationId],['is_queue', 'ya']])->whereIn('status',['created', 'pending'])->get();
                $filterArray    = ["", ""];

            // Jika Role Agent
            }else{
                $ticketCounts = Ticket::select(
                    DB::raw('SUM(CASE WHEN status NOT IN ("deleted", "resolved", "finished") THEN 1 ELSE 0 END) as total'),
                    DB::raw('SUM(CASE WHEN status = "finished" AND  status = "resolved" THEN 1 ELSE 0 END) as resolved')
                )
                ->where('agent_id', $agentId)
                ->first();

                // Get total data yang ingin di tampilkan di dashboard
                $total          = $ticketCounts->total;
                $resolved       = $ticketCounts->resolved;
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();
                $processedTime  = Ticket_detail::where('agent_id', $agentId)->whereIn('status', ['resolved', 'assigned'])->sum('processed_time');
                $workload       = $processedTime-0;

                // Menghitung Waktu Rata-rata Ticket Resolved
                $processedCount  = Ticket_detail::where('agent_id', $agentId)->whereIn('status', ['resolved', 'assigned'])->count();

                if($processedCount == 0){
                    $resolvedAvg    = 0;
                    $roundedAvg     = 0;
                }else {
                    $processedAvg    = $processedTime/$processedCount;
                    $roundedAvg     = round($processedAvg);
                }

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $resolved, $assigned, $workload, $roundedAvg];
                $data1          = Ticket::where([['agent_id', $agentId],['status', 'created']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'ya']])->get();
                $data2          = Ticket::where([['agent_id', $agentId],['status', 'onprocess']])->orWhere([['agent_id', $agentId],['status', 'pending'],['assigned', 'tidak']])->get();
                $data3          = 0;
                $filterArray    = [$agentId, ""];
            }
        }

        $agents = Agent::where([['location_id', $locationId],['is_active', '1']])->get();

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
            "agents"        => $agents
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