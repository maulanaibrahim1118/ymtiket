@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/category-tickets"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                                
                                @can('isActor')
                                <a href="{{ route('wilayah.create') }}"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>
                                @endcan

                                <div class="table-responsive">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                                <th scope="col">WILAYAH NAME</th>
                                                <th scope="col">REGIONAL</th>
                                                <th scope="col">AREA</th>
                                                @can('isActor')
                                                <th scope="col">ACTION</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($wilayahs as $wilayah)
                                            <tr>
                                            <td>{{ $wilayah->name }}</td>
                                            <td>{{ $wilayah->regional->name }}</td>
                                            <td>{{ $wilayah->regional->area->name }}</td>
                                            @can('isActor')
                                            <td class="dropdown">
                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('wilayah.delete', ['id' => encrypt($wilayah->id)]) }}" onsubmit="return confirmAction()" method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                <button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger me-1"></i>Delete</button>
                                                </form>
                                            </td>
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

    <script>
        function confirmAction(event) {
            var lanjut = confirm('Are you sure want to delete this wilayah?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection