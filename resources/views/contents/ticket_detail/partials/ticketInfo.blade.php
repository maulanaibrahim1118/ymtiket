{{-- Left Side --}}
<div class="col-6">
    <div class="row">
        <div class="col-md-3 m-0">
            <label for="no_ticket" class="form-label fw-bold">Ticket Number</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_ticket" class="form-label">: <b>{{ $ticket->no_ticket }}</b></label>
        </div>
        <div class="col-md-3 m-0">
            <label class="form-label fw-bold">Created At</label>
        </div>
        <div class="col-md-9 m-0">
            @if($ticket->jam_kerja == "ya")
            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Work Day</span></label>
            @elseif($ticket->jam_kerja == "tidak")
            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Off Day</span></label>
            @endif
        </div>
        <div class="col-md-3 m-0">
            <label for="no_ticket" class="form-label fw-bold">Created By</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_ticket" class="form-label">: {{ ucwords($ticket->created_by) }} | Reference : {{ ucwords($ticket->source) }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="agent" class="form-label fw-bold">Ticket For</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="estimated" class="form-label fw-bold">Estimated Time</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="estimated" id="estimated" class="form-label">: {{ $ticket->estimated }}</label>
        </div>
    </div>
</div>

{{-- Right Side --}}
<div class="col-6">
    <div class="row">
        <div class="col-md-3 m-0">
            <label for="client/lokasi" class="form-label fw-bold">Client</label>
        </div>
        <div class="col-md-9 m-0">
            @if ($ticket->user->nama == $ticket->location->nama_lokasi)
            <label for="client/lokasi" class="form-label">: <span class="badge bg-primary" style="font-size: 13px;">{{ ucwords($ticket->user->nik) }} - {{ ucwords($ticket->location_name) }} / Store</span></label>
            @else
            <label for="client/lokasi" class="form-label">: <span class="badge bg-primary" style="font-size: 13px;">{{ ucwords($ticket->user->nama) }} / {{ ucwords($ticket->location->nama_lokasi) }}</span></label>
            @endif
        </div>
        <div class="col-md-3 m-0">
            <label for="telp" class="form-label fw-bold">Phone/Ext</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="telp" class="form-label">: <a href="https://wa.me/62{{ substr($ticket->user->telp, 1) }}?text=" target="_blank">{{ $ticket->user->telp }}</a></label>
        </div>
        <div class="col-md-3 m-0">
            <label for="ip_address" class="form-label fw-bold">IP Address</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="ip_address" class="form-label">: {{ $ticket->user->ip_address }}</label>
        </div>
        <div class="col-md-3 m-0">
            <label for="no_asset" class="form-label fw-bold">Asset</label>
        </div>
        <div class="col-md-9 m-0">
            <label for="no_asset" class="form-label">: <a href="{{ route('ticket.asset', ['asset_id' => encrypt($ticket->asset->id)]) }}">{{ $ticket->asset->no_asset }}</a> | {{ $ticket->asset->item->name }}</label>
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
            | <a href="#" data-bs-toggle="modal" data-bs-target="#verticalycentered">See Details</a>

            {{-- Status Ticket Modal --}}
            <div class="modal fade" id="verticalycentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ticket Status Details - <span class="text-success">{{ $ticket->no_ticket}}</span></h5>
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

<div class="col-12">
    <table class="table table-sm table-bordered text-center mb-0">
        <thead>
            <tr>
                <th colspan="3" class="fw-bold bg-light">Ticket Submission Details</th>
                <th class="col-md-1 fw-bold bg-light">Ticket Pending</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            <tr>
                <th class="col-md-1 fw-bold bg-light text-start ps-3">Subject</th>
                <td class="col-md-7 text-start ps-3">{{ ucfirst($ticket->kendala) }}</td>
                <td class="col-md-1">
                    {{-- Tombol Lampiran --}}
                    <a href="{{ asset('uploads/ticket/' . $ticket->file) }}" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark me-1"></i> Attachment</button></a>
                </td>
                @php
                    $totalSeconds = $ticket->pending_time;
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;
                @endphp
                @if($totalSeconds != 0)
                    <td rowspan="2">
                        {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                    </td>
                @else
                    <td rowspan="2">00:00:00</td>
                @endif
            </tr>
            <tr>
                <th class="col-md-1 fw-bold bg-light text-start ps-3">Details</th>
                <td colspan="2" class="text-start ps-3">{{ ucfirst($ticket->detail_kendala) }}</td>
            </tr>
        </tbody>
    </table>
</div>