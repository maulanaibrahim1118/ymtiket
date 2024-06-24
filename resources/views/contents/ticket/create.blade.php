@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="/tickets/store" method="POST" enctype="multipart/form-data" onsubmit="return formValidation()">
                                    @csrf

                                    <div class="col-md-3">
                                        <label for="user_id" class="form-label">Client</label>
                                        <select class="form-select select2 @error('user_id') is-invalid @enderror" name="user_id" id="user_id" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($users as $user)
                                                @if(old('user_id') == $user->id)
                                                    @if($user->location->wilayah_id == 1 || $user->location->wilayah_id == 2)
                                                    <option selected value="{{ $user->id }}">{{ ucwords($user->nama) }}</option>
                                                    @else
                                                    <option selected value="{{ $user->id }}">{{ $user->nik }} - {{ ucwords($user->nama) }}</option>
                                                    @endif
                                                @else
                                                    @if($user->location->wilayah_id == 1 || $user->location->wilayah_id == 2)
                                                    <option value="{{ $user->id }}">{{ ucwords($user->nama) }}</option>
                                                    @else
                                                    <option value="{{ $user->id }}">{{ $user->nik }} - {{ ucwords($user->nama) }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('user_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3" hidden>
                                        <input type="text" name="location_id" class="form-control text-capitalize bg-light @error('location_id') is-invalid @enderror" id="location_id" value="{{ old('location_id') }}" readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location" class="form-label">Store/Division</label>
                                        <input type="text" name="location" class="form-control text-capitalize bg-light @error('location') is-invalid @enderror" id="locationName" value="{{ old('location') }}" disabled>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="asset_id" class="form-label">Asset</label>
                                        <select class="form-select select2 @error('asset_id') is-invalid @enderror" name="asset_id" id="asset_id" disabled>
                                            <option selected value="" disabled>Choose...</option>
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('asset_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="ticket_for" class="form-label">Ticket For</label>
                                        <select class="form-select select2 @error('ticket_for') is-invalid @enderror" name="ticket_for" id="ticket_for" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($ticketFors as $ticketFor)
                                                @if(old('ticket_for') == $ticketFor->location_id)
                                                <option selected value="{{ $ticketFor->location_id }}">{{ ucwords($ticketFor->location->nama_lokasi) }}</option>
                                                @else
                                                <option value="{{ $ticketFor->location_id }}">{{ ucwords($ticketFor->location->nama_lokasi) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('ticket_for')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="kendala" class="form-label">Subject</label>
                                        <input type="text" name="kendala" class="form-control text-capitalize @error('kendala') is-invalid @enderror" id="kendala" maxlength="35" value="{{ old('kendala') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('kendala')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="detail_kendala" class="form-label">Attachment</label>
                                        <input type="file" name="file" id="file" accept=".jpeg, .jpg, .png, .gif, .doc, .docx, .pdf, .xls, .xlsx, .csv, .zip, .rar" class="form-control text-capitalize @error('file') is-invalid @enderror" value="{{ old('file') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    @can('isClient')
                                    <input type="text" name="source" id="source2" value="tidak ada" hidden>
                                    @endcan

                                    @can('isServiceDesk')
                                    <div class="col-md-2">
                                        <label for="source" class="form-label">Reference</label>
                                        <select class="form-select select2 @error('source') is-invalid @enderror" name="source" id="source" required>
                                            <option selected value="" disabled>Choose...</option>
                                            @foreach($source as $data)
                                                <option value="{{ $data }}">{{ ucwords($data) }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('source')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    @endcan

                                    <div class="col-md-12">
                                        <label for="detail_kendala" class="form-label">Details</label>
                                        <textarea name="detail_kendala" class="form-control @error('detail_kendala') is-invalid @enderror" id="detail_kendala" rows="3" required>{{ old('detail_kendala') }}</textarea>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('detail_kendala')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
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

    <script>
        $('#user_id').change(function(){
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
                                    $('#locationName').val(response.nama_lokasi);
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
                                assetDropdown.empty();
                                assetDropdown.append('<option selected value="" disabled>Choose...</option>');
                                $.each(response, function (key, value) {
                                    assetDropdown.append('<option class="text-capitalize" value="' + value.id + '">' + value.no_asset + ' | ' + value.nama_barang + ' | ' + value.merk + '</option>');
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

    <script>
        function formValidation(){
            var asset = document.getElementById('asset_id').value;
            var kendala = document.getElementById('kendala').value;
            var fileInput = document.getElementById('file');
            var maxSizeInBytes = 1024 * 1024; // 1 MB (sesuaikan dengan batas maksimum yang diinginkan)
            var detailKendala = document.getElementById('detail_kendala').value;

            if (asset.length == 0) {
                alert('Asset must be choosed!');
                return false;
            }

            if (kendala.length < 5) {
                alert('Submission must be at least 5 characters!');
                return false;
            }
            
            if (fileInput.files.length > 0) {
                var fileSizeInBytes = fileInput.files[0].size;
                var fileSizeInMB = fileSizeInBytes / (1024 * 1024);

                if (fileSizeInBytes > maxSizeInBytes) {
                alert('File maximum size: ' + maxSizeInBytes / (1024 * 1024) + ' MB');
                return false;
                } 
            }

            if (detailKendala.length < 10) {
                alert('Details must be at least 10 characters!');
                return false;
            }

            var lanjut = confirm('Are you sure the data entered is correct?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection