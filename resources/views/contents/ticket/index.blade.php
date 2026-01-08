@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card" {{ auth()->user()->role_id == 1 ? '' : 'hidden' }}>
                            <div class="filter">
                                <a href="#filterCollapse" data-bs-toggle="collapse" aria-expanded="true" style="text-decoration:none; color:inherit;" class="icon">
                                    <i class="bi bi-chevron-down"></i>
                                </a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-filter me-2"></i>Filter</h5>

                                <div class="collapse " id="filterCollapse">
                                    <form id="filter-form" class="row g-3 py-3">
                                        @csrf

                                        <div class="col-md-4 mt-3">
                                            <select name="client" class="form-select select2" id="client">
                                                <option value="">ALL CLIENTS</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->nik }} - {{ strtoupper($user->nama) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <select name="agent" class="form-select select2" id="agent">
                                                <option value="">ALL AGENTS</option>
                                                @foreach ($filterAgents as $agent)
                                                    <option value="{{ $agent->id }}">{{ strtoupper($agent->nama_agent) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <select name="searchStatus" class="form-select select2" id="searchStatus">
                                                <option value="">ALL STATUS</option>
                                                @foreach ($statuses as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <button type="submit" id="filterBtn" class="btn btn-primary">
                                                <i class="bi bi-search me-1"></i> Search
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="#" id="reloadBtn"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <a href="/tickets/create"><button type="button" class="btn btn-primary" onclick="reloadAction()"><i class="bi bi-plus-lg me-1"></i> Create</button></a>

                                <div class="table-responsive my-3">
                                    @can('isServiceDesk')
                                    @include('contents.ticket.service_desk.table')
                                    @endcan

                                    @can('isAgent')
                                    @include('contents.ticket.agent.table')
                                    @endcan

                                    @can('isClient')
                                    @include('contents.ticket.client.table')
                                    @endcan
                                </div>

                                @include('contents.ticket.partials.modal_action')
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection

@section('customScripts')
<script src="{{ asset('dist/js/refresh-page-interval.js') }}"></script>
<script>
    const listUrl = "{{ route('ticket.list') }}";

    $(document).ready(function () {
        const selectElements = [
            "#client",
            "#agent",
            "#searchStatus",
        ];

        // Menginisialisasi select2 pada semua elemen dalam array
        selectElements.forEach(selector => {
            $(selector).select2({
                dropdownParent: $(selector).parent()
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const collapseEl = document.getElementById('filterCollapse');
        const icon = document.querySelector('.filter .icon i');

        collapseEl.addEventListener('show.bs.collapse', function () {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        });

        collapseEl.addEventListener('hide.bs.collapse', function () {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        });
    });
</script>
@can('isServiceDesk')
<script src="{{ asset('dist/js/app/ticketServiceDesk.js') }}"></script>
@endcan
@can('isAgent')
<script src="{{ asset('dist/js/app/ticketAgent.js') }}"></script>
@endcan
@can('isClient')
<script src="{{ asset('dist/js/app/ticketClient.js') }}"></script>
@endcan
@endsection