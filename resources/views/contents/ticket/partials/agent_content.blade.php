<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">DIBUAT PADA</th>
                <th scope="col">NO. TICKET</th>
                <th scope="col">KENDALA</th>
                <th scope="col">DETAIL KENDALA</th>
                <th scope="col">KETERANGAN</th>
                <th scope="col">STATUS</th>
                <th scope="col">AKSI</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ date('d-M-Y H:i', strtotime($ticket->created_at)) }}</td>
                <td>{{ $ticket->no_ticket }}</td>
                <td>{{ $ticket->kendala }}</td>
                <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

                {{-- Kolom Keterangan --}}
                @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
                    <td><span class="badge bg-secondary">menunggu approval</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "approved")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @else
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @endif

                {{-- Kolom Status --}}
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

                {{-- Kolom Aksi --}}
                <td class="dropdown">
                    <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                    <ul class="dropdown-menu">
                        @if($agentId == $ticket->agent_id)
                            {{-- Jika ticket di assign dan belum di tangani oleh service desk --}}
                            @if($ticket->status == "created")
                                {{-- Tombol Tangani --}}
                                <li>
                                <form action="{{ route('ticket.process1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                @method('put')
                                @csrf
                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                <a href="#">
                                <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bx bx-analyse text-primary" onclick="reloadAction()"></i>Tangani</button>
                                </a>
                                </form>
                                </li>

                            @elseif($ticket->status == "pending") {{-- Jika status pending --}}
                                @if($ticket->need_approval == "ya")
                                    @if($ticket->approved == NULL || $ticket->approved == "rejected")
                                        {{-- Tombol Detail --}}
                                        <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                    @else
                                        @if($ticket->updated_by != auth()->user()->nama)
                                            {{-- Tombol Tangani Setelah Approved --}}
                                            <li>
                                            <form action="{{ route('ticket.process3', ['id' => encrypt($ticket->id)]) }}" method="post">
                                            @method('put')
                                            @csrf
                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                            <input type="text" name="url" value="/ticket-details/{{ encrypt($ticket->id) }}" hidden>
                                            <input type="text" name="agent_id" value="{{ $ticket->agent_id }}" hidden>
                                            <a href="#">
                                            <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</button>
                                            </a>
                                            </form>
                                            </li>
                                        @else
                                            {{-- Tombol Proses Ulang / Jika di pending oleh agent sendiri --}}
                                            <li>
                                            <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                            @method('put')
                                            @csrf
                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                            <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                            <a href="#">
                                            <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</button>
                                            </a>
                                            </form>
                                            </li>
                                        @endif
                                    @endif
                                @else
                                    {{-- Jika ticket di assign dan sudah pernah di tangani oleh service desk --}}
                                    @if($ticket->assigned == "ya" AND $ticket->agent->nama_agent == auth()->user()->nama)
                                        {{-- Tombol Tangani --}}
                                        <li>
                                        <form action="{{ route('ticket.process2', ['id' => encrypt($ticket->id)]) }}" method="post">
                                        @method('put')
                                        @csrf
                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                        <a href="#">
                                        <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</button>
                                        </a>
                                        </form>
                                        </li>

                                    {{-- Jika ticket di pending oleh agent sendiri --}}
                                    @elseif($ticket->assigned == "tidak")
                                        {{-- Tombol Proses Ulang --}}
                                        <li>
                                        <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                        @method('put')
                                        @csrf
                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                        <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                        <a href="#">
                                        <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</button>
                                        </a>
                                        </form>
                                        </li>

                                    @else
                                        {{-- Tombol Detail --}}
                                        <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                    @endif
                                @endif

                            {{-- Jika status ticket onprocess --}}
                            @elseif($ticket->status == "onprocess") {{-- Jika status onprocess dan belum ada detail ticket --}}
                            {{-- Tombol Tangani Kembali --}}
                            <li><a class="dropdown-item text-capitalize text-primary" href="{{ route('ticket.reProcess2', ['id' => encrypt($ticket->id)]) }}"><i class="bx bx-analyse text-primary" onclick="reloadAction()"></i>Tangani</a></li>
                            @elseif($ticket->status == "assigned") {{-- Jika status onprocess dan belum ada detail ticket --}}
                            {{-- Tombol Detail --}}
                            <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                            @else
                                {{-- Tombol Detail --}}
                                <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                            @endif
                        @else
                            {{-- Tombol Detail --}}
                            <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                        @endif
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>