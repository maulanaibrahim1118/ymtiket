@extends('layouts.third')
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
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Tanggal/Waktu</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        @if($ticket->jam_kerja == "ya")
                                        <label for="tanggal" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
                                        @elseif($ticket->jam_kerja == "tidak")
                                        <label for="tanggal" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
                                        @endif
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Telp/Ext</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->client->telp }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">No. Ticket</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->no_ticket }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">IP Address</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->client->ip_address }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">No. Asset</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->asset->no_asset }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Waktu Estimasi</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->estimated }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Client/Lokasi</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->client->nama_client) }} / {{ ucwords($ticket->location->nama_lokasi) }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">PIC Agent</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->agent->nama_agent) }} - <i>{{ ucwords($ticket->agent->location->nama_lokasi) }}</i></label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Kendala</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: {{ ucwords($ticket->kendala) }}</label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Status</label>
                                    </div>
                                    <div class="col-md-5 m-0">
                                        <label for="tanggal" class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                    </div>
                                    <div class="col-md-1 m-0">
                                        <label for="tanggal" class="form-label fw-bold">Detail Kendala</label>
                                    </div>
                                    <div class="col-md-10 m-0">
                                        <label for="tanggal" class="form-label">: {{ $ticket->detail_kendala }}</label>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom mt-1 mb-0"></p>
                                    </div>
                        
                                    <div class="col-md-12" style="font-size: 14px">
                                        <table class="table table-bordered">
                                            <thead class="fw-bold text-center">
                                                <tr>
                                                <td>Kategori Ticket</td>
                                                <td>Sub Kategori Ticket</td>
                                                <td class="col-md-2">Biaya</td>
                                                <td class="col-md-5">Note</td>
                                                <td>Aksi</td>
                                                </tr>
                                            </thead>
                                            <tbody class="text-uppercase">
                                                <tr>
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
                                                    <select class="form-select @error('sub_category_ticket_id') is-invalid @enderror" name="sub_category_ticket_id" id="sub_category_ticket_id">
                                                        <option selected disabled>Choose...</option>
                                                        @foreach($sub_category_tickets as $sct)
                                                            @if(old('sub_category_ticket_id') == $ct->id)
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
                                                <td><input type="text" name="telp" pattern="[0-9]+" class="form-control text-capitalize @error('telp') is-invalid @enderror" id="telp" value="{{ old('telp') }}" maxlength="15" required></td>
                                                <td><input type="text" name="telp" pattern="[0-9]+" class="form-control @error('telp') is-invalid @enderror" id="telp" placeholder="Tuliskan catatan perbaikan..." value="{{ old('telp') }}" maxlength="15" required></td>
                                                <td class="text-center"><button type="button" class="btn btn-sm btn-success rounded-circle"><i class="bi bi-plus-lg"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#"><button type="button" class="btn btn-primary float-end ms-1"><i class="bi bi-save me-1"></i> Simpan</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End row -->
    </section>
@endsection