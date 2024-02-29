@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/category-sub-tickets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ui-radios-grid me-2"></i>{{ $title }}</h5>
                                
                                <a href="/category-sub-tickets/{{ encrypt(auth()->user()->location_id) }}/create"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <table class="table datatable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                            <th scope="col">KATEGORI</th>
                                            <th scope="col">NAMA SUB KATEGORI</th>
                                            <th scope="col">RATA-RATA RESOLVED</th>
                                            <th scope="col">CREATED AT</th>
                                            <th scope="col">UPDATED AT</th>
                                            <th scope="col">UPDATED BY</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($sub_category_tickets as $sct)
                                        <tr>
                                        <td>{{ $sct->category_ticket->nama_kategori }}</td>
                                        <td>{{ $sct->nama_sub_kategori }}</td>
                                        @php
                                            $average = \Carbon\Carbon::parse($sct->avg);
                                        @endphp
                                        @if( $sct->avg >= 3600)
                                        <td>{{ $average->hour }} Jam {{ $average->minute }} Menit {{ $average->second }} Detik</td>
                                        @elseif( $sct->avg >= 60)
                                        <td>{{ $average->minute }} Menit {{ $average->second }} Detik</td>
                                        @elseif( $sct->avg == 0)
                                        <td>0 Detik</td>
                                        @else
                                        <td>{{ $average->second }} Detik</td>
                                        @endif
                                        <td>{{ $sct->created_at }}</td>
                                        <td>{{ $sct->updated_at }}</td>
                                        <td>{{ $sct->updated_by }}</td>
                                        <td class="text-capitalize"><a href="/category-sub-tickets/{{ encrypt(auth()->user()->location_id) }}/edit{{ $sct->id }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection