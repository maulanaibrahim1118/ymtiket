@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-people me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/locations" method="POST">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                                        <input type="text" name="nama_lokasi" class="form-control text-capitalize @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" value="{{ old('nama_lokasi') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nama_lokasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="wilayah" class="form-label">Wilayah</label>
                                        <select class="form-select @error('wilayah') is-invalid @enderror" name="wilayah" id="wilayah">
                                            <option selected disabled>Choose...</option>
                                            @foreach($wilayahs as $wilayah)
                                                @if(old('wilayah') == $wilayah->name)
                                                <option selected value="{{ $wilayah->name }}">{{ ucwords($wilayah->name) }}</option>
                                                @else
                                                <option value="{{ $wilayah->name }}">{{ ucwords($wilayah->name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('wilayah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="regional" class="form-label">Regional</label>
                                        <select class="form-select @error('regional') is-invalid @enderror" name="regional" id="regional">
                                            <option selected disabled>Choose...</option>
                                            @for($i=0; $i < count($regionals); $i++){
                                                @if(old('regional') == $regionals[$i])
                                                <option selected value="{{ $regionals[$i] }}">{{ ucwords($regionals[$i]) }}</option>
                                                @else
                                                <option value="{{ $regionals[$i] }}">{{ ucwords($regionals[$i]) }}</option>
                                                @endif
                                            }@endfor
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('regional')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="area" class="form-label">Area</label>
                                        <select class="form-select @error('area') is-invalid @enderror" name="area" id="area">
                                            <option selected disabled>Choose...</option>
                                            @for($i=0; $i < count($areas); $i++){
                                                @if(old('area') == $areas[$i])
                                                <option selected value="{{ $areas[$i] }}">{{ ucwords($areas[$i]) }}</option>
                                                @else
                                                <option value="{{ $areas[$i] }}">{{ ucwords($areas[$i]) }}</option>
                                                @endif
                                            }@endfor
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('area')
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
                                        <a href="/locations"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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