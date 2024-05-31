<div class="row">
    <div class="col-lg-3 col-md-4 label ">No. Induk Karyawan</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->nik) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label ">Nama Lengkap</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->nama) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Divisi</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->location->nama_lokasi ) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Sub Divisi / Lainnya</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->sub_divisi ) }}</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Jabatan</div>
    <div class="col-lg-9 col-md-8">: {{ ucwords(auth()->user()->position->nama_jabatan ) }}</div>
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