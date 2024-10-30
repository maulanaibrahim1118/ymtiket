@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ui-radios-grid me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/category-sub-tickets" method="POST" onsubmit="return confirmLanjut()">
                                    @csrf
                                    <div class="col-md-3">
                                        <label for="nama_sub_kategori" class="form-label">Nama Sub Kategori Ticket</label>
                                        <input type="text" name="nama_sub_kategori" class="form-control text-capitalize @error('nama_sub_kategori') is-invalid @enderror" id="nama_sub_kategori" value="{{ old('nama_sub_kategori') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nama_sub_kategori')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="category_ticket_id" class="form-label">Kategori Ticket</label>
                                        <select class="form-select @error('category_ticket_id') is-invalid @enderror" name="category_ticket_id" id="category_ticket_id" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($category_tickets as $ct)
                                                @if(old('category_ticket_id') == $ct->id)
                                                <option selected value="{{ $ct->id }}">{{ ucwords($ct->nama_kategori) }}</option>
                                                @else
                                                <option value="{{ $ct->id }}">{{ ucwords($ct->nama_kategori) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('category_ticket_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="jenis_ticket" class="form-label">Ticket Type</label>
                                        <select class="form-select @error('jenis_ticket') is-invalid @enderror" name="jenis_ticket" id="jenis_ticket" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($types as $type)
                                                @if(old('jenis_ticket') == $type['id'])
                                                <option selected value="{{ $type['id'] }}">{{ ucwords($type['name']) }}</option>
                                                @else
                                                <option value="{{ $type['id'] }}">{{ ucwords($type['name']) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('jenis_ticket')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="asset_change" class="form-label">Asset Change</label>
                                        <select class="form-select @error('asset_change') is-invalid @enderror" name="asset_change" id="asset_change" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @for($i=0; $i < count($assetChange); $i++){
                                                @if(old('asset_change') == $assetChange[$i])
                                                <option selected value="{{ $assetChange[$i] }}">{{ ucwords($assetChange[$i]) }}</option>
                                                @else
                                                <option value="{{ $assetChange[$i] }}">{{ ucwords($assetChange[$i]) }}</option>
                                                @endif
                                            }@endfor
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('asset_change')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="url" value="/category-sub-tickets/{{ encrypt(auth()->user()->location_id) }}" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="bi bi-info-circle-fill me-2"></i>Tentang Asset Change
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="card-body">
                                                            Asset Change adalah status yang digunakan untuk menandakan bahwa asset tersebut dapat/tidak dapat dipergunakan lagi, <b>apabila agent memilih Sub Kategori tersebut saat penanganan tiket hingga resolved</b>. Berikut adalah penjelasan lebih lanjut mengenai pilihan yang tersedia :<br><br>
                                                            1. <b>Ya </b>: Status Asset akan berubah menjadi tidak digunakan.<br>
                                                            2. <b>Tidak </b>: Status Asset tidak akan berubah (digunakan).<br>
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
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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
            "#category_ticket_id",
            "#jenis_ticket",
            "#asset_change",
        ];

        // Menginisialisasi select2 pada semua elemen dalam array
        selectElements.forEach(selector => {
            $(selector).select2({
                dropdownParent: $(selector).parent()
            });
        });
    });
</script>
<script>
    function confirmLanjut(){
        var asset_change = document.getElementById('asset_change').value;
        if(asset_change === 'ya'){
            var lanjut = confirm('Apakah anda yakin, Sub Category tersebut dapat merubah status Asset?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
</script>
@endsection