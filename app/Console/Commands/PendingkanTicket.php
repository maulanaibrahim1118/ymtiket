<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Sub_category_ticket;
use App\Progress_ticket;
use App\Ticket_detail;
use App\Ticket;
use Carbon\Carbon;

class PendingkanTicket extends Command
{
    protected $signature = 'ticket:pending';

    protected $description = 'Mengubah status semua ticket yang sedang onproses menjadi pending';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Starting ticket:pending command.');

        $tickets = Ticket::where('status', 'onprocess')->get();

        Log::info('Found ' . $tickets->count() . ' tickets with status "onprocess".');

        foreach ($tickets as $ticket) {
            $ticket->status = 'pending';
            $ticket->last_pending_reason = 'Pending by Sistem';
            $ticket->pending_at = Carbon::now();
            $ticket->save();

            // Mengubah status dan pending_at pada detail tickets yang sedang onproses
            $detailTicket = Ticket_detail::where('ticket_id', $ticket->id)
                                        ->where('status', 'onprocess')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

            if ($detailTicket) {
                $detailTicket->status = 'pending';
                $detailTicket->pending_at = Carbon::now();
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
                    'pending_at' => Carbon::now(),
                    'note' => 'No saved action history',
                    'status' => 'pending',
                    'updated_by' => 'sistem',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            // Membuat entri baru di tabel progress_tickets
            Progress_ticket::create([
                'ticket_id' => $ticket->id,
                'tindakan' => 'Ticket di pending oleh sistem. Alasan: Diluar jam kerja Agent.',
                'process_at' => Carbon::now(),
                'status' => 'pending',
                'updated_by' => 'sistem',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Log::info('Completed ticket:pending command.');

        $this->info('Semua ticket yang sedang onproses, detail ticket yang terkait telah diubah menjadi pending, atau entri baru telah dibuat, dan entri progress ticket telah dibuat.');
    }
}