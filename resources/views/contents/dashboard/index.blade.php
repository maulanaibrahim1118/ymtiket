@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                    @can('isServiceDesk')
                    @include('contents.dashboard.partials.service_desk')
                    @endcan

                    @can('isAgent')
                    @include('contents.dashboard.partials.agent')
                    @endcan

                    @can('isClient')
                    @include('contents.dashboard.partials.client')
                    @endcan
                    
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection