<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light text-center" style="height: 45px;font-size:14px;">
            <tr class="align-middle">
            <th rowspan="2">NO</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">NAMA AGENT</th>
            <th rowspan="2">SUB DIVISI</th>
            <th colspan="2">JENIS TICKET</th>
            </tr>
            <tr>
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
            <td>{{ $nomorUrut++ }}</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            @php
                $avgHourRequest = \Carbon\Carbon::parse($agent->permintaan);
                $avgHourIncident = \Carbon\Carbon::parse($agent->kendala);
            @endphp

            @if($agent->permintaan >= 3600)
            <td class="text-end">{{ $avgHourRequest->hour }} Jam {{ $avgHourRequest->minute }} Menit {{ $avgHourRequest->second }} Detik</td>
            @elseif($agent->permintaan >= 60)
            <td class="text-end">{{ $avgHourRequest->minute }} Menit {{ $avgHourRequest->second }} Detik</td>
            @elseif($agent->permintaan == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $avgHourRequest->second }} Detik</td>
            @endif

            @if($agent->kendala >= 3600)
            <td class="text-end">{{ $avgHourIncident->hour }} Jam {{ $avgHourIncident->minute }} Menit {{ $avgHourIncident->second }} Detik</td>
            @elseif($agent->kendala >= 60)
            <td class="text-end">{{ $avgHourIncident->minute }} Menit {{ $avgHourIncident->second }} Detik</td>
            @elseif($agent->kendala == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $avgHourIncident->second }} Detik</td>
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