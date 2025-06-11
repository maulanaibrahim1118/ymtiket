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
            <tr>
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            <td>{{ $subDivisiReport->sub_divisi }}</td>
            <td>{{ $subDivisiReport->permintaan }}</td>
            <td>{{ $subDivisiReport->kendala }}</td>
            @endforeach
        </tbody>
    </table>
</div>