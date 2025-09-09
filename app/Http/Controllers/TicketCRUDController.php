<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use App\Asset;
use App\Ticket;
use App\Location;
use Carbon\Carbon;
use App\Sub_division;
use App\Ticket_detail;
use App\Category_ticket;
use App\Progress_ticket;
use App\National_holiday;
use App\Sub_category_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketCRUDController extends Controller
{
    public function index()
    {
        // Get data User
        $user       = Auth::user();
        $nik        = $user->nik;
        $role       = $user->role_id;
        $locationId = $user->location_id;
        $positionId = $user->position_id;
        $location   = $user->location->nama_lokasi;
        $codeAccess = $user->code_access;

        // Mencari agent_id untuk parameter menampilkan halaman ticket Agent
        $getAgent   = Agent::where([['is_active', '1'], ['nik', $nik]])->first();
        $agentId    = $getAgent['id'];

        // Query ticket dengan eager loading untuk menghindari N+1 problem
        $ticketsQuery = Ticket::with(['agent', 'location'])->whereNotIn('status', ['deleted']);
        
        // Jika role Client
        if ($role == 3) {
            if ($locationId == 17) {
                switch ($positionId) {
                    case 2:
                        $ticketsQuery->where(function ($query) use ($codeAccess, $locationId) {
                            $query->where('code_access', 'like', '%' . $codeAccess . '%')
                                ->orWhere('location_id', $locationId);
                        });
                        break;
                    case 6:
                        $ticketsQuery->where('code_access', 'like', '%' . $codeAccess);
                        break;
                    case 7:
                        $ticketsQuery->where('code_access', 'like', $area . '%');
                        break;
                    default:
                        $ticketsQuery->where('location_id', $locationId);
                }
            } else {
                $ticketsQuery->where('location_id', $locationId);
            }
        } elseif ($role == 1) {
            $ticketsQuery->where(function ($query) use ($locationId) {
                $query->where('ticket_for', $locationId)
                    ->orWhere('created_by', Auth::user()->nama);
            });
        } else {
            $ticketsQuery->where([['ticket_for', $locationId], ['agent_id', $agentId]]);
        }

        // Optimasi dengan pagination untuk menangani banyak data
        $tickets = $ticketsQuery->orderBy('status', 'ASC')->orderBy('created_at', 'DESC')->get();

        // Mencari agent yang memiliki sub divisi, untuk menentukan antrian dan assign
        $haveSubDivs = Sub_division::select('location_id')->distinct()->pluck('location_id')->toArray();

        // Menggabungkan query Sub Divisi Agent HO & Store dalam satu query
        $baseQuery = Agent::where('location_id', $locationId)
                        ->whereNotIn('id', [$agentId])
                        ->whereNotIn('sub_divisi', ['tidak ada'])
                        ->groupBy('sub_divisi', 'pic_ticket');

        $subDivs = $baseQuery->get(['sub_divisi', 'pic_ticket']);
        $subDivHo = $subDivs->where('pic_ticket', '!=', 'store')->pluck('sub_divisi')->toArray();
        $subDivStore = $subDivs->where('pic_ticket', '!=', 'ho')->pluck('sub_divisi')->toArray();

        // Menggabungkan query untuk Agent HO & Store
        $agents = Agent::where('is_active', '1')
                    ->where('location_id', $locationId)
                    ->where('status', 'present')
                    ->whereNotIn('id', [$agentId])
                    ->get();

        // Memisahkan data Agent HO dan Store
        $hoAgents = $agents->where('pic_ticket', '!=', 'store');
        $storeAgents = $agents->where('pic_ticket', '!=', 'ho');

        return view('contents.ticket.index', [
            "title"         => "Ticket List",
            "path"          => "Ticket",
            "path2"         => "Ticket",
            "tickets"       => $tickets,
            "hoAgents"      => $hoAgents,
            "storeAgents"   => $storeAgents,
            "subDivHo"      => $subDivHo,
            "subDivStore"   => $subDivStore,
            "haveSubDivs"   => $haveSubDivs,
            "agentId"       => $agentId
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
        $role       = Auth::user()->role_id;
        $locationId = Auth::user()->location_id;
        $wilayahId  = Auth::user()->location->wilayah_id;

        // Data Array untuk select option
        $ticketFors = Agent::select('location_id')
            ->distinct()
            ->whereHas('location', function($query) use ($wilayahId) {
                if (in_array($wilayahId, [1, 2])) {
                    $query->where('wilayah_id', $wilayahId);
                } else {
                    $query->where('wilayah_id', 2);
                }
            })
            ->orderBy('location_id', 'ASC')
            ->get();

        // Jika Role Client
        if($role == 3){
            // Menghitung ticket yang belum di close
            $ticketUnclosed = Ticket::where([['location_id', $locationId],['status', 'resolved']])->count();
            
            // Jika tidak ada ticket yang belum di close
            if($ticketUnclosed == 0){
                // Get data client untuk select option
                $users = User::where([['location_id', $locationId],['is_active', '1']])->orderBy('nama', 'ASC')->get();

                return view('contents.ticket.create', [
                    "title"         => "Create Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Tambah",
                    "users"         => $users,
                    "ticketFors"    => $ticketFors
                ]);

            // Jika masih ada ticket yang belum di close
            }else{
                // Kembali ke halaman ticket beserta pesan error
                return back()->with('createError', 'Anda memiliki tiket "resolved", tolong di "close"!');
            }

        // Jika Role Service Desk
        }elseif($role == 1){
            // Get data client untuk select option
            $users = User::where('is_active', '1')->orderBy('nama', 'ASC')->get();
            $source = ['email', 'phone', 'tidak ada'];

            return view('contents.ticket.create', [
                "title"         => "Create Ticket",
                "path"          => "Ticket",
                "path2"         => "Create",
                "users"         => $users,
                "source"        => $source,
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
        $createdBy  = Auth::user()->nama;
        
        // Validating data request
        $validatedData = $request->validate([
            'user_id'           => 'required',
            'asset_id'          => 'required',
            'ticket_for'        => 'required',
            'kendala'           => 'required|min:5|max:35',
            'detail_kendala'    => 'required|min:10',
            'file'              => 'required|max:1024',
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
        if($countTicket >= 1){
            // Redirect ke halaman ticket beserta pesan error
            return redirect('/tickets')->with('error', 'Ticket ini sudah dibuat sebelumnya!');
        }

        // Rename Nama File dari request dan Upload ke folder public
        if($request['file'] == NULL){
            $imageName  = NULL;
        }else{
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads/ticket/'), $imageName);
        }
        
        $ticketFor = $data['ticket_for'];

        // Get NIK Service Desk
        $getServiceDesk = User::where([['is_active', '1'],['location_id', $ticketFor],['role_id', 1]])->whereNotIn('position_id', [2, 7])->inRandomOrder()->first();
        $nikServiceDesk = $getServiceDesk['nik'];

        // Get ID Service Desk
        $getAgent   = Agent::where([['is_active', '1'],['nik', $nikServiceDesk]])->first();
        $agentId    = $getAgent['id'];
        
        // Mencari data Lokasi Client untuk mengisi data code access dan nama lokasi
        $userId     = $data['user_id'];
        $getClient  = User::where([['is_active', '1'],['id', $userId]])->first();
        $namaLokasi = $getClient->location->nama_lokasi;
        $codeAccess = $getClient['code_access'];

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
        $dataValidated = [
            'kendala' => strtolower($data['kendala']),
            'detail_kendala' => strtolower($data['detail_kendala']),
            'asset_id' => $data['asset_id'],
            'user_id' => $data['user_id'],
            'location_id' => $data['location_id'],
            'location_name' => $namaLokasi,
            'agent_id' => $agentId,
            'role' => 1,
            'status' => "created",
            'is_queue' => "tidak",
            'assigned' => "tidak",
            'need_approval' => "tidak",
            'jam_kerja' => $jamKerja,
            'ticket_for' => $data['ticket_for'],
            'code_access' => $codeAccess,
            'estimated' => "-",
            'file' => $imageName ?? null,
            'created_by' => $createdBy,
            'source' => $data['source'],
            'updated_by' => $createdBy,
        ];
        
        $ticket = Ticket::create($dataValidated);

        // Get id ticket yang baru saja dibuat
        $ticket_id = $ticket->id;

        // Get Tanggal dan Waktu saat ini 
        $now = date('d-m-Y H:i:s');

        // Saving data to progress ticket table
        $dataProgressTicket = [
            'ticket_id' => $ticket_id,
            'tindakan' => "Ticket di buat oleh " . ucwords($createdBy),
            'process_at' => $now,
            'status' => "created",
            'updated_by' => $createdBy,
        ];
        
        // Buat dan simpan model dengan mass assignment
        Progress_ticket::create($dataProgressTicket);

        // Redirect ke halaman ticket list beserta notifikasi sukses
        return redirect('/tickets')->with('success', 'Ticket successfully created!');
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
        $role       = Auth::user()->role_id;
        $locationId = Auth::user()->location_id;
        $wilayahId  = Auth::user()->location->wilayah_id;

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Ticket berdasarkan id Ticket
        $ticket = Ticket::where('id', $id)->first();

        // Jika status ticket belum diproses oleh agent
        if($ticket->status == "created"){
            // Data Array untuk select option
            $ticketFors = Agent::select('location_id')
                ->distinct()
                ->whereHas('location', function($query) use ($wilayahId) {
                    if (in_array($wilayahId, [1, 2])) {
                        $query->where('wilayah_id', $wilayahId);
                    } else {
                        $query->where('wilayah_id', 2);
                    }
                })
                ->orderBy('location_id', 'ASC')
                ->get();

            // Get data Asset untuk select option
            $assets = Asset::where('location_id', $ticket->location_id)->get();

            // Jika Role Client
            if($role == 3){
                // Get data Client untuk select option
                $users = User::where([['location_id', $locationId],['is_active', '1']])->orderBy('nama', 'ASC')->get();
                
                return view('contents.ticket.edit', [
                    "title"         => "Edit Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Edit",
                    "ticket"        => $ticket,
                    "assets"        => $assets,
                    "users"         => $users,
                    "ticketFors"    => $ticketFors
                ]);

            // Jika Role Service Desk
            }elseif($role == 1){
                // Get data Service De untuk select option
                $users = User::where('is_active', '1')->orderBy('nama', 'ASC')->get();
                $source = ['email', 'phone', 'tidak ada'];

                return view('contents.ticket.edit', [
                    "title"         => "Edit Ticket",
                    "path"          => "Ticket",
                    "path2"         => "Edit",
                    "ticket"        => $ticket,
                    "assets"        => $assets,
                    "users"         => $users,
                    "source"        => $source,
                    "ticketFors"    => $ticketFors
                ]);
            }

        // Jika status ticket sedang diproses oleh agent
        }else{
            return back()->with('error', 'Tickets are being processed by agents!');
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
            'user_id'           => 'required',
            'asset_id'          => 'required',
            'ticket_for'        => 'required',
            'kendala'           => 'required|min:5|max:35',
            'detail_kendala'    => 'required|min:10',
            'file'              => 'max:1024'
        ];

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules);

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Ticket berdasarkan id Ticket
        $ticket = Ticket::where('id', $id)->first();

        // Get data Request input
        $newFile    = $request['file'];
        $oldFile    = $request['old_file'];
        $userId     = $request['user_id'];

        if($newFile == NULL){
            $imageName = $oldFile;
        }else{
            $imageName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads/ticket'), $imageName);
        }

        // Mencari data Lokasi Client untuk mengisi data code_access
        $getClient      = User::where([['is_active', '1'],['id', $userId]])->first();
        $locationId     = $getClient['location_id'];
        $codeAccess     = $getClient['code_access'];

        // Get NIK Service Desk
        $getServiceDesk = User::where([['is_active', '1'],['location_id', $request['ticket_for']],['role_id', 1]])->whereNotIn('position_id', [2, 7])->first();
        $nikServiceDesk = $getServiceDesk['nik'];

        // Get ID Service Desk
        $getAgent   = Agent::where([['is_active', '1'],['nik', $nikServiceDesk]])->first();
        $agentId    = $getAgent['id'];

        // Updating data to ticket table
        Ticket::where('id', $id)->update([
            'user_id'           => $request['user_id'],
            'agent_id'          => $agentId,
            'location_id'       => $locationId,
            'asset_id'          => $request['asset_id'],
            'ticket_for'        => $request['ticket_for'],
            'kendala'           => strtolower($request['kendala']),
            'detail_kendala'    => strtolower($request['detail_kendala']),
            'file'              => $imageName,
            'code_access'       => $codeAccess,
            'source'            => $request['source'],
            'updated_by'        => Auth::user()->nama
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
        return redirect('/tickets')->with('success', 'Ticket successfully updated!');
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
        $data = User::where([['is_active', '1'],['id',$id]])->first();
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
        $data = Asset::where([['location_id', $id],['status', 'digunakan']])
        ->with('item')
        ->get();

        $data->map(function($data) {
            $data->nama_barang = $data->item->name;

            return $data;
        });
        
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $updatedBy = Auth::user()->nama;

        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        $ticket = Ticket::where('id', $id)->first();
        $statusTicket = $ticket->status;
        if($statusTicket == "created"){
            Ticket::where('id', $id)->update(['status' => 'deleted']);
            $now        = date('d-m-Y H:i:s');

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di hapus oleh ".ucwords($updatedBy);
            $progress_ticket->process_at    = $now;
            $progress_ticket->status        = "deleted";
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            return back()->with('success', 'Ticket successfully deleted!');
        }else{
            return back()->with('error', 'Tickets are being processed by agents!');
        }
    }

    public function ticketAsset(Request $request)
    {
        $nik        = Auth::user()->nik;
        $locationId = Auth::user()->location_id;
        
        // Get id Asset dari request parameter
        $assetId = decrypt($request['asset_id']);
        
        // Mencari agent_id untuk parameter menampilkan halaman ticket Agent
        $getAgent   = Agent::where([['is_active', '1'],['nik', $nik]])->first();
        $agentId    = $getAgent['id'];
        
        // Get data Asset berdasarkan id Asset
        $asset      = Asset::where('id', $assetId)->first();
        $noAsset    = $asset->no_asset;

        // Get data ticket berdasarkan asset id
        $tickets    = Ticket::where('asset_id', $assetId)->whereNotIn('status', ['deleted'])->orderBy('created_at', 'DESC')->get();

        // Mencari agent yang memiliki sub divisi, untuk menentukan antrian dan assign
        $haveSubDivs = Sub_division::select('location_id')->distinct()->pluck('location_id')->toArray();

        // Get data Sub Divisi Agent HO & Store, untuk select option Antrikan
        $subDivHo = Agent::where([['location_id', $locationId],['pic_ticket', '!=', 'store']])->whereNotIn('id', [$agentId])->distinct()->pluck('sub_divisi')->toArray();
        $subDivStore = Agent::where([['location_id', $locationId],['pic_ticket', '!=', 'ho']])->whereNotIn('id', [$agentId])->distinct()->pluck('sub_divisi')->toArray();
        
        $hoAgents      = Agent::where([['is_active', '1'],['location_id', $locationId],['pic_ticket', '!=', 'store'],['status', 'present']])->whereNotIn('id', [$agentId])->get();
        $storeAgents   = Agent::where([['is_active', '1'],['location_id', $locationId],['pic_ticket', '!=', 'ho'],['status', 'present']])->whereNotIn('id', [$agentId])->get();

        return view('contents.ticket.filter.index', [
            "url"           => $noAsset,
            "title"         => "Ticket",
            "path"          => "Ticket",
            "path2"         => "Asset: ". $noAsset,
            "pathFilter"    => "Asset: ". $noAsset,
            "haveSubDivs"   => $haveSubDivs,
            "subDivHo"      => $subDivHo,
            "subDivStore"   => $subDivStore,
            "hoAgents"      => $hoAgents,
            "storeAgents"   => $storeAgents,
            "agentId"       => $agentId,
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
        $role       = Auth::user()->role_id;
        $codeAccess = Auth::user()->code_access;

        // Menentukan Filter Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "All Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where([['id', $filter1],['is_active', '1']])->first();
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
            $pathFilter = "All Period";
        }

        $getUser    = User::where([['is_active', '1'],['id', $id]])->first();
        $nik        = $getUser['nik'];
        $location   = $getUser->location->nama_lokasi;
        $locationId = $getUser->location_id;
        $positionId = $getUser['position_id'];
        $getAgent   = Agent::where([['nik', $nik],['is_active', '1']])->first();
        $agentId    = $getAgent['id'];

        if($role == 3){ // Jika role Client
            if($positionId == 2){ // Jika jabatan Chief
                if($status == "all"){
                    $title      = "Ticket Total";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Need Approval";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Unprocessed";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket On Process";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Pending";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Closed";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }elseif($positionId == "6"){ // Jika jabatan Koordinator Wilayah
                if($status == "all"){
                    $title      = "Ticket Total";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Need Approval";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Unprocessed";
                    $tickets    = $ticket     = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket On Process";
                    $ticket     = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Pending";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Closed";
                    $tickets    = Ticket::where([['code_access', 'like', '%'.$codeAccess],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }elseif($positionId == "7"){ // Jika jabatan Manager
                if($status == "all"){
                    $title      = "Ticket Total";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Need Approval";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Unprocessed";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket On Process";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Pending";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Closed";
                    $tickets    = Ticket::where([['code_access', 'like', $codeAccess.'%'],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }else{ // Jika jabatan selain Korwil, Chief dan Manager
                if($status == "all"){
                    $title      = "Ticket Total";
                    $tickets    = Ticket::where([['location_id', $locationId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }elseif($status == "approval"){
                    $title      = "Ticket Need Approval";
                    $tickets    = Ticket::where([['location_id', $locationId],['need_approval', 'ya'],['approved', NULL],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Unprocessed";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', 'created'],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket On Process";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Pending";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', $status],['created_at', 'like', $filter2.'%']])->get();
                }else{
                    $title      = "Ticket Closed";
                    $tickets    = Ticket::where([['location_id', $locationId],['status', 'finished'],['created_at', 'like', $filter2.'%']])->get();
                }
            }
        }else{ // Jika role Service Desk / Agent
            if($role == 1){
                $pathFilter = "[".$namaAgent."] - [".$pathFilter."]";
                // Menghitung Ticket Total Service Desk
                if($status == "all"){
                    $title      = "Ticket Total Masuk";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted', 'resolved', 'finished'])->get();
                }elseif($status == "unprocess"){
                    $title      = "Ticket Unprocessed";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['status', 'created'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "onprocess"){
                    $title      = "Ticket On Process";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['status', 'onprocess'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "pending"){
                    $title      = "Ticket Pending";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['status', 'pending'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->get();
                }elseif($status == "selesai"){
                    $title      = "Ticket Resolved";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['status', 'resolved'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                                        ->orWhere([['ticket_for', $locationId],['status', 'finished'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])
                                        ->orderBy('updated_at', 'DESC')
                                        ->get();
                }elseif($status == "assign"){
                    $title      = "Ticket Participant";
                    $tickets    = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                                        ->where([['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                                        ->select('tickets.*')
                                        ->get();
                }elseif($status == "workday"){
                    $title      = "Work Day Ticket";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['jam_kerja', 'ya'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }else{
                    $title      = "Off Day Ticket";
                    $tickets    = Ticket::where([['ticket_for', $locationId],['jam_kerja', 'tidak'],['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted'])->get();
                }
            }else{
                $pathFilter = "[".$pathFilter."]";
                // Menghitung Ticket Total Agent
                if($status == "all"){
                    $title      = "Ticket Assigned";
                    $tickets    = Ticket::where([['agent_id', $agentId],['created_at', 'like', $filter2.'%']])->whereNotIn('status', ['deleted', 'resolved', 'finished'])->get();
                }elseif($status == "selesai"){
                    $title      = "Ticket Resolved";
                    $tickets    = Ticket::where([['agent_id', $agentId],['status', 'resolved'],['created_at', 'like', $filter2.'%']])
                        ->orWhere([['agent_id', $agentId],['status', 'finished'],['created_at', 'like', $filter2.'%']])
                        ->get();
                }else{
                    $title      = "Ticket Participant";
                    $tickets    = Ticket::join('ticket_details', 'tickets.id', '=', 'ticket_details.ticket_id')
                        ->where([['ticket_details.agent_id', $agentId],['ticket_details.status', 'assigned'],['ticket_details.created_at', 'like', $filter2.'%']])
                        ->select('tickets.*')
                        ->get();
                }
            }
        }

        $haveSubDivs   = Sub_division::select('location_id')->distinct()->pluck('location_id')->toArray();
        // Get data Sub Divisi Agent HO & Store, untuk select option Antrikan
        $subDivHo = Agent::where([['location_id', $locationId],['pic_ticket', '!=', 'store']])->whereNotIn('id', [$agentId])->distinct()->pluck('sub_divisi')->toArray();
        $subDivStore = Agent::where([['location_id', $locationId],['pic_ticket', '!=', 'ho']])->whereNotIn('id', [$agentId])->distinct()->pluck('sub_divisi')->toArray();

        $hoAgents      = Agent::where([['is_active', '1'],['location_id', $locationId],['pic_ticket', '!=', 'store'],['status', 'present']])->whereNotIn('id', [$agentId])->get();
        $storeAgents   = Agent::where([['is_active', '1'],['location_id', $locationId],['pic_ticket', '!=', 'ho'],['status', 'present']])->whereNotIn('id', [$agentId])->get();

        return view('contents.ticket.filter.index', [
            "title"         => $title,
            "path"          => "Ticket",
            "path2"         => $title,
            "pathFilter"    => $pathFilter,
            "hoAgents"      => $hoAgents,
            "storeAgents"   => $storeAgents,
            "haveSubDivs"   => $haveSubDivs,
            "subDivHo"      => $subDivHo,
            "subDivStore"   => $subDivStore,
            "tickets"       => $tickets,
            "agentId"       => $agentId
        ]);
    }
}