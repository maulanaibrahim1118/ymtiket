<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter != "Semua")
            <tr>
            <td colspan="6" class="font-monospace ps-3">Tanggal : {{ $pathFilter }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
            <th rowspan="2">NO</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">NAMA AGENT</th>
            <th rowspan="2">SUB DIVISI</th>
            <th colspan="2">JENIS TICKET</th>
            </tr>
            <tr class="text-center align-middle">
            <th>PERMINTAAN</th>
            <th>KENDALA</th>
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
                $avgHourRequest = \Carbon\Carbon::parse($agent->avg_permintaan);
                $avgHourIncident = \Carbon\Carbon::parse($agent->avg_kendala);
            @endphp

            @if($agent->avg_permintaan >= 3600)
            <td class="text-end">{{ str_pad($avgHourRequest->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_permintaan >= 60)
            <td class="text-end">{{ str_pad($avgHourRequest->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_permintaan == 0)
            <td class="text-end">00:00:00</td>
            @else
            <td class="text-end">{{ str_pad($avgHourRequest->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourRequest->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif

            @if($agent->avg_kendala >= 3600)
            <td class="text-end">{{ str_pad($avgHourIncident->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_kendala >= 60)
            <td class="text-end">{{ str_pad($avgHourIncident->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_kendala == 0)
            <td class="text-end">00:00:00</td>
            @else
            <td class="text-end">{{ str_pad($avgHourIncident->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgHourIncident->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif
            </tr>
            @endforeach
            {{-- <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                <th>{{ $total[7] }}</th>
                <th>{{ $total[8] }}</th>
            </tr> --}}
        </tbody>
    </table>
</div>