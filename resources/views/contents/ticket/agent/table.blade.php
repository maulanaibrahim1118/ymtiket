<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">CREATED AT</th>
                <th scope="col">TICKET NUMBER</th>
                <th scope="col">SUBJECT</th>
                <th scope="col">DETAILS</th>
                <th scope="col">NOTE</th>
                <th scope="col">STATUS</th>
                <th scope="col">ACTION</th>
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
                <td>{{ $ticket->kendala }}</td>
                <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

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
                    <td></td>
                @endif

                {{-- Kolom Status --}}
                @include('contents.ticket.partials.status_column')

                {{-- Kolom Aksi --}}
                <td>
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
                                    <form action="{{ route('ticket.process3', ['id' => encrypt($ticket->id)]) }}" method="post">
                                    @method('put')
                                    @csrf
                                    <input type="text" name="agent_id" value="{{ encrypt($ticket->agent_id) }}" hidden>
                                    <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Process</button>
                                    </form>
                                    </li>
                                @else
                                    {{-- Tombol Proses Ulang / Jika di pending oleh agent sendiri --}}
                                    <li>
                                    <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                    @method('put')
                                    @csrf
                                    <a href="#">
                                    <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Re-Process</button>
                                    </a>
                                    </form>
                                    </li>
                                @endif
                            @endif
                        @else
                            {{-- Jika ticket di assign dan sudah pernah di tangani oleh service desk --}}
                            @if($ticket->assigned == "ya" AND $ticket->agent->nik == auth()->user()->nik)
                                {{-- Tombol Tangani --}}
                                <form action="{{ route('ticket.process2', ['id' => encrypt($ticket->id)]) }}" method="post">
                                @method('put')
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Process</button>
                                </form>

                            {{-- Jika ticket di pending oleh agent sendiri --}}
                            @elseif($ticket->assigned == "tidak")
                                {{-- Tombol Proses Ulang --}}
                                <form action="{{ route('ticket.reProcess1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                @method('put')
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary text-capitalize" onclick="reloadAction()"><i class="bx bx-analyse me-1"></i>Re-Process</button>
                                </form>

                            @else
                                {{-- Tombol Detail --}}
                                <a class="btn btn-sm btn-outline-secondary text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text me-1"></i>Detail</a>
                            @endif
                        @endif

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
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('dist/js/refresh-page-interval.js') }}"></script>