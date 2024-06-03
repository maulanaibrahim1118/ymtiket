<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">DIBUAT PADA</th>
                <th scope="col">NO. TICKET</th>
                <th scope="col">CLIENT</th>
                <th scope="col">KENDALA</th>
                <th scope="col">DETAIL KENDALA</th>
                <th scope="col">AGENT</th>
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
                <td>{{ $ticket->user->nama }}</td>
                <td>{{ $ticket->kendala }}</td>
                <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>
                <td>{{ $ticket->agent->nama_agent }}</td>

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
                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
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