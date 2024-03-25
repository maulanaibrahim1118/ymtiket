<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Asset;
use App\Client;
use App\Ticket;
use App\Location;
use App\Ticket_detail;
use App\Category_ticket;
use App\Progress_ticket;
use App\National_holiday;
use App\Sub_category_ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data User
        $nik        = Auth::user()->nik;
        $role       = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $positionId = Auth::user()->position_id;
        $location   = Auth::user()->location->nama_lokasi;

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

        // Jika role Client
        if($role == "client"){

            // Jika jabatan Chief
            if($positionId == "2"){
                $tickets = Ticket::where('ticket_area', 'like', $ticketChief.'%')->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

            // Jika jabatan Koordinator Wilayah
            }elseif($positionId == "6"){
                $tickets = Ticket::where('ticket_area', $ticketKorwil)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

            // Jika jabatan Manager
            }elseif($positionId == "7"){
                $tickets = Ticket::where('ticket_area', 'like', $area.'%')->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

            // Jika jabatan selain Korwil, Chief dan Manager
            }else{
                $tickets = Ticket::where('location_id', $locationId)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
            }

        // Jika role Service Desk
        }elseif($role == "service desk"){
            $tickets = Ticket::where('ticket_for', $namaLokasi)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

        // Jika role Agent
        }else{
            $tickets = Ticket::where([['ticket_for', $namaLokasi],['agent_id', $agentId]])->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();
        }

        // Get data Agent HO, untuk select option
        $hoAgents   = Agent::where([['location_id', $locationId],['sub_divisi', 'hardware maintenance'],['status', 'present']])
            ->orWhere([['location_id', $locationId],['pic_ticket', 'ho'],['status', 'present']])
            ->whereNotIn('id', [$agentId])
            ->get();
        
        // Get data Agent Cabang, untuk select option
        $storeAgent = Agent::where([['location_id', $locationId],['sub_divisi', 'hardware maintenance'],['status', 'present']])
            ->orWhere([['location_id', $locationId],['pic_ticket', 'store'],['status', 'present']])
            ->whereNotIn('id', [$agentId])
            ->get();

        return view('contents.ticket.index', [
            "title"         => "Ticket",
            "path"          => "Ticket",
            "path2"         => "Ticket",
            "tickets"       => $tickets,
            "hoAgents"      => $hoAgents,
            "storeAgents"   => $storeAgent
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get data User
        $role       = Auth::user()->role;
        $locationId = Auth::user()->location_id;

        // Menghitung ticket yang belum di close
        $ticketUnclosed = Ticket::where([['location_id', $locationId],['status', 'resolved']])->count();

        // Data Array untuk select option
        $ticketFors  = ["information technology", "inventory control", "project me", "project sipil"];

        // Jika Role Client
        if($role == "client"){
            // Jika tidak ada ticket yang belum di close
            if($ticketUnclosed == 0){
                // Get data client untuk select option
                $clients = Client::where('location_id', $locationId)->orderBy('nama_client', 'ASC')->get();
                
                return view('contents.ticket.create', [
                    "title"         => "Create Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Tambah",
                    "clients"       => $clients,
                    "ticketFors"    => $ticketFors
                ]);

            // Jika masih ada ticket yang belum di close
            }else{
                // Kembali ke halaman ticket beserta pesan error
                return back()->with('createError', 'Tolong ticket resolved nya di close terlebih dahulu!');
            }

        // Jika Role Service Desk
        }elseif($role == "service desk"){
            // Get data client untuk select option
            $clients = Client::orderBy('nama_client', 'ASC')->get();

            return view('contents.ticket.create', [
                "title"         => "Create Ticket",
                "path"          => "Ticket",
                "path2"         => "Tambah",
                "clients"       => $clients,
                "ticketFors"    => $ticketFors
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get Nama User
        $updatedBy  = Auth::user()->nama;
        
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

        // Menampung semua data request
        $data = $request->all();

        /**
         * Cek kendala, asset dan waktu ticket
         * Untuk menghindari looping create atau ticket yang sama dalam satu hari
         */

        // Mencari waktu saat ini
        $now = date('Y-m-d');

        // Menghitung jumlah ticket yang memiliki asset dan kendala yang sama pada hari ini
        $countTicket = Ticket::where([['kendala', $request['kendala']],['asset_id', $request['asset_id']],['created_at', 'like', $now.'%']])->count();

        // Jika ada ticket yang sama
        if($countTicket == 1){
            // Redirect ke halaman ticket beserta pesan error
            return redirect('/tickets')->with('error', 'Ticket ini sudah dibuat sebelumnya!');
        }

        // Rename Nama File dari request dan Upload ke folder public
        if($request['file'] == NULL){
            $imageName  = NULL;
        }else{
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $imageName);
        }
        
        // Mencari Service Desk berdasarkan ticket_for
        $ticketFor      = $data['ticket_for'];
        $getLocAgent    = Location::where('nama_lokasi', $ticketFor)->first();
        $locIdAgent     = $getLocAgent['id'];

        // Get NIK Service Desk
        $getServiceDesk = User::where([['location_id', $locIdAgent],['role', 'service desk']])->first();
        $nikServiceDesk = $getServiceDesk['nik'];

        // Get ID Service Desk
        $getAgent       = Agent::where('nik', $nikServiceDesk)->first();
        $agentId        = $getAgent['id'];
        
        // Mencari data Lokasi Client untuk mengisi data ticket_area
        $clientId       = $data['client_id'];
        $getClient      = Client::where('id', $clientId)->first();
        $locationId     = $getClient['location_id'];

        // Mengambil huruf terakhir area, regional dan wilayah sebagai code 'ticket area'
        $getLocation    = Location::where('id', $locationId)->first();
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -2);

        // Penentuan kode ticket area
        if($getLocation['wilayah'] == "head office"){
            $ticketArea     = "ho";
        }else{
            $ticketArea     = $area.$regional.$wilayah;
        }

        // Ambil hari dan tanggal saat ini
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

        // Jika hari ini, merupakan hari sabtu atau minggu atau hari libur nasional
        if ($currentDay == "SAT" or $currentDay == "SUN" or $checkHoliday == 1){
            $jamKerja = "tidak";

        // Jika hari ini, bukan hari sabtu atau minggu atau hari libur nasional
        }else{
            // Jika jam saat ini, merupakan jam kerja
            if ($isWorkTime) {
                $jamKerja = "ya";
            
            // Jika jam saat ini, bukan jam kerja
            }else{
                $jamKerja = "tidak";
            }
        }

        // Simpan data Ticket sesuai request input dan variabel yang telah ditentukan
        $ticket                 = new Ticket;
        $ticket->kendala        = strtolower($data['kendala']);
        $ticket->detail_kendala = strtolower($data['detail_kendala']);
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

        // Get id ticket yang baru saja dibuat
        $ticket_id = $ticket->id;

        // Get Tanggal dan Waktu saat ini 
        $now = date('d-m-Y H:i:s');

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $ticket_id;
        $progress_ticket->tindakan      = "Ticket di buat oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "created";
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        // Redirect ke halaman ticket list beserta notifikasi sukses
        return redirect('/tickets')->with('success', 'Ticket berhasil dibuat!');
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
    public function edit(Request $request)
    {
        // Get data User
        $role       = Auth::user()->role;
        $locationId = Auth::user()->location_id;

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Ticket berdasarkan id Ticket
        $ticket = Ticket::where('id', $id)->first();

        // Jika status ticket belum diproses oleh agent
        if($ticket->status == "created"){
            // Data Array untuk select option
            $ticketFors = ["information technology", "inventory control", "project me", "project sipil"];

            // Get data Asset untuk select option
            $assets = Asset::where('location_id', $ticket->location_id)->get();

            // Jika Role Client
            if($role == "client"){
                // Get data Client untuk select option
                $clients    = Client::where('location_id', $locationId)->orderBy('nama_client', 'ASC')->get();
                
                return view('contents.ticket.edit', [
                    "title"         => "Edit Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Edit",
                    "ticket"        => $ticket,
                    "assets"        => $assets,
                    "clients"       => $clients,
                    "ticketFors"    => $ticketFors
                ]);

            // Jika Role Service Desk
            }elseif($role == "service desk"){
                // Get data Client untuk select option
                $clients    = Client::orderBy('nama_client', 'ASC')->get();

                return view('contents.ticket.edit', [
                    "title"         => "Edit Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Edit",
                    "ticket"        => $ticket,
                    "assets"        => $assets,
                    "clients"       => $clients,
                    "ticketFors"    => $ticketFors
                ]);
            }

        // Jika status ticket sedang diproses oleh agent
        }else{
            return back()->with('error', 'Ticket sedang diproses oleh Agent!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Get Nama User
        $updatedBy = Auth::user()->nama;

        // Get Tanggal dan Waktu saat ini
        $now = date('d-m-Y H:i:s');

        // Validating data request
        $rules = [
            'client_id'         => 'required',
            'asset_id'          => 'required',
            'ticket_for'        => 'required',
            'kendala'           => 'required|min:5|max:50',
            'detail_kendala'    => 'required|min:10',
            'file'              => 'max:1024',
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
            'file.max'                  => 'Maksimal ukuran file 1Mb!',
        ]);

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Ticket berdasarkan id Ticket
        $ticket = Ticket::where('id', $id)->first();

        // Get data Request input
        $newFile    = $request['file'];
        $oldFile    = $request['old_file'];
        $clientId   = $request['client_id'];

        if($newFile == NULL){
            $imageName = $oldFile;
        }else{
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $imageName);
        }

        // Mencari data Lokasi Client untuk mengisi data ticket_area
        $getClient      = Client::where('id', $clientId)->first();
        $locationId     = $getClient['location_id'];

        // Mengambil huruf terakhir area, regional dan wilayah sebagai code 'ticket area'
        $getLocation    = Location::where('id', $locationId)->first();
        $area           = substr($getLocation['area'], -1);
        $regional       = substr($getLocation['regional'], -1);
        $wilayah        = substr($getLocation['wilayah'], -2);

        // Penentuan kode ticket area
        if($getLocation['wilayah'] == "head office"){
            $ticketArea     = "ho";
        }else{
            $ticketArea     = $area.$regional.$wilayah;
        }

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'client_id'         => $request['client_id'],
            'location_id'       => $locationId,
            'asset_id'          => $request['asset_id'],
            'ticket_for'        => $request['ticket_for'],
            'kendala'           => strtolower($request['kendala']),
            'detail_kendala'    => strtolower($request['detail_kendala']),
            'file'              => $imageName,
            'ticket_area'       => $ticketArea,
            'updated_by'        => $request['client_id']
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di edit oleh ".ucwords($updatedBy);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "edited";
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();
        
        // Redirect ke halaman ticket list beserta notifikasi sukses
        return redirect('/tickets')->with('success', 'Ticket berhasil di edit!');
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

    // Get data client untuk JQuery Select Option
    public function getClient($id = 0)
    {
        $data = Client::where('id',$id)->first();
        return response()->json($data);
    }

    // Get data lokasi untuk JQuery Select Option
    public function getLocation($id = 0)
    {
        $data = Location::where('id',$id)->first();
        return response()->json($data);
    }

    // Get data asset untuk JQuery Select Option
    public function getAssets($id = 0)
    {
        $data = Asset::where([['location_id', $id],['status', 'digunakan']])->get();
        return response()->json($data);
    }

    // Proses ticket yang baru dibuat atau status created (role: service desk)
    public function process1(Request $request){
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get NIK Agent dari request input
        $nikAgent = $request['nik'];
        
        // Get Tanggal dan Waktu saat ini
        $now = date('d-m-Y H:i:s');

        // Mengganti status ticket dan memulai hitung waktu proses ticket
        Ticket::where('id', $id)->update([
            'status'        => "onprocess",
            'process_at'    => $now,
            'assigned'      => "tidak",
            'is_queue'      => "tidak",
            'updated_by'    => $request['updated_by']
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di proses oleh ".ucwords($request['updated_by']);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "onprocess";
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        // Menampilkan halaman proses ticket
        return redirect()->route('ticket-detail.create', ['ticket_id' => encrypt($id)]);
    }

    // Proses ticket yang sudah pernah di proses sebelumnya oleh service desk/agent lain
    public function process2(Request $request){
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get NIK Agent dari request input
        $nikAgent = $request['nik'];
        
        // Get Tanggal dan Waktu saat ini
        $now = date('d-m-Y H:i:s');

        $status = "onprocess";

        // Data Array unutk select option
        $types  = ["kendala", "permintaan"];

        // Mencari waktu pending
        $ticket         = Ticket::where('id', $id)->first();
        $now            = Carbon::parse($now);
        $pendingAt      = Carbon::parse($ticket->pending_at);
        $pending_time   = $pendingAt->diffInSeconds($now);

        $agentId        = $ticket->agent_id;

        // Mencari Sub Category Ticket terakhir yang diproses agent sebelumnya
        $ticket_detail  = Ticket_detail::where('ticket_id', $id)->latest()->first();
        $subCategoryId  = $ticket_detail->sub_category_ticket_id;

        // Mencari Category Ticket terakhir yang diproses agent sebelumnya
        $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
        $categoryId     = $subCategory->category_ticket_id;

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'status'        => $status,
            'process_at'    => $now,
            'pending_at'    => "-",
            'assigned'      => "tidak",
            'pending_time'  => $pending_time,
            'updated_by'    => $request['updated_by']
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di proses oleh ".ucwords($request['updated_by']);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "onprocess";
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();
        
        return view('contents.ticket_detail.create2', [
            "title"                 => "Tangani Ticket",
            "path"                  => "Ticket",
            "path2"                 => "Tangani",
            "category_tickets"      => Category_ticket::all(),
            "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
            "progress_tickets"      => Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get(),
            "ticket"                => Ticket::where('id', $id)->first(),
            "td"                    => Ticket_detail::where('ticket_id', $id)->latest()->first(),
            "countDetail"           => Ticket_detail::where('ticket_id', $id)->count(),
            "ticket_details"        => Ticket_detail::where('ticket_id', $id)->get(),
            "types"                 => $types
        ]);
    }

    // Proses ticket yang sudah di approve
    public function process3(Request $request){
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get ID Agent dari request input
        $agentId = $request['agent_id'];

        $status = "onprocess";
        $now    = date('d-m-Y H:i:s');

        // Mencari apakah agent tersebut sudah pernah menangani ticket tersebut atau tidak
        $countDetail = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->count();

        // Jika agent belum pernah menangani ticket tersebut
        if($countDetail == 0){
            // Mencari data detail ticket agent sebelumnya
            $getDetail = Ticket_detail::where([['ticket_id', $id],['biaya', '!=', 0]])->latest()->first();
            $detail_id = $getDetail->id;
            
            // Membuat Detail Ticket sama seperti agent sebelumnya
            $ticket_detail                          = new Ticket_detail;
            $ticket_detail->ticket_id               = $id;
            $ticket_detail->jenis_ticket            = $getDetail->jenis_ticket;
            $ticket_detail->sub_category_ticket_id  = $getDetail->sub_category_ticket_id;
            $ticket_detail->agent_id                = $agentId;
            $ticket_detail->process_at              = $now;
            $ticket_detail->pending_at              = "-";
            $ticket_detail->biaya                   = $getDetail->biaya;
            $ticket_detail->note                    = $getDetail->note;
            $ticket_detail->status                  = "onprocess";
            $ticket_detail->updated_by              = $request['updated_by'];
            $ticket_detail->save();
        
        // Jika agent sudah pernah menangani ticket tersebut
        }else{
            $getDetail = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
            $detail_id = $getDetail->id;
            
            Ticket_detail::where('id', $detail_id)->update([
                'process_at'  => $now,
                'status' => $status
            ]);
        }

        // Menghitung pending time ticket
        $ticket = Ticket::where('id', $id)->first();
        $now = Carbon::parse($now);
        $pendingAt = Carbon::parse($ticket->pending_at);
        $pending_time = $pendingAt->diffInSeconds($now);

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'status'        => $status,
            'pending_at'    => "-",
            'pending_time'  => $pending_time,
            'updated_by'    => $request['updated_by']
        ]);
        
        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $id;
        $progress_ticket->tindakan      = "Ticket di proses oleh ".ucwords($request['updated_by']);
        $progress_ticket->process_at    = $now;
        $progress_ticket->status        = "onprocess";
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();
        
        // Redirect ke halaman ticket detail
        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
    }

    public function queue(Request $request)
    {
        $ticketId   = $request['ticket_id'];
        $now        = date('d-m-Y H:i:s');
        $isQueue    = "ya";

        Ticket::where('id', $ticketId)->update([
            'is_queue'          => $isQueue,
            'sub_divisi_agent'  => $request['sub_divisi'],
            'updated_by'        => $request['updated_by']
        ]);

        // Saving data to progress ticket table
        $progress_ticket                = new Progress_ticket;
        $progress_ticket->ticket_id     = $ticketId;
        $progress_ticket->tindakan      = "Ticket sedang dalam antrian";
        $progress_ticket->status        = "edited";
        $progress_ticket->process_at    = $now;
        $progress_ticket->updated_by    = $request['updated_by'];
        $progress_ticket->save();

        // Kembali ke halaman ticket beserta pesan sukses
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
            $subDivisi  = $getAgent->sub_divisi;
            $now        = date('d-m-Y H:i:s');

            Ticket::where('id', $request['ticket_id'])->update([
                'assigned'          => "ya",
                'agent_id'          => $agentId,
                'sub_divisi_agent'  => $subDivisi,
                'updated_by'        => $request['updated_by'],
                'role'              => "agent"
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

            // Cek status ticket
            $ticket = Ticket::where('id', $ticketId)->first();
            $statusTicket = $ticket->status;

            if($statusTicket == "pending"){
                // Mencari tanggal/waktu pending untuk menghitung total waktu pending
                $getTicketDetail    = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1]])->first();
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
                Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1],['status', 'pending']])->update([
                    'pending_at'    => $pending_at2,
                    'pending_time'  => $pending_time,
                    'status'        => $status
                ]);

                // Updating data to ticket table
                Ticket::where('id', $ticketId)->update([
                    'pending_at'    => $pending_at2,
                    'pending_time'  => $pending_time,
                    'status'        => $status
                ]);
            }

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

            return redirect('tickets')->with('success', 'Ticket berhasil di assign ke '.ucwords($agentName2).'!');
        }
    }

    public function pending(Request $request)
    {
        // Get data User
        $nik        = Auth::user()->nik;
        $role       = Auth::user()->role;
        
        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
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

            if($role == "service desk"){
                return redirect('/tickets')->with('success', 'Ticket telah selesai diproses!');
            }else{
                $getAgent       = Agent::where('nik', $nik)->first();
                $agentId2       = $getAgent->id;
                $subDivisi      = $getAgent->sub_divisi;
                $agentStatus    = $getAgent->status;
                $agentLocation  = $getAgent->location->nama_lokasi;
    
                if($agentStatus != 'present'){ // Jika Agent tidak hadir, izin, keluar kota, dll
                    return redirect('/tickets')->with('success', 'Ticket berhasil di pending!');
                }else{ // Jika agent hadir di kantor
                    if($subDivisi == "helpdesk"){
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'helpdesk']])->first();
                    }elseif($subDivisi == "hardware maintenance"){
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'hardware maintenance']])->first();
                    }elseif($subDivisi == "infrastructur networking"){
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'infrastructur networking']])->first();
                    }elseif($subDivisi == "tech support"){
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'tech support']])->first();
                    }else{
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'none']])->first();
                    }
    
                    if($getAntrian == NULL){ // Jika antrian ticket sudah habis
                        return redirect('/tickets')->with('success', 'Ticket berhasil di pending!');
                    }else {
                        $ticketId = $getAntrian->id;
    
                        Ticket::where('id', $ticketId)->update([
                            'agent_id'      => $agentId2,
                            'role'          => "agent",
                            'is_queue'      => "tidak",
                            'updated_by'    => $updatedBy
                        ]);
            
                        return redirect('/tickets')->with('success', 'Ticket berhasil di pending!');
                    }
                }
            }

            return redirect('/tickets')->with('success', 'Ticket berhasil di pending!');
        }
    }

    // Proses kembali jika status pending
    public function reProcess1(Request $request)
    {
        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
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
        $progress_ticket->tindakan      = "Ticket di proses kembali oleh ".ucwords($updatedBy);
        $progress_ticket->status        = $status;
        $progress_ticket->process_at    = $now;
        $progress_ticket->updated_by    = $updatedBy;
        $progress_ticket->save();

        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
    }

    // Proses kembali jika status onprocess (melanjutkan proses ticket)
    public function reProcess2(Request $request){
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        $getTicket      = Ticket::where('id', $id)->first();
        $agentId        = $getTicket->agent_id;
        $countDetailAll = Ticket_detail::where('ticket_id', $id)->count();
        $countDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->count();
        if($countDetail == NULL){
            if($countDetailAll == NULL){
                return redirect()->route('ticket-detail.create', ['ticket_id' => encrypt($id)]);
            }else{
                $ticket_detail  = Ticket_detail::where('ticket_id', $id)->latest()->first();
                $subCategoryId  = $ticket_detail->sub_category_ticket_id;
                $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
                $categoryId     = $subCategory->category_ticket_id;
                $types          = ["kendala", "permintaan"];

                return view('contents.ticket_detail.create2', [
                    "title"                 => "Tangani Ticket",
                    "path"                  => "Ticket",
                    "path2"                 => "Tangani",
                    "category_tickets"      => Category_ticket::all(),
                    "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
                    "progress_tickets"      => Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get(),
                    "ticket"                => Ticket::where('id', $id)->first(),
                    "td"                    => Ticket_detail::where('ticket_id', $id)->latest()->first(),
                    "countDetail"           => Ticket_detail::where('ticket_id', $id)->count(),
                    "ticket_details"        => Ticket_detail::where('ticket_id', $id)->get(),
                    "types"                 => $types
                ]);
            }
        }else {
            return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
        }
    }

    public function resolved(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
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
        $getDetail      = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
        $detail_id      = $getDetail->id;
        $ticket_detail  = Ticket_detail::where('id', $detail_id)->first();
        $processAt2     = Carbon::parse($ticket_detail->process_at);
        $now            = Carbon::parse($now);
        $pendingTime    = $ticket_detail->pending_time;
        $processedTime  = $ticket_detail->processed_time;
        $processedTime2 = $processAt2->diffInSeconds($now);
        $processedTime3 = ($processedTime+$processedTime2)-$pendingTime;

        // Mencari Sub Category ID untuk mendapatkan data asset_change
        $subCategoryId  = $ticket_detail->sub_category_ticket_id;
        $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
        $assetChange    = $subCategory->asset_change;

        // Jika Sub Category Ticket tersebut 
        if($assetChange == "ya"){
            $assetId    = $getTicket->asset_id;
            
            Asset::where('id', $assetId)->update([
                'status'    => "tidak digunakan"
            ]);
        }

        Ticket::where('id', $id)->update([
            'status'            => "resolved",
            'processed_time'    => $processedTime1,
            'updated_by'        => $updatedBy
        ]);

        Ticket_detail::where('id', $detail_id)->update([
            'status'            => "resolved",
            'processed_time'    => $processedTime3,
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
            return redirect('/tickets')->with('success', 'Ticket telah selesai diproses!');
        }else{
            $nik            = $request['nik'];
            $getAgent       = Agent::where('nik', $nik)->first();
            $agentId2       = $getAgent->id;
            $subDivisi      = $getAgent->sub_divisi;
            $agentStatus    = $getAgent->status;
            $agentLocation  = $getAgent->location->nama_lokasi;

            if($agentStatus != 'present'){ // Jika Agent tidak hadir, izin, keluar kota, dll
                return redirect('/tickets')->with('success', 'Ticket telah selesai diproses!');
            }else{ // Jika agent hadir di kantor
                if($subDivisi == "helpdesk"){
                    $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'helpdesk']])->first();
                }elseif($subDivisi == "hardware maintenance"){
                    $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'hardware maintenance']])->first();
                }elseif($subDivisi == "infrastructur networking"){
                    $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'infrastructur networking']])->first();
                }elseif($subDivisi == "tech support"){
                    $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'tech support']])->first();
                }else{
                    $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', 'none']])->first();
                }

                if($getAntrian == NULL){ // Jika antrian ticket sudah habis
                    return redirect('/tickets')->with('success', 'Ticket telah selesai diproses!');
                }else {
                    $ticketId = $getAntrian->id;

                    Ticket::where('id', $ticketId)->update([
                        'agent_id'      => $agentId2,
                        'role'          => "agent",
                        'is_queue'      => "tidak",
                        'updated_by'    => $updatedBy
                    ]);
        
                    return redirect('/tickets')->with('success', 'Ticket telah selesai diproses!');
                }
            }
        }
    }

    public function finished(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
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
            
            return redirect('/tickets')->with('success', 'Ticket berhasil di close!');
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

            // Get id ticket yang baru dibuat
            $ticket_id  = $ticket->id;
            $now        = date('d-m-Y H:i:s');

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $ticket_id;
            $progress_ticket->tindakan      = "Ticket di buat oleh Sistem";
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = "created";
            $progress_ticket->updated_by    = "Sistem";
            $progress_ticket->save();
         
            return redirect('/tickets')->with('success', 'Ticket berhasil di close!');
        }
    }

    public function delete(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        $ticket = Ticket::where('id', $id)->first();
        $statusTicket = $ticket->status;
        if($statusTicket == "created"){
            Ticket::where('id', $id)->update(['status' => 'deleted']);
            $now        = date('d-m-Y H:i:s');
            $updatedBy  = $request['updated_by'];

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di hapus oleh ".ucwords($updatedBy);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = "deleted";
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            return back()->with('success', 'Ticket berhasil dihapus!');
        }else{
            return back()->with('error', 'Ticket sedang diproses oleh Agent!');
        }
    }

    public function ticketAsset(Request $request)
    {
        // Get id Asset dari request parameter
        $assetId = decrypt($request['asset_id']);
        
        // Get data Asset berdasarkan id Asset
        $asset      = Asset::where('id', $assetId)->first();
        $noAsset    = $asset->no_asset;

        // Get data ticket berdasarkan asset id
        $tickets    = Ticket::where('asset_id', $assetId)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

        return view('contents.ticket.filter.index', [
            "url"           => $noAsset,
            "title"         => "Ticket",
            "path"          => "Ticket",
            "path2"         => "Asset: ". $noAsset,
            "pathFilter"    => "Asset: ". $noAsset,
            "hoAgents"      => Agent::all(),
            "storeAgents"   => Agent::all(),
            "tickets"       => $tickets
        ]);
    }

    // Menampilkan Data Ticket sesuai menu Dashboard yang di klik
    public function ticketDashboard(Request $request)
    {
        $status     = $request->input('status');
        $agent      = $request->input('filter1');
        $periode    = $request->input('filter2');
        
        $id         = Auth::user()->id;
        $role       = Auth::user()->role;

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
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Belum Disetujui";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', 'like', $ticketChief.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $ticketChief.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Belum Disetujui";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = $ticket     = Ticket::where([['ticket_area', $ticketKorwil],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $ticket     = Ticket::where([['ticket_area', $ticketKorwil],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', $ticketKorwil],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                                        ->orWhere([['ticket_area', $ticketKorwil],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }elseif($positionId == "7"){ // Jika jabatan Manager
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Belum Disetujui";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Belum Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket Sedang Di Proses";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Sedang Di Pending";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Sudah Selesai";
                    $tickets    = Ticket::where([['ticket_area', 'like', $area.'%'],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['ticket_area', 'like', $area.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                if($status == "all"){
                    $title      = "Total Ticket";
                    $tickets    = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Belum Disetujui";
                    $tickets    = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
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
                }elseif($status == "assign"){
                    $title      = "Ticket Tidak Selesai";
                    $tickets    = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                                        ->where([['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                                        ->select('tickets.*')
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
                    $title      = "Ticket Tidak Selesai";
                    $tickets    = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                        ->where([['ticket_details.agent_id', $agentId],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                        ->select('tickets.*')
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
}