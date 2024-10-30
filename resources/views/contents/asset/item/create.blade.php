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
                                
                                <form class="row g-3 mb-3" action="/asset-items" method="POST">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Item Name</label>
                                        <input type="text" name="name" class="form-control text-capitalize @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="uom" class="form-label">UOM</label>
                                        <input type="text" name="uom" class="form-control text-capitalize @error('uom') is-invalid @enderror" id="uom" value="{{ old('uom') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('uom')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="category_asset_id" class="form-label">Asset Category</label>
                                        <select class="form-select @error('category_asset_id') is-invalid @enderror" name="category_asset_id" id="category_asset_id" value="{{ old('category_asset_id') }}">
                                            <option selected disabled>Choose...</option>
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

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/asset-items"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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
            "#category_asset_id",
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