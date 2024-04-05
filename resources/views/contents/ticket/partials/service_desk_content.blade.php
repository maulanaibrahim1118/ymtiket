<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">DIBUAT PADA</th>
                <th scope="col">NO. TICKET</th>
                <th scope="col">LOKASI</th>
                <th scope="col">KENDALA</th>
                <th scope="col">DETAIL KENDALA</th>
                <th scope="col">PIC</th>
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

                {{-- Kolom Lokasi --}}
                @if($ticket->location->wilayah == "head office")
                    <td>head office</td>
                @else
                    <td>store</td>
                @endif

                <td>{{ $ticket->kendala }}</td>
                <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

                {{-- Kolom PIC --}}
                @if($ticket->agent->nama_agent == auth()->user()->nama)
                    <td><span class="badge bg-info">saya</span></td>
                @else
                    <td>{{ $ticket->agent->nama_agent }}</td>
                @endif

                {{-- Kolom Keterangan --}}
                @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
                    <td><span class="badge bg-secondary">menunggu approval</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "approved")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                @elseif($ticket->need_approval == "ya" AND $ticket->approved == "rejected")
                    <td><span class="badge bg-dark">{{ $ticket->approved }}</span></td>
                    
                @elseif($ticket->assigned == "ya" AND $ticket->status == "created" OR $ticket->assigned == "ya" AND $ticket->status == "pending")
                    <td><span class="badge bg-dark">direct assign</span></td>
                @else
                    @if($ticket->is_queue == "ya" AND $ticket->status == "created" OR $ticket->status == "pending")
                        <td><span class="badge bg-dark">dalam antrian</span></td>
                    @elseif($ticket->is_queue == "tidak" AND $ticket->status == "created")
                        @if($ticket->role == "service desk")
                            <td><span class="badge bg-secondary">diluar antrian</span></td>
                        @else
                            <td></td>
                        @endif
                    @else
                        <td></td>
                    @endif
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

                        {{-- ========== Jika status ticket created ========== --}}
                        @if($ticket->status == "created" AND $ticket->agent->nik == auth()->user()->nik)
                    
                            {{-- Tombol Tangani --}}
                            <li>
                                <form action="{{ route('ticket.process1', ['id' => encrypt($ticket->id)]) }}" method="post">
                                @method('put')
                                @csrf
                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                <a href="#">
                                <button type="submit" class="dropdown-item text-capitalize text-primary" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</button>
                                </a>
                                </form>
                            </li>
                    
                            {{-- Tombol Antrikan --}}
                            @if($ticket->is_queue == "tidak")
                                @if(auth()->user()->location_id == 10)
                            <li><button class="dropdown-item text-capitalize text-success" id="antrikanButton" data-bs-toggle="modal" data-bs-target="#antrikanModal" name="{{ $ticket->id }}" value="{{ $ticket->ticket_area }}" onclick="tampilkanData1(this)"><i class="bi bi-list-check text-success"></i>Antrikan</button></li>
                                @else
                                <li>
                                    <form action="{{ route('ticket.queue', ['id' => encrypt($ticket->id)]) }}" method="post">
                                    @method('put')
                                    @csrf
                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="sub_divisi" value="none" hidden>
                                    <a href="#">
                                    <button type="submit" class="dropdown-item text-capitalize text-success"><i class="bi bi-list-check text-success"></i>Antrikan</button>
                                    </a>
                                    </form>
                                </li>
                                @endif
                            @else
                            @endif
                    
                            {{-- Tombol Assign --}}
                            <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ $ticket->id }}" value="{{ $ticket->ticket_area }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>
                    
                            {{-- ========== Jika ticket dibuat oleh service desk ========== --}}
                            @if($ticket->user_id == auth()->user()->id)
                    
                                {{-- Tombol Edit --}}
                                <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('ticket.edit', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()">
                                    <i class="bi bi-pencil-square text-warning"></i>Edit
                                </a></li>
                    
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('ticket.delete', ['id' => encrypt($ticket->id)]) }}" method="POST">
                                @method('put')
                                @csrf
                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                </form>
                            @endif
                    
                        {{-- ========== Jika status ticket pending ========== --}}
                        @elseif($ticket->status == "pending" AND $ticket->agent->nik == auth()->user()->nik)
                            @if($ticket->need_approval == "ya")
                                @if($ticket->approved == NULL || $ticket->approved == "rejected")
                    
                                    {{-- Tombol Detail --}}
                                    <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                @else
                                    @if($ticket->updated_by != auth()->user()->nama)
                                        {{-- Tombol Detail --}}
                                        <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                    
                                        {{-- Tombol Assign --}}
                                        <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ $ticket->id }}" value="{{ $ticket->ticket_area }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-dark"></i>Assign</button></li>
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
                                @if($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "tidak")
                                    @if($ticket->is_queue == "tidak")
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
                                        {{-- Tombol Assign --}}
                                        <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ $ticket->id }}" value="{{ $ticket->ticket_area }}" onclick="tampilkanData2(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>
                                        
                                        {{-- Tombol Detail --}}
                                        <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                    @endif
                                {{-- Jika status ticket pending assign --}}
                                @elseif($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "ya")
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
                                @else
                                    {{-- Tombol Detail --}}
                                    <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                @endif
                            @endif
                    
                        {{-- ========== Jika status ticket onprocess ========== --}}
                        @elseif($ticket->status == "onprocess" AND $ticket->agent->nik == auth()->user()->nik) {{-- Jika status onprocess dan belum ada detail ticket --}}
                            {{-- Tombol Tangani Kembali --}}
                            <li><a class="dropdown-item text-capitalize text-primary" href="{{ route('ticket.reProcess2', ['id' => encrypt($ticket->id)]) }}" onclick="reloadAction()"><i class="bx bx-analyse text-primary"></i>Tangani</a></li>
                    
                        {{-- Jika pic ticket bukan service desk --}}
                        @else
                            {{-- Tombol Detail --}}
                            <li><a class="dropdown-item text-capitalize" href="{{ route('ticket-detail.index', ['ticket_id' => encrypt($ticket->id)]) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                        @endif
                    </ul>
                </td>
            </tr>

            {{-- Antrikan Modal --}}
            <div class="modal fade w-100" id="antrikanModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" id="modalContent1">
                    </div>
                </div>
            </div><!-- End Vertically centered Modal-->
            <script>
                // Fungsi untuk menampilkan data pada modal
                function tampilkanData1(ticket_id) {
                    // Mendapatkan elemen modalContent
                    var modalContent1 = document.getElementById("modalContent1");
                
                    // Menampilkan data pada modalContent
                    if(ticket_id.value === "ho"){
                        modalContent1.innerHTML  =
                        '<div class="modal-header">'+
                            '<h5 class="modal-title">.:: Pilih Sub Divisi Agent</h5>'+
                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                        '</div>'+
                        '<form action="/tickets/queue" method="post">'+
                        '@method("put")'+
                        '@csrf'+
                        '<div class="modal-body">'+
                            '<div class="col-md-12">'+
                                '<label for="sub_divisi" class="form-label">Sub Divisi</label>'+
                                '<select class="form-select" name="sub_divisi" id="sub_divisi" required>'+
                                    '<option selected disabled>Choose...</option>'+
                                    '<option value="hardware maintenance">Hardware Maintenance</option>'+
                                    '<option value="helpdesk">Helpdesk</option>'+
                                '</select>'+
                            '</div>'+
                            '<input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>'+
                            '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Antrikan</button>'+
                        '</div>'+
                        '</form>';
                    }else{
                        modalContent1.innerHTML  =
                        '<div class="modal-header">'+
                            '<h5 class="modal-title">.:: Pilih Sub Divisi Agent</h5>'+
                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                        '</div>'+
                        '<form action="/tickets/queue" method="post">'+
                        '@method("put")'+
                        '@csrf'+
                        '<div class="modal-body">'+
                            '<div class="col-md-12">'+
                                '<label for="sub_divisi" class="form-label">Sub Divisi</label>'+
                                '<select class="form-select" name="sub_divisi" id="sub_divisi" required>'+
                                    '<option selected disabled>Choose...</option>'+
                                    '<option value="hardware maintenance">Hardware Maintenance</option>'+
                                    '<option value="infrastructur networking">Infrastructur Networking</option>'+
                                    '<option value="tech support">Tech Support</option>'+
                                '</select>'+
                            '</div>'+
                            '<input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>'+
                            '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Antrikan</button>'+
                        '</div>'+
                        '</form>';
                    }
                }
            </script>

            {{-- Assign Modal --}}
            <div class="modal fade w-100" id="assignModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" id="modalContent2">
                    </div>
                </div>
            </div><!-- End Vertically centered Modal-->
            <script>
                // Fungsi untuk menampilkan data pada modal
                function tampilkanData2(ticket_id) {
                    // Mendapatkan elemen modalContent
                    var modalContent2 = document.getElementById("modalContent2");
                
                    // Menampilkan data pada modalContent
                    if(ticket_id.value === "ho"){
                        modalContent2.innerHTML  =
                        '<div class="modal-header">'+
                            '<h5 class="modal-title">.:: Pilih Nama Agent</h5>'+
                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                        '</div>'+
                        '<form action="/tickets/assign" method="post">'+
                        '@method("put")'+
                        '@csrf'+
                        '<div class="modal-body">'+
                            '<div class="col-md-12">'+
                                '<label for="agent_id" class="form-label">Nama Agent</label>'+
                                '<select class="form-select" name="agent_id" id="agent_id" required>'+
                                    '<option selected disabled>Choose...</option>'+
                                    '@foreach($hoAgents as $hoAgent)'+
                                        '@if(old("agent_id") == $hoAgent->id)'+
                                        '<option selected value="{{ $hoAgent->id }}">{{ ucwords($hoAgent->nama_agent) }}</option>'+
                                        '@else'+
                                        '<option value="{{ $hoAgent->id }}">{{ ucwords($hoAgent->nama_agent) }}</option>'+
                                        '@endif'+
                                    '@endforeach'+
                                '</select>'+
                            '</div>'+
                            '<input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>'+
                            '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>'+
                        '</div>'+
                        '</form>';
                    }else{
                        modalContent2.innerHTML  =
                        '<div class="modal-header">'+
                            '<h5 class="modal-title">.:: Pilih Nama Agent</h5>'+
                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                        '</div>'+
                        '<form action="/tickets/assign" method="post">'+
                        '@method("put")'+
                        '@csrf'+
                        '<div class="modal-body">'+
                            '<div class="col-md-12">'+
                                '<label for="agent_id" class="form-label">Nama Agent</label>'+
                                '<select class="form-select" name="agent_id" id="agent_id" required>'+
                                    '<option selected disabled>Choose...</option>'+
                                    '@foreach($storeAgents as $storeAgent)'+
                                        '@if(old("agent_id") == $storeAgent->id)'+
                                        '<option selected value="{{ $storeAgent->id }}">{{ ucwords($storeAgent->nama_agent) }}</option>'+
                                        '@else'+
                                        '<option value="{{ $storeAgent->id }}">{{ ucwords($storeAgent->nama_agent) }}</option>'+
                                        '@endif'+
                                    '@endforeach'+
                                '</select>'+
                            '</div>'+
                            '<input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>'+
                            '<input type="text" id="ticket_id" name="ticket_id" value="'+ticket_id.name+'" hidden>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>'+
                        '</div>'+
                        '</form>';
                    }
                }
            </script>
            @endforeach
        </tbody>
    </table>
</div>