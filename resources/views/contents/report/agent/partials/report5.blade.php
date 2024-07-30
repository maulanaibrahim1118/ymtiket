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
            <th rowspan="2">EMPLOYEE NUMBER</th>
            <th rowspan="2">AGENT NAME</th>
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
            @foreach($agents as $agent)
            <tr>
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            <td>
                <a href="{{ route('reportAgent.showDetailTicket', ['agent_id' => encrypt($agent->id), 'type' => 'permintaan', 'start_date' => $filterArray[0], 'end_date', $filterArray[1]]) }}" target="_blank">
                    {{ $agent->permintaan }}
                </a>
            </td>
            <td>
                <a href="{{ route('reportAgent.showDetailTicket', ['agent_id' => encrypt($agent->id), 'type' => 'kendala', 'start_date' => $filterArray[0], 'end_date', $filterArray[1]]) }}" target="_blank">
                    {{ $agent->kendala }}
                </a>
            </td>
            @endforeach
            {{-- <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                <th>{{ $total[7] }}</th>
                <th>{{ $total[8] }}</th>
            </tr> --}}
        </tbody>
    </table>
</div>