<div class="print-area d-none">
    <div class="row border border-3 border-secondary px-2 pt-2">
        <div class="col-12 text-center fs-4 fw-bold border-bottom border-1 border-secondary py-2 mb-3 bg-light">
            <div class="row">
                @if($ticket->location->wilayah_id == 1 || $ticket->location->wilayah_id == 2)
                <div class="col-1"><img style="height:32px;width:120px;margin-top:-5px;" src="{{ asset('dist/img/logo/logo5.png') }}" alt=""></div>
                @else
                <div class="col-1"><img style="height:32px;width:35px;margin-top:-5px;" src="{{ asset('dist/img/logo/logo3.png') }}" alt=""></div>
                @endif
                <div class="col-10"><b>FORM DETAIL TIKET KENDALA / PERMINTAAN</b></div>
                <div class="col-1"></div>
            </div>
            
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-3"><b>No. Ticket</b></div>
                <div class="col-9"><span style="margin-left:1px;">: <b>{{ $ticket->no_ticket }}</b></span></div>
                <div class="col-3"><b>Tgl Dibuat</b></div>
                <div class="col-9"><span style="margin-left:1px;">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }}</span></div>
                <div class="col-3"><b>Kendala</b></div>
                <div class="col-9"><span style="margin-left:1px;">: {{ ucwords($ticket->kendala) }}</span></div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                @if($ticket->location->wilayah_id !=1 && $ticket->location->wilayah_id !=2)
                <div class="col-4"><b>Cabang</b></div>
                <div class="col-8">: {{ $ticket->location->site }} - {{ ucwords($ticket->location->nama_lokasi) }}</span></div>
                @else
                <div class="col-4"><b>Divisi</b></div>
                <div class="col-8">: {{ ucwords($ticket->location->nama_lokasi) }}</span></div>
                @endif
                <div class="col-4"><b>Ditujukan Pada</b></div>
                <div class="col-8">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</div>
                <div class="col-4"><b>PIC Agent</b></div>
                <div class="col-8">: {{ ucwords($ticket->agent->nama_agent) }}</div>
            </div>
        </div>
        <div class="col-12"><b>Detail Kendala</b> : {{ ucfirst($ticket->detail_kendala) }}</div>
        <div class="col-12 border-bottom border-1 border-secondary my-3"></div>

        <div class="col-md-12" style="font-size: 14px">
            <p class="mb-2">Detail Barang :</p>

            <table class="table table-bordered border-secondary text-center">
                <thead class="fw-bold bg-light">
                    <tr>
                    <td>No. Asset</td>
                    <td>Nama Barang</td>
                    <td>Merk</td>
                    <td>Model</td>
                    <td>Serial Number</td>
                    </tr>
                </thead>
                <tbody class="text-capitalize">
                    <tr>
                    <td>{{ $ticket->asset->no_asset }}</td>
                    <td>{{ $ticket->asset->item->name }}</td>
                    <td>{{ $ticket->asset->merk }}</td>
                    <td>{{ $ticket->asset->model }}</td>
                    <td>{{ $ticket->asset->serial_number }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12 pb-0">
            <p style="font-size: 12px;">Tanggal Cetak : {{ date('d-m-Y H:i:s') }} | Source : {{ config('app.url') }}</p>
        </div>
    </div>
</div>