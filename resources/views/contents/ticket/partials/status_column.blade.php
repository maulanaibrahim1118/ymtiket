@if($ticket->status == 'created')
    <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'onprocess')
    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'pending')
    <td><span class="badge bg-danger">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'resolved')
    <td><span class="badge bg-primary">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'finished')
    <td><span class="badge bg-success">{{ $ticket->status }}</span></td>
@else
    <td><span class="badge bg-danger">{{ $ticket->status }}</span></td>
@endif