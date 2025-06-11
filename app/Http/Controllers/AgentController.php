<?php

namespace App\Http\Controllers;

use App\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;

        // Get data Agent yang ditampilkan
        $data = Agent::where([['location_id', $locationId],['is_active', '1']])
            ->withCount('ticket_details')
            ->select(
                'agents.*', 
                DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN (\'deleted\')) as total_ticket'),
                DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as processed_time'),
                DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
            )
            ->orderBy('sub_divisi', 'ASC')
            ->orderBy('nama_agent', 'ASC')
            ->get();

        return view('contents.agent.index', [
            "url"   => "",
            "title" => "Agent Panel",
            "path"  => "Agent",
            "path2" => "Agent",
            "data"  => $data
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
        // Log::info($request->input('status'));
        $item = Agent::find($id);
        $item->status = $request->input('status');
        $item->save();

        return response()->json(['success' => true]);
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

    public function agentsRefresh()
    {
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;

        // Get data Agent yang ditampilkan
        $data = Agent::where([['location_id', $locationId],['is_active', '1']])
            ->withCount('ticket_details')
            ->select(
                'agents.*', 
                DB::raw('(SELECT COUNT(id) FROM tickets WHERE tickets.agent_id = agents.id AND tickets.status NOT IN (\'deleted\')) as total_ticket'),
                DB::raw('(SELECT SUM(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as processed_time'),
                DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.agent_id = agents.id) as avg')
            )
            ->orderBy('sub_divisi', 'ASC')
            ->orderBy('nama_agent', 'ASC')
            ->get();
            
        return view('contents.agent.partials.table', compact('data'));
    }
}