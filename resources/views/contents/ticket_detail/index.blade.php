@extends('layouts.main')
@section('content')
    @include('contents.ticket_detail.print')
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

                            @can('isServiceDesk')
                            <div class="filter">
                                <input type="text" value="{{ route('ticket.shared', ['id' => encrypt($ticket->id)]) }}" id="sharedLink" hidden>
                                <a href="#" class="icon" onclick="copyLink()"><i class="bi bi-link-45deg align-middle fs-5"></i></a>
                            </div> <!-- End Filter -->
                            @endcan

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-1"></i>Ticket Details</h5>
                                
                                <div class="row g-3 mb-3" style="font-size: 14px">
                                    @include('contents.ticket_detail.partials.ticketInfo')

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                                    
                                    {{-- Tombol Kembali --}}
                                    <div class="col-6">
                                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
                                    </div>

                                    @can('isClient')
                                    <div class="col-6 mb-1 text-end">
                                        {{-- Tombol Print --}}
                                        <button class="btn btn-sm btn-primary print-button d-print-none float-end ms-1" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Ticket</button>
                                    </div>
                                    @endcan
                                    
                                    @can('isServiceDesk')
                                    @if($ticket->agent->nik == auth()->user()->nik)
                                        @if($ticket->status != "resolved" && $ticket->status != "finished")
                                        <div class="col-6 mb-1 text-end">
                                            <label>The ticket isn't supposed to be for your division? <a href="#" class="fw-bold" data-bs-toggle="modal" data-bs-target="#assignAnotherModal"><span class="badge bg-primary">Assign</span></a> to another division.</label>
                                        </div>
                                        @endif
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| {{ $ticket->no_ticket }}</h5>
                                
                                <div class="row g-3 mb-3 pt-3" style="font-size: 14px">
                                    {{-- Detail Table --}}
                                    <div class="col-md-12 mt-0" style="font-size: 14px">
                                        @include('contents.ticket_detail.partials.detailTable')
                                    </div>

                                    <div class="col-md-12 mt-0">
                                        <p class="border-bottom mb-0"></p>
                                    </div>

                                    <div class="col-md-6">
                                    </div>

                                    {{-- Action --}}
                                    <div class="col-md-6">
                                        @can('isClient') {{-- Jika role sebagai Client --}}
                                            @include('contents.ticket_detail.partials.actionClient')
                                        @endcan

                                        @can('agent-info'){{-- Jika role sebagai Agent/Service Desk --}}
                                            @include('contents.ticket_detail.partials.actionAgent')
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('contents.ticket_detail.partials.comment')
        </div> <!-- End row -->
    </section>
    
    @include('contents.ticket_detail.partials.modal')

    <script>
        function copyLink() {
            var copyText = document.getElementById("sharedLink").value;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(copyText)
                    .then(() => {
                        alert("Link copied to clipboard.");
                    })
                    .catch((err) => {
                        console.error('Failed to copy: ', err);
                    });
            } else {
                // Fallback untuk browser yang tidak mendukung Clipboard API
                copyTextFallback(copyText);
            }
        }

        function copyTextFallback(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                alert("Link copied to clipboard.");
            } catch (err) {
                console.error('Failed to copy fallback: ', err);
            }

            document.body.removeChild(textArea);
        }
    </script>
@endsection