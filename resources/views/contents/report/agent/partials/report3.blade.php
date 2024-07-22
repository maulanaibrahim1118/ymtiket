<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Processed At by the Agents.</p>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter != "Semua")
            <tr>
            <td colspan="7" class="font-monospace ps-3">Tanggal : {{ $pathFilter }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
            <th rowspan="2">#</th>
            <th rowspan="2">EMPLOYEE NUMBER</th>
            <th rowspan="2">AGENT NAME</th>
            <th rowspan="2">SUB DIVISION</th>
            <th colspan="2">AVERAGE</th>
            <th rowspan="2" class="col-md-1">PERCENTAGE (HOUR/DAY)</th>
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
            @foreach($agents as $agent)
            <tr>
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            <td class="text-end">{{ $agent->ticket_per_day }}</td>

            @php
                $hourPerDay = $agent->hour_per_day;
                $hours = floor($hourPerDay / 3600);
                $minutes = floor(($hourPerDay % 3600) / 60);
                $seconds = $hourPerDay % 60;
            @endphp
            @if($hourPerDay != 0)
                <td class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
            @else
                <td class="text-end">00:00:00</td>
            @endif
            <td class="text-end">{{ $agent->percentage }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>