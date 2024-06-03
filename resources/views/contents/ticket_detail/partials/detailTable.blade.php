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
            <td>Attachment</td>
            </tr>
        </thead>
        <tbody class="text-capitalize">
            @if($countDetail == 0) 
            <tr>
                @if($ticket->status == "created")
                    @if(auth()->user()->role_id == 3)
                    <td colspan="8" class="text-lowercase text-secondary">-- ticket belum diproses --</td>
                    @else
                    <td colspan="9" class="text-lowercase text-secondary">-- ticket belum diproses --</td>
                    @endif
                @else
                    @if(auth()->user()->role_id == 3)
                    <td colspan="8" class="text-lowercase text-secondary">-- belum ada tindakan lebih lanjut dari agent --</td>
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
            <td class="text-capitalize">
                <a href="{{ asset('uploads/penanganan/' . $td->file) }}" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-richtext"></i></button></a>
            </td>
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