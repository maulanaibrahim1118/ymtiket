<?php

namespace App\Exports;

use App\Agent;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $locationIds;
    protected $startDate;
    protected $endDate;
    protected $report;

    public function __construct($locationIds, $startDate, $endDate, $report)
    {
        $this->locationIds = $locationIds;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->report = $report;
    }

    public function collection()
    {
        $agents = Agent::whereIn('location_id', $this->locationIds)
            ->where('is_active', '1')
            ->with([
                'ticket' => function($query) {
                    $query->whereNotIn('status', ['deleted']);
    
                    // Menentukan jenis filter berdasarkan input tanggal
                    if ($this->startDate && !$this->endDate) {
                        // Jika hanya start_date yang diisi, filter berdasarkan tanggal itu saja
                        $query->whereDate('created_at', '=', $this->startDate);
                    } elseif (!$this->startDate && $this->endDate) {
                        // Jika hanya end_date yang diisi, filter berdasarkan tanggal itu saja
                        $query->whereDate('created_at', '=', $this->endDate);
                    } elseif ($this->startDate && $this->endDate) {
                        // Jika kedua tanggal diisi, filter antara dua tanggal tersebut
                        $query->whereDate('created_at', '>=', $this->startDate)
                              ->whereDate('created_at', '<=', $this->endDate);
                    }
    
                    $query->with('ticket_detail');
                },
                'ticket_details' => function($query) {
                    if ($this->startDate && !$this->endDate) {
                        $query->whereDate('created_at', '=', $this->startDate);
                    } elseif (!$this->startDate && $this->endDate) {
                        $query->whereDate('created_at', '=', $this->endDate);
                    } elseif ($this->startDate && $this->endDate) {
                        $query->whereDate('created_at', '>=', $this->startDate)
                              ->whereDate('created_at', '<=', $this->endDate);
                    }
                }
            ])
            ->withCount(['ticket', 'ticket_details'])
            ->orderBy('sub_divisi', 'ASC')
            ->get();

        $agents->map(function ($agent) {
            $totalTicket = $agent->ticket_details->count();
            $workHour = $agent->ticket_details->sum('processed_time');
            $uniqueDates = $agent->ticket_details->pluck('created_at')
                ->map(function ($date) {
                    return $date ? $date->format('Y-m-d') : null;
                })
                ->unique()
                ->filter()
                ->count();

            $agent->total_ticket = $agent->ticket->count();
            $agent->ticket_unprocessed = $agent->ticket->where('status', 'created')->count();
            $agent->ticket_pending = $agent->ticket->where('status', 'pending')->count();
            $agent->ticket_onprocess = $agent->ticket->where('status', 'onprocess')->count();
            $agent->ticket_finish = $agent->ticket->whereIn('status', ['resolved', 'finished'])->count();
            $agent->ticket_assigned = $agent->ticket_details->where('status', 'assigned')->count();

            $agent->avg_pending = $agent->ticket->pluck('ticket_detail.pending_time')->average();
            $agent->avg_finish = $agent->ticket->pluck('ticket_detail.processed_time')->average();

            if ($totalTicket == 0 || $workHour == 0 || $uniqueDates == 0) {
                $agent->ticket_per_day = 0;
                $agent->hour_per_day = 0;
            } else {
                $agent->ticket_per_day = round($totalTicket / $uniqueDates);
                $agent->hour_per_day = round($workHour / $uniqueDates);
            }
            $agent->percentage = round(($agent->hour_per_day / 28800) * 100);

            $agent->avg_permintaan = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->average('processed_time');
            $agent->avg_kendala = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->average('processed_time');

            $agent->permintaan = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'permintaan')->count();
            $agent->kendala = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->where('jenis_ticket', 'kendala')->count();

            $agent->jml_ticket = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->count();
            $agent->jml_process = $agent->ticket_details->whereIn('status', ['resolved', 'assigned'])->sum('processed_time');

            return $agent;
        });

        return $agents;
    }

    public function map($agent): array
    {
        if($this->report == 1){
            return [
                $agent->nik.' ',
                ucwords($agent->nama_agent),
                ucwords($agent->sub_divisi),
                $agent->ticket_pending ? $agent->ticket_pending : '0',
                $agent->ticket_onprocess ? $agent->ticket_onprocess : '0',
                $agent->ticket_finish ? $agent->ticket_finish : '0',
                $agent->ticket_assigned ? $agent->ticket_assigned : '0',
            ];
        }
        if($this->report == 2){
            $average1 = $agent->avg_pending;
            $average2 = $agent->avg_finish;

            $hours1 = floor($average1 / 3600);
            $minutes1 = floor(($average1 % 3600) / 60);
            $seconds1 = $average1 % 60;
            
            $hours2 = floor($average2 / 3600);
            $minutes2 = floor(($average2 % 3600) / 60);
            $seconds2 = $average2 % 60;

            return [
                $agent->nik.' ',
                ucwords($agent->nama_agent),
                ucwords($agent->sub_divisi),
                $average1 ? sprintf('%02d:%02d:%02d', $hours1, $minutes1, $seconds1) : '00:00:00',
                $average2 ? sprintf('%02d:%02d:%02d', $hours2, $minutes2, $seconds2) : '00:00:00',
            ];
        }
        if($this->report == 3){
            $hourPerDay = $agent->hour_per_day;
            $hours = floor($hourPerDay / 3600);
            $minutes = floor(($hourPerDay % 3600) / 60);
            $seconds = $hourPerDay % 60;
            return [
                $agent->nik.' ',
                ucwords($agent->nama_agent),
                ucwords($agent->sub_divisi),
                $agent->ticket_per_day ? $agent->ticket_per_day : '0',
                $hourPerDay ? sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) : '00:00:00',
                $agent->percentage . '%',
            ];
        }
        if($this->report == 4){
            $average1 = $agent->avg_permintaan;
            $average2 = $agent->avg_kendala;

            $hours1 = floor($average1 / 3600);
            $minutes1 = floor(($average1 % 3600) / 60);
            $seconds1 = $average1 % 60;
            
            $hours2 = floor($average2 / 3600);
            $minutes2 = floor(($average2 % 3600) / 60);
            $seconds2 = $average2 % 60;

            return [
                $agent->nik.' ',
                ucwords($agent->nama_agent),
                ucwords($agent->sub_divisi),
                $average1 ? sprintf('%02d:%02d:%02d', $hours1, $minutes1, $seconds1) : '00:00:00',
                $average2 ? sprintf('%02d:%02d:%02d', $hours2, $minutes2, $seconds2) : '00:00:00',
            ];
        }
        if($this->report == 5){
            return [
                $agent->nik.' ',
                ucwords($agent->nama_agent),
                ucwords($agent->sub_divisi),
                $agent->permintaan ? $agent->permintaan : '0',
                $agent->kendala ? $agent->kendala : '0',
            ];
        }
    }

    public function headings(): array
    {
        if($this->report == 1){
            return [
                'Employee Number',
                'Agent Name',
                'Sub Division',
                'Pending',
                'Onprocess',
                'Resolved',
                'Participant',
            ];
        }
        if($this->report == 2){
            return [
                'Employee Number',
                'Agent Name',
                'Sub Division',
                'Average Pending',
                'Average Resolved',
            ];
        }
        if($this->report == 3){
            return [
                'Employee Number',
                'Agent Name',
                'Sub Division',
                'Average Ticket/Day',
                'Average Hour/Day',
                'Percentage (Hour/Day)',
            ];
        }
        if($this->report == 4){
            return [
                'Employee Number',
                'Agent Name',
                'Sub Division',
                'Average Request',
                'Average Accident',
            ];
        }
        if($this->report == 5){
            return [
                'Employee Number',
                'Agent Name',
                'Sub Division',
                'Request',
                'Accident',
            ];
        }
    }
}