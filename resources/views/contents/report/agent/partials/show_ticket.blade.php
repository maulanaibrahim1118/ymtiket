<div class="table-responsive">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">CREATED AT</th>
                <th scope="col">PROCESS AT</th>
                <th scope="col">TICKET NUMBER</th>
                <th scope="col">CLIENT</th>
                <th scope="col">SUBJECT</th>
                <th scope="col">DETAILS</th>
                <th scope="col">AGENT</th>
                <th scope="col">STATUS</th>
                <th scope="col">PROCESSED TIME</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($tickets as $ticket)
            <tr>
            <td>{{ date('d-M-Y H:i', strtotime($ticket->created_at)) }}</td>
            <td>{{ optional($ticket->ticket_detail)->process_at ? date('d-M-Y H:i', strtotime($ticket->ticket_detail->process_at)) : '-' }}</td>
            <td>{{ $ticket->no_ticket }}</td>
            <td>
                @if($ticket->location->wilayah_id == 1 || $ticket->location->wilayah_id == 2)
                {{ $ticket->user->nama }} - {{ $ticket->location->nama_lokasi }}
                @else
                    {{ $ticket->location->site }} - {{ $ticket->location->nama_lokasi }}
                @endif
            </td>
            <td>{{ $ticket->kendala }}</td>
            <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

            @if($ticket->agent->nama_agent == auth()->user()->nama)
                <td><span class="badge bg-info">me</span></td>
            @else
                <td>{{ $ticket->agent->nama_agent }}</td>
            @endif

            {{-- Kolom Status --}}
            @include('contents.ticket.partials.status_column')

            @php
                $totalSeconds = optional($ticket->ticket_detail)->processed_time ?? 0;
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
            @endphp
            @if($totalSeconds != 0)
                <td>
                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                </td>
            @else
                <td>00:00:00</td>
            @endif
            
            <td>
                <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}">
                    <i class="bi bi-file-text me-1"></i>Detail
                </a>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>