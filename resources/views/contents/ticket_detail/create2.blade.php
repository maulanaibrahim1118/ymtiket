@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>Ticket Details</h5>
                                
                                <div class="row g-3 mb-3" style="font-size: 14px">
                                    @include('contents.ticket_detail.partials.ticketInfo')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary">| {{ $ticket->no_ticket }}</h5>
                                
                                <div class="row g-3 mb-3" style="font-size: 14px">

                                    <div class="col-md-12" style="font-size: 14px">
                                        <p class="mb-2">Previous Ticket Processing Details :</p>
                                        @include('contents.ticket_detail.partials.detailTable')
                                    </div>

                                    <div class="col-md-12 mb-0" style="font-size: 14px">
                                        <form class="row" action="/ticket-details/process" method="POST" enctype="multipart/form-data" onsubmit="return formValidation()">
                                            @csrf
                                            <p class="mb-2">Your Ticket Processing Details :</p>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="fw-bold text-center">
                                                        <tr>
                                                        <td class="col-md-2">Type*</td>
                                                        <td class="col-md-4">Category*</td>
                                                        <td class="col-md-4">Sub Category*</td>
                                                        <td class="col-md-2">Cost</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                        <td>
                                                        <select class="form-select @error('jenis_ticket') is-invalid @enderror" name="jenis_ticket" id="jenis_ticket" value="{{ old('jenis_ticket') }}">
                                                            <option selected disabled>Choose...</option>
                                                            @for($i=0; $i < count($types); $i++){
                                                                @if(old('jenis_ticket', $td->jenis_ticket) == $types[$i])
                                                                <option selected value="{{ $types[$i] }}">{{ ucwords($types[$i]) }}</option>
                                                                @else
                                                                <option value="{{ $types[$i] }}">{{ ucwords($types[$i]) }}</option>
                                                                @endif
                                                            }@endfor
                                                        </select>
                
                                                        <!-- Showing notification error for input validation -->
                                                        @error('jenis_ticket')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                        </td>
                                                        <td>
                                                        <select class="form-select w-100 @error('category_ticket_id') is-invalid @enderror" name="category_ticket_id" id="category_ticket_id">
                                                            <option selected disabled>Choose...</option>
                                                            @foreach($category_tickets as $ct)
                                                                @if(old('category_ticket_id', $td->sub_category_ticket->category_ticket_id) == $ct->id)
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
                                                        </td>
                                                        <td>
                                                            <select class="form-select w-100 @error('sub_category_ticket_id') is-invalid @enderror" name="sub_category_ticket_id" id="sub_category_ticket_id">
                                                                <option selected disabled>Choose...</option>
                                                                @foreach($sub_category_tickets as $sct)
                                                                    @if(old('sub_category_ticket_id', $td->sub_category_ticket_id) == $sct->id)
                                                                    <option selected value="{{ $sct->id }}">{{ ucwords($sct->nama_sub_kategori) }}</option>
                                                                    @else
                                                                    <option value="{{ $sct->id }}">{{ ucwords($sct->nama_sub_kategori) }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                    
                                                            <!-- Showing notification error for input validation -->
                                                            @error('sub_category_ticket_id')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">IDR</span>
                                                            <input type="text" name="biaya" class="form-control text-capitalize @error('biaya') is-invalid @enderror" id="biaya" placeholder="0" value="{{ old('biaya', $td->biaya) }}">
                                                        </div>
                                                        </td>
                                                        <script>
                                                            $(document).ready(function(){
                                                                var harga = document.getElementById("biaya");
                                                                harga.addEventListener("keyup", function(e) {
                                                                    // tambahkan 'Rp.' pada saat form di ketik
                                                                    // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                                                                    harga.value = formatRupiah(this.value);
                                                                });

                                                                /* Fungsi formatRupiah */
                                                                function formatRupiah(angka, prefix) {
                                                                    var number_string = angka.replace(/[^.\d]/g, "").toString(),
                                                                    split = number_string.split("."),
                                                                    sisa = split[0].length % 3,
                                                                    harga = split[0].substr(0, sisa),
                                                                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                                                                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                                                                    if (ribuan) {
                                                                    separator = sisa ? "," : "";
                                                                    harga += separator + ribuan.join(",");
                                                                    }

                                                                    harga = split[1] != undefined ? harga + "." + split[1] : harga;
                                                                    return prefix == undefined ? harga : harga ? harga : "";
                                                                }
                                                            });
                                                        </script>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-center align-middle">Action*</td>
                                                            <td colspan="3">
                                                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" id="note" rows="3" placeholder="Type your action suggestion...">{{ old('note') }}</textarea>

                                                            <!-- Showing notification error for input validation -->
                                                            @error('note')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-center align-middle">Attach File</td>
                                                            <td colspan="3">
                                                                <input type="file" name="file" id="file" accept=".jpeg, .jpg, .png, .gif, .doc, .docx, .pdf, .xls, .xlsx, .csv, .zip, .rar" class="form-control text-capitalize @error('file') is-invalid @enderror" value="{{ old('file') }}">

                                                            <!-- Showing notification error for input validation -->
                                                            @error('file')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <input name="ticket_id" id="ticket_id" value="{{ encrypt($ticket->id) }}" hidden>
                                            <input name="agent_id" id="agent_id" value="{{ encrypt($ticket->agent_id) }}" hidden>
                                            <input name="process_at" id="process_at" value="{{ $ticket->process_at }}" hidden>
                                            
                                            <div class="col-md-12">
                                                <p class="border-bottom mt-1 mb-3"></p>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <b>(*)</b> : Mandatory
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary float-end ms-1"><i class="bi bi-save me-1"></i> Save</button>
                                                <button type="reset" class="btn btn-warning float-end ms-1"><i class="bi bi-trash me-1"></i> Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End row -->
    </section>

    {{-- Lampiran Modal --}}
    <div class="modal fade" id="lampiranModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" id="modalContent1">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Attachment - <span class="text-success">{{ $ticket->no_ticket}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <img src="{{ asset('uploads/ticket/' . $ticket->file) }}" class="rounded mx-auto d-block w-100" alt="...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- End Lampiran Modal-->

    <script>
        $('#category_ticket_id').change(function(){
            var category = $(this).val();
            var url = '{{ route("getSubCategoryTicket", ":id") }}';
            url = url.replace(':id', category);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    var subDropdown = $('#sub_category_ticket_id');
                    subDropdown.empty();
                    subDropdown.append('<option selected disabled>Choose...</option>');
                    $.each(response, function (key, value) {
                        subDropdown.append('<option class="text-capitalize" value="' + value.id + '">' + value.nama_sub_kategori + '</option>');
                    });
                    // Aktifkan dropdown no. asset
                    subDropdown.prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>

    <script>
        function formValidation(){
            var kendala = document.getElementById('sub_category_ticket_id').value;
            var tindakan = document.getElementById('note').value;
            var fileInput = document.getElementById('file');
            var maxSizeInBytes = 1024 * 1024;

            if (kendala.length == 0) {
                alert('Sub Kategori Ticket harus dipilih!');
                return false;
            }

            if (tindakan.length < 10) {
                alert('Action Suggestion must be at least 10 characters!');
                return false;
            }

            var fileSizeInBytes = fileInput.files[0].size;
            var fileSizeInMB = fileSizeInBytes / (1024 * 1024);

            if (fileSizeInBytes > maxSizeInBytes) {
            alert('File maximum size: ' + maxSizeInBytes / (1024 * 1024) + ' MB');
            return false;
            } 
            
            return true;
        }
    </script>
@endsection