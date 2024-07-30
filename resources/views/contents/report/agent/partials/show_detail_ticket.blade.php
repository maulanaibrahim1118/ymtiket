<div class="table-responsive">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">CREATED AT</th>
                <th scope="col">TICKET NUMBER</th>
                <th scope="col">CLIENT</th>
                <th scope="col">SUBJECT</th>
                <th scope="col">DETAILS</th>
                <th scope="col">AGENT</th>
                <th scope="col">STATUS</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($tickets as $ticket)
            <tr>
            <td>{{ date('d-M-Y H:i', strtotime($ticket->created_at)) }}</td>
            <td>{{ $ticket->ticket->no_ticket }}</td>
            <td>
                @if($ticket->ticket->location->wilayah_id == 1 || $ticket->ticket->location->wilayah_id == 2)
                {{ $ticket->ticket->user->nama }} - {{ $ticket->ticket->location->nama_lokasi }}
                @else
                    {{ $ticket->ticket->location->site }} - {{ $ticket->ticket->location->nama_lokasi }}
                @endif
            </td>
            <td>{{ $ticket->ticket->kendala }}</td>
            <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->ticket->detail_kendala }}</td>

            @if($ticket->agent->nama_agent == auth()->user()->nama)
                <td><span class="badge bg-info">me</span></td>
            @else
                <td>{{ $ticket->agent->nama_agent }}</td>
            @endif

            @if($ticket->ticket->status == 'created')
                <td><span class="badge bg-secondary">{{ $ticket->ticket->status }}</span></td>
            @elseif($ticket->ticket->status == 'onprocess')
                <td><span class="badge bg-warning">{{ $ticket->ticket->status }}</span></td>
            @elseif($ticket->ticket->status == 'pending')
                <td><span class="badge bg-danger">{{ $ticket->ticket->status }}</span></td>
            @elseif($ticket->ticket->status == 'resolved')
                <td><span class="badge bg-primary">{{ $ticket->ticket->status }}</span></td>
            @elseif($ticket->ticket->status == 'finished')
                <td><span class="badge bg-success">{{ $ticket->ticket->status }}</span></td>
            @else
                <td><span class="badge bg-danger">{{ $ticket->ticket->status }}</span></td>
            @endif
            
            <td>
                <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->ticket->id)]) }}">
                    <i class="bi bi-file-text me-1"></i>Detail
                </a>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>