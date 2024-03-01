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
                                
                                <form class="row g-3 mb-3" action="/category-sub-tickets" method="POST">
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
                                        <select class="form-select @error('category_ticket_id') is-invalid @enderror" name="category_ticket_id" id="category_ticket_id" value="{{ old('category_ticket_id') }}">
                                            <option selected disabled>Choose...</option>
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

                                    <div class="col-md-2">
                                        <label for="asset_change" class="form-label">Asset Change</label>
                                        <select class="form-select @error('asset_change') is-invalid @enderror" name="asset_change" id="asset_change">
                                            <option selected disabled>Choose...</option>
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