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
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ui-radios-grid me-2"></i>{{ $title }}</h5>
                                @else
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ui-radios-grid me-2"></i>{{ $title }} <span class="text-secondary">| {{ $pathFilter }} </span></h5>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                                <th scope="col">NAMA SUB KATEGORI</th>
                                                <th scope="col">KATEGORI</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($data as $data)
                                            <tr>
                                                <td>{{ $data->sub_category_ticket->nama_sub_kategori }}</td>
                                                <td>{{ $data->sub_category_ticket->category_ticket->nama_kategori }}</td>
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