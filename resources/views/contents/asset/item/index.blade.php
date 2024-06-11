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
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-gem me-2"></i>{{ $title }}</h5>
                                
                                <a href="/asset-items/create"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Create</button></a>

                                <table class="table datatable table-hover">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">ITEM NAME</th>
                                        <th scope="col">UOM</th>
                                        <th scope="col">ASSET CATEGORY</th>
                                        <th scope="col">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($items as $item)
                                        <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->uom }}</td>
                                        <td>{{ $item->category_asset->nama_kategori }}</td>
                                        <td class="text-capitalize"><a href="{{ route('item.edit', ['id' => encrypt($item->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
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