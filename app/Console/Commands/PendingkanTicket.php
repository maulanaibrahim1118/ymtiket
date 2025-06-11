<?php

namespace App\Console\Commands;

use Exception;
use App\Ticket;
use Carbon\Carbon;
use App\Ticket_detail;
use App\Progress_ticket;
use App\Sub_category_ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendingkanTicket extends Command
{
    protected $signature = 'ticket:pending';

    protected $description = 'Menghentikan seluruh proses ticket yang sedang berjalan hari ini, untuk dihitung waktu pengerjaannya.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Starting ticket stop and calculate command.');

        $now = date('Y-m-d H:i:s');
        $tickets = Ticket::whereIn('status', ['onprocess','pending','standby'])->get();

        Log::info('Found ' . $tickets->count() . ' tickets with status "onprocess" or "pending" or "standby.');

        DB::beginTransaction();

        try {
            foreach ($tickets as $ticket) {
                Log::info('Ticket: ' . $ticket->no_ticket . ' | ' . $ticket->status);
                $processAt = Carbon::parse($ticket->process_at);
                $processedTime = $processAt->diffInSeconds($now);

                if ($ticket->status == "onprocess" || $ticket->status == "standby") {
                    $detailTicket = Ticket_detail::where('ticket_id', $ticket->id)->orderBy('created_at', 'desc')->first();

                    if ($ticket->status == "standby") {
                        $standbyAt = Carbon::parse($detailTicket->standby_at);
                        $standbyTime1 = $detailTicket->standby_time ?? 0;
                        $standbyTime2 = $standbyAt->diffInSeconds($now);
                        $standbyTime = $standbyTime1+$standbyTime2;

                        $detailTicket->standby_time = $standbyTime;
                        $detailTicket->save();

                        $ticket->standby_time = $standbyTime;
                        $ticket->save();
                    }

                    if ($detailTicket) {
                        $detailPendingTime = $detailTicket->pending_time ?? 0;
                        $detailStandbyTime = $detailTicket->standby_time ?? 0;
                        $detailProcessedTime = $processedTime-$detailPendingTime-$detailStandbyTime;
                        Log::info('proctime: '.$processedTime.' - detpentime: '.$detailPendingTime.' - detstandtime: '.$detailStandbyTime.' = '.$detailProcessedTime);

                        $detailTicket->status = 'assigned';
                        $detailTicket->processed_time = $detailProcessedTime;
                        $detailTicket->save();
                    } else {
                        $none = Sub_category_ticket::where('nama_sub_kategori', 'none')->first();
                        $defaultSubCategory = $none->id;

                        // Jika tidak ada detail ticket yang sedang onproses, buat entri baru
                        Ticket_detail::create([
                            'ticket_id' => $ticket->id,
                            'jenis_ticket' => 'none',
                            'sub_category_ticket_id' => $defaultSubCategory,
                            'agent_id' => $ticket->agent_id,
                            'process_at' => $ticket->process_at,
                            'processed_time' => $processedTime,
                            'note' => 'No saved action history',
                            'status' => 'assigned',
                            'updated_by' => 'sistem',
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                    }

                    // Membuat entri baru di tabel progress_tickets
                    Progress_ticket::create([
                        'ticket_id' => $ticket->id,
                        'tindakan' => 'Ticket di pending oleh sistem. Alasan: Diluar jam kerja Agent.',
                        'process_at' => $now,
                        'status' => 'pending',
                        'updated_by' => 'sistem',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $ticket->status = 'pending';
                    $ticket->assigned = 'ya';
                    $ticket->last_pending_reason = 'Pending by Sistem';
                    $ticket->pending_at = $now;
                    $ticket->standby_at = null;
                    $ticket->save();
                } else {
                    $detailTicket = Ticket_detail::where('ticket_id', $ticket->id)->orderBy('created_at', 'desc')->first();
                    
                    if($detailTicket->status == "pending") {
                        $pendingTime0 = $ticket->pending_time ?? 0;
                        $detailPendingTime0 = $detailTicket->pending_time ?? 0;

                        $pendingAt = Carbon::parse($detailTicket->pending_at);
                        $detailPendingTime = $pendingAt->diffInSeconds($now);

                        $detailProcessedTime = $processedTime-($detailPendingTime0+$detailPendingTime);

                        $detailTicket->status = 'assigned';
                        $detailTicket->pending_time = $detailPendingTime0+$detailPendingTime;
                        $detailTicket->processed_time = $detailProcessedTime;
                        $detailTicket->save();

                        $ticket->assigned = 'ya';
                        $ticket->pending_at = $now;
                        $ticket->pending_time = $pendingTime0+$detailPendingTime;
                        $ticket->save();
                    }
                }
                Log::info('Completed');
            }

            DB::commit();
            Log::info('All Ticket with status onprocess or pending or standby are successfully stopped and calculated the process for today.');
            $this->info('Semua ticket yang sedang onproses atau pending atau standby, berhasil di hentikan dan di hitung prosesnya untuk hari ini.');
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Log::error('Terjadi kesalahan: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        
            // Lempar kembali exception
            throw $e;
        }
    }
}