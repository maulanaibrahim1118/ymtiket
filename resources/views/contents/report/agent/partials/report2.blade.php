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

            @php
                $avgPending = $agent->avg_pending;
                $hours = floor($avgPending / 3600);
                $minutes = floor(($avgPending % 3600) / 60);
                $seconds = $avgPending % 60;
                @endphp
                @if($avgPending != 0)
                <td class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
                @else
                <td class="text-end">00:00:00</td>
            @endif

            @php
                $avgFinish = $agent->avg_finish;
                $hours = floor($avgFinish / 3600);
                $minutes = floor(($avgFinish % 3600) / 60);
                $seconds = $avgFinish % 60;
            @endphp
            @if($avgFinish != 0)
                <td class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
            @else
                <td class="text-end">00:00:00</td>
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                @php
                $totalAvgPending = $total[3];
                $hours = floor($totalAvgPending / 3600);
                $minutes = floor(($totalAvgPending % 3600) / 60);
                $seconds = $totalAvgPending % 60;
                @endphp
                @if($totalAvgPending != 0)
                <th class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </th>
                @else
                <th class="text-end">00:00:00</th>
                @endif

                @php
                $totalAvgFinish = $total[4];
                $hours = floor($totalAvgFinish / 3600);
                $minutes = floor(($totalAvgFinish % 3600) / 60);
                $seconds = $totalAvgFinish % 60;
                @endphp
                @if($totalAvgFinish != 0)
                <th class="text-end">
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </th>
                @else
                <th class="text-end">00:00:00</th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>