@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/locations"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                                
                                <a href="/locations/create"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <div class="table-responsive mt-2">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                                <th scope="col">NAMA LOKASI</th>
                                                <th scope="col">WILAYAH</th>
                                                <th scope="col">REGIONAL</th>
                                                <th scope="col">AREA</th>
                                                <th scope="col">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($locations as $location)
                                            <tr>
                                                <td>{{ $location->nama_lokasi }}</td>
                                                <td>{{ $location->wilayah }}</td>
                                                <td>{{ $location->regional }}</td>
                                                <td>{{ $location->area }}</td>
                                                <td class="text-capitalize"><a href="{{ route('location.edit', ['id' => encrypt($location->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection