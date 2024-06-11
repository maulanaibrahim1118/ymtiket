@if($ticket->status == "onprocess" AND $ticket->agent->nik == auth()->user()->nik)
    {{-- Tombol Selesai --}}
    <form action="{{ route('ticket.resolved', ['id' => encrypt($ticket->id)]) }}" method="POST">
    @method('put')
    @csrf
    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
    <input type="text" name="agent_id" value="{{ $ticket->agent_id }}" hidden>
    <button type="submit" class="btn btn-sm btn-primary float-end ms-1"><i class="bi bi-check-circle me-1"></i> Resolve</button>
    </form>

    {{-- Tombol Pending --}}
    <button type="button" class="btn btn-sm btn-danger float-end ms-1" id="pendingButton" data-bs-toggle="modal" data-bs-target="#pendingModal"><i class="bi bi-stop-circle me-1"></i> Pending</button>

    {{-- Tombol Edit --}}
    <a href="{{ route('ticket-detail.edit', ['id' => encrypt($ticket->id)]) }}"><button type="button" class="btn btn-sm btn-warning float-end ms-1"><i class="bi bi-pencil-square me-1"></i> Edit</button></a>

    @can('isServiceDesk')
    {{-- Tombol Antrikan --}}
    @if(in_array(auth()->user()->location_id, $haveSubDivs))
        <button type="button" class="btn btn-sm btn-success float-end ms-1" id="antrikanButton" data-bs-toggle="modal" data-bs-target="#antrikanModal"><i class="bi bi-list-check me-1"></i> Queue</button>
    @else
        <form action="{{ route('ticket.queue', ['id' => $ticket->id]) }}" method="post">
        @method('put')
        @csrf
        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
        <input type="text" name="sub_divisi" value="none" hidden>
        <a href="#">
        <button type="submit" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-list-check me-1"></i> Queue</button>
        </a>
        </form>
    @endif
    @endcan
    
    {{-- Tombol Assign --}}
    <button type="button" class="btn btn-sm btn-outline-dark float-end ms-1" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal"><i class="bx bx-share me-1"></i> Assign</button>
    
@elseif($ticket->status == "resolved")
    @can('isServiceDesk')
        {{-- Tombol Close --}}
        <button type="button" class="btn btn-sm btn-success float-end ms-1" id="closedButton" data-bs-toggle="modal" data-bs-target="#closedModal"><i class="bi bi-check-circle me-1"></i> Close</button>
    @endcan
@else
@endif