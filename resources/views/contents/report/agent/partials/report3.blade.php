<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light text-center" style="height: 45px;font-size:14px;">
            <tr class="align-middle">
            <th rowspan="2">NO</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">NAMA AGENT</th>
            <th rowspan="2">SUB DIVISI</th>
            <th colspan="2">RATA-RATA</th>
            </tr>
            <tr>
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
            <td>{{ $nomorUrut++ }}</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            <td class="text-end">{{ $agent->ticket_per_day }}</td>

            @php
                $hourPerDay = \Carbon\Carbon::parse($agent->hour_per_day);
                // $totalHour  = \Carbon\Carbon::parse($total[6]);
            @endphp

            @if($agent->hour_per_day >= 3600)
            <td class="text-end">{{ $hourPerDay->hour }} Jam {{ $hourPerDay->minute }} Menit {{ $hourPerDay->second }} Detik</td>
            @elseif($agent->hour_per_day >= 60)
            <td class="text-end">{{ $hourPerDay->minute }} Menit {{ $hourPerDay->second }} Detik</td>
            @elseif($agent->hour_per_day == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $hourPerDay->second }} Detik</td>
            @endif
            </tr>
            @endforeach
            {{-- <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                <th>{{ $total[5] }}</th>

                @if($total[6] >= 3600)
                <th>{{ $totalHour->hour }} Jam {{ $totalHour->minute }} Menit {{ $totalHour->second }} Detik</th>
                @elseif($total[6] >= 60)
                <th>{{ $totalHour->minute }} Menit {{ $totalHour->second }} Detik</th>
                @elseif($total[6] == 0)
                <th>0 Detik</th>
                @else
                <th>{{ $totalHour->second }} Detik</th>
                @endif
            </tr> --}}
        </tbody>
    </table>
</div>