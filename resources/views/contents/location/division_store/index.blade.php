@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/locations"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                                
                                <a href="/locations/create"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <div class="col-md-12 pb-3">
                                    <div class="accordion mt-4" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <i class="bi bi-shop me-2"></i> Store List
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="card-body">
                                                        <div class="table-responsive mt-2">
                                                            <table class="table datatable table-hover">
                                                                <thead class="bg-light" style="height: 45px;font-size:14px;">
                                                                    <tr>
                                                                        <th scope="col">SITE</th>
                                                                        <th scope="col">NAMA CABANG</th>
                                                                        <th scope="col">NO. TELP</th>
                                                                        <th scope="col">IP ADDRESS</th>
                                                                        <th scope="col">WILAYAH</th>
                                                                        <th scope="col">REGIONAL</th>
                                                                        <th scope="col">AREA</th>
                                                                        <th scope="col">STATUS</th>
                                                                        <th scope="col">AKSI</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                                                    @foreach($stores as $store)
                                                                    <tr>
                                                                        <td>{{ $store->site }}</td>
                                                                        <td>{{ $store->nama_lokasi }}</td>
                                                                        <td>{{ $store->user->telp }}</td>
                                                                        <td>{{ $store->user->ip_address }}</td>
                                                                        <td>{{ $store->wilayah->name }}</td>
                                                                        <td>{{ $store->wilayah->regional->name }}</td>
                                                                        <td>{{ $store->wilayah->regional->area->name }}</td>
                                                                        @if($store->is_active == '1')
                                                                        <td><span class="badge bg-primary">Aktif</span></td>
                                                                        @else
                                                                        <td><span class="badge bg-secondary">Tutup</span></td>
                                                                        @endif
                                                                        <td class="dropdown">
                                                                            <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                                                                            <ul class="dropdown-menu">
                                                                                @if($store->is_active == 1)
                                                                                    {{-- Tombol Edit --}}
                                                                                    <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('location.edit', ['id' => encrypt($store->id)]) }}"><i class="bi bi-pencil-square text-warning"></i>
                                                                                        Edit</a>
                                                                                    </li>
                                
                                                                                    {{-- Tombol Hapus --}}
                                                                                    <form action="{{ route('location.close', ['id' => encrypt($store->id)]) }}" onsubmit="return confirmAction()" method="POST">
                                                                                    @method('put')
                                                                                    @csrf
                                                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                                                    <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bi bi-x-circle text-danger"></i>Tutup</button></li>
                                                                                    </form>
                                                                                @else
                                                                                    {{-- Tombol Aktifkan --}}
                                                                                    {{-- Tombol Hapus --}}
                                                                                    <form action="{{ route('location.activate', ['id' => encrypt($store->id)]) }}" method="POST">
                                                                                        @method('put')
                                                                                        @csrf
                                                                                        <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                                                        <li><button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-x-circle text-primary"></i>Aktifkan</button></li>
                                                                                    </form>
                                                                                @endif
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                    <i class="bi bi-building me-2"></i> Division List
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="card-body">
                                                        <div class="table-responsive mt-2">
                                                            <table class="table datatable table-hover">
                                                                <thead class="bg-light" style="height: 45px;font-size:14px;">
                                                                    <tr>
                                                                        <th scope="col">NAMA DIVISI</th>
                                                                        <th scope="col">LOKASI</th>
                                                                        <th scope="col">AKSI</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                                                    @foreach($divisions as $division)
                                                                    <tr>
                                                                        <td>{{ $division->nama_lokasi }}</td>
                                                                        <td>{{ $division->wilayah->name }}</td>
                                                                        <td class="text-capitalize"><a href="{{ route('location.edit', ['id' => encrypt($division->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>

    <script>
        function confirmAction(event) {
            var lanjut = confirm('Apakah anda yakin cabang ini akan di tutup?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection