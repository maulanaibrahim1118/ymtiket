@extends('layouts.main')
@section('content')
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
                                {{-- Service Desk & Client --}}
                                @can('isActor')
                                <a href="/tickets/create"><button type="button" class="btn btn-primary" onclick="reloadAction()"><i class="bi bi-plus-lg me-1"></i> Create</button></a>
                                @endcan
                                @endcan

                                @if(auth()->user()->role_id == 1)
                                @include('contents.ticket.service_desk.table')
                                @endif

                                @if(auth()->user()->role_id == 2)
                                @include('contents.ticket.agent.table')
                                @endif

                                @can('isClient')
                                @include('contents.ticket.client.table')
                                @endcan
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection

@section('customScripts')
<script>
//     $(document).ready(function() {
//     $('#ticketsTable').DataTable({
//         paging: true, // Enable DataTables pagination
//         ordering: true,
//         searching: true,
//         pageLength: 10, // Number of rows per page in DataTables
//         lengthChange: true // Allow user to change number of rows per page
//     });
// });
</script>
@endsection