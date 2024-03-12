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
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ui-radios-grid me-2"></i>{{ $title }}</h5>
                                
                                <a href="/category-assets/create"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <table class="table datatable table-hover">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                            <th scope="col">NAMA KATEGORI</th>
                                            <th scope="col">CREATED AT</th>
                                            <th scope="col">UPDATED AT</th>
                                            <th scope="col">UPDATED BY</th>
                                            <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($category_assets as $ca)
                                        <tr>
                                        <td>{{ $ca->nama_kategori }}</td>
                                        <td>{{ $ca->created_at }}</td>
                                        <td>{{ $ca->updated_at }}</td>
                                        <td>{{ $ca->updated_by }}</td>
                                        <td class="text-capitalize"><a href="/category-assets/{{ $ca->id }}/edit" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
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