@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card pb-3">
                            <div class="filter w-50">
                                <div class="row float-end">
                                    <form class="search-form d-flex align-items-center" action="{{ route('reportAgent.filter') }}" method="POST">
                                        @csrf
                                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm select2 me-2" value="{{ old('start_date', $filterArray[0]) }}">
                                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm me-2" value="{{ old('end_date', $filterArray[1]) }}">
                                        <button type="submit" class="btn btn-sm btn-primary px-3 me-3">Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2"></i>{{ $title }}</h5>
                            </div>
                            <div class="accordion accordion-flush px-4" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Report #1 | Jumlah Ticket
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    @include('contents.report.agent.partials.report1')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Report #2 | Rata-Rata Waktu Pending & Resolved
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    @include('contents.report.agent.partials.report2')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                            Report #3 | Rata-Rata Ticket & Jam Kerja Harian
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    @include('contents.report.agent.partials.report3')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                            Report #4 | Rata-Rata Waktu Permintaan & Kendala
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    @include('contents.report.agent.partials.report4')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div><!-- End Card Body --> --}}
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    {{-- <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Waktu Proses Ticket</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report2')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Harian</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report3')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Waktu Pending Per Hari</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report4')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 --> --}}
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection