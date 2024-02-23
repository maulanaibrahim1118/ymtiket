<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Ticket;
use App\Ticket_detail;
use App\Agent;
use App\User;
use App\Client;
use App\Location;
use App\Asset;
use App\Progress_ticket;
use App\National_holiday;
use App\Category_ticket;
use App\Sub_category_ticket;

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

        // Mencari nik dan lokasi user untuk parameter menampilkan halaman client
        $getUser    = User::where('id', $id)->first();
        $nik        = $getUser['nik'];
        $locationId = $getUser['location_id'];
        $positionId = $getUser['position_id'];

        // Mencari agent_id untuk parameter menampilkan halaman ticket Agent
        $getAgent   = Agent::where('nik', $nik)->first();
        $agentId    = $getAgent['id'];
        
        // Mencari nama lokasi user untuk parameter ticket_for atau menampilkan halaman ticket Service Desk
        $getLocation    = Location::where('id', $locationId)->first();
        $namaLokasi     = $getLocation['nama_lokasi'];

        // Mencari area, regional, wilayah user untuk parameter menampilkan halaman ticket bagi Manager, Chief, Korwil
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -2);
        $ticketKorwil   = $area.$regional.$wilayah;
        $ticketChief    = $area.$regional;

        if($role == "client"){ // Jika role Client
            if($positionId == "2"){ // Jika jabatan Chief
                $tickets    = Ticket::where('ticket_area', 'like', $ticketChief.'%')->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                $tickets    = Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
            }elseif($positionId == "7"){ // Jika jabatan Manager
                $tickets    = Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                $tickets    = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
            }
        }elseif($role == "service desk"){ // Jika role Service Desk
            $tickets    = Ticket::where('ticket_for', $namaLokasi)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
        }else{ // Jika role Agent
            $tickets    = Ticket::where([['ticket_for', $namaLokasi],['role', $role],['agent_id', $agentId]])->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
        }

        return view('contents.ticket.index', [
            "url"       => "",
            "title"     => "Ticket",
            "path"      => "Ticket",
            "path2"     => "Ticket",
            "agents"    => Agent::where('location_id', $locationId)->whereNotIn('id', [$agentId])->get(),
            "tickets"   => $tickets
        ]);
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

        $totalTicket    = Ticket::where('user_id', $id2)->count();
        $ticketClosed   = Ticket::where([['user_id', $id2],['status', 'finished']])->count();
        $ticketDeleted  = Ticket::where([['user_id', $id2],['status', 'deleted']])->count();
        $ticketUnclosed = $totalTicket-$ticketClosed-$ticketDeleted;

        $ticketFors  = ["information technology", "inventory control", "project me", "project sipil"];

        if($role2 == "client"){
            if($ticketUnclosed == 0){ // Jika tidak ada ticket yang belum di close
                return view('contents.ticket.create', [
                    "url"           => '/tickets'.'/'.$id.'-'.$role,
                    "title"         => "Create Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Tambah",
                    "ticketNumber"  => $ticketNumber,
                    "ticketFors"    => $ticketFors,
                    "clients"       => Client::where('location_id', $locationId)->orderBy('nama_client', 'ASC')->get()
                ]);
            }else{ // Jika masih ada ticket yang belum di close
                return back()->with('createError', 'Ticket sebelumnya belum selesai / belum ditutup!');
            }
        }elseif($role2 == "service desk"){
            return view('contents.ticket.create', [
                "url"           => '/tickets'.'/'.$id.'-'.$role,
                "title"         => "Create Ticket",
                "path"          => "Ticket",
                "path2"         => "Tambah",
                "ticketFors"    => $ticketFors,
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
            'kendala'           => 'required|min:5|max:50',
            'detail_kendala'    => 'required|min:10',
            'file'              => 'required|max:1024',
        ],
        // Create custom notification for the validation request
        [
            'client_id.required'        => 'Client harus dipilih!',
            'asset_id.required'         => 'No. Asset harus dipilih!',
            'ticket_for.required'       => 'Ditujukan Kepada harus dipilih!',
            'kendala.required'          => 'Kendala harus diisi!',
            'kendala.min'               => 'Ketik minimal 5 digit!',
            'kendala.max'               => 'Ketik maksimal 50 digit!',
            'detail_kendala.required'   => 'Detail Kendala harus diisi!',
            'detail_kendala.min'        => 'Ketik minimal 10 digit!',
            'file.required'             => 'Lampiran harus diisi!',
            'file.max'                  => 'Maksimal ukuran file 1Mb!',
        ]);

        if($request['file'] == NULL){
            $imageName  = NULL;
        }else{
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $imageName);
        }
        
        // Menampung semua request
        $data       = $request->all();
        $updatedBy  = $data['updated_by'];

        // Mencari Service Desk berdasarkan ticket_for
        $ticketFor      = $data['ticket_for'];
        $getLocAgent    = Location::where('nama_lokasi', $ticketFor)->first();
        $locIdAgent     = $getLocAgent['id'];
        $getServiceDesk = User::where([['location_id', $locIdAgent],['role', 'service desk']])->first();
        $nikServiceDesk = $getServiceDesk['nik'];
        $getAgent       = Agent::where('nik', $nikServiceDesk)->first();
        $agentId        = $getAgent['id'];
        
        // Mencari Area, Regional, Wilayah Client untuk mengisi data ticket_area
        $clientId       = $data['client_id'];
        $getClient      = Client::where('id', $clientId)->first();
        $locationId     = $getClient['location_id'];
        $getLocation    = Location::where('id', $locationId)->first();
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -2);
        $ticketArea     = $area.$regional.$wilayah;

        // Ambil waktu saat ini
        $currentDay     = date('D');
        $currentDate    = date('d-m-y');
        $currentTime    = Carbon::now();

        // Mencari apakah tanggal sekarang merupakan libur nasional atau bukan
        $checkHoliday   = National_holiday::where('tanggal', $currentDate)->count();

        // Tentukan hari dam jam kerja (contoh: 9 pagi hingga 5 sore)
        $workStartTime  = Carbon::createFromTime(8, 0, 0);
        $workEndTime    = Carbon::createFromTime(17, 0, 0);

        // Periksa apakah waktu saat ini berada dalam rentang hari dan jam kerja
        $isWorkTime     = $currentTime->between($workStartTime, $workEndTime);

        // Tampilkan hasil
        if ($currentDay == "SAT" or $currentDay == "SUN" or $checkHoliday == 1){ // Jika hari ini, merupakan hari sabtu atau minggu atau hari libur nasional
            $jamKerja = "tidak";
        }else{ // Jika hari ini, bukan hari sabtu atau minggu atau hari libur nasional
            if ($isWorkTime) {
                $jamKerja = "ya";
            } else {
                $jamKerja = "tidak";
            }
        }

        // Saving data to ticket table
        $ticket                 = new Ticket;
        $ticket->kendala        = $data['kendala'];
        $ticket->detail_kendala = $data['detail_kendala'];
        $ticket->asset_id       = $data['asset_id'];
        $ticket->user_id        = $data['user_id'];
        $ticket->client_id      = $data['client_id'];
        $ticket->location_id    = $data['location_id'];
        $ticket->agent_id       = $agentId;
        $ticket->role           = "service desk";
        $ticket->status         = "created";
        $ticket->is_queue       = "tidak";
        $ticket->assigned       = "tidak";
        $ticket->need_approval  = "tidak";
        $ticket->jam_kerja      = $jamKerja;
        $ticket->ticket_for     = $data['ticket_for'];
        $ticket->ticket_area    = $ticketArea;
        $ticket->estimated      = "-";
        $ticket->file           = $imageName ?? null;
        $ticket->updated_by     = $updatedBy;
        $ticket->save();

        // Get id ticket yang baru dibuat
        $ticket_id  = $ticket->id;
        $now        = date('d-m-Y H:i:s');

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $ticket_id;
        $progress_ticket->tindakan      = "Ticket di buat oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "created";
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        // Redirect to the employee view if create data succeded
        $url = $data['url'];
        return redirect($url)->with('success', 'Ticket berhasil dibuat!');
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
    public function edit($id = 0, $role = 0, Ticket $ticket)
    {
        $id2            = decrypt($id);
        $role2          = decrypt($role);
        $getUser        = User::where('id', $id2)->first();
        $locationId     = $getUser['location_id'];

        $ticketFors     = ["information technology", "inventory control", "project me", "project sipil"];

        if($role2 == "client"){
            return view('contents.ticket.edit', [
                "url"           => '/tickets'.'/'.$id.'-'.$role,
                "title"         => "Edit Ticket",
                "path"          => "Ticket",
                "path2"         => "Edit",
                "ticket"        => $ticket,
                "ticketFors"    => $ticketFors,
                "assets"        => Asset::where('location_id', $ticket->location_id)->get(),
                "clients"       => Client::where('location_id', $locationId)->orderBy('nama_client', 'ASC')->get()
            ]);
        }elseif($role2 == "service desk"){
            return view('contents.ticket.edit', [
                "url"           => '/tickets'.'/'.$id.'-'.$role,
                "title"         => "Edit Ticket",
                "path"          => "Ticket",
                "path2"         => "Edit",
                "ticket"        => $ticket,
                "ticketFors"    => $ticketFors,
                "assets"        => Asset::where('location_id', $ticket->location_id)->get(),
                "clients"       => Client::orderBy('nama_client', 'ASC')->get()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $id)
    {
        $updatedBy  = $request['updated_by'];

        // Validating data request
        $rules = [
            'client_id'         => 'required',
            'asset_id'          => 'required',
            'ticket_for'        => 'required',
            'kendala'           => 'required|min:5|max:50',
            'detail_kendala'    => 'required|min:10',
            'file'              => 'required|max:1024',
            'updated_by'        => 'required'
        ];

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'client_id.required'        => 'Client harus dipilih!',
            'asset_id.required'         => 'No. Asset harus dipilih!',
            'ticket_for.required'       => 'Ditujukan Kepada harus dipilih!',
            'kendala.required'          => 'Kendala harus diisi!',
            'kendala.min'               => 'Ketik minimal 5 digit!',
            'kendala.max'               => 'Ketik maksimal 50 digit!',
            'detail_kendala.required'   => 'Detail Kendala harus diisi!',
            'detail_kendala.min'        => 'Ketik minimal 10 digit!',
            'updated_by.required'       => 'Wajib diisi!',
            'file.required'             => 'Lampiran harus diisi!',
            'file.max'                  => 'Maksimal ukuran file 1Mb!',
        ]);

        $newFile    = $request['file'];
        $oldFile    = $request['old_file'];

        if($newFile == NULL){
            $imageName  = $oldFile;
        }else{
            $imageName  = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $imageName);
        }

        // Updating data to ticket table
        Ticket::where('id', $id->id)->update([
            'client_id'         => $request['client_id'],
            'asset_id'          => $request['asset_id'],
            'ticket_for'        => $request['ticket_for'],
            'kendala'           => $request['kendala'],
            'detail_kendala'    => $request['detail_kendala'],
            'file'              => $imageName,
            'updated_by'        => $request['client_id']
        ]);

        // Get id ticket yang baru dibuat
        $ticket_id  = $id->id;
        $now        = date('d-m-Y H:i:s');

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $ticket_id;
        $progress_ticket->tindakan      = "Ticket di edit oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "edited";
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();
        
        $url = $request['url'];
        return redirect($url)->with('success', 'Ticket berhasil di edit!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    // Proses ticket yang baru dibuat atau status created (role: service desk)
    public function process1(Request $request, $id = 0){
        $id2        = decrypt($id);
        $now        = date('d-m-Y H:i:s');
        $nikAgent   = $request['nik'];

        // Mengganti status ticket dan memulai hitung waktu proses ticket
        Ticket::where('id', $id2)->update([
            'status'        => "onprocess",
            'process_at'    => $now,
            'assigned'      => "tidak",
            'is_queue'      => "tidak",
            'updated_by'    => $request['updated_by']
        ]);

        // Menampilkan halaman proses ticket
        return redirect('/ticket-details'.'/'.$id.'/create');
    }

    // Proses ticket yang sudah pernah di proses sebelumnya oleh service desk/agent lain
    public function process2(Request $request, $id = 0){
        $id2            = decrypt($id);
        $status         = "onprocess";
        $now            = date('d-m-Y H:i:s');
        $types          = ["kendala", "permintaan"];
        $nikAgent       = $request['nik'];
        $ticket         = Ticket::where('id', $id2)->first();
        $now            = Carbon::parse($now);
        $pendingAt      = Carbon::parse($ticket->pending_at);
        $pending_time   = $pendingAt->diffInSeconds($now);

        $agentId        = $ticket->agent_id;
        $ticket_detail  = Ticket_detail::where('ticket_id', $id2)->latest()->first();
        $subCategoryId  = $ticket_detail->sub_category_ticket_id;
        $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
        $categoryId     = $subCategory->category_ticket_id;

        // Updating data to ticket table
        Ticket::where('id', $id2)->update([
            'status'        => $status,
            'process_at'    => $now,
            'pending_at'    => "-",
            'assigned'      => "tidak",
            'pending_time'  => $pending_time,
            'updated_by'    => $request['updated_by']
        ]);

        Agent::where('nik', $nikAgent)->update([
            'status'        => "working",
            'updated_by'    => $request['updated_by']
        ]);

        return view('contents.ticket_detail.edit', [
            "title"                 => "Tangani Ticket",
            "path"                  => "Ticket",
            "path2"                 => "Tangani",
            "category_tickets"      => Category_ticket::all(),
            "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
            "ticket"                => Ticket::where('id', $id2)->first(),
            "td"                    => Ticket_detail::where('ticket_id', $id2)->latest()->first(),
            "types"                 => $types
        ]);
    }

    public function queue(Request $request, $id)
    {
        Ticket::where('id', $id)->update([
            'is_queue'      => "ya",
            'updated_by'    => $request['updated_by']
        ]);
        return back()->with('success', 'Ticket berhasil di antrikan!');
    }

    // Assign pada saat status ticket masih created (role: service desk)
    public function assign(Request $request)
    {
        $updatedBy  = $request['updated_by'];
        if($request['agent_id'] == NULL){
            return back()->with('error', 'Nama Agent harus dipilih!');
        }else {
            $getAgent   = Agent::where('id', $request['agent_id'])->first();
            $agentName  = $getAgent->nama_agent;
            $agentId    = $getAgent->id;
            $now        = date('d-m-Y H:i:s');

            Ticket::where('id', $request['ticket_id'])->update([
                'assigned'      => "ya",
                'agent_id'      => $agentId,
                'updated_by'    => $request['updated_by'],
                'role'          => "agent"
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $request['ticket_id'];
            $progress_ticket->tindakan      = "Ticket di assign ke ".ucwords($agentName)." oleh ".ucwords($updatedBy);
            $progress_ticket->status        = "assigned";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            return back()->with('success', 'Ticket berhasil di assign ke '.ucwords($agentName).'!');
        }
    }

    // Assign pada saat status ticket sudah onprocess (role: service desk/agent)
    public function assign2(Request $request)
    {
        if($request['agent_id'] == NULL){
            return back()->with('error', 'Nama Agent harus dipilih!');
        }else {
            $agentId1   = $request['agent_id1']; // Agent sebelumnya (yang meng assign ticket)
            $ticketId   = $request['ticket_id'];
            $updatedBy  = $request['updated_by'];
            $url        = $request['url'];

            $getAgent   = Agent::where('id', $request['agent_id'])->first(); // Agent yang menerima ticket assign
            $agentName2 = $getAgent->nama_agent;
            $agentId2   = $getAgent->id;
            $now        = date('d-m-Y H:i:s');

            // Updating data to ticket table
            Ticket::where('id', $ticketId)->update([
                'status'        => "pending",
                'pending_at'    => $now,
                'assigned'      => "ya",
                'agent_id'      => $agentId2,
                'updated_by'    => $updatedBy,
                'role'          => "agent"
            ]);

            $getTicketDetail    = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1]])->first();
            $subCategoryId      = $getTicketDetail->sub_category_ticket_id;
            $biaya              = $getTicketDetail->biaya;
            $note               = $getTicketDetail->note;
            $processAt          = Carbon::parse($getTicketDetail->process_at);
            $now                = Carbon::parse($now);
            $processedTime      = $processAt->diffInSeconds($now);

            // Updating data to ticket detail table (agent pertama)
            Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1]])->update([
                'processed_time'    => $processedTime,
                'status'            => "assigned",
                'updated_by'        => $updatedBy
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket di pending oleh Sistem";
            $progress_ticket->status        = "pending";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = "sistem";
            $progress_ticket->save();
        
            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticketId;
            $progress_ticket->tindakan      = "Ticket di assign ke ".ucwords($agentName2)." oleh ".ucwords($updatedBy);
            $progress_ticket->status        = "assigned";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            return redirect($url)->with('success', 'Ticket berhasil di assign ke '.ucwords($agentName2).'!');
        }
    }

    public function pending($id = 0, Request $request)
    {
        $alasan     = $request['alasanPending'];

        if($alasan == NULL){
            return back()->with('error', 'Tolong sebutkan alasan pending!');
        }else {
            $now        = date('d-m-Y H:i:s');
            $status     = "pending";
            $updatedBy  = $request['updated_by'];

            // Mencari agent_id untuk merubah pending at pada tabel ticket detail
            $agentNik   = $request['nik'];
            $getAgent   = Agent::where('nik', $agentNik)->first();
            $agentId    = $getAgent->id;
            
            // Updating data to ticket table
            Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->update([
                'pending_at'    => $now,
                'status'        => $status
            ]);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'pending_at'    => $now,
                'status'        => $status
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di pending oleh ".ucwords($updatedBy)." (Alasan : ".$alasan.")";
            $progress_ticket->status        = $status;
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            return redirect($request['url'])->with('success', 'Ticket berhasil di pending!');
        }
    }

    // Proses kembali jika status pending
    public function reProcess1($id = 0, Request $request)
    {
        $updatedBy  = $request['updated_by'];
        // Mencari agent_id untuk merubah pending_time pada tabel ticket detail
        $agentNik   = $request['nik'];
        $getAgent   = Agent::where('nik', $agentNik)->first();
        $agentId    = $getAgent->id;

        // Mencari tanggal/waktu pending untuk menghitung total waktu pending
        $getTicketDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->first();
        $getPending1        = $getTicketDetail->pending_time;
        $now                = date('d-m-Y H:i:s');
        $reProcess_at       = Carbon::parse($now);
        $pending_at1        = Carbon::parse($getTicketDetail->pending_at);
        $pending_at2        = '-';
        $status             = "onprocess";

        // Mencari lama nya waktu ticket di pending
        $getPending2    = $pending_at1->diffInSeconds($reProcess_at);
        $pending_time   = $getPending1+$getPending2;
        
        // Updating data to ticket table
        Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->update([
            'pending_at'    => $pending_at2,
            'pending_time'  => $pending_time,
            'status'        => $status
        ]);

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'pending_at'    => $pending_at2,
            'pending_time'  => $pending_time,
            'status'        => $status
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di proses ulang oleh ".ucwords($updatedBy);
        $progress_ticket->status        = $status;
        $progress_ticket->process_at    = $now;
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        return redirect('/ticket-details'.'/'.encrypt($id));
    }

    // Proses kembali jika status onprocess (melanjutkan proses ticket)
    public function reProcess2($id = 0){
        $getTicket      = Ticket::where('id', $id)->first();
        $agentId        = $getTicket->agent_id;
        $countDetailAll = Ticket_detail::where('ticket_id', $id)->count();
        $countDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->count();
        if($countDetail == NULL){
            if($countDetailAll == NULL){
                return redirect('/ticket-details'.'/'.encrypt($id).'/create');
            }else{
                $ticket_detail  = Ticket_detail::where('ticket_id', $id)->latest()->first();
                $subCategoryId  = $ticket_detail->sub_category_ticket_id;
                $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
                $categoryId     = $subCategory->category_ticket_id;
                $types          = ["kendala", "permintaan"];

                return view('contents.ticket_detail.edit', [
                    "title"                 => "Tangani Ticket",
                    "path"                  => "Ticket",
                    "path2"                 => "Tangani",
                    "category_tickets"      => Category_ticket::all(),
                    "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
                    "ticket"                => Ticket::where('id', $id)->first(),
                    "td"                    => Ticket_detail::where('ticket_id', $id)->latest()->first(),
                    "types"                 => $types
                ]);
            }
        }else {
            return redirect('/ticket-details'.'/'.encrypt($id));
        }
    }

    public function resolved(Request $request, $id)
    {
        $agentId        = $request['agent_id'];
        $updatedBy      = $request['updated_by'];
        $url            = $request['url'];
        $role           = $request['role'];
        $now            = date('d-m-Y H:i:s');

        // Mencari lamanya ticket di proses
        $getTicket      = Ticket::where('id', $id)->first();
        $processAt1     = Carbon::parse($getTicket->process_at);
        $now            = Carbon::parse($now);
        $processedTime1 = $processAt1->diffInSeconds($now);


        // Mencari lamanya ticket di proses berdasarkan agent/service desk
        $getDetail      = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->first();
        $processAt2     = Carbon::parse($getDetail->process_at);
        $now            = Carbon::parse($now);
        $processedTime2 = $processAt2->diffInSeconds($now);

        Ticket::where('id', $id)->update([
            'status'            => "resolved",
            'processed_time'    => $processedTime1,
            'updated_by'        => $updatedBy
        ]);

        Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->update([
            'status'            => "resolved",
            'processed_time'    => $processedTime2,
            'updated_by'        => $updatedBy
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket telah selesai di proses oleh ".ucwords($updatedBy);
        $progress_ticket->status        = "resolved";
        $progress_ticket->process_at    = $now;
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        if($role == "service desk"){
            return redirect($url)->with('success', 'Ticket telah selesai diproses!');
        }else{
            $nik            = $request['nik'];
            $getAgent       = Agent::where('nik', $nik)->first();
            $agentId2       = $getAgent->id;
            $agentStatus    = $getAgent->status;

            $countAntrian   = Ticket::where('is_queue', 'ya')->count();

            if($countAntrian == NULL){ // Jika antrian ticket sudah habis
                return redirect($url)->with('success', 'Ticket telah selesai diproses!');
            }else {
                if($agentStatus != 'present'){ // Jika Agent tidak hadir, izin, keluar kota, dll
                    return redirect($url)->with('success', 'Ticket telah selesai diproses!');
                }else{ // Jika agent hadir di kantor
                    $getAntrian     = Ticket::where('is_queue', 'ya')->first();
                    $ticketId       = $getAntrian->id;
                    Ticket::where('id', $ticketId)->update([
                        'agent_id'      => $agentId2,
                        'role'          => "agent",
                        'is_queue'      => "tidak",
                        'updated_by'    => $updatedBy
                    ]);
        
                    return redirect($url)->with('success', 'Ticket telah selesai diproses!');
                }
            }
        }
    }

    public function finished(Request $request, $id)
    {
        $status = $request['closedStatus'];

        // Menampilkan pesan error jika status closed tidak dipilih
        if($status == NULL){
            return back()->with('error', 'Status Closed harus dipilih!');
        }

        $now            = date('d-m-Y H:i:s');
        $alasanClosed   = $request['alasanClosed'];
        $updatedBy      = $request['updated_by'];

        if($status == "selesai"){
            $statusClosed   = $status;
            
            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'status'        => "finished",
                'closed_status' => $statusClosed,
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di tutup oleh ".ucwords($updatedBy);
            $progress_ticket->status        = "finished";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
            
            return redirect($request['url'])->with('success', 'Ticket berhasil di close!');
        }else{
            $statusClosed   = $status." - ".$alasanClosed;
            
            // Mendapatkan data ticket sebelumnya
            $getTicket  = Ticket::where('id', $id)->first();
            $kendala    = $getTicket->kendala;

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

            // Mencari Service Desk berdasarkan ticket_for
            $ticketFor      = $getTicket->ticket_for;
            $getLocAgent    = Location::where('nama_lokasi', $ticketFor)->first();
            $locIdAgent     = $getLocAgent['id'];
            $getServiceDesk = User::where([['location_id', $locIdAgent],['role', 'service desk']])->first();
            $nikServiceDesk = $getServiceDesk['nik'];
            $getAgent       = Agent::where('nik', $nikServiceDesk)->first();
            $agentId        = $getAgent['id'];
            
            // Mencari Area, Regional, Wilayah Client untuk mengisi data ticket_area
            $clientId       = $getTicket->client_id;
            $getClient      = Client::where('id', $clientId)->first();
            $locationId     = $getClient['location_id'];
            $getLocation    = Location::where('id', $locationId)->first();
            $area           = substr($getLocation['area'], -1);
            $regional       = substr($getLocation['regional'], -1);
            $wilayah        = substr($getLocation['wilayah'], -2);
            $ticketArea     = $area.$regional.$wilayah;

            // Ambil waktu saat ini
            $currentDay     = date('D');
            $currentDate    = date('d-m-y');
            $currentTime    = Carbon::now();

            // Mencari apakah tanggal sekarang merupakan libur nasional atau bukan
            $checkHoliday   = National_holiday::where('tanggal', $currentDate)->count();

            // Tentukan hari dam jam kerja (contoh: 9 pagi hingga 5 sore)
            $workStartTime  = Carbon::createFromTime(8, 0, 0);
            $workEndTime    = Carbon::createFromTime(17, 0, 0);

            // Periksa apakah waktu saat ini berada dalam rentang hari dan jam kerja
            $isWorkTime     = $currentTime->between($workStartTime, $workEndTime);

            // Tampilkan hasil
            if ($currentDay == "SAT" or $currentDay == "SUN" or $checkHoliday == 1){ // Jika hari ini, merupakan hari sabtu atau minggu atau hari libur nasional
                $jamKerja = "tidak";
            }else{ // Jika hari ini, bukan hari sabtu atau minggu atau hari libur nasional
                if ($isWorkTime) {
                    $jamKerja = "ya";
                } else {
                    $jamKerja = "tidak";
                }
            }

            // Membuatkan No. Ticket Baru
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

            $no_ticket  = 'T'.sprintf('%08d', $ticketNumber);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'status'        => "finished",
                'closed_status' => $statusClosed,
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di tutup oleh ".ucwords($updatedBy);
            $progress_ticket->status        = "finished";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            // Saving data to ticket table
            $ticket                 = new Ticket;
            $ticket->no_ticket      = $no_ticket;
            $ticket->kendala        = "Re: (".$getTicket->no_ticket.') '.$kendala;
            $ticket->detail_kendala = $getTicket->detail_kendala;
            $ticket->asset_id       = $getTicket->asset_id;
            $ticket->user_id        = $getTicket->user_id;
            $ticket->client_id      = $getTicket->client_id;
            $ticket->location_id    = $getTicket->location_id;
            $ticket->agent_id       = $agentId;
            $ticket->role           = "service desk";
            $ticket->status         = "created";
            $ticket->is_queue       = "tidak";
            $ticket->assigned       = "tidak";
            $ticket->need_approval  = "tidak";
            $ticket->jam_kerja      = $jamKerja;
            $ticket->ticket_for     = $getTicket->ticket_for;
            $ticket->ticket_area    = $ticketArea;
            $ticket->estimated      = "-";
            $ticket->file           = $getTicket->file;
            $ticket->updated_by     = $updatedBy;
            $ticket->save();
         
            return redirect($request['url'])->with('success', 'Ticket berhasil di close!');
        }
    }

    public function delete(Request $request, $id)
    {
        Ticket::where('id', $id)->update(['status' => 'deleted']);
        $now        = date('d-m-Y H:i:s');
        $updatedBy  = $request['updated_by'];

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di edit oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "deleted";
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        return back()->with('success', 'Ticket berhasil dihapus!');
    }
}