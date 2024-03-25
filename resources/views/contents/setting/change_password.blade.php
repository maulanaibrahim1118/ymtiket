@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bx bx-cog me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="{{ route('change.password') }}" method="POST">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="current_password" class="form-label">Password Lama</label>
                                        <input type="password" name="current_password" class="form-control text-capitalize @error('current_password') is-invalid @enderror" id="current_password" value="{{ old('current_password') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-8"></div>

                                    <div class="col-md-3">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <input type="password" name="new_password" class="form-control text-capitalize @error('new_password') is-invalid @enderror" id="new_password" value="{{ old('new_password') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('new_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation" class="form-control text-capitalize @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" value="{{ old('new_password_confirmation') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('new_password_confirmation')
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