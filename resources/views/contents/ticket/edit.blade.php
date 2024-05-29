@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span>| {{ $ticket->no_ticket }}</span></h5>
                                
                                <form class="row g-3 mb-3" action="{{ route('ticket.update', ['id' => encrypt($ticket->id)]) }}" method="POST" enctype="multipart/form-data" onsubmit="return formValidation()">
                                    @method('put')
                                    @csrf
                                    <div class="col-md-1" hidden>
                                        <label for="no_ticket" class="form-label">No. Ticket</label>
                                        <input type="text" name="no_ticket" class="form-control text-capitalize bg-light @error('no_ticket') is-invalid @enderror" id="no_ticket" value="{{ $ticket->no_ticket }}" hidden>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('no_ticket')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="user_id" class="form-label">Client</label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                                            <option selected disabled>Choose...</option>
                                            @foreach($users as $user)
                                                @if(old('user_id', $ticket->user_id) == $user->id)
                                                <option selected value="{{ $user->id }}">{{ ucwords($user->nama) }}</option>
                                                @else
                                                <option value="{{ $user->id }}">{{ ucwords($user->nama) }}</option>
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
                                        <input type="text" name="location_id" class="form-control text-capitalize bg-light @error('location_id') is-invalid @enderror" id="location_id" value="{{ old('location_id', $ticket->location_id) }}" readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location" class="form-label">Lokasi</label>
                                        <input type="text" name="location" class="form-control text-capitalize bg-light @error('location') is-invalid @enderror" id="locationName" value="{{ old('location', $ticket->location->nama_lokasi) }}" disabled>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="asset_id" class="form-label">Asset</label>
                                        <select class="form-select @error('asset_id') is-invalid @enderror" name="asset_id" id="asset_id">
                                            <option value="" disabled>Choose...</option>
                                            @foreach($assets as $asset)
                                            @if(old('asset_id', $ticket->asset_id) == $asset->id)
                                            <option selected value="{{ $asset->id }}">{{ ucwords($asset->no_asset) }} | {{ ucwords($asset->nama_barang) }} | {{ $asset->merk }}</option>
                                            @else
                                            <option value="{{ $asset->id }}">{{ ucwords($asset->no_asset) }} | {{ ucwords($asset->nama_barang) }} | {{ $asset->merk }}</option>
                                            @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('asset_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

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
                                                                assetDropdown.append('<option selected disabled>Choose...</option>');
                                                                $.each(response, function (key, value) {
                                                                    assetDropdown.append('<option value="' + value.id + '">' + value.no_asset + ' | ' + value.nama_barang + ' | ' + value.merk + '</option>');
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
                                    
                                    <div class="col-md-3">
                                        <label for="ticket_for" class="form-label">Diajukan Kepada</label>
                                        <select class="form-select @error('ticket_for') is-invalid @enderror" name="ticket_for" id="ticket_for">
                                            <option value="" disabled>Choose...</option>
                                            @foreach($ticketFors as $ticketFor)
                                                @if(old('ticket_for', $ticket->ticket_for) == $ticketFor->location_id)
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
                                        <label for="kendala" class="form-label">Kendala</label>
                                        <input type="text" name="kendala" class="form-control text-capitalize @error('kendala') is-invalid @enderror" id="kendala" maxlength="35" value="{{ old('kendala', $ticket->kendala) }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('kendala')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="detail_kendala" class="form-label">Lampiran</label>
                                        <input type="file" name="file" id="file" accept=".jpeg, .jpg, .png, .gif, .doc, .docx, .pdf, .xls, .xlsx, .csv" class="form-control text-capitalize @error('file') is-invalid @enderror">
                                        <input type="text" name="old_file" value="{{ $ticket->file }}" hidden>

                                        <!-- Showing notification error for input validation -->
                                        @error('file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        @if ($ticket->file == NULL)
                                        Lampiran sebelumnya: Tidak ada
                                        @else
                                        Lampiran sebelumnya: <a href="#" data-bs-toggle="modal" data-bs-target="#lampiranModal">{{ $ticket->file }}</a>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label for="detail_kendala" class="form-label">Detail Kendala</label>
                                        <textarea name="detail_kendala" class="form-control @error('detail_kendala') is-invalid @enderror" id="detail_kendala" rows="3">{{ old('detail_kendala', $ticket->detail_kendala) }}</textarea>

                                        <!-- Showing notification error for input validation -->
                                        @error('detail_kendala')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                    </div>
                                </form><!-- End Input Form -->

                                <div class="modal fade" id="lampiranModal" tabindex="-1">
                                    @if($ticket->file == NULL)
                                    <div class="modal-dialog modal-dialog-centered">
                                    @else
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                    @endif
                                        <div class="modal-content" id="modalContent">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Lampiran Ticket - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-12">
                                                    @if($ticket->file == NULL)
                                                    <p class="text-center">Tidak ada lampiran...</p>
                                                    @else
                                                    <img src="{{ asset('uploads/ticket/' . $ticket->file) }}" class="rounded mx-auto d-block w-100" alt="...">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Lampiran Modal-->

                                <script>
                                    function formValidation(){
                                        var asset = document.getElementById('asset_id').value;
                                        var kendala = document.getElementById('kendala').value;
                                        var fileInput = document.getElementById('file');
                                        var maxSizeInBytes = 1024 * 1024; // 1 MB (sesuaikan dengan batas maksimum yang diinginkan)
                                        var detailKendala = document.getElementById('detail_kendala').value;

                                        if (asset.length == 0) {
                                            alert('Asset harus dipilih!');
                                            return false;
                                        }

                                        if (kendala.length < 5) {
                                            alert('Ketikkan kendala minimal 5 karakter!');
                                            return false;
                                        }
                                        
                                        if (fileInput.files.length > 0) {
                                            var fileSizeInBytes = fileInput.files[0].size;
                                            var fileSizeInMB = fileSizeInBytes / (1024 * 1024);

                                            if (fileSizeInBytes > maxSizeInBytes) {
                                            alert('Ukuran file melebihi batas maksimum. Batas: ' + maxSizeInBytes / (1024 * 1024) + ' MB');
                                            return false;
                                            } 
                                        }

                                        if (detailKendala.length < 10) {
                                            alert('Ketikkan detail kendala minimal 10 karakter!');
                                            return false;
                                        }

                                        var lanjut = confirm('Apakah anda yakin data yang di input sudah sesuai?');

                                        if(lanjut){
                                            return true;
                                        }else{
                                            return false;
                                        }
                                    }
                                </script>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection