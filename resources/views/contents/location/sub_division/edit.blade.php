@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="{{ route('subDivision.update', ['id' => encrypt($subDivision->id)]) }}" method="POST">
                                    @method('put')
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Sub Division Name</label>
                                        <input type="text" name="name" class="form-control text-capitalize @error('name') is-invalid @enderror" id="name" value="{{ old('name', $subDivision->name) }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location_id" class="form-label">Division Name</label>
                                        <select class="form-select @error('location_id') is-invalid @enderror" name="location_id" id="location" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($locations as $location)
                                            @if(old('location_id', $subDivision->location_id) == $location->id)
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

                                    <div class="col-md-2">
                                        <label for="code_access" class="form-label">Code Access</label>
                                        <select class="form-select @error('code_access') is-invalid @enderror" name="code_access" id="code_access" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @if(old('code_access', $subDivision->code_access) == "tidak ada")
                                                <option selected value="tidak ada">Tidak Ada</option>
                                                @for($i=0; $i < count($codes); $i++){
                                                <option value="{{ $codes[$i] }}">{{ ucwords($codes[$i]) }}</option>
                                                }@endfor
                                            @else
                                                <option value="tidak ada">Tidak Ada</option>
                                                @for($i=0; $i < count($codes); $i++){
                                                    @if(old('code_access', $subDivision->code_access) == $codes[$i])
                                                    <option selected value="{{ $codes[$i] }}">{{ ucwords($codes[$i]) }}</option>
                                                    @else
                                                    <option value="{{ $codes[$i] }}">{{ ucwords($codes[$i]) }}</option>
                                                    @endif
                                                }@endfor
                                            @endif
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('code_access')
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
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="bi bi-info-circle-fill me-2"></i>About Code Access
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="card-body">
                                                            Code Access adalah kode yang digunakan untuk menentukan pic ticket Agent. Berikut adalah penjelasan lebih lanjut mengenai pilihan kode yang tersedia :<br><br>
                                                            1. <b>Tidak Ada </b>: Khusus untuk Divisi/Sub Divisi yang tidak memiliki Agent maupun Service Desk.<br>
                                                            2. <b>All </b>: Agent dari Sub Divisi tersebut bisa mendapatkan ticket dari Head Office maupun Cabang.<br>
                                                            3. <b>Ho </b>: Agent dari Sub Divisi tersebut hanya mendapatkan ticket dari Head Office Saja.<br>
                                                            4. <b>Store </b>: Agent dari Sub Divisi tersebut hanya mendapatkan ticket dari Cabang saja.<br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Save</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/location-sub-divisions"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
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