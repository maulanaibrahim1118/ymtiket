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
                                
                                @can('isActor')
                                <a href="/category-sub-tickets/create"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>
                                @endcan

                                <div class="table-responsive mt-2">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                                <th scope="col">NAME</th>
                                                <th scope="col">CATEGORY</th>
                                                <th scope="col">TICKET TYPE</th>
                                                <th scope="col">ASSET CHANGE</th>
                                                <th scope="col">CREATED AT</th>
                                                <th scope="col">UPDATED AT</th>
                                                <th scope="col">UPDATED BY</th>
                                                @can('isActor')
                                                <th scope="col">ACTION</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($sub_category_tickets as $sct)
                                            <tr>
                                            <td>{{ $sct->nama_sub_kategori }}</td>
                                            <td>{{ $sct->category_ticket->nama_kategori }}</td>
                                            <td>{{ $sct->jenis_ticket }}</td>
                                            <td>{{ $sct->asset_change }}</td>
                                            <td>{{ $sct->created_at }}</td>
                                            <td>{{ $sct->updated_at }}</td>
                                            <td>{{ $sct->updated_by }}</td>
                                            @can('isActor')
                                            <td class="text-capitalize"><a href="{{ route('sct.edit', ['id' => encrypt($sct->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
                                            @endcan
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