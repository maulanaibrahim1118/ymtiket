<div class="row">
    <div class="col-lg-3 col-md-4 label ">Site Cabang</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->nik) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label ">Nama Cabang</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->nama) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Wilayah</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->location->wilayah->name ) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Regional</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->location->wilayah->regional->name ) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Area</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->location->wilayah->regional->area->name ) }}</div>
</div>

<h5 class="card-title">Detail Kontak</h5>

<div class="row">
    <div class="col-lg-3 col-md-4 label">Telp/Ext</div>
    <div class="col-lg-9 col-md-8">: {{ auth()->user()->telp }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">IP Address</div>
    <div class="col-lg-9 col-md-8">: {{ auth()->user()->ip_address }}</div>
</div>