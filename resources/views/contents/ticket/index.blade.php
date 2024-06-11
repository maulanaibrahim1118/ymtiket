@extends('layouts.main')
@section('content')
    <!-- Showing Notification -->
    @if(session()->has('createError'))
    <script>
        swal("Mohon Maaf!", "{{ session('createError') }}", "warning", {
            timer: 3000
        });
    </script>
    @endif

    @if(session()->has('error'))
    <script>
        swal("Gagal!", "{{ session('error') }}", "warning", {
            timer: 3000
        });
    </script>
    @endif

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="#"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                @can('manage-ticket')
                                <a href="/tickets/create"><button type="button" class="btn btn-primary" onclick="reloadAction()"><i class="bi bi-plus-lg me-1"></i> Create</button></a>
                                @endcan

                                @can('isServiceDesk')
                                @include('contents.ticket.partials.service_desk_content')
                                @endcan

                                @can('isAgent')
                                @include('contents.ticket.partials.agent_content')
                                @endcan

                                @can('isClient')
                                @include('contents.ticket.partials.client_content')
                                @endcan
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
    
    <script src="{{ asset('dist/js/refresh-page-interval.js') }}"></script>
@endsection