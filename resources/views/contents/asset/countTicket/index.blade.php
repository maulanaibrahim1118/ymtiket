@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            @if($url == "")
                            <div class="filter">
                                <a class="icon" href="/assets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->
                            @endif

                            <div class="card-body pb-0">
                                @if($path2 == $path)
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| All </span></h5>
                                @else
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| {{ $path2 }} </span></h5>
                                @endif
                                
                                <table class="table datatable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NO. ASSET</th>
                                        <th scope="col">NAMA BARANG</th>
                                        <th scope="col">MERK</th>
                                        <th scope="col">MODEL</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">LOKASI</th>
                                        <th scope="col">TOTAL KENDALA</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($tickets as $ticket)
                                        <tr>
                                        <td>{{ $ticket->asset->no_asset }}</td>
                                        <td>{{ $ticket->asset->nama_barang }}</td>
                                        <td>{{ $ticket->asset->merk }}</td>
                                        <td>{{ $ticket->asset->model }}</td>
                                        <td>{{ $ticket->asset->status }}</td>
                                        <td>{{ $ticket->asset->location->nama_lokasi }}</td>
                                        <td><a href="/tickets/asset{{ encrypt($ticket->asset_id) }}">{{ $ticket->asset_count }}</a></td>
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