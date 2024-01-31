@extends('layouts.secondary')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <div class="row g-3 mb-3 pt-3" style="font-size: 14px">
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Tanggal/Waktu</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        @if($ticket->jam_kerja == "ya")
                                        <label for="tanggal" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
                                        @elseif($ticket->jam_kerja == "tidak")
                                        <label for="tanggal" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
                                        @endif
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Telp/Ext</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->client->telp }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">No. Ticket</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->no_ticket }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">IP Address</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->client->ip_address }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">No. Asset</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->asset->no_asset }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Waktu Estimasi</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->estimated }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Client/Lokasi</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->client->nama_client) }} / {{ ucwords($ticket->location->nama_lokasi) }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">PIC Agent</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->agent->nama_agent) }} - <i>{{ ucwords($ticket->agent->location->nama_lokasi) }}</i></label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Kendala</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->kendala) }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Status</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        @if($ticket->status == 'created')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
                                        @elseif($ticket->status == 'onprocess')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
                                        @elseif($ticket->status == 'pending')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
                                        @elseif($ticket->status == 'resolved')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
                                        @elseif($ticket->status == 'finished')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Status Ticket - <span class="text-success">{{ $ticket->no_ticket}}</span></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="activity">
                                                        @foreach($progress_tickets as $pt)
                                                        <div class="activity-item d-flex">
                                                            <div class="activite-label pe-3">{{ date('d-M-Y H:i', strtotime($pt->created_at)) }}</div>
                                                            <i class='bi bi-circle-fill activity-badge text-secondary align-self-start'></i>
                                                            <div class="activity-content">
                                                                {{ $pt->tindakan }} <a href="#" class="text-secondary">{{ ucwords($pt->updated_by) }}</a>
                                                            </div>
                                                        </div><!-- End activity item-->
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End Vertically centered Modal-->
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Detail Kendala</label>
                                    </div>
                                    <div class="col-md-10 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->detail_kendala }}</label>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                        
                                    <div class="col-md-12" style="font-size: 14px">
                                        <table class="table table-bordered">
                                            <thead class="fw-bold text-center">
                                                <tr>
                                                <td>Kategori Ticket</td>
                                                <td>Sub Kategori Ticket</td>
                                                <td class="col-md-2">Biaya</td>
                                                <td class="col-md-4">Note</td>
                                                </tr>
                                            </thead>
                                            <tbody class="text-uppercase">
                                                @foreach($ticket_details as $td)
                                                <tr>
                                                <td>{{ $td->sub_category_ticket->category->nama_kategori }}</td>
                                                <td>{{ $td->sub_category_ticket->nama_sub_kategori }}</td>
                                                <td>{{ $td->biaya }}</td>
                                                <td>{{ $td->note }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        @if(auth()->user()->role == "client") {{-- Jika role sebagai Client --}}
                                            @if($ticket->status == "resolved") {{-- Jika status resolved, muncul tombol close/selesai --}}
                                                <a href="#"><button type="button" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-check-circle me-1"></i> Close</button></a>
                                            @else {{-- Jika status bukan resolved, tidak akan muncul tombol apapun --}}
                                            @endif
                                        @else {{-- Jika role sebagai Agent/Service Desk --}}
                                            @if($ticket->status == "created")
                                                <a href="/ticket-details/{{ encrypt($ticket->id) }}/create">
                                                <button type="button" class="btn btn-sm btn-primary float-end ms-1">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Proses
                                                </button>
                                                </a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-outline-success float-end ms-1"><i class="bx bx-share me-1"></i> Assign</button></a>
                                            @elseif($ticket->status == "onprocess")
                                                <a href="#"><button type="button" class="btn btn-sm btn-primary float-end ms-1"><i class="bi bi-check-circle me-1"></i> Resolved</button></a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-danger float-end ms-1"><i class="bi bi-stop-circle me-1"></i> Pending</button></a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-pencil-square me-1"></i> Edit</button></a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-outline-success float-end ms-1"><i class="bx bx-share me-1"></i> Assign</button></a>
                                                @elseif($ticket->status == "pending")
                                                <a href="#"><button type="button" class="btn btn-sm btn-primary float-end ms-1"><i class="bi bi-arrow-repeat me-1"></i> Proses Ulang</button></a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-pencil-square me-1"></i> Edit</button></a>
                                                <a href="#"><button type="button" class="btn btn-sm btn-outline-success float-end ms-1"><i class="bx bx-share me-1"></i> Assign</button></a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-2">
                                <h5 class="card-title">Komentar</h5>
                                <div class="col-md-12">
                                    <form action="/ticket-comments" method="POST">
                                        @csrf
                                        <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                                        <input type="text" name="ticket_id" value="{{ $ticket->id }}" hidden>
                                        <textarea name="komentar" class="form-control h-50" id="komentar" rows="4" placeholder="Tulis komentar anda..." required></textarea>
                                        <!-- Showing notification error for input validation -->
                                        @error('komentar')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                        <a href="#"><button type="submit" class="btn btn-sm btn-primary float-end mt-2 ms-1"><i class="bi bi-send me-1"></i> Kirim</button></a>
                                    </form>
                                    @if(session()->has('success'))
                                    <script>
                                        swal("Terkirim!", "{{ session('success') }}", "success", {
                                            timer: 3000
                                        });
                                    </script>
                                    @endif
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-6 -->

            <!-- Right side columns -->
            <div class="col-lg-6 flex-nowrap">

                <!-- Recent Activity -->
                <div class="card py-4 h-100" style="max-height: 232px; overflow: hidden;">
                    <div class="card-body overflow-auto">
                        <div class="activity">
                            @if($checkComment == 0)
                            <p class="text-center">Belum ada komentar.</p>
                            @else
                            @foreach($comments as $comment)
                            <div class="activity-item d-flex">
                                <div class="activite-label pe-3">{{ date('d-M-Y H:i', strtotime($comment->created_at)) }}</div>
                                <i class='bi bi-circle-fill activity-badge text-secondary align-self-start'></i>
                                <div class="activity-content">
                                    <a href="#" class="fw-bold text-dark">{{ ucwords($comment->user->nama) }}</a> : {{ $comment->komentar }}
                                </div>
                            </div><!-- End activity item-->
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End col-lg-6 -->
        </div> <!-- End row -->
    </section>
@endsection