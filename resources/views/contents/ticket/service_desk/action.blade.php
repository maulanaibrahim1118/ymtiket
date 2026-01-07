@can('isActor')
{{-- Kolom Aksi --}}
<td class="dropdown">
    <a class="action-icon pe-2" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
    <ul class="dropdown-menu" style="font-size:16px;">
        @if($agentId == $ticket->agent_id)
            {{-- ========== Jika status ticket created ========== --}}
            @if($ticket->status == "created" AND $ticket->agent->nik == auth()->user()->nik)
                {{-- Tombol Detail --}}
                <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Details</a></li>

                {{-- Tombol Tangani --}}
                <li>
                    <form action="{{ route('ticket.process1', ['id' => encrypt($ticket->id)]) }}" method="post">
                    @method('put')
                    @csrf
                    <a href="#">
                    <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Process</button>
                    </a>
                    </form>
                </li>
        
                {{-- Tombol Antrikan --}}
                @if($ticket->is_queue == "tidak")
                    @if(in_array(auth()->user()->location_id, $haveSubDivs))
                    <li><button class="dropdown-item text-capitalize text-success" id="antrikanButton" data-bs-toggle="modal" data-bs-target="#antrikanModal" name="{{ encrypt($ticket->id) }}" value="{{ $ticket->location->wilayah_id }}" onclick="tampilkanData1(this)"><i class="bi bi-list-check text-success"></i>Queue</button></li>
                    @else
                    <li>
                        <form action="{{ route('ticket.queue', ['id' => encrypt($ticket->id)]) }}" method="post">
                        @method('put')
                        @csrf
                        <input type="text" name="sub_divisi" value="none" hidden>
                        <a href="#">
                        <button type="submit" class="dropdown-item text-capitalize text-success"><i class="bi bi-list-check text-success"></i>Queue</button>
                        </a>
                        </form>
                    </li>
                    @endif
                @else
                @endif
        
                {{-- Tombol Assign --}}
                <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ encrypt($ticket->id) }}" value="{{ $ticket->location->wilayah_id }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>
        
                {{-- ========== Jika ticket dibuat oleh service desk ========== --}}
                @if($ticket->created_by == auth()->user()->nama)
        
                    {{-- Tombol Edit --}}
                    <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('ticket.edit', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()">
                        <i class="bi bi-pencil-square text-warning"></i>Edit
                    </a></li>
        
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('ticket.delete', ['id' => encrypt($ticket->id)]) }}" method="POST">
                    @method('put')
                    @csrf
                    <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Delete</button></li>
                    </form>
                @endif
        
            {{-- ========== Jika status ticket pending ========== --}}
            @elseif($ticket->status == "pending" AND $ticket->agent->nik == auth()->user()->nik)
                {{-- Tombol Detail --}}
                <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Details</a></li>

                @if($ticket->need_approval == "ya")
                    @if($ticket->approved == NULL || $ticket->approved == "rejected")
                    @else
                        @if($ticket->updated_by != auth()->user()->nama)
                            {{-- Tombol Assign --}}
                            <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ encrypt($ticket->id) }}" value="{{ $ticket->location->wilayah_id }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-dark"></i>Assign</button></li>
                        @else
        
                            {{-- Tombol Proses Ulang / Jika di pending oleh agent sendiri --}}
                            <li>
                                <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                                @method('put')
                                @csrf
                                <a href="#">
                                <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bx bx-analyse text-primary"></i>Re-Process</button>
                                </a>
                                </form>
                            </li>
                        @endif
                    @endif
                @else
                    @if($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "tidak")
                        @if($ticket->is_queue == "tidak")
                            {{-- Tombol Proses Ulang --}}
                            <li>
                                <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                                @method('put')
                                @csrf
                                <a href="#">
                                <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bx bx-analyse text-primary"></i>Re-Process</button>
                                </a>
                                </form>
                            </li>
                        @else
                            {{-- Tombol Tangani --}}
                            <li>
                                <form action="{{ route('ticket.process2', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                                @method('put')
                                @csrf
                                <a href="#">
                                <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bx bx-analyse text-primary"></i>Process</button>
                                </a>
                                </form>
                            </li>

                            {{-- Tombol Assign --}}
                            <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ encrypt($ticket->id) }}" value="{{ $ticket->location->wilayah_id }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>
                        @endif
                        
                    {{-- Jika status ticket pending assign --}}
                    @elseif($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "ya")
                        {{-- Tombol Tangani --}}
                        <li>
                            <form action="{{ route('ticket.process2', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                            @method('put')
                            @csrf
                            <a href="#">
                            <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bx bx-analyse text-primary"></i>Process</button>
                            </a>
                            </form>
                        </li>

                        {{-- Tombol Assign --}}
                        <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ encrypt($ticket->id) }}" value="{{ $ticket->location->wilayah_id }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>
                    @endif
                @endif
        
            @elseif($ticket->status == "standby" AND $ticket->agent->nik == auth()->user()->nik)
                <form action="{{ route('ticket.reProcess3', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                    @method('put')
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize"><i class="bx bx-analyse me-1"></i>Re-Process</button>
                </form>
            {{-- ========== Jika status ticket onprocess ========== --}}
            @elseif($ticket->status == "onprocess" AND $ticket->agent->nik == auth()->user()->nik) {{-- Jika status onprocess dan belum ada detail ticket --}}
                {{-- Tombol Tangani Kembali --}}
                <li><a class="dropdown-item text-capitalize text-primary" href="{{ route('ticket.reProcess2', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Re-Process</a></li>
        
            @else
                {{-- Tombol Detail --}}
                <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Details</a></li>

            @endif
        @else
            {{-- Tombol Detail --}}
            <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Details</a></li>

            @if($ticket->status == "created" || $ticket->status == "onprocess" || $ticket->status == "pending")
                {{-- Jika bukan chief --}}
                @if(auth()->user()->position_id != 2)
                    {{-- Tombol Tarik Ticket --}}
                    <li>
                        <form action="{{ route('ticket.pull', ['id' => encrypt($ticket->id)]) }}" method="post" onsubmit="return reloadAction();">
                            @method('put')
                            @csrf
                            <a href="#">
                            <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-sign-turn-left text-primary"></i>Pull</button>
                            </a>
                        </form>
                    </li>
                @endif
            @endif
        @endif
    </ul>
</td>
@endcan