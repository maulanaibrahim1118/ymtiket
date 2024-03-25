@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-2">
                                @if($pathFilter == "[Semua Agent] - [Semua Periode]" OR $pathFilter == "[Semua Periode]")
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                @else
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| {{ $pathFilter }} </span></h5>
                                @endif
                                
                                @can('isServiceDesk')
                                @include('contents.ticket.partials.service_desk_content')
                                @endcan

                                @can('isAgent')
                                @include('contents.ticket.partials.agent_content')
                                @endcan

                                @can('isClient')
                                @include('contents.ticket.partials.client_content')
                                @endcan
                                
                                <div class="col-md-12 border-top mb-3"></div>
                                <div class="col-md-12 mb-5">
                                    <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection