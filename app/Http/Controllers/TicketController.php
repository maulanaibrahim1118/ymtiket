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
use Illuminate\Support\Facades\Log;
use App\Jobs\SendFonnteNotification;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function checkOnprocessTicket($ticketId, $agentId, $updatedBy)
    {
        // Find the ticket with status "onprocess" for the given agent
        $ticket = Ticket::where([['agent_id', $agentId], ['status', 'onprocess']])->whereNotIn('id', [$ticketId])->first();

        if ($ticket) {
            // Get the most recent ticket detail
            $detail = Ticket_detail::where([['ticket_id', $ticket->id], ['agent_id', $agentId]])->latest()->first();
            $now = Carbon::now(); // Get current time once

            // Update the ticket status to "standby"
            $ticket->update([
                'status'      => "standby",
                'standby_at'  => $now,
                'assigned'    => "tidak",
                'is_queue'    => "tidak",
                'updated_by'  => $updatedBy,
                'updated_at'  => $now
            ]);

            // Update the ticket detail status to "standby"
            if ($detail) {
                $detail->update([
                    'status'      => "standby",
                    'standby_at'  => $now,
                    'updated_by'  => $updatedBy,
                    'updated_at'  => $now
                ]);
            } else {
                // Mencari waktu proses ticket agent
                $processAt = Carbon::parse($ticket->process_at);
                $none = Sub_category_ticket::where('nama_sub_kategori', 'none')->first();
                $defaultSubCategory = $none->id;

                // Saving data to ticket_detail table
                $ticket_detail                          = new Ticket_detail;
                $ticket_detail->ticket_id               = $ticket->id;
                $ticket_detail->jenis_ticket            = "none";
                $ticket_detail->sub_category_ticket_id  = $defaultSubCategory;
                $ticket_detail->agent_id                = $agentId;
                $ticket_detail->process_at              = $ticket->process_at;
                $ticket_detail->standby_at              = $now;
                $ticket_detail->note                    = "No saved action history";
                $ticket_detail->file                    = null;
                $ticket_detail->status                  = "standby";
                $ticket_detail->updated_by              = $updatedBy;
                $ticket_detail->save();
            }
        }
    }

    // Proses ticket yang baru dibuat atau status created (role: service desk)
    public function process1(Request $request)
    {
        $updatedBy = Auth::user()->nama;
        $id = decrypt($request['id']); // Decrypt ticket ID

        DB::beginTransaction();

        try {
            // Get current time once
            $now = Carbon::now();
            
            $ticket = Ticket::findOrFail($id);

            // Periksa tiket onprocess sebelumnya
            $this->checkOnprocessTicket($id, $ticket->agent_id, $updatedBy);
            

            // Mengganti status ticket dan memulai hitung waktu proses ticket
            if($ticket->status != "onprocess") {
                $ticket->update([
                    'status'      => "onprocess",
                    'process_at'  => $now,
                    'assigned'    => "tidak",
                    'is_queue'    => "tidak",
                    'updated_by'  => $updatedBy,
                    'updated_at'  => $now
                ]);

                // Menyimpan data ke tabel progress_ticket
                $progress_ticket = new Progress_ticket;
                $progress_ticket->ticket_id  = $id;
                $progress_ticket->tindakan   = "Ticket di proses oleh " . ucwords($updatedBy);
                $progress_ticket->process_at = $now;
                $progress_ticket->status     = "onprocess";
                $progress_ticket->updated_by = $updatedBy;
                $progress_ticket->save();

            }
            
            DB::commit();

            // Redirect to ticket detail page
            return redirect()->route('ticket-detail.create', ['ticket_id' => encrypt($id)]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing ticket: ' . $e->getMessage());
            return back()->with('error', 'Ticket cannot be processed!');
        }
    }

    // Proses ticket yang sudah pernah di proses sebelumnya oleh service desk/agent lain
    public function process2(Request $request)
    {
        $updatedBy = Auth::user()->nama;
        $id = decrypt($request->input('id'));
        $nikAgent = $request->input('nik');

        $now = Carbon::now();
        $status = "onprocess";
        $types = ["kendala", "permintaan"];

        // Mencari ticket dan menghitung waktu pending
        $ticket = Ticket::findOrFail($id);
        $pendingAt = Carbon::parse($ticket->pending_at);
        $pendingTime = $pendingAt->diffInSeconds($now);
        
        $pending_time = $ticket->pending_time ? $ticket->pending_time + $pendingTime : $pendingTime;

        // Mencari Sub Category Ticket terakhir yang diproses agent sebelumnya
        $ticket_detail = Ticket_detail::where('ticket_id', $id)->latest()->first();
        $subCategoryId = $ticket_detail->sub_category_ticket_id ?? null;

        // Mencari Category Ticket terakhir yang diproses agent sebelumnya
        $subCategory = Sub_category_ticket::find($subCategoryId);
        $categoryId = $subCategory->category_ticket_id ?? null;

        DB::beginTransaction();

        try {
            $this->checkOnprocessTicket($id, $ticket->agent_id, $updatedBy);

            if($ticket->status != "onprocess") {
                // Update data ticket
                $ticket->update([
                    'status'        => $status,
                    'process_at'    => $now,
                    'pending_at'    => null,
                    'assigned'      => "tidak",
                    'is_queue'      => "tidak",
                    'pending_time'  => $pending_time,
                    'updated_by'    => $updatedBy,
                ]);

                // Simpan data ke progress ticket
                Progress_ticket::create([
                    'ticket_id'     => $id,
                    'tindakan'      => "Ticket di proses oleh " . ucwords($updatedBy),
                    'process_at'    => $now,
                    'status'        => $status,
                    'updated_by'    => $updatedBy,
                ]);

                DB::commit();
            }

            // Mencari extension file
            $ext = substr($ticket->file, -4);

            return view('contents.ticket_detail.create2', [
                "title"                 => "Ticket Process",
                "path"                  => "Ticket",
                "path2"                 => "Process",
                "category_tickets"      => Category_ticket::whereNotIn('nama_kategori', ['none'])->get(),
                "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
                "progress_tickets"      => Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get(),
                "ticket"                => $ticket,
                "td"                    => $ticket_detail,
                "countDetail"           => Ticket_detail::where('ticket_id', $id)->count(),
                "ticket_details"        => Ticket_detail::where('ticket_id', $id)->get(),
                "types"                 => $types,
                "ext"                   => $ext,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ticket cannot be processed!');
        }
    }

    // Proses ticket yang sudah di approve
    public function process3(Request $request)
    {
        $updatedBy = Auth::user()->nama;

        $id = decrypt($request['id']);
        $agentId = decrypt($request['agent_id']);

        $status = "onprocess";
        $now = Carbon::now();

        // Mencari apakah agent tersebut sudah pernah menangani ticket tersebut atau tidak
        $getDetail = Ticket_detail::where('ticket_id', $id)
            ->where('agent_id', $agentId)
            ->latest()
            ->first();

        DB::beginTransaction();

        try {
            if ($getDetail === null) {
                // Agent belum pernah menangani ticket tersebut
                $previousDetail = Ticket_detail::where('ticket_id', $id)
                    ->where('biaya', '!=', 0)
                    ->latest()
                    ->first();

                // Membuat Detail Ticket sama seperti agent sebelumnya
                Ticket_detail::create([
                    'ticket_id'               => $id,
                    'jenis_ticket'            => $previousDetail->jenis_ticket,
                    'sub_category_ticket_id'  => $previousDetail->sub_category_ticket_id,
                    'agent_id'                => $agentId,
                    'process_at'              => $now,
                    'pending_at'              => NULL,
                    'biaya'                   => $previousDetail->biaya,
                    'note'                    => $previousDetail->note,
                    'status'                  => $status,
                    'updated_by'              => $updatedBy,
                ]);
            } else {
                // Agent sudah pernah menangani ticket tersebut
                $getDetail->update([
                    'process_at' => $now,
                    'status'     => $status,
                ]);
            }

            // Menghitung pending time ticket
            $ticket = Ticket::find($id);
            $pendingAt = Carbon::parse($ticket->pending_at);
            $pendingTime = $pendingAt->diffInSeconds($now);

            $pending_time = $ticket->pending_time ? $ticket->pending_time + $pendingTime : $pendingTime;

            if($ticket->status != "onprocess") {
                // Updating data to ticket table
                $ticket->update([
                    'status'        => $status,
                    'pending_at'    => NULL,
                    'is_queue'      => "tidak",
                    'pending_time'  => $pending_time,
                    'updated_by'    => $updatedBy,
                ]);

                // Menyimpan data ke progress_ticket
                Progress_ticket::create([
                    'ticket_id'     => $id,
                    'tindakan'      => "Ticket di proses oleh " . ucwords($updatedBy),
                    'process_at'    => $now,
                    'status'        => $status,
                    'updated_by'    => $updatedBy,
                ]);
            }

            DB::commit();

            // Redirect ke halaman ticket detail
            return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->with('error', 'Ticket cannot be processed!');
            throw $e;
        }
    }

    public function queue(Request $request)
    {
        $updatedBy = Auth::user()->nama;
        $locationId = Auth::user()->location_id;
        $nik = Auth::user()->nik;
        $agentAuth = Agent::where('nik', $nik)->first();
        $agentIdAuth = $agentAuth->id;
        
        $ticketId   = decrypt($request['id']);
        $subDivisi  = $request['sub_divisi'];
        $now        = date('d-m-Y H:i:s');
        $isQueue    = "ya";

        $ticket     = Ticket::where('id', $ticketId)->first();
        $status     = $ticket->status;
        $agentId    = $ticket->agent_id;

        DB::beginTransaction();

        try {
            if($status == "created"){
                Ticket::where('id', $ticketId)->update([
                    'sub_divisi_agent'  => $subDivisi,
                    'updated_by'        => $updatedBy
                ]);
            }else{
                $ticketDetail   = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId]])->latest()->first();
                $ticketDetailId = $ticketDetail->id;
                $processAt      = Carbon::parse($ticketDetail->process_at);
                $now            = Carbon::parse($now);
                $processedTime  = $processAt->diffInSeconds($now);

                // Updating data to ticket detail table (agent pertama)
                Ticket_detail::where('id', $ticketDetailId)->update([
                    'processed_time'    => $processedTime,
                    'status'            => "assigned",
                    'updated_by'        => $updatedBy
                ]);

                Ticket::where('id', $ticketId)->update([
                    'pending_at'        => $now,
                    'status'            => "pending",
                    'pending_at'        => date('Y-m-d H:i:s'),
                    'sub_divisi_agent'  => $subDivisi,
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
            }

            $today = Carbon::today()->toDateString(); // Mendapatkan tanggal hari ini

            // Mendapatkan daftar agent_id dari tabel agents berdasarkan sub_divisi
            $agentIds = Agent::where([
                ['sub_divisi', $subDivisi], 
                ['status', 'present'], 
                ['is_active', '1']
            ])->whereNotIn('nik', [$nik])->pluck('id');

            // Mendapatkan daftar agent_id dari tabel tickets berdasarkan sub_divisi_agent dan status "onprocess"
            $ticketAgentIds = Ticket::where([
                ['sub_divisi_agent', $subDivisi], 
                ['status', 'onprocess']
            ])->pluck('agent_id')->unique();

            // Mencari agent_id yang tidak memiliki tiket dengan status "onprocess"
            $uniqueAgentIds = $agentIds->diff($ticketAgentIds)->values();
            
            if ($ticketAgentIds->isNotEmpty()) {
                // Menghitung jumlah tiket < 5 untuk setiap agen tanggal hari ini
                $ticketsToday = Ticket::select('agent_id')
                    ->whereIn('agent_id', $uniqueAgentIds)
                    ->whereDate('created_at', $today)
                    ->groupBy('agent_id')
                    ->havingRaw('COUNT(*) < 5') // Menambahkan kondisi hanya agen dengan total tiket kurang dari 5
                    ->selectRaw('agent_id, COUNT(*) as total')
                    ->pluck('total', 'agent_id');

                // Jika ada agen yang memenuhi kondisi tersebut, alokasikan tiket ke agen tersebut
                if ($ticketsToday->isNotEmpty()) {
                    // Menginisialisasi ticketCounts dengan nilai 0 untuk semua unique agent ids
                    $ticketCounts = $uniqueAgentIds->mapWithKeys(function ($id) {
                        return [$id => 0];
                    });

                    // Memperbarui ticketCounts dengan data tiket yang ditemukan
                    foreach ($ticketsToday as $agentId => $count) {
                        $ticketCounts[$agentId] = $count;
                    }

                    // Mendapatkan nilai terkecil dari jumlah tiket
                    $minTicketCount = $ticketCounts->min();

                    // Mendapatkan agent_id dengan nilai tiket terkecil, dan memilih salah satu jika ada beberapa
                    $agentWithMinTickets = $ticketCounts->filter(function ($count) use ($minTicketCount) {
                        return $count == $minTicketCount;
                    })->keys()->sort()->first();

                    Ticket::where('id', $ticketId)->update([
                        'agent_id' => $agentWithMinTickets,
                        'assigned' => "ya",
                        'updated_by' => $updatedBy
                    ]);

                    $agent = Agent::where('id', $agentWithMinTickets)->first();
                    $agentName = $agent->nama_agent;

                    $progress_ticket = new Progress_ticket;
                    $progress_ticket->ticket_id = $ticketId;
                    $progress_ticket->tindakan = "Ticket di assign dari antrian ke " . ucwords($agentName) . " oleh sistem";
                    $progress_ticket->status = "assigned";
                    $progress_ticket->process_at = $now;
                    $progress_ticket->updated_by = $updatedBy;
                    $progress_ticket->save();

                    $progress_ticket = new Progress_ticket;
                    $progress_ticket->ticket_id = $ticketId;
                    $progress_ticket->tindakan = "Ticket sedang dalam antrian";
                    $progress_ticket->status = "edited";
                    $progress_ticket->process_at = $now;
                    $progress_ticket->updated_by = $updatedBy;
                    $progress_ticket->save();

                    DB::commit();
                    // Kembali ke halaman ticket beserta pesan sukses
                    return back()->with('success', 'Ticket successfully assigned to ' . ucwords($agentName) . '!');
                }
            }
            
            // Jika semua agen memiliki lebih dari 5 tiket, atau jika tidak ada agen yang memenuhi kondisi di atas, tiket diantrikan
            Ticket::where('id', $ticketId)->update([
                'is_queue' => $isQueue,
                'updated_by' => $updatedBy
            ]);

            $progress_ticket = new Progress_ticket;
            $progress_ticket->ticket_id = $ticketId;
            $progress_ticket->tindakan = "Ticket sedang dalam antrian";
            $progress_ticket->status = "edited";
            $progress_ticket->process_at = $now;
            $progress_ticket->updated_by = $updatedBy;
            $progress_ticket->save();

            DB::commit();
            
            // Kembali ke halaman ticket beserta pesan sukses
            return back()->with('success', 'Ticket successfully queued!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->with('error', 'Ticket cannot be queued!');
            throw $e;
        }
    }

    // Assign pada saat status ticket masih created (role: service desk)
    public function assign(Request $request)
    {
        $updatedBy = Auth::user()->nama;
        $ticketId = decrypt($request['ticket_id']);
        
        if($request['agent_id'] == NULL){
            return back()->with('error', 'Agent Name required!');
        } else {
            $getAgent   = Agent::where([['is_active', '1'],['id', $request['agent_id']]])->first();
            $agentName  = $getAgent->nama_agent;
            $agentId    = $getAgent->id;
            $subDivisi  = $getAgent->sub_divisi;
            $now        = date('d-m-Y H:i:s');

            // Mencari data no. hp untuk agent yang akan di assign
            $userAgent = User::where('nik', $getAgent->nik)->first();
            $agentPhone = $userAgent->telp;

            DB::beginTransaction();

            try {
                $ticket = Ticket::find($ticketId);

                if ($ticket) {
                    $ticket->update([
                        'assigned'         => "ya",
                        'is_queue'         => "tidak",
                        'agent_id'         => $agentId,
                        'sub_divisi_agent' => $subDivisi,
                        'updated_by'       => $updatedBy,
                        'role'             => 2
                    ]);

                    $noTiket = $ticket->no_ticket;
                    
                    if($ticket->location->wilayah_id == 1 || $ticket->location->wilayah_id == 2){
                        $cabang = ucwords($ticket->user->nama)." (".ucwords($ticket->location_name).")";
                    } else {
                        $cabang = ucwords($ticket->location_name);
                    }

                    $kendala = $ticket->kendala;

                    // Kirim notifikasi ke WhatsApp via job/helper
                    if (!empty($agentPhone) && strlen(preg_replace('/\D/', '', $agentPhone)) >= 8) {
                        SendFonnteNotification::dispatch("+$agentPhone", "Tiket baru telah ditugaskan ke Anda!\n\nNo Tiket: $noTiket\nClient: $cabang\nKendala: $kendala");
                    }
                }

                // Saving data to progress ticket table
                $progress_ticket                = new Progress_ticket;
                $progress_ticket->ticket_id     = $ticketId;
                $progress_ticket->tindakan      = "Ticket di assign ke ".ucwords($agentName)." oleh ".ucwords($updatedBy);
                $progress_ticket->status        = "assigned";
                $progress_ticket->process_at    = $now;
                $progress_ticket->updated_by    = $updatedBy;
                $progress_ticket->save();

                DB::commit();
                return back()->with('success', 'Ticket successfully assigned to '.ucwords($agentName).'!');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();
                return back()->with('error', 'Ticket cannot be assign!');
                throw $e;
            }
        }
    }

    // Assign pada saat status ticket sudah onprocess (role: service desk/agent)
    public function assign2(Request $request)
    {
        if($request['agent_id'] == NULL){
            return back()->with('error', 'Agent Name required!');
        }else {
            $agentId1   = decrypt($request['agent_id1']); // Agent sebelumnya (yang meng assign ticket)
            $ticketId   = decrypt($request['ticket_id']);
            $updatedBy  = Auth::user()->nama;
            $url        = $request['url'];

            $getAgent   = Agent::where([['is_active', '1'],['id', $request['agent_id']]])->first(); // Agent yang menerima ticket assign
            $agentName2 = $getAgent->nama_agent;
            $agentId2   = $getAgent->id;
            $now        = date('d-m-Y H:i:s');

            // Mencari data no. hp untuk agent yang akan di assign
            $userAgent = User::where('nik', $getAgent->nik)->first();
            $agentPhone = $userAgent->telp;
            
            // Get data ticket
            $ticket = Ticket::where('id', $ticketId)->first();
            $statusTicket = $ticket->status;
            $noTiket = $ticket->no_ticket;

            if($ticket->location->wilayah_id == 1 || $ticket->location->wilayah_id == 2){
                $cabang = ucwords($ticket->user->nama)." (".ucwords($ticket->location_name).")";
            } else {
                $cabang = ucwords($ticket->location_name);
            }
            
            $kendala = $ticket->kendala;

            DB::beginTransaction();

            try {
                if($statusTicket == "pending"){
                    // Mencari tanggal/waktu pending untuk menghitung total waktu pending
                    $getTicketDetail    = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1]])->latest()->first();
                    $ticketDetailId     = $getTicketDetail->id;
                    $getPending1        = $getTicketDetail->pending_time;
                    $now                = date('d-m-Y H:i:s');
                    $reProcess_at       = Carbon::parse($now);
                    $pending_at1        = Carbon::parse($getTicketDetail->pending_at);
                    $pending_at2        = NULL;
                    $status             = "onprocess";

                    // Mencari lama nya waktu ticket di pending
                    $getPending2    = $pending_at1->diffInSeconds($reProcess_at);
                    $pending_time   = $getPending1+$getPending2;
                    
                    // Updating data to ticket table
                    Ticket_detail::where([['id', $ticketDetailId],['status', 'pending']])->update([
                        'pending_at'    => $pending_at2,
                        'pending_time'  => $pending_time,
                        'status'        => $status
                    ]);

                    // Updating data to ticket table
                    Ticket::where('id', $ticketId)->update([
                        'pending_at'    => $pending_at2,
                        'pending_time'  => $pending_time,
                        'assigned'      => "ya",
                        'is_queue'      => "tidak",
                        'status'        => $status
                    ]);
                }

                // Updating data to ticket table
                Ticket::where('id', $ticketId)->update([
                    'status'        => "pending",
                    'pending_at'    => $now,
                    'assigned'      => "ya",
                    'is_queue'      => "tidak",
                    'agent_id'      => $agentId2,
                    'updated_by'    => $updatedBy,
                    'role'          => 2
                ]);
                
                // Kirim notifikasi ke WhatsApp via job/helper
                if (!empty($agentPhone) && strlen(preg_replace('/\D/', '', $agentPhone)) >= 8) {
                    SendFonnteNotification::dispatch("+$agentPhone", "Tiket baru telah ditugaskan ke Anda!\n\nNo Tiket: $noTiket\nClient: $cabang\nKendala: $kendala");
                }

                $getTicketDetail    = Ticket_detail::where([['ticket_id', $ticketId],['agent_id', $agentId1]])->latest()->first();
                $ticketDetailId     = $getTicketDetail->id;
                $subCategoryId      = $getTicketDetail->sub_category_ticket_id;
                $biaya              = $getTicketDetail->biaya;
                $note               = $getTicketDetail->note;
                $processAt          = Carbon::parse($getTicketDetail->process_at);
                $now                = Carbon::parse($now);
                $processedTime      = $processAt->diffInSeconds($now);

                // Updating data to ticket detail table (agent pertama)
                Ticket_detail::where('id', $ticketDetailId)->update([
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

                DB::commit();
                return redirect('tickets')->with('success', 'Ticket successfully assigned to '.ucwords($agentName2).'!');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();
                return back()->with('error', 'Ticket cannot be assign!');
                throw $e;
            }
        }
    }

    public function assignAnother(Request $request){
        // Get data User
        $updatedBy = Auth::user()->nama;

        // Get id Ticket dari request parameter
        $id = decrypt($request['ticket_id']);
        $ticketFor = $request['ticket_for'];
        $now = date('d-m-Y H:i:s');

        // Mencari ID Service Desk Divisi Lain
        $userSD     = User::where([['is_active', '1'],['location_id', $ticketFor],['role_id', 1]])->whereNotIn('position_id', [2, 7, 8])->first();
        $nikSD      = $userSD->nik;
        $agentSD    = Agent::where('nik', $nikSD)->first();
        $agentId    = $agentSD->id;

        // Mencari Ticket
        $ticket = Ticket::where('id', $id)->first();

        // Mencari Detail Ticket
        $ticketDetail = Ticket_detail::where('ticket_id', $id)->latest()->first();
        if($ticketDetail != NULL){
            $ticketDetailId = $ticketDetail->id;
        }

        // Mencari nama divisi yang di assign
        $location = Location::where('id', $ticketFor)->first();
        $locationName = $location->nama_lokasi;

        DB::beginTransaction();

        try {
            Ticket::where('id', $id)->update([
                'is_queue'      => "tidak",
                'assigned'      => "ya",
                'ticket_for'    => $ticketFor,
                'agent_id'      => $agentId,
                'updated_by'    => $updatedBy
            ]);

            if($ticket->status == "onprocess"){
                $processedTime1 = $ticketDetail->processed_time;
                $pendingTime1 = $ticketDetail->pending_time;

                // Mencari waktu proses ticket agent
                $processAt = Carbon::parse($ticketDetail->process_at);
                $processedTime2 = $processAt->diffInSeconds($now);
                $processedTime = ($processedTime1+$processedTime2)-$pendingTime1;

                Ticket::where('id', $id)->update([
                    'status'        => "pending",
                    'pending_at'    => $now,
                    'updated_by'    => $updatedBy
                ]);

                Ticket_detail::where('id', $ticketDetailId)->update([
                    'status'            => 'assigned',
                    'processed_time'    => $processedTime,
                    'updated_by'        => $updatedBy
                ]);

                $progress_ticket                = new Progress_ticket;
                $progress_ticket->ticket_id     = $id;
                $progress_ticket->tindakan      = "Ticket di pending oleh Sistem";
                $progress_ticket->status        = "pending";
                $progress_ticket->process_at    = $now;
                $progress_ticket->updated_by    = "sistem";
                $progress_ticket->save();

            }elseif($ticket->status == "pending"){
                $processedTime1 = $ticketDetail->processed_time;

                if($ticketDetail->pending_at == "-" || $ticketDetail->pending_at == NULL){
                    $pendingTime = 0;

                    if($ticket->pending_at == "-" || $ticket->pending_at == NULL){
                        $ticketPending = $ticket->$pending_time+$pendingTime;
                    }else{
                        // Mencari waktu pending ticket all
                        $pendingAt2 = Carbon::parse($ticket->pending_at);
                        $pendingTime2 = $pendingAt2->diffInSeconds($now);

                        if($ticket->pending_time == NULL || $ticket->pending_time == 0){
                            $ticketPending = $pendingTime2;
                        }else{
                            $ticketPending = $ticket->pending_time+$pendingTime2;
                        }
                    }
                }else{
                    // Mencari waktu pending ticket agent
                    $pendingAt = Carbon::parse($ticketDetail->pending_at);
                    $pendingTime = $pendingAt->diffInSeconds($now);
                    $ticketPending = $ticket->pending_time+$pendingTime;
                }

                // Mencari waktu proses ticket agent
                $processAt = Carbon::parse($ticketDetail->process_at);
                $processedTime2 = $processAt->diffInSeconds($now);
                $processedTime = ($processedTime1+$processedTime2)-$pendingTime;

                if($ticket->pending_time == NULL){
                    $ticketPending = $pendingTime;
                }else{
                    $ticketPending = $ticket->pending_time+$pendingTime;
                }

                Ticket::where('id', $id)->update([
                    'pending_at'    => $now,
                    'pending_time'  => $ticketPending,
                    'updated_by'    => $updatedBy
                ]);

                Ticket_detail::where('id', $ticketDetailId)->update([
                    'status'            => 'assigned',
                    'processed_time'    => $processedTime,
                    'pending_time'      => $pendingTime,
                    'updated_by'        => $updatedBy
                ]);

                $progress_ticket                = new Progress_ticket;
                $progress_ticket->ticket_id     = $id;
                $progress_ticket->tindakan      = "Ticket di pending oleh Sistem";
                $progress_ticket->status        = "pending";
                $progress_ticket->process_at    = $now;
                $progress_ticket->updated_by    = "sistem";
                $progress_ticket->save();
            }

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di assign ke Divisi ".$locationName." oleh ".ucwords($updatedBy);
            $progress_ticket->status        = "assigned";
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();
            
            DB::commit();
            return redirect('tickets')->with('success', 'Ticket successfully assigned to '.ucwords($locationName).'!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->with('error', 'Ticket cannot be assign!');
            throw $e;
        }
    }

    public function pull(Request $request)
    {
        // Get data User
        $nik = Auth::user()->nik;
        $role = Auth::user()->role_id;
        $updatedBy = Auth::user()->nama;
        
        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);

        $now = date('d-m-Y H:i:s');
        $agent = Agent::where([['is_active', '1'],['nik', $nik]])->first();
        $agentId = $agent->id;
        $ticket = Ticket::where('id', $id)->first();
        $ticketDetail = Ticket_detail::where('ticket_id', $id)->latest()->first();
        $none = Sub_category_ticket::where('nama_sub_kategori', 'none')->first();
        $defaultSubCategory = $none->id;

        DB::beginTransaction();

        try {
            Ticket::where('id', $id)->update([
                'is_queue'  => "tidak",
                'assigned'  => "tidak",
            ]);

            if($ticket->status == "created"){
                Ticket::where('id', $id)->update([
                    'agent_id'      => $agentId,
                    'updated_by'    => $updatedBy
                ]);

                // Saving data to progress ticket table
                $progress_ticket                = new Progress_ticket;
                $progress_ticket->ticket_id     = $id;
                $progress_ticket->tindakan      = "Ticket di tarik kembali oleh ".ucwords($updatedBy);
                $progress_ticket->status        = "assigned";
                $progress_ticket->process_at    = $now;
                $progress_ticket->updated_by    = $updatedBy;
                $progress_ticket->save();
            
                DB::commit();
                return redirect('/tickets')->with('success', 'Ticket successfully pulled!');
            }else{
                $now = Carbon::parse($now);

                if($ticket->status == "onprocess"){
                    if($ticketDetail == NULL){
                        // Mencari waktu proses ticket agent
                        $processAt = Carbon::parse($ticket->process_at);
                        $processedTime = $processAt->diffInSeconds($now);

                        // Saving data to ticket_detail table
                        $ticket_detail                          = new Ticket_detail;
                        $ticket_detail->ticket_id               = $id;
                        $ticket_detail->jenis_ticket            = "none";
                        $ticket_detail->sub_category_ticket_id  = $defaultSubCategory;
                        $ticket_detail->agent_id                = $ticket->agent_id;
                        $ticket_detail->process_at              = $ticket->process_at;
                        $ticket_detail->pending_at              = NULL;
                        $ticket_detail->processed_time          = $processedTime;
                        $ticket_detail->note                    = "No saved action history";
                        $ticket_detail->file                    = null;
                        $ticket_detail->status                  = "assigned";
                        $ticket_detail->updated_by              = $updatedBy;
                        $ticket_detail->save();
                    }else{
                        $ticketDetailId = $ticketDetail->id;
                        
                        if($ticket->agent_id == $ticketDetail->agent_id){
                            $processedTime1 = $ticketDetail->processed_time;
                            $pendingTime1 = $ticketDetail->pending_time;

                            // Mencari waktu proses ticket agent
                            $processAt = Carbon::parse($ticketDetail->process_at);
                            $processedTime2 = $processAt->diffInSeconds($now);
                            $processedTime = ($processedTime1+$processedTime2)-$pendingTime1;
                            
                            Ticket_detail::where('id', $ticketDetailId)->update([
                                'status'            => 'assigned',
                                'processed_time'    => $processedTime,
                                'updated_by'        => $updatedBy
                            ]);

                        // Jika status onprocess namun agent belum melakukan tindakan apapun
                        }else{
                            // Mencari waktu proses ticket agent
                            $processAt = Carbon::parse($ticket->process_at);
                            $processedTime = $processAt->diffInSeconds($now);

                            // Saving data to ticket_detail table
                            $ticket_detail                          = new Ticket_detail;
                            $ticket_detail->ticket_id               = $id;
                            $ticket_detail->jenis_ticket            = "none";
                            $ticket_detail->sub_category_ticket_id  = $defaultSubCategory;
                            $ticket_detail->agent_id                = $ticket->agent_id;
                            $ticket_detail->process_at              = $ticket->process_at;
                            $ticket_detail->pending_at              = NULL;
                            $ticket_detail->processed_time          = $processedTime;
                            $ticket_detail->note                    = "No saved action history";
                            $ticket_detail->file                    = null;
                            $ticket_detail->status                  = "assigned";
                            $ticket_detail->updated_by              = $updatedBy;
                            $ticket_detail->save();
                        }
                    }
                }else{ // Jika Pending
                    $ticketDetailId = $ticketDetail->id;
                    $processedTime1 = $ticketDetail->processed_time;

                    if($ticketDetail->pending_at == "-" || $ticketDetail->pending_at == NULL && $ticket->pending_at == "-" || $ticket->pending_at == NULL){
                        $countPending = 0;

                        $totalPending = $ticketDetail->pending_time + $countPending; // Menentukan total pending agent
                        $ticketPending = $ticket->pending_time + $countPending; // Menentukan total pending ticket
                    }else{
                        // Mencari waktu pending ticket agent
                        $pendingAt = Carbon::parse($ticketDetail->pending_at);
                        $countPending = $pendingAt->diffInSeconds($now);

                        $totalPending = $ticketDetail->pending_time + $countPending; // Menentukan total pending agent
                    }

                    $pendingAt2 = Carbon::parse($ticket->pending_at);
                    $countPending2 = $pendingAt2->diffInSeconds($now);
                    $ticketPending = $ticket->pending_time + $countPending2;

                    // Mencari waktu proses ticket agent
                    $processAt = Carbon::parse($ticketDetail->process_at);
                    $processedTime2 = $processAt->diffInSeconds($now);
                    $processedTime = ($processedTime1+$processedTime2)-$totalPending;

                    if($ticketDetail->agent_id != $agentId && $ticketDetail->processed_time == NULL){
                        Ticket_detail::where('id', $ticketDetailId)->update([
                            'status'            => 'assigned',
                            'processed_time'    => $processedTime,
                            'pending_time'      => $totalPending,
                            'updated_by'        => $updatedBy
                        ]);
                    }

                    Ticket::where('id', $id)->update([
                        'pending_time'  => $ticketPending,
                        'updated_by'    => $updatedBy
                    ]);
                }

                Ticket::where('id', $id)->update([
                    'agent_id'      => $agentId,
                    'status'        => "pending",
                    'assigned'      => "ya",
                    'pending_at'    => $now,
                    'updated_by'    => $updatedBy
                ]);

                // Saving data to progress ticket table
                $progress_ticket                = new Progress_ticket;
                $progress_ticket->ticket_id     = $id;
                $progress_ticket->tindakan      = "Ticket di tarik kembali oleh ".ucwords($updatedBy);
                $progress_ticket->status        = "assigned";
                $progress_ticket->process_at    = $now;
                $progress_ticket->updated_by    = $updatedBy;
                $progress_ticket->save();

                DB::commit();
                return redirect('/tickets')->with('success', 'Ticket successfully pulled!');
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->with('error', 'Ticket cannot be pull!');
            throw $e;
        }
    }

    public function pending(Request $request)
    {
        // Get data User
        $nik        = Auth::user()->nik;
        $role       = Auth::user()->role_id;
        $updatedBy  = Auth::user()->nama;
        
        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        $alasan = $request['alasanPending'];

        if($alasan == NULL){
            return back()->with('error', 'Pending Reason required!');
        }else {
            $now        = date('Y-m-d H:i:s');
            $status     = "pending";

            // Mencari agent_id untuk merubah pending at pada tabel ticket detail
            $agentNik   = $nik;
            $getAgent   = Agent::where([['is_active', '1'],['nik', $agentNik]])->first();
            $agentId    = $getAgent->id;

            $ticketDetail = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
            $ticketDetailId = $ticketDetail->id;
            
            // Updating data to ticket table
            Ticket_detail::where('id', $ticketDetailId)->update([
                'pending_at'    => $now,
                'status'        => $status
            ]);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'is_queue'              => "tidak",
                'pending_at'            => $now,
                'status'                => $status,
                'last_pending_reason'   => $alasan,
            ]);

            // Saving data to progress ticket table
            $progress_ticket                = new Progress_ticket;
            $progress_ticket->ticket_id     = $id;
            $progress_ticket->tindakan      = "Ticket di pending oleh ".ucwords($updatedBy)." (Alasan : ".$alasan.")";
            $progress_ticket->status        = $status;
            $progress_ticket->process_at    = $now;
            $progress_ticket->updated_by    = $updatedBy;
            $progress_ticket->save();

            if($role == 1){
                return redirect('/tickets')->with('success', 'Ticket successfully pending!');
            }else{
                $getAgent       = Agent::where([['is_active', '1'],['nik', $nik]])->first();
                $agentId2       = $getAgent->id;
                $subDivisi      = $getAgent->sub_divisi;
                $agentStatus    = $getAgent->status;
                $agentLocation  = $getAgent->location->id;
                $countTicket    = Ticket::where('agent_id', $agentId2)->count();
                $countResolved  = Ticket::where('agent_id', $agentId2)->whereIn('status', ['resolved', 'finished'])->count();
    
                if($agentStatus != 'present'){ // Jika Agent tidak hadir, izin, keluar kota, dll
                    return redirect('/tickets')->with('success', 'Ticket successfully pending!');
                }else{ // Jika agent hadir di kantor
                    if($countTicket-$countResolved >= 5){
                        return redirect('/tickets')->with('success', 'Ticket successfully pending!');
                    }else{
                        if($subDivisi){
                            $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', $subDivisi]])->first();
                        }
        
                        if($getAntrian == NULL){ // Jika antrian ticket sudah habis
                            return redirect('/tickets')->with('success', 'Ticket successfully pending!');
                        }else {
                            $ticketId = $getAntrian->id;
        
                            Ticket::where('id', $ticketId)->update([
                                'agent_id'      => $agentId2,
                                'role'          => 2,
                                'is_queue'      => "tidak",
                                'assigned'      => "ya",
                                'updated_by'    => $updatedBy
                            ]);
                
                            return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket successfully pending!');
                        }
                    }
                }
            }

            return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket successfully pending!');
        }
    }

    // Proses kembali jika status pending
    public function reProcess1(Request $request)
    {
        $nik = Auth::user()->nik;
        $updatedBy = Auth::user()->nama;

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Mencari agent_id untuk merubah pending_time pada tabel ticket detail
        $getAgent   = Agent::where([['is_active', '1'],['nik', $nik]])->first();
        $agentId    = $getAgent->id;

        // Mencari tanggal/waktu pending untuk menghitung total waktu pending
        $getTicketDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
        $ticketDetailId     = $getTicketDetail->id;
        $now                = date('d-m-Y H:i:s');
        $reProcess_at       = Carbon::parse($now);
        $pending_at1        = Carbon::parse($getTicketDetail->pending_at);
        $pending_at2        = NULL;
        $status             = "onprocess";
        
        // Mencari lama nya waktu ticket di pending
        $getPending2    = $pending_at1->diffInSeconds($reProcess_at);
        $pending_time   = $getTicketDetail->pending_time+$getPending2;
        $ticket         = Ticket::where('id', $id)->first();

        if($ticket->pending_time == NULL || $ticket->pending_time == 0){
            $pending_time2 = $pending_time;
        }else{
            $pending_time2  = $ticket->pending_time+$getPending2;
        }
        
        $this->checkOnprocessTicket($id, $ticket->agent_id, $updatedBy);
        
        if($ticket->status != "onprocess") {
            // Updating data to ticket table
            Ticket_detail::where('id', $ticketDetailId)->update([
                'pending_at'    => $pending_at2,
                'pending_time'  => $pending_time,
                'status'        => $status
            ]);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'is_queue'      => "tidak",
                'assigned'      => "tidak",
                'pending_at'    => $pending_at2,
                'pending_time'  => $pending_time2,
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
        }

        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
    }

    // Proses kembali jika status onprocess (melanjutkan proses ticket)
    public function reProcess2(Request $request){
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        $getTicket      = Ticket::where('id', $id)->first();
        $agentId        = $getTicket->agent_id;
        $countDetailAll = Ticket_detail::where('ticket_id', $id)->count();
        $countDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->whereNotIn('status', ['assigned'])->count();

        // Mencari extension file
        $ext = substr($getTicket->file, -4);

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
                    "title"                 => "Ticket Process",
                    "path"                  => "Ticket",
                    "path2"                 => "Process",
                    "category_tickets"      => Category_ticket::whereNotIn('nama_kategori', ['none'])->get(),
                    "sub_category_tickets"  => Sub_category_ticket::where('category_ticket_id', $categoryId)->get(),
                    "progress_tickets"      => Progress_ticket::where('ticket_id', $id)->orderBy('created_at', 'DESC')->get(),
                    "ticket"                => Ticket::where('id', $id)->first(),
                    "td"                    => Ticket_detail::where('ticket_id', $id)->latest()->first(),
                    "countDetail"           => Ticket_detail::where('ticket_id', $id)->count(),
                    "ticket_details"        => Ticket_detail::where('ticket_id', $id)->get(),
                    "types"                 => $types,
                    "ext"                   => $ext
                ]);
            }
        }else {
            return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
        }
    }

    public function reProcess3(Request $request)
    {
        $nik = Auth::user()->nik;
        $updatedBy = Auth::user()->nama;

        // Get id Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Mencari agent_id untuk merubah pending_time pada tabel ticket detail
        $getAgent   = Agent::where([['is_active', '1'],['nik', $nik]])->first();
        $agentId    = $getAgent->id;

        // Mencari tanggal/waktu pending untuk menghitung total waktu pending
        $getTicketDetail    = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
        $ticketDetailId     = $getTicketDetail->id;
        $now                = date('d-m-Y H:i:s');
        $reProcess_at       = Carbon::parse($now);
        $standby_at1        = Carbon::parse($getTicketDetail->standby_at);
        $standby_at2        = NULL;
        $status             = "onprocess";
        
        // Mencari lama nya waktu ticket di pending
        $getStandby2    = $standby_at1->diffInSeconds($reProcess_at);
        $standby_time   = $getTicketDetail->standby_time+$getStandby2;
        $ticket         = Ticket::where('id', $id)->first();

        if($ticket->standby_time == NULL || $ticket->standby_time == 0){
            $standby_time2 = $standby_time;
        }else{
            $standby_time2  = $ticket->standby_time+$getStandby2;
        }

        $this->checkOnprocessTicket($id, $ticket->agent_id, $updatedBy);
        
        if($ticket->status != "onprocess") {
            // Updating data to ticket table
            Ticket_detail::where('id', $ticketDetailId)->update([
                'standby_at'    => $standby_at2,
                'standby_time'  => $standby_time,
                'status'        => $status
            ]);

            // Updating data to ticket table
            Ticket::where('id', $id)->update([
                'is_queue'      => "tidak",
                'assigned'      => "tidak",
                'standby_at'    => $standby_at2,
                'standby_time'  => $standby_time2,
                'status'        => $status
            ]);
        }

        return redirect()->route('ticket-detail.index', ['ticket_id' => encrypt($id)]);
    }

    public function resolved(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        $role           = Auth::user()->role_id;
        $nik            = Auth::user()->nik;
        $updatedBy      = Auth::user()->nama;
        $agentId        = decrypt($request['agent_id']);
        $now            = date('d-m-Y H:i:s');

        // Mencari lamanya ticket di proses
        $getTicket      = Ticket::where('id', $id)->first();

        // Mencari lamanya ticket di proses berdasarkan agent/service desk
        $getDetail      = Ticket_detail::where([['ticket_id', $id],['agent_id', $agentId]])->latest()->first();
        $detail_id      = $getDetail->id;
        $ticket_detail  = Ticket_detail::where('id', $detail_id)->first();
        $processAt2     = Carbon::parse($ticket_detail->process_at);
        $now            = Carbon::parse($now);
        $pendingTime    = $ticket_detail->pending_time;
        $standbyTime    = $ticket_detail->standby_time ? $ticket_detail->standby_time : 0;
        $processedTime  = $ticket_detail->processed_time;
        $processedTime2 = $processAt2->diffInSeconds($now);
        $processedTime3 = ($processedTime+$processedTime2)-$pendingTime-$standbyTime;

        // Mencari Sub Category ID untuk mendapatkan data asset_change
        $subCategoryId  = $ticket_detail->sub_category_ticket_id;
        $subCategory    = Sub_category_ticket::where('id', $subCategoryId)->first();
        $assetChange    = $subCategory->asset_change;

        // Jika Sub Category Ticket tersebut dapat merubah status asset
        if($assetChange == "ya"){
            $assetId    = $getTicket->asset_id;
            
            Asset::where('id', $assetId)->update([
                'status'    => "tidak digunakan"
            ]);
        }

        Ticket_detail::where('id', $detail_id)->update([
            'status'            => "resolved",
            'processed_time'    => $processedTime3,
            'updated_by'        => $updatedBy
        ]);

        $processedTime1 = Ticket_detail::where('ticket_id', $id)->sum('processed_time');

        Ticket::where('id', $id)->update([
            'status'            => "resolved",
            'is_queue'          => "tidak",
            'assigned'          => "tidak",
            'processed_time'    => $processedTime1,
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

        if($role == 1){
            return redirect('/tickets')->with('success', 'Ticket resolved!');
        }else{
            $getAgent       = Agent::where([['nik', $nik],['is_active', '1']])->first();
            $agentId2       = $getAgent->id;
            $subDivisi      = $getAgent->sub_divisi;
            $agentStatus    = $getAgent->status;
            $agentLocation  = $getAgent->location->id;
            $countTicket    = Ticket::where('agent_id', $agentId2)->count();
            $countResolved  = Ticket::where('agent_id', $agentId2)->whereIn('status', ['resolved', 'finished'])->count();

            if($agentStatus != 'present'){ // Jika Agent tidak hadir
                return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket resolved!');
            }else{ // Jika agent hadir di kantor
                if($countTicket-$countResolved > 0){
                    return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket resolved!');
                }else{
                    if($subDivisi){
                        $getAntrian     = Ticket::where([['ticket_for', $agentLocation],['is_queue', 'ya'],['sub_divisi_agent', $subDivisi]])->first();
                    }

                    if($getAntrian == NULL){ // Jika antrian ticket sudah habis
                        return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket resolved!');
                    }else {
                        $ticketId = $getAntrian->id;

                        Ticket::where('id', $ticketId)->update([
                            'agent_id'      => $agentId2,
                            'role'          => 2,
                            'is_queue'      => "tidak",
                            'assigned'      => "ya",
                            'updated_by'    => $updatedBy
                        ]);
            
                        return redirect()->route('ticket.dashboard', ['status' => 'all', 'filter1' => $agentId2, 'filter2' => ''])->with('success', 'Ticket resolved!');
                    }
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
            return back()->with('error', 'Closed Status required!');
        }

        $now            = date('d-m-Y H:i:s');
        $alasanClosed   = $request['alasanClosed'];
        $updatedBy      = Auth::user()->nama;

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
            
            return redirect('/tickets')->with('success', 'Ticket closed!');
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
            $getLocAgent    = Location::where('id', $ticketFor)->first();
            $locIdAgent     = $getLocAgent['id'];
            $getServiceDesk = User::where([['is_active', '1'],['location_id', $locIdAgent],['role_id', 1]])->first();
            $nikServiceDesk = $getServiceDesk['nik'];
            $getAgent       = Agent::where([['nik', $nikServiceDesk],['is_active', '1']])->first();
            $agentId        = $getAgent['id'];
            
            // Mencari Area, Regional, Wilayah Client untuk mengisi data code_access
            $userId         = $getTicket->user_id;
            $getClient      = User::where([['is_active', '1'],['is_active', '1'],['id', $userId]])->first();
            $locationId     = $getClient['location_id'];
            $codeAccess     = $getClient['code_access'];

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
            $ticket->kendala        = "Re: (".$getTicket->no_ticket.") ".$kendala;
            $ticket->detail_kendala = "Re: (".$getTicket->detail_kendala.") ".$request['alasanClosed'];
            $ticket->asset_id       = $getTicket->asset_id;
            $ticket->user_id        = $getTicket->user_id;
            $ticket->location_id    = $getTicket->location_id;
            $ticket->location_name  = $getTicket->location->nama_lokasi;
            $ticket->agent_id       = $agentId;
            $ticket->role           = 1;
            $ticket->status         = "created";
            $ticket->is_queue       = "tidak";
            $ticket->assigned       = "tidak";
            $ticket->need_approval  = "tidak";
            $ticket->jam_kerja      = $jamKerja;
            $ticket->ticket_for     = $getTicket->ticket_for;
            $ticket->code_access    = $codeAccess;
            $ticket->estimated      = "-";
            $ticket->file           = $getTicket->file;
            $ticket->created_by     = $updatedBy;
            $ticket->source         = $getTicket->source;
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
         
            return redirect('/tickets')->with('success', 'Ticket closed!');
        }
    }
}