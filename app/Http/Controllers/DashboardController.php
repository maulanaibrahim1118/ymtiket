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
                    // Get total data yang ingin di tampilkan di dashboard
                    $total      = Ticket::where('code_access', 'like', '%'.$codeAccess.'%')->whereNotIn('status', ['deleted'])->count();
                    $approval   = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['need_approval', 'ya'],['approved', NULL]])->count();
                    $unProcess  = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'created']])->count();
                    $onProcess  = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'onprocess']])->count();
                    $pending    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'pending']])->count();
                    $finished   = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'finished']])->count();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1   = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
                    
                // Jika jabatan Koordinator Wilayah
                }elseif($positionId == 6){
                    // Get total data yang ingin di tampilkan di dashboard
                    $total      = Ticket::where('code_access', 'like', '%'.$codeAccess)->whereNotIn('status', ['deleted'])->count();
                    $approval   = Ticket::where([['code_access', 'like', '%'.$codeAccess],['need_approval', 'ya'],['approved', NULL]])->count();
                    $unProcess  = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'created']])->count();
                    $onProcess  = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'onprocess']])->count();
                    $pending    = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'pending']])->count();
                    $finished   = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'finished']])->count();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1   = Ticket::where([['code_access', '%'.$codeAccess],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();

                // Jika jabatan Manager
                }elseif($positionId == 7){
                    // Get total data yang ingin di tampilkan di dashboard
                    $total      = Ticket::where('code_access', 'like', $codeAccess.'%')->whereNotIn('status', ['deleted'])->count();
                    $approval   = Ticket::where([['code_access', 'like', $codeAccess.'%'],['need_approval', 'ya'],['approved', NULL]])->count();
                    $unProcess  = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'created']])->count();
                    $onProcess  = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'onprocess']])->count();
                    $pending    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'pending']])->count();
                    $finished   = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'finished']])->count();

                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
                // Jika jabatan selain Korwil, Chief dan Manager
                }else{
                    // Get total data yang ingin di tampilkan di dashboard
                    $total      = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->count();
                    $approval   = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL]])->count();
                    $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created']])->count();
                    $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess']])->count();
                    $pending    = Ticket::where([['location_id', $locationId],['status', 'pending']])->count();
                    $finished   = Ticket::where([['location_id', $locationId],['status', 'finished']])->count();
    
                    // Menampilkan Data Ticket yang belum di Close
                    $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
                }
            // Jika bukan Divisi Operational
            }else{
                // Get total data yang ingin di tampilkan di dashboard
                $total      = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->count();
                $approval   = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL]])->count();
                $unProcess  = Ticket::where([['location_id', $locationId],['status', 'created']])->count();
                $onProcess  = Ticket::where([['location_id', $locationId],['status', 'onprocess']])->count();
                $pending    = Ticket::where([['location_id', $locationId],['status', 'pending']])->count();
                $finished   = Ticket::where([['location_id', $locationId],['status', 'finished']])->count();

                // Menampilkan Data Ticket yang belum di Close
                $data1      = Ticket::where([['location_id', $locationId],['status', 'resolved']])->orderBy('created_at', 'DESC')->get();
            }

            // Mengembalikan data untuk di tampilkan di view
            $dataArray      = [$total, $approval, $unProcess, $onProcess, $pending, $finished];
            $data2          = 0;
            $data3          = 0;
            $filterArray    = ["", ""];

        }else{
            // Jika Role Service Desk
            if($role == "1"){
                // Get total data yang ingin di tampilkan di dashboard
                $total          = Ticket::where('ticket_for', $locationId)->whereNotIn('status', ['deleted', 'resolved', 'finished'])->count();
                $unProcess      = Ticket::where([['ticket_for', $locationId],['status', 'created']])->count();
                $onProcess      = Ticket::where([['ticket_for', $locationId],['status', 'onprocess']])->count();
                $pending        = Ticket::where([['ticket_for', $locationId],['status', 'pending']])->count();
                $resolved       = Ticket::where([['ticket_for', $locationId],['status', 'resolved']])->orWhere([['ticket_for', $locationId],['status', 'finished']])->count();
                $assigned       = Ticket_detail::where([['agent_id', $agentId],['status', 'assigned']])->count();
                $workTimeTicket = Ticket::where([['ticket_for', $locationId],['jam_kerja', 'ya']])->whereNotIn('status', ['deleted'])->count();
                $freeTimeTicket = Ticket::where([['ticket_for', $locationId],['jam_kerja', 'tidak']])->whereNotIn('status', ['deleted'])->count();
                $asset          = Ticket::where('ticket_for', $locationId)->whereNotIn('status', ['deleted'])->distinct()->count('asset_id');
                $category       = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')->where('tickets.ticket_for', $locationId)->distinct()->count('ticket_details.sub_category_ticket_id');

                // Mengembalikan data untuk di tampilkan di view
                $dataArray      = [$total, $unProcess, $onProcess, $pending, $resolved, $assigned, $workTimeTicket, $freeTimeTicket, $asset, $category]; 
                $data1          = Agent::where([['location_id', $locationId],['is_active', '1']])
                    ->withCount('ticket_details')
                    ->select(
                        'agents.*', 
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted")) as total_ticket'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","resolved","finished","created")) as ticket_onprocess'),
                        DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN ("deleted","onprocess","pending","created")) as ticket_finish'),
                        DB::raw('(SELECT COUNT(id) FROM ticket_details WHERE ticket_details.agent_id = agents.id AND ticket_details.status = "assigned") as assigned'),
                        // DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status = "created") as ticket_unprocessed'),
                        DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as sum'),
                        DB::raw('(SELECT COUNT(DISTINCT DATE(created_at)) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as working_days')
                        // DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
                    )
                    ->orderBy('sub_divisi', 'ASC')
                    ->get();
                $data2          = Ticket::where([['ticket_for', $locationId],['status','created'],['is_queue', 'tidak'],['assigned', 'tidak']])->get();
                $data3          = Ticket::where([['ticket_for', $locationId],['status','created'],['is_queue', 'ya']])->get();
                $filterArray    = ["", ""];

            // Jika Role Agent
            }else{
                // Get total data yang ingin di tampilkan di dashboard
                $total          = Ticket::where('agent_id', $agentId)->whereNotIn('status', ['deleted', 'resolved', 'finished'])->count();
                $resolved       = Ticket::where([['agent_id', $agentId],['status', 'resolved']])->orWhere([['agent_id', $agentId],['status', 'finished']])->count();
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