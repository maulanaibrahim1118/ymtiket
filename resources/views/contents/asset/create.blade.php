@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-gem me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/assets" method="POST">
                                    @csrf
                                    <div class="col-md-2">
                                        <label for="nik" class="form-label">No. Asset</label>
                                        <input type="text" name="no_asset" class="form-control text-capitalize @error('no_asset') is-invalid @enderror" id="no_asset" value="{{ old('no_asset') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('no_asset')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="nama_barang" class="form-label">Nama Barang</label>
                                        <input type="text" name="nama_barang" class="form-control text-capitalize @error('nama_barang') is-invalid @enderror" id="nama_barang" value="{{ old('nama_barang') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('nama_barang')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="merk" class="form-label">Merk</label>
                                        <input type="text" name="merk" class="form-control text-capitalize @error('merk') is-invalid @enderror" id="merk" value="{{ old('merk') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('merk')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="model" class="form-label">Model</label>
                                        <input type="text" name="model" class="form-control text-capitalize @error('model') is-invalid @enderror" id="model" value="{{ old('model') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('model')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="serial_number" class="form-label">Serial Number</label>
                                        <input type="text" name="serial_number" class="form-control text-capitalize @error('serial_number') is-invalid @enderror" id="serial_number" value="{{ old('serial_number') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('serial_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="category_asset_id" class="form-label">Kategori</label>
                                        <select class="form-select @error('category_asset_id') is-invalid @enderror" name="category_asset_id" id="category_asset_id" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($category_assets as $ca)
                                                @if(old('category_asset_id') == $ca->id)
                                                <option selected value="{{ $ca->id }}">{{ ucwords($ca->nama_kategori) }}</option>
                                                @else
                                                <option value="{{ $ca->id }}">{{ ucwords($ca->nama_kategori) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('category_asset_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="location_id" value="{{ auth()->user()->location_id }}" hidden>
                                    <input type="text" name="status" value="digunakan" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/assets"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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