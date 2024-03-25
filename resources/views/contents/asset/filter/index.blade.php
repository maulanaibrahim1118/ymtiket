@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-2">
                                @if($pathFilter == "[Semua Agent] - [Semua Periode]")
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                @else
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| {{ $pathFilter }} </span></h5>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                            <th scope="col">NO. ASSET</th>
                                            <th scope="col">NAMA BARANG</th>
                                            <th scope="col">MERK</th>
                                            <th scope="col">MODEL</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">LOKASI</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($tickets as $ticket)
                                            <tr>
                                            <td><a href="{{ route('ticket.asset', ['asset_id' => encrypt($ticket->asset->id)]) }}">{{ $ticket->asset->no_asset }}</a></td>
                                            <td>{{ $ticket->asset->nama_barang }}</td>
                                            <td>{{ $ticket->asset->merk }}</td>
                                            <td>{{ $ticket->asset->model }}</td>
                                            <td>{{ $ticket->asset->status }}</td>
                                            <td>{{ $ticket->asset->location->nama_lokasi }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 border-top mb-3"></div>
                                <div class="col-md-12">
                                    <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection