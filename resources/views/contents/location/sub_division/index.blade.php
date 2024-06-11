@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/location-sub-divisions"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                                
                                <a href="{{ route('subDivision.create') }}"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <div class="table-responsive mt-2">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                                <th scope="col">SUB DIVISION NAME</th>
                                                <th scope="col">DIVISION NAME</th>
                                                <th scope="col">CODE ACCESS</th>
                                                <th scope="col">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($subDivisions as $subDivision)
                                            <tr>
                                                <td>{{ $subDivision->name }}</td>
                                                <td>{{ $subDivision->location->nama_lokasi }}</td>
                                                <td>{{ $subDivision->code_access }}</td>
                                                <td class="text-capitalize"><a href="{{ route('subDivision.edit', ['id' => encrypt($subDivision->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
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