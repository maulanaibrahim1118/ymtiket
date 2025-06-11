<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Created At.</p>

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
                <th colspan="4">TICKET STATUS</th>
            </tr>
            <tr class="text-center align-middle">
                <th>PENDING</th>
                <th>ONPROCESS</th>
                <th>RESOLVED</th>
                <th>PARTICIPANT</th>
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
                    <td class="text-end">{{ $subDivisiReport->ticket_pending }}</td>
                    <td class="text-end">{{ $subDivisiReport->ticket_onprocess }}</td>
                    <td class="text-end">{{ $subDivisiReport->ticket_finish }}</td>
                    <td class="text-end">{{ $subDivisiReport->ticket_assigned }}</td>
                </tr>
            @endforeach
        </tbody>
    
        <tfoot>
            <!-- Row total untuk semua sub_divisi -->
            <tr class="bg-light text-end">
                <th colspan="2" class="text-center">TOTAL</th>
                <th>{{ $total[0] }}</th>
                <th>{{ $total[1] }}</th>
                <th>{{ $total[2] }}</th>
                <th>{{ $total[5] }}</th>
            </tr>
        </tfoot>
    </table>
</div>