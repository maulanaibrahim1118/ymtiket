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
                                
                                <form class="row g-3 mb-3" action="/locations" method="POST">
                                    @csrf
                                    <div class="col-md-1">
                                        <label for="site" class="form-label">Site / Code</label>
                                        <input type="number" name="site" class="form-control text-capitalize @error('site') is-invalid @enderror" id="site" value="{{ old('site') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('site')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-1">
                                        <label for="initial" class="form-label">Initial</label>
                                        <input type="text" name="initial" class="form-control text-capitalize @error('initial') is-invalid @enderror" id="initial" maxlength="5" value="{{ old('initial') }}">
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('initial')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="nama_lokasi" class="form-label">Store / Division Name</label>
                                        <input type="text" name="nama_lokasi" class="form-control text-capitalize @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" value="{{ old('nama_lokasi') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nama_lokasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="telp" class="form-label">Phone / Ext</label>
                                        <input type="text" name="telp" pattern="[0-9]+" class="form-control text-capitalize @error('telp') is-invalid @enderror" id="telp" value="{{ old('telp') }}" maxlength="15" title="Tolong di input dalam bentuk nomor.">

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

                                    <div class="col-md-3">
                                        <label for="wilayah_id" class="form-label">Wilayah</label>
                                        <select class="form-select @error('wilayah_id') is-invalid @enderror" name="wilayah_id" id="wilayah" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($wilayahs as $wilayah)
                                                @if(old('wilayah_id') == $wilayah->id)
                                                <option selected value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
                                                @else
                                                <option value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('wilayah_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="regional" class="form-label">Regional</label>
                                        <input type="text" name="regional" class="form-control text-capitalize bg-light @error('regional') is-invalid @enderror" id="regional" value="{{ old('regional') }}" readonly>

                                        <!-- Showing notification error for input validation -->
                                        @error('regional')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="area" class="form-label">Area</label>
                                        <input type="text" name="area" class="form-control text-capitalize bg-light @error('area') is-invalid @enderror" id="area" value="{{ old('area') }}" readonly>

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
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Save</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/locations"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
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
            "#wilayah",
        ];

        // Menginisialisasi select2 pada semua elemen dalam array
        selectElements.forEach(selector => {
            $(selector).select2({
                dropdownParent: $(selector).parent()
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#wilayah').on('change', function () {
            var wilayahId = $(this).val();
            if (wilayahId) {
                $.ajax({
                    url: '/get-detail-wilayah/' + wilayahId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        if (data.regional) {
                            $('#regional').val(data.regional.name);
                            if (data.regional.area) {
                                $('#area').val(data.regional.area.name);
                            } else {
                                $('#area').val('');
                            }
                        } else {
                            $('#regional').val('');
                            $('#area').val('');
                        }
                    }
                });
            } else {
                $('#regional').val('');
                $('#area').val('');
            }
        });
    });
</script>
@endsection