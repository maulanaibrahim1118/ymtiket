<?php

namespace App\Exports;

use App\Category_ticket;
use App\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $locationId;
    protected $category;
    protected $startDate;
    protected $endDate;
    protected $agents;

    public function __construct($locationId, $category, $startDate, $endDate)
    {
        $this->locationId = $locationId;
        $this->category = $category;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->agents = Agent::where([['location_id', $locationId],['is_active', '1']])->get();
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Category_ticket::where('location_id', $this->locationId);

        if (!empty($this->category)) {
            $query->where('nama_kategori', $this->category);
        }

        $categories = $query->with(['sub_category_tickets.ticket_details' => function($query) {
            if (!empty($this->startDate) && !empty($this->endDate)) {
                if ($start_date == $end_date) {
                    $query->whereDate('created_at', '=', $startDate);
                } else {
                    $query->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate);
                }
            } elseif (!empty($this->startDate)) {
                $query->whereDate('created_at', '>=', $this->startDate);
            } elseif (!empty($this->endDate)) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }
            $query->whereIn('status', ['resolved', 'assigned']);
            $query->with('agent');
        }])->get();

        $data = [];
        foreach ($categories as $category) {
            foreach ($category->sub_category_tickets as $subCategory) {
                $row = [
                    'category' => $category->nama_kategori,
                    'sub_category' => $subCategory->nama_sub_kategori,
                ];
                foreach ($this->agents as $agent) {
                    $avgTime = $subCategory->ticket_details->where('agent_id', $agent->id)->avg('processed_time');
                    $row[$agent->nama_agent] = $avgTime ? round($avgTime) : '00:00:00';
                }
                $row['Total Average'] = $subCategory->ticket_details->avg('processed_time');
                $data[] = $row;
            }
        }
        return collect($data);
    }

    public function map($row): array
    {
        $mappedData = [
            ucwords($row['category']),
            $row['sub_category'],
        ];
        foreach ($this->agents as $agent) {
            $mappedData[] = $this->formatTime($row[$agent->nama_agent]);
        }
        $mappedData[] = $this->formatTime($row['Total Average']);
        return $mappedData;
    }

    private function formatTime($seconds)
    {
        if ($seconds == '00:00:00') return $seconds;
        $hour = intdiv($seconds, 3600);
        $minute = intdiv($seconds % 3600, 60);
        $second = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }

    public function headings(): array
    {
        $headings = ['Category','Sub Category'];
        $headings[] = 'Total Average';
        foreach ($this->agents as $agent) {
            $headings[] = ucwords($agent->nama_agent);
        }
        return $headings;
    }
}