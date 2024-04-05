@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">
                            @if(session()->has('error'))
                            <script>
                                swal("Gagal!", "{{ session('error') }}", "warning", {
                                    timer: 3000
                                });
                            </script>
                            @endif
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <div class="row g-3 mb-3 pt-3" style="font-size: 14px">
                                    <div class="col-md-2 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Tanggal/Waktu</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if($ticket->jam_kerja == "ya")
                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
                                        @elseif($ticket->jam_kerja == "tidak")
                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
                                        @endif
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="telp" class="form-label fw-bold">Telp/Ext</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="telp" class="form-label">: {{ $ticket->client->telp }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="no_ticket" class="form-label fw-bold">No. Ticket</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="no_ticket" class="form-label">: {{ $ticket->no_ticket }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="ip_address" class="form-label fw-bold">IP Address</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="ip_address" class="form-label">: {{ $ticket->client->ip_address }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="client/lokasi" class="form-label fw-bold">Client/Lokasi</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if ($ticket->client->nama_client == $ticket->location->nama_lokasi)
                                        <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->client->nik) }} - {{ ucwords($ticket->client->nama_client) }} / Store</label>
                                        @else
                                        <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->client->nama_client) }} / {{ ucwords($ticket->location->nama_lokasi) }}</label>
                                        @endif
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="no_asset" class="form-label fw-bold">No. Asset</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="no_asset" class="form-label">: <a href="{{ route('ticket.asset', ['asset_id' => encrypt($ticket->asset->id)]) }}">{{ $ticket->asset->no_asset }}</a></label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="agent" class="form-label fw-bold">Ditujukan Pada</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="estimated" class="form-label fw-bold">Waktu Estimasi</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="estimated" id="estimated" class="form-label">: {{ $ticket->estimated }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="kendala" class="form-label fw-bold">Kendala</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="kendala" class="form-label">: {{ ucfirst($ticket->kendala) }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="status" class="form-label fw-bold">Status Ticket</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if($ticket->status == 'created')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'onprocess')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'pending')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'resolved')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'finished')
                                        <label for="tanggal" class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
                                        @endif
                                        | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>
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
                                                            <div class="activite-label pe-3">{{ date('d-M-Y H:i', strtotime($pt->process_at)) }}</div>
                                                            <i class='bi bi-circle-fill activity-badge text-secondary align-self-start'></i>
                                                            <div class="activity-content">
                                                                {{ $pt->tindakan }}</a>
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
                                    
                                    <div class="col-md-2 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Detail Kendala</label>
                                    </div>
                                    <div class="col-md-10 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucfirst($ticket->detail_kendala) }}</label>
                                    </div>

                                    <div class="col-md-9">
                                        {{-- Tombol Lampiran --}}
                                        @if($ext == "xlsx")
                                        <a href="{{ asset('uploads/' . $ticket->file) }}"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark me-1"></i> Lampiran</button></a>
                                        @else
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="lampiranButton" data-bs-toggle="modal" data-bs-target="#lampiranModal"><i class="bi bi-file-earmark me-1"></i> Lampiran</button>
                                        @endif

                                        {{-- Lampiran Modal --}}
                                        <div class="modal fade" id="lampiranModal" tabindex="-1">
                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <div class="modal-content" id="modalContent1">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Lampiran Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="col-md-12">
                                                            <img src="{{ asset('uploads/' . $ticket->file) }}" class="rounded mx-auto d-block w-100" alt="...">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End Lampiran Modal-->
                                    </div>

                                    <div class="col-md-3 mb-0">
                                        <table class="table table-sm table-bordered text-center mb-0">
                                            <thead>
                                                <tr>
                                                    @php
                                                        $carbonInstance = \Carbon\Carbon::parse($ticket->pending_time);
                                                    @endphp
                                                    @if($ticket->pending_time >= 3600)
                                                    <td class="col-md-1 fw-bold bg-light">Ticket Pending </td>
                                                    <td class="col-md-2">{{ $carbonInstance->hour }} jam {{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                    @elseif($ticket->pending_time >= 60)
                                                    <td class="col-md-1 fw-bold bg-light">Ticket Pending </td>
                                                    <td class="col-md-2">{{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                    @elseif($ticket->pending_time == 0)
                                                    <td class="col-md-1 fw-bold bg-light">Ticket Pending </td>
                                                    <td class="col-md-2">0 detik</td>
                                                    @else
                                                    <td class="col-md-1 fw-bold bg-light">Ticket Pending </td>
                                                    <td class="col-md-2">{{ $carbonInstance->second }} detik</td>
                                                    @endif
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                        
                                    <div class="col-md-12" style="font-size: 14px">
                                        <p class="mb-2">Detail penanganan Ticket :</p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="fw-bold bg-light">
                                                    <tr>
                                                    <td>Jenis Ticket</td>
                                                    <td>Kategori Ticket</td>
                                                    <td>Sub Kategori Ticket</td>
                                                    <td>Biaya</td>
                                                    <td>PIC Agent</td>
                                                    <td>Status</td>
                                                    @can('agent-info')
                                                    <td>Lama Pending</td>
                                                    <td>Lama Proses</td>
                                                    @endcan
                                                    <td>Saran Tindakan</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-capitalize">
                                                    @if($countDetail == 0) 
                                                    <tr>
                                                        @if($ticket->status == "created")
                                                            @if(auth()->user()->role == "client")
                                                            <td colspan="7" class="text-lowercase text-secondary">-- ticket belum diproses --</td>
                                                            @else
                                                            <td colspan="9" class="text-lowercase text-secondary">-- ticket belum diproses --</td>
                                                            @endif
                                                        @else
                                                            @if(auth()->user()->role == "client")
                                                            <td colspan="7" class="text-lowercase text-secondary">-- belum ada tindakan lebih lanjut dari agent --</td>
                                                            @else
                                                            <td colspan="9" class="text-lowercase text-secondary">-- belum ada tindakan lebih lanjut dari agent --</td>
                                                            @endif
                                                        @endif
                                                    </tr>
                                                    @else
                                                    @foreach($ticket_details as $td)
                                                    <tr>
                                                    <td>{{ $td->jenis_ticket }}</td>
                                                    <td>{{ $td->sub_category_ticket->category_ticket->nama_kategori }}</td>
                                                    <td>{{ $td->sub_category_ticket->nama_sub_kategori }}</td>
                                                    <td>IDR. {{ number_format($td->biaya,2,'.',',') }}</td>
                                                    <td>{{ $td->agent->nama_agent }}</td>

                                                    @if($td->status == 'onprocess')
                                                    <td><span class="badge bg-warning">{{ $td->status }}</span></td>
                                                    @elseif($td->status == 'pending')
                                                    <td><span class="badge bg-danger">{{ $td->status }}</span></td>
                                                    @elseif($td->status == 'resolved')
                                                    <td><span class="badge bg-primary">{{ $td->status }}</span></td>
                                                    @elseif($td->status == 'assigned')
                                                    <td><span class="badge bg-danger">not resolved</span></td>
                                                    @endif

                                                    @can('agent-info')
                                                    @if($td->pending_time == NULL)
                                                    <td>-</td>
                                                    @else
                                                        @php
                                                            $carbonInstance = \Carbon\Carbon::parse($td->pending_time);
                                                        @endphp
                                                        @if($td->pending_time >= 3600)
                                                        <td>{{ $carbonInstance->hour }} jam {{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                        @elseif($td->pending_time >= 60)
                                                        <td>{{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                        @else
                                                        <td>{{ $carbonInstance->second }} detik</td>
                                                        @endif
                                                    @endif

                                                    @if($td->processed_time == NULL)
                                                    <td>-</td>
                                                    @else
                                                        @php
                                                            $carbonInstance = \Carbon\Carbon::parse($td->processed_time);
                                                        @endphp
                                                        @if($td->processed_time >= 3600)
                                                        <td>{{ $carbonInstance->hour }} jam {{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                        @elseif($td->processed_time >= 60)
                                                        <td>{{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</td>
                                                        @else
                                                        <td>{{ $carbonInstance->second }} detik</td>
                                                        @endif
                                                    @endif
                                                    @endcan

                                                    <td class="text-capitalize"><button type="button" class="btn btn-sm btn-light ms-1" id="actionButton" data-bs-toggle="modal" data-bs-target="#actionModal" name="{{ $td->note }}" onclick="tampilkanData(this)"><i class="bi bi-search me-1"></i> Lihat</button></td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($ticket->need_approval == "ya")
                                            <div class="col-md-12 mb-2">
                                            <p class="mb-2">Detail persetujuan Ticket :</p>
                                            <table class="table table-sm table-bordered text-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <td class="col-md-2 fw-bold bg-light">Status Approval</td>
                                                        <td class="col-md-2 fw-bold bg-light">Tanggal / Waktu</td>
                                                        <td class="col-md-2 fw-bold bg-light">Atasan Terkait</td>
                                                        <td class="col-md-7 fw-bold bg-light">Alasan</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @if($ticket_approval->status == "null")
                                                            <td class="col-md-2"><span class="badge bg-secondary">Belum Disetujui</span></td>
                                                            <td class="col-md-2"></td>
                                                        @else
                                                            @if($ticket_approval->status == "approved")
                                                                <td class="col-md-2"><span class="badge bg-success">{{ ucwords($ticket_approval->status) }}</td>
                                                            @else
                                                                <td class="col-md-2"><span class="badge bg-danger">{{ ucwords($ticket_approval->status) }}</td>
                                                            @endif
                                                            <td class="col-md-2">{{ date('d-M-Y', strtotime($ticket_approval->updated_at)) }} / <span class="text-secondary">{{ date('H:i', strtotime($ticket_approval->updated_at)) }}</span></td>
                                                        @endif
                                                        <td class="col-md-2">{{ ucwords($ticket_approval->approved_by) }}</td>
                                                        <td class="col-md-7">{{ ucfirst($ticket_approval->reason) }}</td>
                                                    </tr>
                                            </table>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>


                                    {{-- Saran Tindakan Modal --}}
                                    <div class="modal fade" id="actionModal" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" id="modalContent2">
                                            </div>
                                        </div>
                                    </div><!-- End Vertically centered Modal-->
                                    <script>
                                    // Fungsi untuk menampilkan data pada modal
                                    function tampilkanData(ticket_id) {
                                        // Mendapatkan elemen modalContent
                                        var modalContent2 = document.getElementById("modalContent2");
                                    
                                        // Menampilkan data pada modalContent
                                        modalContent2.innerHTML  =
                                        '<div class="modal-header">'+
                                            '<h5 class="modal-title">Saran Tindakan Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>'+
                                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
                                        '</div>'+
                                        '<form action="/tickets/assign" method="post">'+
                                        '@method("put")'+
                                        '@csrf'+
                                        '<div class="modal-body">'+
                                            '<div class="col-md-12">'+
                                                '<p>'+ticket_id.name+'</p>'+
                                            '</div>'+
                                            '<input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>'+
                                        '</div>'+
                                        '<div class="modal-footer">'+
                                            '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>'+
                                        '</div>'+
                                        '</form>';
                                    }
                                    </script>

                                    <div class="col-md-6">
                                        {{-- Tombol Kembali --}}
                                        <a href="/tickets"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                    </div>

                                    <div class="col-md-6">
                                    @can('isClient') {{-- Jika role sebagai Client --}}
                                        @if($ticket->status == "resolved") {{-- Jika status resolved, muncul tombol close/selesai --}}
                                            {{-- Tombol Close --}}
                                            <button type="button" class="btn btn-sm btn-success float-end ms-1" id="closedButton" data-bs-toggle="modal" data-bs-target="#closedModal"><i class="bi bi-check-circle me-1"></i> Close</button>
                                            <div class="modal fade" id="closedModal" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="modalContent4">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Status Closed Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('ticket.finished', ['id' => encrypt($ticket->id)]) }}" method="post">
                                                        @method("put")
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-md-6 mb-2">
                                                                <select class="form-select" name="closedStatus" id="closedStatus" value="{{ old('closedStatus') }}">
                                                                    <option selected disabled>Choose...</option>
                                                                    <option value="selesai">Selesai</option>
                                                                    <option value="belum selesai">Belum Selesai</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <textarea name="alasanClosed" class="form-control" id="alasanClosed" rows="3" placeholder="Tuliskan keterangan tambahan (opsional)">{{ old('alasanClosed') }}</textarea>
                                                            </div>
                                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                            <input type="text" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" hidden>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Kirim</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- End Close Modal-->
                                        @else {{-- Jika status bukan resolved, tidak akan muncul tombol apapun --}}
                                        @endif

                                        @can('isKorwil')
                                            @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
                                                {{-- Tombol Rejected --}}
                                                <button type="button" class="btn btn-sm btn-danger float-end ms-1" id="rejectedButton" data-bs-toggle="modal" data-bs-target="#rejectedModal"><i class="bi bi-x-circle me-1"></i> Tidak Setuju</button>
                                                <div class="modal fade" id="rejectedModal" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" id="modalContent4">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Alasan Tidak Menyetujui Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="/ticket-approval" method="post">
                                                            @method("put")
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="col-md-12">
                                                                    <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="Tuliskan alasan anda tidak menyetujui..." required>{{ old('reason') }}</textarea>
                                                                </div>
                                                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                                <input type="text" name="status" value="rejected" hidden>
                                                                <input type="text" name="ticket_id" value="{{ $ticket->id }}" hidden>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Kirim</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div><!-- End Rejected Modal-->

                                                {{-- Tombol Approved --}}
                                                <form action="/ticket-approval" method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                <input type="text" name="status" value="approved" hidden>
                                                <input type="text" name="reason" value="" hidden>
                                                <input type="text" name="ticket_id" value="{{ $ticket->id }}" hidden>

                                                <button type="submit" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-check-circle me-1"></i> Setuju</button>
                                                </form>

                                                <p class="float-end me-2 fw-bold">Approval Biaya Penanganan Ticket :</p>
                                            @else
                                            @endif
                                        @endcan
                                    @endcan

                                    @can('agent-info'){{-- Jika role sebagai Agent/Service Desk --}}
                                        @if($ticket->status == "onprocess" AND $ticket->agent->nik == auth()->user()->nik)
                                            {{-- Tombol Selesai --}}
                                            <form action="{{ route('ticket.resolved', ['id' => encrypt($ticket->id)]) }}" method="POST">
                                            @method('put')
                                            @csrf
                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                            <input type="text" name="agent_id" value="{{ $ticket->agent_id }}" hidden>
                                            <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                            <input type="text" name="role" value="{{ auth()->user()->role }}" hidden>
                                            <input type="text" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" hidden>
                                            <button type="submit" class="btn btn-sm btn-primary float-end ms-1"><i class="bi bi-check-circle me-1"></i> Selesai</button>
                                            </form>

                                            {{-- Tombol Pending --}}
                                            <button type="button" class="btn btn-sm btn-danger float-end ms-1" id="pendingButton" data-bs-toggle="modal" data-bs-target="#pendingModal"><i class="bi bi-stop-circle me-1"></i> Pending</button>
                                            </form>
                                            <div class="modal fade" id="pendingModal" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="modalContent3">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Alasan Pending Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('ticket.pending', ['id' => encrypt($ticket->id)]) }}" method="post">
                                                        @method("put")
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <textarea name="alasanPending" class="form-control @error('alasanPending') is-invalid @enderror" id="alasanPending" rows="3" placeholder="Sebutkan alasan pending...">{{ old('alasanPending') }}</textarea>
                                                            </div>
                                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                            <input type="text" name="nik" value="{{ auth()->user()->nik }}" hidden>
                                                            <input type="text" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" hidden>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger"><i class="bi bi-stop-circle me-2"></i>Pending</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- End Pending Modal-->

                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('ticket-detail.edit', ['id' => encrypt($ticket->id)]) }}"><button type="button" class="btn btn-sm btn-warning float-end ms-1"><i class="bi bi-pencil-square me-1"></i> Edit</button></a>

                                            {{-- Tombol Antrikan --}}
                                            <button type="button" class="btn btn-sm btn-success float-end ms-1" id="antrikanButton" data-bs-toggle="modal" data-bs-target="#antrikanModal"><i class="bi bi-list-check me-1"></i> Antrikan</button>
                                            <div class="modal fade" id="antrikanModal" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="modalContent4">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">.:: Pilih Sub Divisi Agent</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="/tickets/queue" method="post">
                                                        @method("put")
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <label for="sub_divisi" class="form-label">Sub Divisi</label>
                                                                <select class="form-select" name="sub_divisi" id="sub_divisi" required>
                                                                    <option selected disabled>Choose...</option>
                                                                    @if($ticket->ticket_area == "ho")
                                                                    <option value="hardware maintenance">Hardware Maintenance</option>
                                                                    <option value="helpdesk">Helpdesk</option>
                                                                    @else
                                                                    <option value="hardware maintenance">Hardware Maintenance</option>
                                                                    <option value="infrastructur networking">Infrastructur Networking</option>
                                                                    <option value="tech support">Tech Support</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                            <input type="text" id="ticket_id" name="ticket_id" value="{{ $ticket->id }}" hidden>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Antrikan</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- End Antrikan Modal-->
                                            
                                            {{-- Tombol Assign --}}
                                            <button type="button" class="btn btn-sm btn-outline-dark float-end ms-1" id="assignButton" data-bs-toggle="modal" data-bs-target="#assignModal"><i class="bx bx-share me-1"></i> Assign</button>
                                            <div class="modal fade" id="assignModal" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="modalContent4">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">.:: Pilih Nama Agent</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="/tickets/assign2" method="post">
                                                        @method("put")
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <label for="agent_id" class="form-label">Nama Agent</label>
                                                                <select class="form-select" name="agent_id" id="agent_id" required>
                                                                    <option selected disabled>Choose...</option>
                                                                    @foreach($agents as $agent)
                                                                        @if(old("agent_id") == $agent->id)
                                                                        <option selected value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                                                                        @else
                                                                        <option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                            <input type="text" id="ticket_id" name="ticket_id" value="{{ $ticket->id }}" hidden>
                                                            <input type="text" id="agent_id1" name="agent_id1" value="{{ $ticket->agent_id }}" hidden>
                                                            <input type="text" id="url" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" hidden>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- End Assign Modal-->
                                        @else
                                        @endif
                                    @endcan
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
                                    @if(session()->has('commentSuccess'))
                                    <script>
                                        swal("Terkirim!", "{{ session('commentSuccess') }}", "success", {
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
                                    @if(auth()->user()->nama == $comment->user->nama)
                                    <a href="#" class="fw-bold text-dark pe-1">{{ ucwords($comment->user->nama) }}</a><span class="badge bg-info text-capitalize">saya</span></td> : {{ $comment->komentar }}
                                    @else
                                    <a href="#" class="fw-bold text-dark">{{ ucwords($comment->user->nama) }}</a></td> : {{ $comment->komentar }}
                                    @endif
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