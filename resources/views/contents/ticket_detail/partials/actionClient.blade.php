@if($ticket->status == "resolved") {{-- Jika status resolved, muncul tombol close/selesai --}}
    {{-- Tombol Close --}}
    <button type="button" class="btn btn-sm btn-success float-end ms-1" id="closedButton" data-bs-toggle="modal" data-bs-target="#closedModal"><i class="bi bi-check-circle me-1"></i> Close</button>
@else {{-- Jika status bukan resolved, tidak akan muncul tombol apapun --}}
@endif

{{-- Tombol Print --}}
<button class="btn btn-sm btn-primary print-button d-print-none float-end ms-1" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print</button>

@can('isKorwil')
    @if($ticket->need_approval == "ya" AND $ticket->approved == NULL)
        {{-- Tombol Rejected --}}
        <button type="button" class="btn btn-sm btn-danger float-end ms-1" id="rejectedButton" data-bs-toggle="modal" data-bs-target="#rejectedModal"><i class="bi bi-x-circle me-1"></i> Reject</button>
        <div class="modal fade" id="rejectedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="modalContent4">
                    <div class="modal-header">
                        <h5 class="modal-title">Approval Reason - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/ticket-approval" method="post">
                    @method("put")
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12">
                            <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="Type your approval reason..." required>{{ old('reason') }}</textarea>
                        </div>
                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                        <input type="text" name="status" value="rejected" hidden>
                        <input type="text" name="ticket_id" value="{{ $ticket->id }}" hidden>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Submit</button>
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

        <button type="submit" class="btn btn-sm btn-success float-end ms-1"><i class="bi bi-check-circle me-1"></i> Approve</button>
        </form>

        <p class="float-end me-2 fw-bold">Ticket Cost Approval :</p>
    @else
    @endif
@endcan