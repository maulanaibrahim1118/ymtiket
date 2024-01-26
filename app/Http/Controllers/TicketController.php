<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\Agent;
use App\User;

class TicketController extends Controller
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
        $locationId = $getUser['location_id'];
        $positionId = $getUser['position_id'];
        $getAgent   = Agent::where('nik', $nik)->first();
        $agentId    = $getAgent['id'];

        if($role == "client"){ // Jika role Client
            if($positionId == "3"){ // Jika jabatan Chief
                
            }elseif($positionId == "7"){ // Jika jabatan Koordinator Wilayah
                
            }elseif($positionId == "8"){ // Jika jabatan Manager
                
            }else{ // Jika jabatan selain Korwil, Chief dan Manager

            }
        }elseif($role == "service desk"){ // Jika role Service Desk
            return view('contents.ticket.index', [
                "url"       => "",
                "title"     => "Ticket List",
                "path"      => "Ticket",
                "tickets"   => Ticket::where([['ticket_for', $locationId],['role', $role]])->get()
            ]);
        }else{ // Jika role Agent
            return view('contents.ticket.index', [
                "url"       => "",
                "title"     => "Ticket List",
                "path"      => "Ticket",
                "tickets"   => Ticket::where([['ticket_for', $locationId],['role', $role],['agent_id', $agentId]])->get()
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
