@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/category-assets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-person-workspace me-2"></i>{{ $title }} <span>| {{ $subtitle }}</span></h5>
                                
                                @if($reqPage == "show_ticket")
                                @include('contents.report.agent.partials.show_ticket')
                                @else
                                @include('contents.report.agent.partials.show_detail_ticket')
                                @endif
                                
                                <div class="col-md-12 border-top mb-3"></div>
                                <div class="col-md-12 pb-5">
                                    <a href="/report-agents"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection