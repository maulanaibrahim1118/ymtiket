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
                                    @include('contents.ticket_detail.partials.ticketInfo')

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                        
                                    {{-- Detail Table --}}
                                    <div class="col-md-12" style="font-size: 14px">
                                        <p class="mb-2">Detail penanganan Ticket :</p>
                                        @include('contents.ticket_detail.partials.detailTable')
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>

                                    {{-- Tombol Kembali --}}
                                    <div class="col-md-6">
                                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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
    @include('contents.ticket_detail.print')
@endsection