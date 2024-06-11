<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="fw-bold bg-light">
            <tr>
            <td>Type</td>
            <td>Category</td>
            <td>Sub Category</td>
            <td>Cost</td>
            <td>Agent PIC</td>
            <td>Status</td>
            @can('agent-info')
            <td>Pending Time</td>
            <td>Prosessed Time</td>
            <td>Action Suggestion</td>
            <td>Attachment</td>
            @endcan
            </tr>
        </thead>
        <tbody class="text-capitalize">
            @if($countDetail == 0) 
            <tr>
                @if($ticket->status == "created")
                    @if(auth()->user()->role_id == 3)
                    <td colspan="8" class="text-lowercase text-secondary">-- ticket uprosessed --</td>
                    @else
                    <td colspan="9" class="text-lowercase text-secondary">-- ticket unrosessed --</td>
                    @endif
                @else
                    @if(auth()->user()->role_id == 3)
                    <td colspan="8" class="text-lowercase text-secondary">-- there has been no further action from the agent --</td>
                    @else
                    <td colspan="9" class="text-lowercase text-secondary">-- there has been no further action from the agent --</td>
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
            @php
                $carbonInstance = \Carbon\Carbon::parse($td->pending_time);
            @endphp
            @if($td->pending_time == NULL)
            <td>00:00:00</td>
            @elseif($td->pending_time >= 86400)
            <td>{{ str_pad($carbonInstance->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->second, 2, "0", STR_PAD_LEFT) }}</td>
            @else
            <td>{{ str_pad($carbonInstance->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif

            @php
                $carbonInstance = \Carbon\Carbon::parse($td->processed_time);
            @endphp
            @if($td->processed_time == NULL)
            <td>00:00:00</td>
            @elseif($td->processed_time >= 86400)
            <td>{{ str_pad($carbonInstance->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->second, 2, "0", STR_PAD_LEFT) }}</td>
            @else
            <td>{{ str_pad($carbonInstance->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($carbonInstance->second, 2, "0", STR_PAD_LEFT) }}</td>
            @endif
            
            <td class="text-capitalize"><button type="button" class="btn btn-sm btn-light ms-1" id="actionButton" data-bs-toggle="modal" data-bs-target="#actionModal" name="{{ $td->note }}" onclick="tampilkanData(this)"><i class="bi bi-search me-1"></i> See Details</button></td>
            {{-- Saran Tindakan Modal --}}
            <div class="modal fade" id="actionModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" id="modalContent2">
                    </div>
                </div>
            </div><!-- End Vertically centered Modal-->
            <script>
                // Fungsi untuk menampilkan data pada saran tindakan modal
                function tampilkanData(ticket_id) {
                    // Mendapatkan elemen modalContent
                    var modalContent2 = document.getElementById("modalContent2");
                
                    // Menampilkan data pada modalContent
                    modalContent2.innerHTML  =
                    '<div class="modal-header">'+
                        '<h5 class="modal-title">Action Suggestion - <span class="text-success">{{ $ticket->no_ticket}}</h5>'+
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
            <td class="text-capitalize">
                @if($td->file)
                <a href="{{ asset('uploads/penanganan/' . $td->file) }}" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-richtext"></i></button></a>
                @else
                Not Yet
                @endif
            </td>
            @endcan
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

@if($ticket->need_approval == "ya")
<div class="col-md-12 mb-2">
<p class="mb-2">
    Ticket Approval Details :</p>
<table class="table table-sm table-bordered text-center mb-0">
    <thead>
        <tr>
            <td class="col-md-2 fw-bold bg-light">Status Approval</td>
            <td class="col-md-2 fw-bold bg-light">Date / Time</td>
            <td class="col-md-2 fw-bold bg-light"></td>
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