<td class="text-nowrap">
@if($agentId == $ticket->agent_id)
    {{-- Jika ticket di assign dan belum di tangani oleh service desk --}}
    @if($ticket->status == "created")
        {{-- Tombol Tangani --}}
        <form action="{{ route('ticket.process1', ['id' => encrypt($ticket->id)]) }}" method="post">
        @method('put')
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Process</button>
        </form>

    @elseif($ticket->status == "pending") {{-- Jika status pending --}}
        @if($ticket->need_approval == "ya")
            @if($ticket->approved == NULL || $ticket->approved == "rejected")
                {{-- Tombol Detail --}}
                <a class="btn btn-sm btn-outline-secondary text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
            @else
                @if($ticket->updated_by != auth()->user()->nama)
                    {{-- Tombol Tangani Setelah Approved --}}
                    <li>
                    <form action="{{ route('ticket.process3', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                    @method('put')
                    @csrf
                    <input type="text" name="agent_id" value="{{ encrypt($ticket->agent_id) }}" hidden>
                    <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Process</button>
                    </form>
                    </li>
                @else
                    {{-- Tombol Proses Ulang / Jika di pending oleh agent sendiri --}}
                    <li>
                    <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                    @method('put')
                    @csrf
                    <a href="#">
                    <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Re-Process</button>
                    </a>
                    </form>
                    </li>
                @endif
            @endif
        @else
            {{-- Jika ticket di assign dan sudah pernah di tangani oleh service desk --}}
            @if($ticket->assigned == "ya" AND $ticket->agent->nik == auth()->user()->nik)
                {{-- Tombol Tangani --}}
                <form action="{{ route('ticket.process2', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                @method('put')
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Process</button>
                </form>

            {{-- Jika ticket di pending oleh agent sendiri --}}
            @elseif($ticket->assigned == "tidak")
                {{-- Tombol Proses Ulang --}}
                <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                @method('put')
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Re-Process</button>
                </form>
            @else
                {{-- Tombol Detail --}}
                <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
            @endif
        @endif
    @elseif($ticket->status == "standby")
        <form action="{{ route('ticket.reProcess3', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
            @method('put')
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Re-Process</button>
        </form>
    {{-- Jika status ticket onprocess --}}
    @elseif($ticket->status == "onprocess") {{-- Jika status onprocess dan belum ada detail ticket --}}
    {{-- Tombol Tangani Kembali --}}
    <a class="btn btn-sm btn-outline-primary text-capitalize" href="{{ route('ticket.reProcess2', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Re-Process</a>
    @elseif($ticket->status == "assigned") {{-- Jika status onprocess dan belum ada detail ticket --}}
    {{-- Tombol Detail --}}
    <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
    @else
        {{-- Tombol Detail --}}
        <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
    @endif
@else
    {{-- Tombol Detail --}}
    <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
@endif
</td>