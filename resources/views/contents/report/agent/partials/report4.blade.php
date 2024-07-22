<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Processed At by the Agents.</p>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter != "Semua")
            <tr>
            <td colspan="6" class="font-monospace ps-3">Tanggal : {{ $pathFilter }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
            <th rowspan="2">#</th>
            <th rowspan="2">EMPLOYEE NUMBER</th>
            <th rowspan="2">AGENT NAME</th>
            <th rowspan="2">SUB DIVISION</th>
            <th colspan="2">TICKET TYPE</th>
            </tr>
            <tr class="text-center align-middle">
            <th>REQUEST</th>
            <th>ACCIDENT</th>
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
            @php
                $avgHourRequest = $agent->avg_permintaan;
                $hours = floor($avgHourRequest / 3600);
                $minutes = floor(($avgHourRequest % 3600) / 60);
                $seconds = $avgHourRequest % 60;
            @endphp
            @if($avgHourRequest != 0)
                <td class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
            @else
                <td class="text-end">00:00:00</td>
            @endif

            @php
                $avgHourIncident = $agent->avg_kendala;
                $hours = floor($avgHourIncident / 3600);
                $minutes = floor(($avgHourIncident % 3600) / 60);
                $seconds = $avgHourIncident % 60;
            @endphp
            @if($avgHourIncident != 0)
                <td class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
            @else
                <td class="text-end">00:00:00</td>
            @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>