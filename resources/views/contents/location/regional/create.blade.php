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
                                
                                <form class="row g-3 mb-3" action="{{ route('regional.store') }}" method="POST">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Regional Name</label>
                                        <input type="text" name="name" pattern="\D*" title="Only letters are allowed!" class="form-control text-capitalize @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="area_id" class="form-label">Area</label>
                                        <select class="form-select @error('area_id') is-invalid @enderror" name="area_id" id="area_id" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($areas as $area)
                                                @if(old('area_id') == $area->id)
                                                <option selected value="{{ $area->id }}">{{ ucwords($area->name) }}</option>
                                                @else
                                                <option value="{{ $area->id }}">{{ ucwords($area->name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('area_id')
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
                                        <p class="m-0"><span class="badge bg-primary">*Note: Regional 1 => Regional A, Regional 2 => Regional B, etc.</span></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Save</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
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

@section('customScripts')
<script>
    $(document).ready(function () {
        const selectElements = [
            "#area_id",
        ];

        // Menginisialisasi select2 pada semua elemen dalam array
        selectElements.forEach(selector => {
            $(selector).select2({
                dropdownParent: $(selector).parent()
            });
        });
    });
</script>
@endsection