@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/users"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-person-check me-2"></i>{{ $title }}</h5>
                                
                                <a href="users/create"><button type="button" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</button></a>

                                <div class="table-responsive mt-2">
                                    <table class="table datatable table-hover">
                                        <thead class="bg-light" style="height: 45px;font-size:14px;">
                                            <tr>
                                            <th scope="col">NIK</th>
                                            <th scope="col">NAMA LENGKAP</th>
                                            <th scope="col">JABATAN</th>
                                            <th scope="col">LOKASI</th>
                                            <th scope="col">ROLE</th>
                                            <th scope="col">TELP/EXT</th>
                                            <th scope="col">IP ADDRESS</th>
                                            <th scope="col">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                            @foreach($users as $user)
                                            <tr>
                                            <td>{{ $user->nik }}</td>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->position->nama_jabatan }}</td>
                                            <td>{{ $user->location->nama_lokasi }}</td>
                                            <td>{{ $user->role->role_name }}</td>
                                            <td>{{ $user->telp }}</td>
                                            <td>{{ $user->ip_address }}</td>
                                            <td class="dropdown">
                                                <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                                                <ul class="dropdown-menu">
                                                    {{-- Tombol Edit --}}
                                                    <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('user.edit', ['id' => encrypt($user->id)]) }}"><i class="bi bi-pencil-square text-warning"></i>
                                                        Edit</a>
                                                    </li>

                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('user.delete', ['id' => encrypt($user->id)]) }}" onsubmit="return confirmAction()" method="POST">
                                                    @method('put')
                                                    @csrf
                                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                                    <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bx bx-trash text-danger"></i>Hapus</button></li>
                                                    </form>
                                                </ul>
                                            </td>
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
            var lanjut = confirm('Apakah anda yakin ingin menghapus user tersebut?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection