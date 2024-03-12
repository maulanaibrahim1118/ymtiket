<div class="col-md-12 pb-0">
    <div class="card">

    <div class="filter">
        <a class="icon" href="#" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="bx bx-filter"></i></a>
        <div class="modal fade" id="filterModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="modalContent4">
                    <div class="modal-header">
                        <h5 class="modal-title">.:: Filter Report Dashboard</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/dashboard/filter/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" method="GET">
                    @csrf
                    <div class="modal-body">
                        <p>Pilih filter berdasarkan :</p>
                        <div class="row">
                        <input name="filter1" value="{{ $filterArray[0] }}" hidden>
                        <div class="col-md-6">
                            <select class="form-select" name="filter2" id="filter2">
                                <option value="">Semua Periode</option>
                                <option value="today">Hari Ini</option>
                                <option value="monthly">Bulan Ini</option>
                                <option value="yearly">Tahun Ini</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt me-2"></i>Terapkan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div><!-- End Filter Modal-->
    </div>
    <div class="card-body">
        @if($pathFilter == "Semua")
        <h5 class="card-title border-bottom"><i class="bi bi-house-door me-2"></i>Dashboard</h5>
        @else
        <h5 class="card-title border-bottom"><i class="bi bi-house-door me-2"></i>Dashboard <span>| {{ $pathFilter }}</span></h5>
        @endif
    </div>

    </div>
</div>

<div class="col-md-2">
    <a href="/tickets/{{ encrypt('all') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Total Ticket</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[0] }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-md-2">
    <a href="/tickets/{{ encrypt('selesai') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[1] }}</h6>
            <span class="text-primary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-md-2">
    <a href="/tickets/{{ encrypt('assign') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Tidak Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[2] }}</h6>
            <span class="text-danger small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Danger Card -->

<div class="col-md-3">
    <a href="#">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">Total Waktu Kerja</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-stopwatch"></i>
        </div>
        <div class="ps-3">
            @php
                $carbonInstance = \Carbon\Carbon::parse($dataArray[3]);
            @endphp
            @if($dataArray[3] >= 3600)
            <h6>{{ $carbonInstance->hour }} Jam</h6>
            <span class="text-warning small pt-1 fw-bold">{{ $carbonInstance->minute }} Menit | {{ $carbonInstance->second }} Detik</span>
            @elseif($dataArray[3] >= 60)
            <h6>{{ $carbonInstance->minute }} Menit</h6>
            <span class="text-warning small pt-1 fw-bold">{{ $carbonInstance->second }} Detik</span>
            @else
            <h6>{{ $carbonInstance->second }}</h6>
            <span class="text-warning small pt-1 fw-bold">Detik</span>
            @endif
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Warning Card -->

<div class="col-md-3">
    <a href="#">
    <div class="card info-card success-card">

    <div class="card-body">
        <h5 class="card-title">Rata-Rata Ticket Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-stopwatch"></i>
        </div>
        <div class="ps-3">
            @php
                $carbonInstance = \Carbon\Carbon::parse($dataArray[4]);
            @endphp
            @if($dataArray[4] >= 3600)
            <h6>{{ $carbonInstance->hour }} Jam</h6>
            <span class="text-success small pt-1 fw-bold">{{ $carbonInstance->minute }} Menit | {{ $carbonInstance->second }} Detik</span>
            @elseif($dataArray[4] >= 60)
            <h6>{{ $carbonInstance->minute }} Menit</h6>
            <span class="text-success small pt-1 fw-bold">{{ $carbonInstance->second }} Detik</span>
            @else
            <h6>{{ $carbonInstance->second }}</h6>
            <span class="text-success small pt-1 fw-bold">Detik</span>
            @endif
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-12">
    <div class="card info-table">
        <div class="card-body">
            @if($pathFilter == "Semua")
            <h5 class="card-title">Ticket Belum Di Proses</h5>
            @else
            <h5 class="card-title">Ticket Belum Di Proses <span>| {{ $pathFilter }}</span></h5>
            @endif

            <table class="table datatable">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">NO. TICKET</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">DETAIL KENDALA</th>
                    <th scope="col">DIBUAT PADA</th>
                    <th scope="col">PIC</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($data1 as $data)
                    <tr>
                    <td>{{ $data->no_ticket }}</td>
                    <td>{{ $data->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $data->detail_kendala }}</td>

                    {{-- Kolom Dibuat Pada --}}
                    @if($data->jam_kerja == 'ya')
                    <td>{{ date('d-M-Y H:i:s', strtotime($data->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                    @else
                    <td>{{ date('d-M-Y H:i:s', strtotime($data->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                    @endif

                    {{-- Kolom PIC --}}
                    @if($data->agent->nama_agent == auth()->user()->nama)
                    <td>{{ $data->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $data->agent->nama_agent }}</td>
                    @endif

                    {{-- Kolom Status --}}
                    @if($data->status == 'created')
                    <td><span class="badge bg-secondary">{{ $data->status }}</span></td>
                    @elseif($data->status == 'onprocess')
                    <td><span class="badge bg-warning">{{ $data->status }}</span></td>
                    @elseif($data->status == 'pending')
                    <td><span class="badge bg-danger">{{ $data->status }}</span></td>
                    @elseif($data->status == 'resolved')
                    <td><span class="badge bg-primary">{{ $data->status }}</span></td>
                    @elseif($data->status == 'finished')
                    <td><span class="badge bg-success">{{ $data->status }}</span></td>
                    @endif

                    {{-- Kolom Keterangan --}}
                    @if($data->assigned == "ya" AND $data->status == "created" OR $data->assigned == "ya" AND $data->status == "pending")
                    <td><span class="badge bg-primary">direct assign</span></td>
                    @else
                        @if($data->is_queue == "ya" AND $data->status == "created")
                        <td><span class="badge bg-success">dalam antrian</span></td>
                        @elseif($data->is_queue == "tidak" AND $data->status == "created")
                            @if($data->role == "service desk")
                            <td><span class="badge bg-secondary">diluar antrian</span></td>
                            @else
                            <td></td>
                            @endif
                        @else
                        <td></td>
                        @endif
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Info Table -->

<div class="col-12">
    <div class="card info-table">
        <div class="card-body">
            @if($pathFilter == "Semua")
            <h5 class="card-title">Ticket Sedang Di Proses</h5>
            @else
            <h5 class="card-title">Ticket Sedang Di Proses <span>| {{ $pathFilter }}</span></h5>
            @endif

            <table class="table datatable">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">NO. TICKET</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">DETAIL KENDALA</th>
                    <th scope="col">DIBUAT PADA</th>
                    <th scope="col">PIC</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($data2 as $data)
                    <tr>
                    <td>{{ $data->no_ticket }}</td>
                    <td>{{ $data->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $data->detail_kendala }}</td>

                    {{-- Kolom Dibuat Pada --}}
                    @if($data->jam_kerja == 'ya')
                    <td>{{ date('d-M-Y H:i:s', strtotime($data->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                    @else
                    <td>{{ date('d-M-Y H:i:s', strtotime($data->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                    @endif

                    {{-- Kolom PIC --}}
                    @if($data->agent->nama_agent == auth()->user()->nama)
                    <td>{{ $data->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $data->agent->nama_agent }}</td>
                    @endif

                    {{-- Kolom Status --}}
                    @if($data->status == 'created')
                    <td><span class="badge bg-secondary">{{ $data->status }}</span></td>
                    @elseif($data->status == 'onprocess')
                    <td><span class="badge bg-warning">{{ $data->status }}</span></td>
                    @elseif($data->status == 'pending')
                    <td><span class="badge bg-danger">{{ $data->status }}</span></td>
                    @elseif($data->status == 'resolved')
                    <td><span class="badge bg-primary">{{ $data->status }}</span></td>
                    @elseif($data->status == 'finished')
                    <td><span class="badge bg-success">{{ $data->status }}</span></td>
                    @endif

                    {{-- Kolom Keterangan --}}
                    @if($data->assigned == "ya" AND $data->status == "created" OR $data->assigned == "ya" AND $data->status == "pending")
                    <td><span class="badge bg-primary">direct assign</span></td>
                    @else
                        @if($data->is_queue == "ya" AND $data->status == "created")
                        <td><span class="badge bg-success">dalam antrian</span></td>
                        @elseif($data->is_queue == "tidak" AND $data->status == "created")
                            @if($data->role == "service desk")
                            <td><span class="badge bg-secondary">diluar antrian</span></td>
                            @else
                            <td></td>
                            @endif
                        @else
                        <td></td>
                        @endif
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Info Table -->