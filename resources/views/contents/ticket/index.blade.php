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
                                
                                <a href="/tickets/create{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>
                                <!-- Showing Notification Login Error -->
                                @if(session()->has('createError'))
                                <script>
                                    swal("Mohon Maaf!", "{{ session('createError') }}", "warning", {
                                        timer: 3000
                                    });
                                </script>
                                @endif

                                <table class="table datatable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NO. TICKET</th>
                                        <th scope="col">KENDALA</th>
                                        <th scope="col">LOKASI</th>
                                        <th scope="col">DIBUAT PADA</th>
                                        <th scope="col">DIBUAT OLEH</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($tickets as $ticket)
                                        <tr>
                                        <td>{{ $ticket->no_ticket }}</td>
                                        <td>{{ $ticket->kendala }}</td>
                                        <td>{{ $ticket->client->location->nama_lokasi }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($ticket->created_at)) }}</td>
                                        @if(auth()->user()->id == $ticket->user_id)
                                        <td>{{ $ticket->user->nama }} <span class="badge bg-info">saya</span></td>
                                        @else
                                        <td>{{ $ticket->user->nama }}</td>
                                        @endif
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
                                        <td class="dropdown">
                                        <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                                            <ul class="dropdown-menu">

                                            {{-- ========== Aksi untuk role client ========== --}}
                                            @if(auth()->user()->role == "client")
                                                <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                @if($ticket->status == "created") {{-- Jika status created, ticket masih bisa di hapus dan di edit --}}
                                                    @if($ticket->user_id == auth()->user()->id) {{-- Jika ticket dibuat oleh client sendiri --}}
                                                        <li><a class="dropdown-item text-capitalize" href="/tickets{{ encrypt(auth()->user()->id) }}-{{ encrypt(auth()->user()->role) }}/{{ $ticket->id }}/edit"><i class="bi bi-pencil-square text-success"></i>
                                                            Edit</a>
                                                        </li>
                                                        <form action="/tickets/{{ $ticket->id }}" method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <li><button type="submit" class="dropdown-item text-capitalize"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                                        </form>
                                                    @else {{-- Jika ticket dibuatkan oleh service desk --}}
                                                    @endif
                                                @else {{-- Jika status selain created, tombol hapus dan edit di hilangkan --}}
                                                @endif

                                            {{-- ========== Aksi untuk role service desk ========== --}}
                                            @elseif(auth()->user()->role == "service desk")
                                                @if($ticket->user_id == auth()->user()->id) {{-- Jika ticket dibuat oleh service desk --}}
                                                    @if($ticket->status == "created") {{-- Jika status created, ticket masih bisa di hapus, di edit dan di proses--}}
                                                        <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                        <li><a class="dropdown-item text-capitalize" href="/tickets{{ encrypt(auth()->user()->id) }}-{{ encrypt(auth()->user()->role) }}/{{ $ticket->id }}/edit"><i class="bi bi-pencil-square text-warning"></i>Edit</a></li>
                                                        <form action="/tickets/{{ $ticket->id }}" method="POST">
                                                        @method('put')
                                                        @csrf
                                                        <li><button type="submit" class="dropdown-item text-capitalize"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                                        </form>
                                                    @else {{-- Jika status selain created, tombol hapus, edit dan proses di hilangkan --}}
                                                        <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                    @endif
                                                @else {{-- Jika ticket bukan dibuat oleh service desk --}}
                                                    <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                                @endif

                                            {{-- ========== Aksi untuk role agent ========== --}}
                                            @else
                                                <li><a class="dropdown-item text-capitalize" href="/ticket-details/{{  encrypt($ticket->id) }}"><i class="bi bi-file-text text-secondary"></i>Detail</a></li>
                                            @endif
                                            </ul>
                                        </td>
                                        </tr>
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