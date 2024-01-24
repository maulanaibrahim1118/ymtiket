@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card success-card">
        
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
        
                            <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                            <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                            </ul>
                        </div>
        
                        <div class="card-body">
                            <h5 class="card-title">Total Ticket</h5>
        
                            <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $agent->total_ticket }}</h6>
                                <span class="text-success small pt-1 fw-bold">Ticket</span>
                            </div>
                            </div>
                        </div>
        
                        </div>
                    </div><!-- End Success Card -->
                    
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card warning-card">
        
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
        
                            <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                            <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                            </ul>
                        </div>
        
                        <div class="card-body">
                            <h5 class="card-title">Total Workload</h5>
        
                            <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $agent->total_resolved_time }}</h6>
                                <span class="text-warning small pt-1 fw-bold">Menit</span>
                            </div>
                            </div>
                        </div>
        
                        </div>
                    </div><!-- End Warning Card -->
                    
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card primary-card">
        
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
        
                            <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                            <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                            </ul>
                        </div>
        
                        <div class="card-body">
                            <h5 class="card-title">Rata-Rata Resolved</h5>
        
                            <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $agent->rate }}</h6>
                                <span class="text-primary small pt-1 fw-bold">Menit</span>
                            </div>
                            </div>
                        </div>
        
                        </div>
                    </div><!-- End Primary Card -->

                    <div class="col-12">
                        <div class="card info-table">
        
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
            
                                <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                                <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                                <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                                </ul>
                            </div>
            
                            <div class="card-body">
                                <h5 class="card-title">Ticket On Process</h5>
            
                                <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                    <th scope="col">TANGGAL</th>
                                    <th scope="col">NO. TICKET</th>
                                    <th scope="col">KENDALA</th>
                                    <th scope="col">LOKASI</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                    <tr>
                                    <th scope="row"><a href="#">{{ $ticket->created_at }}</a></th>
                                    <td>{{ $ticket->no_ticket }}</td>
                                    <td>{{ $ticket->kendala }}</td>
                                    <td>{{ $ticket->client->location->nama_lokasi }}</td>
                                    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
                                    <td class="dropdown">
                                    <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item text-capitalize" href="#"><i class="bi bi-file-text text-primary"></i>Detail</a></li>
                                        <li><a class="dropdown-item text-capitalize" href="#"><i class="bi bi-arrow-repeat text-success"></i>Proses</a></li>
                                        <li><a class="dropdown-item text-capitalize" href="#"><i class="bx bx-share text-warning"></i>Assign</a></li>
                                        </ul>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- End Info Table -->
                    
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection