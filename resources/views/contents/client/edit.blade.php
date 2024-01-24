@extends('layouts.third')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-people me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/clients/{{ $client->id }}" method="POST">
                                    @method('put')
                                    @csrf
                                    <div class="col-md-1">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" name="nik" class="form-control text-capitalize @error('nik') is-invalid @enderror" id="nik" value="{{ old('nik', $client->nik) }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nik')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="nama_client" class="form-label">Nama Client</label>
                                        <input type="text" name="nama_client" class="form-control text-capitalize @error('nama_client') is-invalid @enderror" id="nama_client" value="{{ old('nama_client', $client->nama_client) }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('nama_client')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label for="position_id" class="form-label">Jabatan</label>
                                        <!-- Showing notification error for input validation -->
                                        @error('position_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                        <select class="form-select @error('position_id') is-invalid @enderror" name="position_id" id="position_id">
                                            <option selected disabled>Choose...</option>
                                            @foreach($positions as $position)
                                                @if(old('position_id', $client->position_id) == $position->id)
                                                <option selected value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                @else
                                                <option value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location_id" class="form-label">Lokasi</label>
                                        <!-- Showing notification error for input validation -->
                                        @error('location_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                        <select class="form-select @error('location_id') is-invalid @enderror" name="location_id" id="location_id">
                                            <option selected disabled>Choose...</option>
                                            @foreach($locations as $location)
                                                @if(old('location_id', $client->location_id) == $location->id)
                                                <option selected value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @else
                                                <option value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="telp" class="form-label">Telp/Ext</label>
                                        <input type="text" name="telp" class="form-control text-capitalize @error('telp') is-invalid @enderror" id="telp" value="{{ old('telp', $client->telp) }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('telp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="ip_address" class="form-label">IP Address</label>
                                        <input type="text" name="ip_address" class="form-control text-capitalize @error('ip_address') is-invalid @enderror" id="ip_address" value="{{ old('ip_address', $client->ip_address) }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('ip_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/clients"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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