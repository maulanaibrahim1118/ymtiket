@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon pe-2" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                    </li>
                
                                    <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                                    <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                                    <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                                </ul>

                                <a class="icon" href="/tickets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <a href="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}/create"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>
                                <!-- Showing Notification Login Error -->
                                @if(session()->has('createError'))
                                <script>
                                    swal("Mohon Maaf!", "{{ session('createError') }}", "warning", {
                                        timer: 3000
                                    });
                                </script>
                                @endif

                                @if(session()->has('error'))
                                <script>
                                    swal("Gagal!", "{{ session('error') }}", "warning", {
                                        timer: 3000
                                    });
                                </script>
                                @endif

                                <table class="table datatable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NO. TICKET</th>
                                        <th scope="col">KENDALA</th>
                                        <th scope="col">DETAIL KENDALA</th>
                                        <th scope="col">DIBUAT PADA</th>
                                        <th scope="col">PIC</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">KETERANGAN</th>
                                        <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($tickets as $ticket)
                                        <tr>
                                        <td>{{ $ticket->no_ticket }}</td>
                                        <td>{{ $ticket->kendala }}</td>
                                        <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

                                        {{-- Kolom Dibuat Pada --}}
                                        @if($ticket->jam_kerja == 'ya')
                                        <td>{{ date('d-M-Y H:i:s', strtotime($ticket->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                                        @else
                                        <td>{{ date('d-M-Y H:i:s', strtotime($ticket->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                                        @endif

                                        {{-- Kolom PIC --}}
                                        @if($ticket->agent->nama_agent == auth()->user()->nama)
                                        <td>{{ $ticket->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                                        @else
                                        <td>{{ $ticket->agent->nama_agent }}</td>
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
                                        @endif

                                        {{-- Kolom Keterangan --}}
                                        @if($ticket->assigned == "ya" AND $ticket->status == "created" OR $ticket->assigned == "ya" AND $ticket->status == "pending")
                                        <td><span class="badge bg-primary">direct assign</span></td>
                                        @else
                                            @if($ticket->is_queue == "ya" AND $ticket->status == "created")
                                            <td><span class="badge bg-success">dalam antrian</span></td>
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

                                        {{-- Kolom Aksi --}}
                                        <td class="dropdown">
                                        <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                                            <ul class="dropdown-menu">

                                            {{-- ========== Aksi untuk role client ========== --}}
                                            @if(auth()->user()->role == "client")
                                                {{-- Tombol Detail --}}
                                                <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>

                                                @if($ticket->status == "created") {{-- Jika status created, ticket masih bisa di hapus dan di edit --}}
                                                    @if($ticket->user_id == auth()->user()->id) {{-- Jika ticket dibuat oleh client sendiri --}}
                                                        {{-- Tombol Edit --}}
                                                        <li><a class="dropdown-item text-capitalize text-warning" href="/tickets/{{ encrypt(auth()->user()->id) }}-{{ encrypt(auth()->user()->role) }}/edit{{ $ticket->id }}"><i class="bi bi-pencil-square text-warning"></i>
                                                            Edit</a>
                                                        </li>
                                                        {{-- Tombol Hapus --}}
                                                        <form action="/tickets/delete{{ $ticket->id }}" method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                        <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                                        </form>
                                                    @else {{-- Jika ticket dibuatkan oleh service desk --}}
                                                    @endif
                                                @else {{-- Jika status selain created, tombol hapus dan edit di hilangkan --}}
                                                @endif

                                            {{-- ========== Aksi untuk role service desk ========== --}}
                                            @elseif(auth()->user()->role == "service desk")
                                                {{-- Jika status ticket created --}}
                                                @if($ticket->status == "created" AND $ticket->agent->nik == auth()->user()->nik)
                                                    {{-- Tombol Tangani --}}
                                                    <li>
                                                    <form action="/tickets/{{ encrypt($ticket->id) }}/process1" method="post">
                                                    @method('put')
                                                    @csrf
                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                    <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                                    <a href="#">
                                                    <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Tangani</button>
                                                    </a>
                                                    </form>
                                                    </li>

                                                    @if($ticket->is_queue == "tidak")
                                                    {{-- Tombol Antrikan --}}
                                                    <li>
                                                    <form action="/tickets/queue{{ $ticket->id }}" method="post">
                                                    @method('put')
                                                    @csrf
                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                    <a href="#">
                                                    <button type="submit" class="dropdown-item text-capitalize text-success"><i class="bi bi-list-check text-success"></i>Antrikan</button>
                                                    </a>
                                                    </form>
                                                    </li>
                                                    @else
                                                    @endif
                                                    
                                                    {{-- Tombol Assign --}}
                                                    <li><button class="dropdown-item text-capitalize" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal" name="{{ $ticket->id }}" onclick="tampilkanData(this)"><i class="bx bx-share text-secondary"></i>Assign</button></li>

                                                    {{-- ========== Jika ticket dibuat oleh service desk ========== --}}
                                                    @if($ticket->user_id == auth()->user()->id)
                                                        {{-- Tombol Edit --}}
                                                        <li><a class="dropdown-item text-capitalize text-warning" href="/tickets/{{ encrypt(auth()->user()->id) }}-{{ encrypt(auth()->user()->role) }}/edit{{ $ticket->id }}">
                                                            <i class="bi bi-pencil-square text-warning"></i>Edit
                                                        </a></li>
                                                        {{-- Tombol Hapus --}}
                                                        <form action="/tickets/delete{{ $ticket->id }}" method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                        <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                                        </form>
                                                    @endif

                                                {{-- Jika status ticket pending --}}
                                                @elseif($ticket->status == "pending") {{-- Jika status pending --}}
                                                    @if($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "tidak")
                                                        {{-- Tombol Proses Ulang --}}
                                                        <li>
                                                        <form action="/tickets/{{ $ticket->id }}/reProcess1" method="post">
                                                        @method('put')
                                                        @csrf
                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                        <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                                        <a href="#">
                                                        <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Proses Ulang</button>
                                                        </a>
                                                        </form>
                                                        </li>

                                                    {{-- Jika status ticket pending assign --}}
                                                    @elseif($ticket->agent->nik == auth()->user()->nik AND $ticket->assigned == "ya")
                                                        {{-- Tombol Tangani --}}
                                                        <li>
                                                        <form action="/tickets/{{ encrypt($ticket->id) }}/process2" method="post">
                                                        @method('put')
                                                        @csrf
                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                        <a href="#">
                                                        <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Tangani</button>
                                                        </a>
                                                        </form>
                                                        </li>

                                                    @else
                                                        {{-- Tombol Detail --}}
                                                        <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                    @endif

                                                {{-- Jika status ticket onprocess --}}
                                                @elseif($ticket->status == "onprocess" and $ticket->agent->nik == auth()->user()->nik) {{-- Jika status onprocess dan belum ada detail ticket --}}
                                                    {{-- Tombol Tangani Kembali --}}
                                                    <li><a class="dropdown-item text-capitalize text-primary" href="/tickets/{{ $ticket->id }}/reProcess2"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Lanjutkan</a></li>

                                                {{-- Jika pic ticket bukan service desk --}}
                                                @else
                                                    {{-- Tombol Detail --}}
                                                    <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                @endif


                                            {{-- ========== Aksi untuk role agent ========== --}}
                                            @else
                                                {{-- Jika ticket di assign dan belum di tangani oleh service desk --}}
                                                @if($ticket->status == "created")
                                                    {{-- Tombol Tangani --}}
                                                    <li>
                                                    <form action="/tickets/{{ encrypt($ticket->id) }}/process1" method="post">
                                                    @method('put')
                                                    @csrf
                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                    <a href="#">
                                                    <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Tangani</button>
                                                    </a>
                                                    </form>
                                                    </li>

                                                {{-- Jika ticket di assign dan sudah pernah di tangani oleh service desk --}}
                                                @elseif($ticket->status == "pending" and $ticket->assigned == "ya")
                                                    {{-- Tombol Tangani --}}
                                                    <li>
                                                    <form action="/tickets/{{ encrypt($ticket->id) }}/process2" method="post">
                                                    @method('put')
                                                    @csrf
                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                    <a href="#">
                                                    <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Tangani</button>
                                                    </a>
                                                    </form>
                                                    </li>

                                                {{-- Jika ticket di pending oleh agent sendiri --}}
                                                @elseif($ticket->status == "pending" and $ticket->assigned == "tidak")
                                                    {{-- Tombol Proses Ulang --}}
                                                    <li>
                                                        <form action="/tickets/{{ $ticket->id }}/reProcess1" method="post">
                                                        @method('put')
                                                        @csrf
                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                        <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                                        <a href="#">
                                                        <button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Proses Ulang</button>
                                                        </a>
                                                        </form>
                                                        </li>

                                                {{-- Jika status ticket onprocess --}}
                                                @elseif($ticket->status == "onprocess" and $ticket->assigned == "tidak") {{-- Jika status onprocess dan belum ada detail ticket --}}
                                                    {{-- Tombol Tangani Kembali --}}
                                                    <li><a class="dropdown-item text-capitalize text-primary" href="/tickets/{{ $ticket->id }}/reProcess2"><i class="bi bi-box-arrow-in-down-right text-primary"></i>Lanjutkan</a></li>
                                                @else
                                                    {{-- Tombol Detail --}}
                                                    <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                @endif
                                            @endif
                                            </ul>
                                        </td>
                                        </tr>

                                        {{-- Assign Modal --}}
                                        <div class="modal fade" id="assignModal" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="modalContent">
                                                </div>
                                            </div>
                                        </div><!-- End Vertically centered Modal-->
                                        <script>
                                        // Fungsi untuk menampilkan data pada modal
                                        function tampilkanData(ticket_id) {
                                            // Mendapatkan elemen modalContent
                                            var modalContent = document.getElementById("modalContent");
                                        
                                            // Menampilkan data pada modalContent
                                            modalContent.innerHTML  =
                                            '<div class="modal-header">'+
                                                '<h5 class="modal-title">Pilih Nama Agent</h5>'+
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
                                                        '@foreach($agents as $agent)'+
                                                            '@if(old("agent_id") == $agent->id)'+
                                                            '<option selected value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>'+
                                                            '@else'+
                                                            '<option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>'+
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
                                        </script>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection