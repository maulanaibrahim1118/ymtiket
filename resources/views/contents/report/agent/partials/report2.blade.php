<div class="table-responsive mt-2">
    <table class="table table-bordered table-hover">
        <thead class="bg-light text-center" style="height: 45px;font-size:14px;">
            <tr class="align-middle">
            <th rowspan="2">NO</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">NAMA AGENT</th>
            <th rowspan="2">SUB DIVISI</th>
            <th colspan="2">RATA-RATA WAKTU</th>
            </tr>
            <tr>
            <th>PENDING</th>
            <th>RESOLVED</th>
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
                $average1   = \Carbon\Carbon::parse($agent->avg_pending);
                $average2   = \Carbon\Carbon::parse($agent->avg_finish);
            @endphp

            @if($agent->avg_pending >= 3600)
            <td class="text-end">{{ $average1->hour }} Jam {{ $average1->minute }} Menit {{ $average1->second }} Detik</td>
            @elseif($agent->avg_pending >= 60)
            <td class="text-end">{{ $average1->minute }} Menit {{ $average1->second }} Detik</td>
            @elseif($agent->avg_pending == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $average1->second }} Detik</td>
            @endif

            @if($agent->avg_finish >= 3600)
            <td class="text-end">{{ $average2->hour }} Jam {{ $average2->minute }} Menit {{ $average2->second }} Detik</td>
            @elseif($agent->avg_finish >= 60)
            <td class="text-end">{{ $average2->minute }} Menit {{ $average2->second }} Detik</td>
            @elseif($agent->avg_finish == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $average2->second }} Detik</td>
            @endif
            </tr>
            @endforeach
            
            <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                @php
                    $avgTotal1  = \Carbon\Carbon::parse($total[3]);
                    $avgTotal2  = \Carbon\Carbon::parse($total[4]);
                @endphp

                @if($total[3] >= 3600)
                <th>{{ $avgTotal1->hour }} Jam {{ $avgTotal1->minute }} Menit {{ $avgTotal1->second }} Detik</th>
                @elseif($total[3] >= 60)
                <th>{{ $avgTotal1->minute }} Menit {{ $avgTotal1->second }} Detik</th>
                @elseif($total[3] == 0)
                <th>0 Detik</th>
                @else
                <th>{{ $avgTotal1->second }} Detik</th>
                @endif

                @if($total[4] >= 3600)
                <th>{{ $avgTotal2->hour }} Jam {{ $avgTotal2->minute }} Menit {{ $avgTotal2->second }} Detik</th>
                @elseif($total[4] >= 60)
                <th>{{ $avgTotal2->minute }} Menit {{ $avgTotal2->second }} Detik</th>
                @elseif($total[4] == 0)
                <th>0 Detik</th>
                @else
                <th>{{ $avgTotal2->second }} Detik</th>
                @endif
            </tr>
        </tbody>
    </table>
</div>