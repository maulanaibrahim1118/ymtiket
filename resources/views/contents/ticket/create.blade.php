@extends('layouts.secondary')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/tickets" method="POST">
                                    @csrf
                                    <div class="col-md-1">
                                        <label for="no_ticket" class="form-label">No. Ticket</label>
                                        <input type="text" name="no_ticket" class="form-control text-capitalize bg-light @error('no_ticket') is-invalid @enderror" id="no_ticket" value="{{ old('no_ticket', 'T'.sprintf('%08d', $ticketNumber)) }}" readonly>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('no_ticket')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="client_id" class="form-label">Client</label>
                                        <select class="form-select @error('client_id') is-invalid @enderror" name="client_id" id="client_id">
                                            <option selected disabled>Choose...</option>
                                            @foreach($clients as $client)
                                                @if(old('client_id') == $client->id)
                                                <option selected value="{{ $client->id }}">{{ ucwords($client->nama_client) }}</option>
                                                @else
                                                <option value="{{ $client->id }}">{{ ucwords($client->nama_client) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('client_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3" hidden>
                                        <input type="text" name="location_id" class="form-control text-capitalize bg-light @error('location_id') is-invalid @enderror" id="location_id" value="{{ old('location_id') }}" readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location" class="form-label">Lokasi</label>
                                        <input type="text" name="location" class="form-control text-capitalize bg-light @error('location') is-invalid @enderror" id="location" value="{{ old('location') }}" disabled>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="asset_id" class="form-label">No. Asset</label>
                                        <select class="form-select @error('asset_id') is-invalid @enderror" name="asset_id" id="asset_id" disabled>
                                            <option selected disabled>Choose...</option>
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('asset_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <script>
                                        $('#client_id').change(function(){
                                            var client = $(this).val();
                                            var url = '{{ route("getClient", ":id") }}';
                                            url = url.replace(':id', client);
                                            $.ajax({
                                                url: url,
                                                type: 'get',
                                                dataType: 'json',
                                                success: function(response){
                                                    if(response != null){
                                                        $('#location_id').val(response.location_id);
                                                        var locationId = response.location_id;
                                                        var assetDropdown = $('#asset_id');

                                                        var Location = '{{ route("getLocation", ":id") }}';
                                                        url = Location.replace(':id', locationId);
                                                        $.ajax({
                                                            url: url,
                                                            type: 'get',
                                                            dataType: 'json',
                                                            success: function(response){
                                                                if(response != null){
                                                                    $('#location').val(response.nama_lokasi);
                                                                }
                                                            }
                                                        });

                                                        var Asset = '{{ route("getAssets", ":id") }}';
                                                        url = Asset.replace(':id', locationId);
                                                        $.ajax({
                                                            url: url,
                                                            type: 'get',
                                                            dataType: 'json',
                                                            success: function(response){
                                                                $.each(response, function (key, value) {
                                                                    assetDropdown.append('<option value="' + value.id + '">' + value.no_asset + '</option>');
                                                                });
                                                                // Aktifkan dropdown no. asset
                                                                assetDropdown.prop('disabled', false);
                                                            },
                                                            error: function (xhr, status, error) {
                                                                console.error(xhr.responseText);
                                                            }
                                                        });
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    
                                    <div class="col-md-2">
                                        <label for="ticket_for" class="form-label">Diajukan Kepada</label>
                                        <select class="form-select @error('ticket_for') is-invalid @enderror" name="ticket_for" id="ticket_for">
                                            <option selected disabled>Choose...</option>
                                            <option value="{{ old('10', 10) }}">Information Technology</option>
                                            <option value="{{ old('12', 12) }}">Inventory Control</option>
                                            <option value="{{ old('28', 28) }}">Project ME</option>
                                            <option value="{{ old('29', 29) }}">Project Sipil</option>
                                        </select>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('ticket_for')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-2">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kendala" class="form-label">Kendala</label>
                                        <input type="text" name="kendala" class="form-control text-capitalize @error('no_ticket') is-invalid @enderror" id="kendala" value="{{ old('kendala') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('kendala')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="detail_kendala" class="form-label">Detail Kendala</label>
                                        <textarea name="detail_kendala" class="form-control" id="detail_kendala" rows="3"></textarea>
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                                    <input type="text" name="url" value="{{ $url }}" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/tickets{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
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