@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/clients"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-people me-2"></i>{{ $title }}</h5>
                                
                                <a href="/clients/create"><button type="button" class="btn btn-primary position-relative float-start me-2" style="margin-top: 6px"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <table class="table datatable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NIK/SITE</th>
                                        <th scope="col">NAMA CLIENT</th>
                                        <th scope="col">JABATAN</th>
                                        <th scope="col">LOKASI</th>
                                        <th scope="col">TELP/EXT</th>
                                        <th scope="col">IP ADDRESS</th>
                                        <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($clients as $client)
                                        <tr>
                                        <td>{{ $client->nik }}</td>
                                        <td>{{ $client->nama_client }}</td>
                                        <td>{{ $client->position->nama_jabatan }}</td>
                                        <td>{{ $client->location->nama_lokasi }}</td>
                                        <td>{{ $client->telp }}</td>
                                        <td>{{ $client->ip_address }}</td>
                                        <td class="text-capitalize"><a href="/clients/{{ $client->id }}/edit" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
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