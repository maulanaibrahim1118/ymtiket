@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-person-check me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/users" method="POST">
                                    @csrf
                                    <div class="col-lg-2">
                                        <label for="nik" class="form-label">NIK / Site Cabang</label>
                                        <input type="text" name="nik" pattern="[0-9]+" class="form-control text-capitalize @error('nik') is-invalid @enderror" id="nik" value="{{ old('nik') }}" maxlength="8" title="Tolong di input dalam bentuk nomor." required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nik')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control text-capitalize @error('nama') is-invalid @enderror" id="nama" value="{{ old('nama') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control text-capitalize @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" value="{{ old('role') }}">
                                            <option selected disabled>Choose...</option>
                                            @for($i=0; $i < count($roles); $i++){
                                                @if(old('role') == $roles[$i])
                                                <option selected value="{{ $roles[$i] }}">{{ ucwords($roles[$i]) }}</option>
                                                @else
                                                <option value="{{ $roles[$i] }}">{{ ucwords($roles[$i]) }}</option>
                                                @endif
                                            }@endfor
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-5">
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="position_id" class="form-label">Jabatan</label>
                                        <select class="form-select @error('position_id') is-invalid @enderror" name="position_id" id="position_id" value="{{ old('position_id') }}">
                                            <option selected disabled>Choose...</option>
                                            @foreach($positions as $position)
                                                @if(old('position_id') == $position->id)
                                                <option selected value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                @else
                                                    @if($position->id == 5)
                                                    <option value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }} (khusus cabang)</option>
                                                    @else
                                                    <option value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('position_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location_id" class="form-label">Divisi / Cabang</label>
                                        <select class="form-select @error('location_id') is-invalid @enderror" name="location_id" id="location_id" value="{{ old('location_id') }}" required>
                                            <option selected disabled>Choose...</option>
                                            @foreach($locations as $location)
                                                @if(old('location_id') == $location->id)
                                                <option selected value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @else
                                                <option value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('location_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <script>
                                        $('#location_id').change(function(){
                                            var location_id = $(this).val();
                                            var sub_divisi = $('#sub_divisi');
                                            if (location_id == 10) {
                                                sub_divisi.empty();
                                                sub_divisi.append('<option selected disabled>Choose...</option>');
                                                sub_divisi.append('<option value="hardware maintenance">Hardware Maintenance</option>');
                                                sub_divisi.append('<option value="helpdesk">Helpdesk</option>');
                                                sub_divisi.append('<option value="infrastructur networking">Infrastructur Networking</option>');
                                                sub_divisi.append('<option value="tech support">Tech Support</option>');

                                                // Aktifkan dropdown sub_divisi
                                                sub_divisi.prop('disabled', false);
                                            } else {
                                                sub_divisi.empty();
                                                sub_divisi.append('<option selected value="none">Tidak Ada</option>');
                                                sub_divisi.prop('disabled', false);
                                            }
                                        });
                                    </script>

                                    <div class="col-md-2">
                                        <label for="sub_divisi" class="form-label">Sub Divisi</label>
                                        <select class="form-select @error('sub_divisi') is-invalid @enderror" name="sub_divisi" id="sub_divisi" disabled>
                                            <option selected disabled>Choose...</option>
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('sub_divisi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4"></div>

                                    <div class="col-md-2">
                                        <label for="nama_client" class="form-label">No. Telp/Ext</label>
                                        <input type="text" name="telp" pattern="[0-9]+" class="form-control text-capitalize @error('telp') is-invalid @enderror" id="telp" value="{{ old('telp') }}" maxlength="15" title="Tolong di input dalam bentuk nomor." required>

                                        <!-- Showing notification error for input validation -->
                                        @error('telp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="ipAddress" class="form-label me-2">IP Address :</label><br>
                                        <input type="text" name="ip_1" id="ip1" pattern="[0-9]+" class="form-control text-center d-inline" style="width:15%;margin-right:5px;" maxlength="3" title="Tolong di input dalam bentuk nomor." required><b>.</b>
                                        <input type="text" name="ip_2" id="ip2" pattern="[0-9]+" class="form-control text-center d-inline" style="width:15%;margin-right:5px;" maxlength="3" title="Tolong di input dalam bentuk nomor." required><b>.</b>
                                        <input type="text" name="ip_3" id="ip3" pattern="[0-9]+" class="form-control text-center d-inline" style="width:15%;margin-right:5px;" maxlength="3" title="Tolong di input dalam bentuk nomor." required><b>.</b>
                                        <input type="text" name="ip_4" id="ip4" pattern="[0-9]+" class="form-control text-center d-inline" style="width:15%;margin-right:5px;" maxlength="3" title="Tolong di input dalam bentuk nomor." required>
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/users"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                    </div>
                                </form><!-- End Input Form -->
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection