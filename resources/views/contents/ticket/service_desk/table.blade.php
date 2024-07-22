<style>
    .table thead th {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
    .table tbody td {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
</style>
<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">CREATED AT</th>
                <th scope="col">TICKET NUMBER</th>
                <th scope="col">CLIENT</th>
                <th scope="col">SUBJECT</th>
                <th scope="col">DETAILS</th>
                <th scope="col">AGENT</th>
                <th scope="col">NOTE</th>
                <th scope="col">STATUS</th>
                @can('isActor')
                <th scope="col">ACTION</th>
                @endcan
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($tickets as $ticket)
            @if($ticket->status == "resolved" || $ticket->status == "finished")
            <tr class="bg-light">
            @else
            <tr>
            @endif
                <td>{{ date('d-M-Y H:i', strtotime($ticket->created_at)) }}</td>
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

                {{-- Kolom PIC --}}
                @if($ticket->agent->nama_agent == auth()->user()->nama)
                    <td><span class="badge bg-info">me</span></td>
                @else
                    <td>{{ $ticket->agent->nama_agent }}</td>
                @endif

                {{-- Kolom Keterangan --}}
                @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
                    <td><span class="badge bg-secondary">waiting for approval</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "approved")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "rejected")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                    
                @elseif($ticket->assigned == "ya" AND $ticket->status == "created" OR $ticket->assigned == "ya" AND $ticket->status == "pending")
                    <td><span class="badge bg-dark">direct assign</span></td>
                @else
                    @if($ticket->is_queue == "ya" AND $ticket->status == "created" OR $ticket->is_queue == "ya" AND $ticket->status == "pending")
                        <td>
                            <span class="badge bg-dark">
                                @if(in_array(auth()->user()->location_id, $haveSubDivs))
                                In Queue | {{ $ticket->sub_divisi_agent }}
                                @else
                                In Queue
                                @endif
                            </span>
                        </td>
                    @elseif($ticket->is_queue == "tidak" AND $ticket->status == "created")
                        @if($ticket->role == 1)
                            <td><span class="badge bg-secondary">Not In Queue</span></td>
                        @else
                            <td></td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endif

                {{-- Kolom Status --}}
                @include('contents.ticket.partials.status_column')

                @can('isActor')
                {{-- Kolom Aksi --}}
                <td class="dropdown">
                    <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                    <ul class="dropdown-menu">
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
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('contents.ticket.partials.modal_action')

<script src="{{ asset('dist/js/refresh-page-interval.js') }}"></script>