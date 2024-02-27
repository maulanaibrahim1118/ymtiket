@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card mb-4">
                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                
                                <div class="row g-3 mb-3 pt-3" style="font-size: 14px">
                                    <div class="col-md-2 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Tanggal/Waktu</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if($ticket->jam_kerja == "ya")
                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
                                        @elseif($ticket->jam_kerja == "tidak")
                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
                                        @endif
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="telp" class="form-label fw-bold">Telp/Ext</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="telp" class="form-label">: {{ $ticket->client->telp }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="no_ticket" class="form-label fw-bold">No. Ticket</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="no_ticket" class="form-label">: {{ $ticket->no_ticket }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="ip_address" class="form-label fw-bold">IP Address</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="ip_address" class="form-label">: {{ $ticket->client->ip_address }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="no_asset" class="form-label fw-bold">No. Asset</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="no_asset" class="form-label">: <a href="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}/{{ $ticket->asset_id }}">{{ $ticket->asset->no_asset }}</a></label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="estimated" class="form-label fw-bold">Waktu Estimasi</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="estimated" id="estimated" class="form-label">: {{ $ticket->estimated }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="client/lokasi" class="form-label fw-bold">Client/Lokasi</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if ($ticket->client->nama_client == $ticket->location->nama_lokasi)
                                        <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->client->nik) }} - {{ ucwords($ticket->client->nama_client) }} / Store</label>
                                        @else
                                        <label for="client/lokasi" class="form-label">: {{ ucwords($ticket->client->nama_client) }} / {{ ucwords($ticket->location->nama_lokasi) }}</label>
                                        @endif
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="agent" class="form-label fw-bold">Ditujukan Pada</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="kendala" class="form-label fw-bold">Kendala</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        <label for="kendala" class="form-label">: {{ ucwords($ticket->kendala) }}</label>
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="status" class="form-label fw-bold">Status</label>
                                    </div>
                                    <div class="col-md-4 m-0">
                                        @if($ticket->status == 'created')
                                        <label for="status" class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'onprocess')
                                        <label for="status" class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'pending')
                                        <label for="status" class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'resolved')
                                        <label for="status" class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
                                        @elseif($ticket->status == 'finished')
                                        <label for="status" class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
                                        @endif
                                    </div>
                                    <div class="col-md-2 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Detail Kendala</label>
                                    </div>
                                    <div class="col-md-10 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->detail_kendala }}</label>
                                    </div>

                                    <div class="col-md-12">
                                        @if($ext == "xlsx")
                                        <a href="{{ asset('uploads/' . $ticket->file) }}"><button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark me-1"></i> Lampiran</button></a>
                                        @else
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="lampiranButton" data-bs-toggle="modal" data-bs-target="#lampiranModal"><i class="bi bi-file-earmark me-1"></i> Lampiran</button>
                                        @endif
                                        
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
                                                            <img src="{{ asset('uploads/' . $ticket->file) }}" class="rounded mx-auto d-block w-100" alt="...">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End Lampiran Modal-->
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                        
                                    <form class="row g-3" action="/ticket-details/process" method="POST">
                                    @csrf
                                    <div class="col-md-12 mb-0" style="font-size: 14px">
                                        <table class="table table-bordered">
                                            <thead class="fw-bold text-center">
                                                <tr>
                                                <td>Jenis Ticket*</td>
                                                <td>Kategori Ticket*</td>
                                                <td>Sub Kategori Ticket*</td>
                                                <td class="col-md-2">Biaya</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                <td>
                                                <select class="form-select @error('jenis_ticket') is-invalid @enderror" name="jenis_ticket" id="jenis_ticket" value="{{ old('jenis_ticket') }}">
                                                    <option selected disabled>Choose...</option>
                                                    @for($i=0; $i < count($types); $i++){
                                                        @if(old('jenis_ticket') == $types[$i])
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
                                                <select class="form-select @error('category_ticket_id') is-invalid @enderror" name="category_ticket_id" id="category_ticket_id">
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
                                                </td>
                                                <td>
                                                <select class="form-select @error('sub_category_ticket_id') is-invalid @enderror" name="sub_category_ticket_id" id="sub_category_ticket_id" disabled>
                                                    <option selected disabled>Choose...</option>
                                                </select>
                                                </td>
                                                <td>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1">IDR</span>
                                                    <input type="text" name="biaya" class="form-control text-capitalize @error('biaya') is-invalid @enderror" id="biaya" placeholder="0" value="{{ old('biaya') }}">
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
                                                    <td class="fw-bold text-center align-middle">Saran Tindakan*</td>
                                                    <td colspan="3">
                                                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" id="note" rows="3" placeholder="Sebutkan saran tindakan...">{{ old('note') }}</textarea>

                                                    <!-- Showing notification error for input validation -->
                                                    @error('note')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

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
                                    <input name="ticket_id" id="ticket_id" value="{{ $ticket->id }}" hidden>
                                    <input name="no_ticket" id="no_ticket" value="{{ $ticket->no_ticket }}" hidden>
                                    <input name="agent_id" id="agent_id" value="{{ $ticket->agent_id }}" hidden>
                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                    <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                                    <input type="text" name="url" value="{{ encrypt($ticket->id) }}" hidden>
                                    <input type="text" name="status" value="onprocess" hidden>
                                    <input type="text" name="process_at" value="{{ $ticket->process_at }}" hidden>

                                    <div class="col-md-6">
                                        (*) : Mandatory
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary float-end ms-1"><i class="bi bi-save me-1"></i> Simpan</button>
                                        <button type="reset" class="btn btn-warning float-end ms-1"><i class="bi bi-trash me-1"></i> Reset</button>
                                    </div>
                                    <form class="row g-3 mb-3" action="/tickets" method="POST">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End row -->
    </section>
@endsection