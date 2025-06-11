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
    
            @foreach($subDivisiReports as $subDivisiReport)
                <!-- Row untuk nama sub_divisi -->
                <tr>
                    <td class="text-center">{{ $nomorUrut++ }}.</td>
                    <td>{{ $subDivisiReport->sub_divisi ?? 'No Sub Division' }}</td>
                    @php
                        $avgPending = $subDivisiReport->avg_permintaan;
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
                        $avgFinish = $subDivisiReport->avg_kendala;
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
                    {{-- <td class="text-end">{{ $subDivisiReport->avg_pending }}</td>
                    <td class="text-end">{{ $subDivisiReport->avg_finish }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>