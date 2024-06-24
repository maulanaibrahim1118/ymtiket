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
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ date('d-M-Y H:i', strtotime($ticket->created_at)) }}</td>
                <td>{{ $ticket->no_ticket }}</td>
                <td>{{ $ticket->user->nama }}</td>
                <td>{{ $ticket->kendala }}</td>
                <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>
                <td>{{ $ticket->agent->nama_agent }}</td>

                {{-- Kolom Keterangan --}}
                @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
                    <td><span class="badge bg-secondary">waiting for approval</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "approved")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @else
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @endif

                {{-- Kolom Status --}}
                @include('contents.ticket.partials.status_column')

                {{-- Kolom Aksi --}}
                <td class="dropdown">
                    <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                    <ul class="dropdown-menu">

                        {{-- Tombol Detail --}}
                        <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                    
                        @if($ticket->status == "created") {{-- Jika status created, ticket masih bisa di hapus dan di edit --}}
                            @if($ticket->created_by == auth()->user()->nama) {{-- Jika ticket dibuat oleh client sendiri --}}
                                {{-- Tombol Edit --}}
                                <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('ticket.edit', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()"><i class="bi bi-pencil-square text-warning"></i>
                                    Edit</a>
                                </li>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('ticket.delete', ['id' => encrypt($ticket->id)]) }}" method="POST">
                                @method('put')
                                @csrf
                                <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Delete</button></li>
                                </form>
                            @else {{-- Jika ticket dibuatkan oleh service desk --}}
                            @endif
                        @else {{-- Jika status selain created, tombol hapus dan edit di hilangkan --}}
                        @endif
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>