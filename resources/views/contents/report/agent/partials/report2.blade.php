<div class="table-responsive mt-2">
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
            <th colspan="2">RATA-RATA WAKTU</th>
            </tr>
            <tr class="text-center align-middle">
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
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            @php
                $average1   = \Carbon\Carbon::parse($agent->avg_pending);
                $average2   = \Carbon\Carbon::parse($agent->avg_finish);
            @endphp

            @if($agent->avg_pending >= 3600)
            <td class="text-end">{{ str_pad($average1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_pending >= 60)
            <td class="text-end">{{ str_pad($average1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_pending == 0)
            <td class="text-end">00:00:00</td>
            @else
            <td class="text-end">{{ str_pad($average1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average1->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif

            @if($agent->avg_finish >= 3600)
            <td class="text-end">{{ str_pad($average2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_finish >= 60)
            <td class="text-end">{{ str_pad($average2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->second, 2, "0", STR_PAD_LEFT) }}</td>
            @elseif($agent->avg_finish == 0)
            <td class="text-end">00:00:00</td>
            @else
            <td class="text-end">{{ str_pad($average2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($average2->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                @php
                    $avgTotal1  = \Carbon\Carbon::parse($total[3]);
                    $avgTotal2  = \Carbon\Carbon::parse($total[4]);
                @endphp

                @if($total[3] >= 3600)
                <th class="text-end">{{ str_pad($avgTotal1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->second, 2, "0", STR_PAD_LEFT) }}</th>
                @elseif($total[3] >= 60)
                <th class="text-end">{{ str_pad($avgTotal1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->second, 2, "0", STR_PAD_LEFT) }}</th>
                @elseif($total[3] == 0)
                <th>0 Detik</th>
                @else
                <th class="text-end">{{ str_pad($avgTotal1->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal1->second, 2, "0", STR_PAD_LEFT) }}</th>
                @endif

                @if($total[4] >= 3600)
                <th class="text-end">{{ str_pad($avgTotal2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->second, 2, "0", STR_PAD_LEFT) }}</th>
                @elseif($total[4] >= 60)
                <th class="text-end">{{ str_pad($avgTotal2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->second, 2, "0", STR_PAD_LEFT) }}</th>
                @elseif($total[4] == 0)
                <th>0 Detik</th>
                @else
                <th class="text-end">{{ str_pad($avgTotal2->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal2->second, 2, "0", STR_PAD_LEFT) }}</th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>