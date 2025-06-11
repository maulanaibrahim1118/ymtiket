@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                    @if(auth()->user()->role_id == 1)
                    @include('contents.dashboard.partials.service_desk')
                    @endif

                    @if(auth()->user()->role_id == 2)
                    @include('contents.dashboard.partials.agent')
                    @endif

                    @can('isClient')
                    @include('contents.dashboard.partials.client')
                    @endcan
                    
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>

    <script src="{{ asset('dist/js/refresh-page-interval.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const rows = document.querySelectorAll(".clickable-row");
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    window.location.href = row.dataset.href;
                });
            });
        });
    </script>
@endsection