@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Status Ticket</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report1')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    <div class="col-12">
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
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection