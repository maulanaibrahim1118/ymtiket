@if($ticket->status == 'created')
    <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'onprocess')
    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'standby')
    @can('isClient')
    <td><span class="badge bg-warning">onprocess</span></td>
    @endcan
    @can('isAgent')
    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
    @endcan
    @can('isServiceDesk')
    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
    @endcan
@elseif($ticket->status == 'pending')
    <td><span class="badge bg-danger">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'resolved')
    <td><span class="badge bg-primary">{{ $ticket->status }}</span></td>
@elseif($ticket->status == 'finished')
    <td><span class="badge bg-success">{{ $ticket->status }}</span></td>
@else
    <td><span class="badge bg-danger">{{ $ticket->status }}</span></td>
@endif