@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/assets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-box2 me-2"></i>{{ $title }}</h5>
                                
                                <a href="/assets/create"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <table class="table datatable table-hover">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NO. ASSET</th>
                                        <th scope="col">KATEGORI</th>
                                        <th scope="col">NAMA BARANG</th>
                                        <th scope="col">MERK</th>
                                        <th scope="col">MODEL</th>
                                        <th scope="col">SERIAL NUMBER</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">LOKASI</th>
                                        <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($assets as $asset)
                                        <tr>
                                        <td><a href="/tickets/asset{{ encrypt($asset->id) }}">{{ $asset->no_asset }}</a></td>
                                        <td>{{ $asset->category_asset->nama_kategori }}</td>
                                        <td>{{ $asset->nama_barang }}</td>
                                        <td>{{ $asset->merk }}</td>
                                        <td>{{ $asset->model }}</td>
                                        <td>{{ $asset->serial_number }}</td>
                                        @if($asset->status == "digunakan")
                                        <td><span class="badge bg-success">{{ $asset->status }}</span></td>
                                        @else
                                        <td><span class="badge bg-secondary">{{ $asset->status }}</span></td>
                                        @endif
                                        <td>{{ $asset->location->nama_lokasi }}</td>
                                        <td class="text-capitalize"><a href="/assets/{{ $asset->id }}/edit" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
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