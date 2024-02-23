@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                    @if(auth()->user()->role == "service desk")
                    @include('contents.dashboard.partials.service_desk')
                    @elseif(auth()->user()->role == "agent")
                    @include('contents.dashboard.partials.agent')
                    @else
                    @include('contents.dashboard.partials.client')
                    @endif
                    
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection