<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Created At.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter != "Semua")
            <tr>
            <td colspan="7" class="font-monospace ps-3">Date : {{ $pathFilter }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
            <th rowspan="2">#</th>
            <th rowspan="2">EMPLOYEE NUMBER</th>
            <th rowspan="2">AGENT NAME</th>
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
            @foreach($agents as $agent)
            <tr>
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            <td>{{ $agent->nik }}</td>
            <td>{{ $agent->nama_agent }}</td>
            <td>{{ $agent->sub_divisi }}</td>
            <td class="text-end">
                <a href="{{ route('reportAgent.showTicket', ['agent_id' => encrypt($agent->id), 'status' => 'pending', 'start_date' => $filterArray[0], 'end_date' => $filterArray[1]]) }}" target="_blank">
                    {{ $agent->ticket_pending }}
                </a>
            </td>
            <td class="text-end">
                <a href="{{ route('reportAgent.showTicket', ['agent_id' => encrypt($agent->id), 'status' => 'onprocess', 'start_date' => $filterArray[0], 'end_date' => $filterArray[1]]) }}" target="_blank">
                    {{ $agent->ticket_onprocess }}
                </a>
            </td>
            <td class="text-end">
                <a href="{{ route('reportAgent.showTicket', ['agent_id' => encrypt($agent->id), 'status' => 'finished', 'start_date' => $filterArray[0], 'end_date' => $filterArray[1]]) }}" target="_blank">
                    {{ $agent->ticket_finish }}
                </a>
            </td>
            <td class="text-end">
                <a href="{{ route('reportAgent.showDetailTicket', ['agent_id' => encrypt($agent->id), 'type' => 'assigned', 'start_date' => $filterArray[0], 'end_date' => $filterArray[1]]) }}" target="_blank">
                    {{ $agent->ticket_assigned }}
                </a>
            </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-light text-end">
                <th class="text-center" colspan="4">TOTAL</th>
                <th>{{ $total[0] }}</th>
                <th>{{ $total[1] }}</th>
                <th>{{ $total[2] }}</th>
                <th>{{ $total[5] }}</th>
            </tr>
        </tfoot>
    </table>
</div>