<div class="table-responsive">
    <table class="table table-bordered">
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
            {{-- @php
                $nomorUrut = 1;
            @endphp
            @foreach($data1 as $data)
            <tr>
            <td>{{ $nomorUrut++ }}</td>
            <td>{{ $data->nik }}</td>
            <td>{{ $data->nama_agent }}</td>
            <td>{{ $data->sub_divisi }}</td>
            <td></td>
            @php
                $average1   = \Carbon\Carbon::parse($data->avg_pending);
            @endphp

            @if( $data->avg_pending >= 3600)
            <td class="text-end">{{ $average1->hour }} Jam {{ $average1->minute }} Menit {{ $average1->second }} Detik</td>
            @elseif( $data->avg_pending >= 60 )
            <td class="text-end">{{ $average1->minute }} Menit {{ $average1->second }} Detik</td>
            @elseif( $data->avg_pending == 0)
            <td class="text-end">0 Detik</td>
            @else
            <td class="text-end">{{ $average1->second }} Detik</td>
            @endif
            </tr>
            @endforeach

            <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                <th class="text-center"></th>
                @php
                    $avgTotal1  = \Carbon\Carbon::parse($total1[3]);
                @endphp
                @if( $total1[0] >= 3600)
                <td>{{ $avgTotal1->hour }} Jam {{ $avgTotal1->minute }} Menit {{ $avgTotal1->second }} Detik</td>
                @elseif( $total1[3] >= 60)
                <td>{{ $avgTotal1->minute }} Menit {{ $avgTotal1->second }} Detik</td>
                @elseif( $total1[3] == 0)
                <td>0 Detik</td>
                @else
                <td>{{ $avgTotal1->second }} Detik</td>
                @endif
            </tr> --}}
        </tbody>
    </table>
</div>