{{-- Lampiran Modal --}}
<div class="modal fade" id="lampiranModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" id="modalContent1">
            <div class="modal-header">
                <h5 class="modal-title">Ticket Attachment - <span class="text-success">{{ $ticket->no_ticket}}</h5>
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

{{-- Antrikan Modal --}}
<div class="modal fade" id="antrikanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent4">
            <div class="modal-header">
                <h5 class="modal-title">.:: Queue Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/tickets/queue" method="post">
            @method("put")
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <label for="sub_divisi" class="form-label">Sub Division</label>
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
                    
                    <script>
                        $(document).ready(function () {
                            $("#sub_divisi").select2({
                                dropdownParent: $("#sub_divisi").parent(), // Menentukan parent untuk dropdown
                            });
                        });
                    </script>
                </div>
                <input type="text" id="ticket_id" name="id" value="{{ encrypt($ticket->id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="reloadAction()"><i class="bi bi-list-check me-2"></i>Queue</button>
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
                <h5 class="modal-title">.:: Assign Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/tickets/assign2" method="post">
            @method("put")
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <label for="agent_id" class="form-label">Agent Name</label>
                    <select class="form-select select2" name="agent_id" id="agent_id" required>
                        <option selected disabled>Choose...</option>
                        @foreach($agents as $agent)
                            @if(old("agent_id") == $agent->id)
                            <option selected value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                            @else
                            <option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                            @endif
                        @endforeach
                    </select>
                    <script>
                        $(document).ready(function () {
                            $("#agent_id").select2({
                                dropdownParent: $("#agent_id").parent(), // Menentukan parent untuk dropdown
                            });
                        });
                    </script>
                </div>
                <input type="text" id="ticket_id" name="ticket_id" value="{{ encrypt($ticket->id) }}" hidden>
                <input type="text" id="agent_id1" name="agent_id1" value="{{ encrypt($ticket->agent_id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="reloadAction()"><i class="bx bx-share me-2"></i>Assign</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Assign Modal-->

{{-- Assign Another Modal --}}
<div class="modal fade" id="assignAnotherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent4">
            <div class="modal-header">
                <h5 class="modal-title">.:: Assign Ticket To Another Division</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/tickets/assignAnother" method="post">
            @method("put")
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <label for="ticket_for" class="form-label">Division Name</label>
                    <select class="form-select" name="ticket_for" id="ticket_for" required>
                        <option selected disabled>Choose...</option>
                        @foreach($ticketFors as $ticketFor)
                            @if(old("ticket_for") == $ticketFor->location_id)
                            <option selected value="{{ $ticketFor->location_id }}">{{ ucwords($ticketFor->location->nama_lokasi) }}</option>
                            @else
                            <option value="{{ $ticketFor->location_id }}">{{ ucwords($ticketFor->location->nama_lokasi) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="text" id="ticket_id" name="ticket_id" value="{{ encrypt($ticket->id) }}" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="reloadAction()"><i class="bx bx-share me-2"></i>Assign</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Assign Another Modal-->

{{-- Pending Modal --}}
<div class="modal fade" id="pendingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent3">
            <div class="modal-header">
                <h5 class="modal-title">Pending Reason - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ticket.pending', ['id' => encrypt($ticket->id)]) }}" method="post">
            @method("put")
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <textarea name="alasanPending" class="form-control @error('alasanPending') is-invalid @enderror" id="alasanPending" rows="3" placeholder="Type your pending reason...">{{ old('alasanPending') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" onclick="reloadAction()"><i class="bi bi-stop-circle me-2"></i>Pending</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Pending Modal-->

<div class="modal fade" id="closedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent4">
            <div class="modal-header">
                <h5 class="modal-title">Ticket Closed Status - <span class="text-success">{{ $ticket->no_ticket}}</h5>
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
                    <textarea name="alasanClosed" class="form-control" id="alasanClosed" rows="3" placeholder="Type your additional informations (optional)">{{ old('alasanClosed') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="reloadAction()"><i class="bi bi-send me-2"></i>Send</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- End Close Modal-->