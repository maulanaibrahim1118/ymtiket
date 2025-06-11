<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Processed At by the Agents.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter != "Semua")
            <tr>
                <td colspan="6" class="font-monospace ps-3">Date : {{ $pathFilter }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
                <th rowspan="2">#</th>
                <th rowspan="2">SUB DIVISION</th>
                <th colspan="2">AVERAGE</th>
                <th rowspan="2" class="col-md-2">TOTAL WORKDAY</th>
                <th rowspan="2" class="col-md-2">PERCENTAGE (HOUR/DAY)</th>
            </tr>
            <tr class="text-center align-middle">
                <th>TICKET/DAY</th>
                <th>HOUR/DAY</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @php
                $nomorUrut = 1;
            @endphp
    
            @foreach($subDivisiReports as $subDivisiReport)
                <!-- Row untuk nama sub_divisi -->
                <tr>
                    <td class="text-center">{{ $nomorUrut++ }}.</td>
                    <td>{{ $subDivisiReport->sub_divisi ?? 'No Sub Division' }}</td>
                    <td class="text-end">{{ $subDivisiReport->ticket_per_day }}</td>
                    @php
                        $hourPerDay = $subDivisiReport->hour_per_day;
                        $hours = floor($hourPerDay / 3600);
                        $minutes = floor(($hourPerDay % 3600) / 60);
                        $seconds = $hourPerDay % 60;
                    @endphp
                    <td class="text-end">
                        {{-- <a href="{{ route('reportAgent.showDetailTicket', ['agent_id' => encrypt($agent->id), 'type' => 'hourperday', 'start_date' => $filterArray[0], 'end_date' => $filterArray[1]]) }}" target="_blank"> --}}
                        @if($hourPerDay != 0)
                            {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                        @else
                            00:00:00
                        @endif
                        {{-- </a> --}}
                    </td>
                    <td class="text-end">{{ $subDivisiReport->totalDay }}</td>
                    <td class="text-end">{{ $subDivisiReport->percentage }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>