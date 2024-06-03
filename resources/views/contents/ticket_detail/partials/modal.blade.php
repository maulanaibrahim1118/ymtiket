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
                    <img src="{{ asset('uploads/ticket/' . $ticket->file) }}" class="rounded mx-auto d-block w-100" alt="...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- End Lampiran Modal-->

{{-- Saran Tindakan Modal --}}
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent2">
        </div>
    </div>
</div><!-- End Vertically centered Modal-->

{{-- Antrikan Modal --}}
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
                        @if($ticket->location->wilayah_id == 2)
                        @foreach($subDivHo as $subDiv)
                            @if(old('sub_divisi') == $subDiv)
                            <option selected value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>
                            @else
                            <option value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>
                            @endif
                        @endforeach
                        @else
                        @foreach($subDivStore as $subDiv)
                            @if(old('sub_divisi') == $subDiv)
                            <option selected value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>
                            @else
                            <option value="{{ $subDiv }}">{{ ucwords($subDiv) }}</option>
                            @endif
                        @endforeach
                        @endif
                    </select>
                </div>
                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                <input type="text" id="ticket_id" name="id" value="{{ $ticket->id }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="bi bi-list-check me-2"></i>Antrikan</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Antrikan Modal-->

{{-- Assign Modal --}}
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
                <input type="text" id="url" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role_id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="bx bx-share me-2"></i>Assign</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Assign Modal-->

{{-- Pending Modal --}}
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
                <input type="text" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role_id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger"><i class="bi bi-stop-circle me-2"></i>Pending</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Pending Modal-->

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
                <input type="text" name="url" value="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role_id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Kirim</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Close Modal-->

<script>
    // Fungsi untuk menampilkan data pada saran tindakan modal
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