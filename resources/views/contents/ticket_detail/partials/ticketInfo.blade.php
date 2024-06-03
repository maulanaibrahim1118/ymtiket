<div class="col-6">
    <div class="row">
        <div class="col-md-3 m-0">
            <label for="no_ticket" class="form-label fw-bold">Dibuat Oleh</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_ticket" class="form-label">: {{ ucwords($ticket->created_by) }} | Referensi : {{ ucwords($ticket->source) }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label class="form-label fw-bold">Tanggal/Waktu</label>
        </div>
        <div class="col-md-9 m-0">
            @if($ticket->jam_kerja == "ya")
            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
            @elseif($ticket->jam_kerja == "tidak")
            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
            @endif
        </div>
        <div class="col-md-3 m-0">
            <label for="no_ticket" class="form-label fw-bold">No. Ticket</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_ticket" class="form-label">: {{ $ticket->no_ticket }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="client/lokasi" class="form-label fw-bold">Pemohon</label>
        </div>
        <div class="col-md-9 m-0">
            @if ($ticket->user->nama == $ticket->location->nama_lokasi)
            <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->user->nik) }} - {{ ucwords($ticket->location_name) }} / Store</label>
            @else
            <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->user->nama) }} / {{ ucwords($ticket->location->nama_lokasi) }}</label>
            @endif
        </div>
        <div class="col-md-3 m-0">
            <label for="agent" class="form-label fw-bold">Ditujukan Pada</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
        </div>
    </div>
</div>

<div class="col-6">
    <div class="row">
        <div class="col-md-3 m-0">
            <label for="telp" class="form-label fw-bold">Telp/Ext</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="telp" class="form-label">: {{ $ticket->user->telp }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="ip_address" class="form-label fw-bold">IP Address</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="ip_address" class="form-label">: {{ $ticket->user->ip_address }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="no_asset" class="form-label fw-bold">No. Asset</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_asset" class="form-label">: <a href="{{ route('ticket.asset', ['asset_id' => encrypt($ticket->asset->id)]) }}">{{ $ticket->asset->no_asset }}</a></label>
        </div>
        <div class="col-md-3 m-0">
            <label for="estimated" class="form-label fw-bold">Waktu Estimasi</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="estimated" id="estimated" class="form-label">: {{ $ticket->estimated }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="status" class="form-label fw-bold">Status Ticket</label>
        </div>
        <div class="col-md-9 m-0">
            @if($ticket->status == 'created')
            <label class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
            @elseif($ticket->status == 'onprocess')
            <label class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
            @elseif($ticket->status == 'pending')
            <label class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
            @elseif($ticket->status == 'resolved')
            <label class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
            @elseif($ticket->status == 'finished')
            <label class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
            @endif
            | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">Lihat Detail Status</a>

            {{-- Status Ticket Modal --}}
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
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12 m-0">
            <label class="form-label fw-bold">Kendala</label>
            <label class="form-label" style="padding-left:115px;">: {{ ucfirst($ticket->kendala) }}</label>
        </div>
        <div class="col-md-12 m-0">
            <label class="form-label fw-bold">Detail Kendala</label>
            <label class="form-label" style="padding-left:70px;">: {{ ucfirst($ticket->detail_kendala) }}</label>
        </div>
    </div>
</div>

<div class="col-md-9">
    {{-- Tombol Lampiran --}}
    @if($ext == "xls" || $ext == "xlsx" || $ext == "pdf" || $ext == "doc" || $ext == "docx" || $ext == "csv")
    <a href="{{ asset('uploads/ticket/' . $ticket->file) }}"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark me-1"></i> Lampiran</button></a>
    @else
    <button type="button" class="btn btn-outline-primary btn-sm" id="lampiranButton" data-bs-toggle="modal" data-bs-target="#lampiranModal"><i class="bi bi-file-earmark me-1"></i> Lampiran</button>
    @endif
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