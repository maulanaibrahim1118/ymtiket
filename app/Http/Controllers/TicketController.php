<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Ticket;
use App\Agent;
use App\User;
use App\Client;
use App\Location;
use App\Asset;
use App\Progress_ticket;

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
        
        $getAgent   = Agent::where('nik', $nik)->first();
        $locationId = $getUser['location_id'];
        $positionId = $getUser['position_id'];
        $agentId    = $getAgent['id'];

        $getLocation    = Location::where('id', $locationId)->first();
        $namaLokasi     = $getLocation['nama_lokasi'];
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -1);

        $ticketKorwil   = $area.$regional.$wilayah;
        $ticketChief    = $area.$regional;

        if($role == "client"){ // Jika role Client
            if($positionId == "3"){ // Jika jabatan Chief
                return view('contents.ticket.index', [
                    "url"       => "",
                    "title"     => "Ticket List",
                    "path"      => "Ticket",
                    "tickets"   => Ticket::where('ticket_area', 'like', $ticketChief.'%')->whereNotIn('status', ['deleted'])->get()
                ]);  
            }elseif($positionId == "7"){ // Jika jabatan Koordinator Wilayah
                return view('contents.ticket.index', [
                    "url"       => "",
                    "title"     => "Ticket List",
                    "path"      => "Ticket",
                    "tickets"   => Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->get()
                ]);   
            }elseif($positionId == "8"){ // Jika jabatan Manager
                return view('contents.ticket.index', [
                    "url"       => "",
                    "title"     => "Ticket List",
                    "path"      => "Ticket",
                    "tickets"   => Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->get()
                ]);                
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                return view('contents.ticket.index', [
                    "url"       => "",
                    "title"     => "Ticket List",
                    "path"      => "Ticket",
                    "tickets"   => Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->get()
                ]);
            }
        }elseif($role == "service desk"){ // Jika role Service Desk
            return view('contents.ticket.index', [
                "url"       => "",
                "title"     => "Ticket List",
                "path"      => "Ticket",
                "tickets"   => Ticket::where([['ticket_for', $locationId],['role', $role]])->whereNotIn('status', ['deleted'])->get()
            ]);
        }else{ // Jika role Agent
            return view('contents.ticket.index', [
                "url"       => "",
                "title"     => "Ticket List",
                "path"      => "Ticket",
                "tickets"   => Ticket::where([['ticket_for', $locationId],['role', $role],['agent_id', $agentId]])->whereNotIn('status', ['deleted'])->get()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = 0, $role = 0)
    {
        $id2            = decrypt($id);
        $role2          = decrypt($role);
        $getUser        = User::where('id', $id2)->first();
        $locationId     = $getUser['location_id'];

        $totalTicket    = Ticket::where('location_id', $locationId)->count();
        $ticketClosed   = Ticket::where([['location_id', $locationId],['status', 'closed']])->count();
        $ticketUnclosed = $totalTicket-$ticketClosed;

        $getMY          = date('my');
        $ticketDefault  = $getMY.'0000';
        $countTicket    = Ticket::where('no_ticket', 'LIKE', 'T'.$getMY.'%')->count();

        if($countTicket == 0){ // Jika jumlah ticket nol, nomor dimulai dari angka 1
            $noTicket       = $ticketDefault+1;
            $ticketNumber   = $noTicket;
        }else{ // Jika jumlah ticket > 0, no ticket = jumlah ticket ditambah 1
            $noTicket       = $ticketDefault+$countTicket+1; 
            $ticketNumber   = $noTicket;
        }

        if($role2 == "client"){
            if($ticketUnclosed == 0){
                return view('contents.ticket.create', [
                    "url"           => $id.'-'.$role,
                    "title"         => "Create Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Tambah",
                    "ticketNumber"  => $ticketNumber,
                    "clients"       => Client::where('location_id', $locationId)->orderBy('nama_client', 'ASC')->get()
                ]);
            }else{
                return back()->with('createError', 'Ticket sebelumnya belum di close!');
            }
        }elseif($role2 == "service desk"){
            return view('contents.ticket.create', [
                "url"           => $id.'-'.$role,
                "title"         => "Create Ticket",
                "path"          => "Ticket",
                "path2"         => "Tambah",
                "ticketNumber"  => $ticketNumber,
                "clients"       => Client::orderBy('nama_client', 'ASC')->get()
            ]);
        }
    }

    public function getClient($id = 0)
    {
        $data = Client::where('id',$id)->first();
        return response()->json($data);
    }

    public function getLocation($id = 0)
    {
        $data = Location::where('id',$id)->first();
        return response()->json($data);
    }

    public function getAssets($id = 0)
    {
        $data = Asset::where('location_id', $id)->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validating data request
        $validatedData = $request->validate([
            'client_id'         => 'required',
            'asset_id'          => 'required',
            'ticket_for'        => 'required',
            'kendala'           => 'required|min:5|max:20',
            'detail_kendala'    => 'required|min:10'
        ],
        // Create custom notification for the validation request
        [
            'client_id.required'        => 'Client harus dipilih!',
            'asset_id.required'         => 'No. Asset harus dipilih!',
            'ticket_for.required'       => 'Ditujukan Kepada harus dipilih!',
            'kendala.required'          => 'Kendala harus diisi!',
            'kendala.min'               => 'Ketik minimal 5 digit!',
            'kendala.max'               => 'Ketik maksimal 20 digit!',
            'detail_kendala.required'   => 'Detail Kendala harus diisi!',
            'detail_kendala.min'        => 'Ketik minimal 10 digit!',
        ]);

        $data           = $request->all();
        $ticketFor      = $data['ticket_for'];
        $clientId       = $data['client_id'];
        $getServiceDesk = User::where([['location_id', $ticketFor],['role', 'service desk']])->first();
        $nikServiceDesk = $getServiceDesk['nik'];
        $getAgent       = Agent::where('nik', $nikServiceDesk)->first();
        $agentId        = $getAgent['id'];
        
        $getClient      = Client::where('id', $clientId)->first();
        $locationId     = $getClient['location_id'];
        $getLocation    = Location::where('id', $locationId)->first();
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -1);
        $ticketArea     = $area.$regional.$wilayah;

        // Ambil waktu saat ini
        $currentTime = Carbon::now();

        // Tentukan jam kerja (contoh: 9 pagi hingga 5 sore)
        $workStartTime = Carbon::createFromTime(8, 0, 0);
        $workEndTime = Carbon::createFromTime(17, 0, 0);

        // Periksa apakah waktu saat ini berada dalam rentang jam kerja
        $isWorkTime = $currentTime->between($workStartTime, $workEndTime);

        // Tampilkan hasil
        if ($isWorkTime) {
            $jamKerja = "ya";
        } else {
            $jamKerja = "tidak";
        }

        // Saving data to ticket table
        $ticket                 = new Ticket;
        $ticket->no_ticket      = $data['no_ticket'];
        $ticket->kendala        = $data['kendala'];
        $ticket->detail_kendala = $data['detail_kendala'];
        $ticket->asset_id       = $data['asset_id'];
        $ticket->user_id        = $data['user_id'];
        $ticket->client_id      = $data['client_id'];
        $ticket->location_id    = $data['location_id'];
        $ticket->agent_id       = $agentId;
        $ticket->role           = "service desk";
        $ticket->status         = "created";
        $ticket->need_approval  = "tidak";
        $ticket->jam_kerja      = $jamKerja;
        $ticket->ticket_for     = $data['ticket_for'];
        $ticket->ticket_area    = $ticketArea;
        $ticket->estimated      = "-";
        $ticket->updated_by     = $data['updated_by'];
        $ticket->save();

        // Get id ticket yang baru dibuat
        $ticket_id  = $ticket->id;

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $ticket_id;
        $progress_ticket->tindakan      = "Ticket dibuat oleh";
        $progress_ticket->lama_tindakan = 0;
        $progress_ticket->updated_by    = $data['updated_by'];
        $progress_ticket->save();

        // Redirect to the employee view if create data succeded
        $url = $data['url'];
        return redirect('/tickets'.$url)->with('success', 'Ticket berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
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
    public function update(Request $request, Ticket $ticket)
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
