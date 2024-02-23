<div class="col-md-12 pb-0">
    <div class="card">

    <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
            <h6>Filter</h6>
        </li>

        <li><a class="dropdown-item" href="/dashboard/{{ encrypt('today') }}/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">Hari Ini</a></li>
        <li><a class="dropdown-item" href="/dashboard/{{ encrypt('monthly') }}/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">Bulan Ini</a></li>
        <li><a class="dropdown-item" href="/dashboard/{{ encrypt('yearly') }}/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">Tahun Ini</a></li>
        </ul>
    </div>
    <div class="card-body">
        @if($path2 == $path)
        <h5 class="card-title border-bottom"><i class="bi bi-house-door me-2"></i>Dashboard <span class="text-secondary">| All</span></h5>
        @else
        <h5 class="card-title border-bottom"><i class="bi bi-house-door me-2"></i>Dashboard <span class="text-secondary">| {{ $path2 }}</span></h5>
        @endif
    </div>

    </div>
</div>

<div class="col-md-2">
    @if($path2 == "Hari Ini")
    <a href="/tickets/{{ encrypt('All') }}-{{ encrypt('today') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Bulan Ini")
    <a href="/tickets/{{ encrypt('All') }}-{{ encrypt('monthly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Tahun Ini")
    <a href="/tickets/{{ encrypt('All') }}-{{ encrypt('yearly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @else
    <a href="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @endif
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Total Ticket</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $total }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-md-2">
    @if($path2 == "Hari Ini")
    <a href="/tickets/{{ encrypt('Selesai') }}-{{ encrypt('today') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Bulan Ini")
    <a href="/tickets/{{ encrypt('Selesai') }}-{{ encrypt('monthly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Tahun Ini")
    <a href="/tickets/{{ encrypt('Selesai') }}-{{ encrypt('yearly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @else
    <a href="/tickets/{{ encrypt('Selesai') }}-{{ encrypt('all') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @endif
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $resolved }}</h6>
            <span class="text-primary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-md-2">
    @if($path2 == "Hari Ini")
    <a href="/tickets/{{ encrypt('Assign') }}-{{ encrypt('today') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Bulan Ini")
    <a href="/tickets/{{ encrypt('Assign') }}-{{ encrypt('monthly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @elseif($path2 == "Tahun Ini")
    <a href="/tickets/{{ encrypt('Assign') }}-{{ encrypt('yearly') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @else
    <a href="/tickets/{{ encrypt('Assign') }}-{{ encrypt('all') }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    @endif
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Assign</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $assigned }}</h6>
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
                $carbonInstance = \Carbon\Carbon::parse($workload);
            @endphp
            @if($workload >= 3600)
            <h6>{{ $carbonInstance->hour }} Jam</h6>
            <span class="text-warning small pt-1 fw-bold">{{ $carbonInstance->minute }} Menit</span>
            @elseif($workload >= 60)
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
                $carbonInstance = \Carbon\Carbon::parse($roundedAvg);
            @endphp
            @if($roundedAvg >= 3600)
            <h6>{{ $carbonInstance->hour }} Jam</h6>
            <span class="text-success small pt-1 fw-bold">{{ $carbonInstance->minute }} Menit | {{ $carbonInstance->second }} Detik</span>
            @elseif($roundedAvg >= 60)
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
            <h5 class="card-title">Ticket Belum Diproses</h5>

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
                    @foreach($newTicket as $nt)
                    <tr>
                    <td>{{ $nt->no_ticket }}</td>
                    <td>{{ $nt->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $nt->detail_kendala }}</td>

                    {{-- Kolom Dibuat Pada --}}
                    @if($nt->jam_kerja == 'ya')
                    <td>{{ date('d-M-Y H:i:s', strtotime($nt->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                    @else
                    <td>{{ date('d-M-Y H:i:s', strtotime($nt->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                    @endif

                    {{-- Kolom PIC --}}
                    @if($nt->agent->nama_agent == auth()->user()->nama)
                    <td>{{ $nt->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $nt->agent->nama_agent }}</td>
                    @endif

                    {{-- Kolom Status --}}
                    @if($nt->status == 'created')
                    <td><span class="badge bg-secondary">{{ $nt->status }}</span></td>
                    @elseif($nt->status == 'onprocess')
                    <td><span class="badge bg-warning">{{ $nt->status }}</span></td>
                    @elseif($nt->status == 'pending')
                    <td><span class="badge bg-danger">{{ $nt->status }}</span></td>
                    @elseif($nt->status == 'resolved')
                    <td><span class="badge bg-primary">{{ $nt->status }}</span></td>
                    @elseif($nt->status == 'finished')
                    <td><span class="badge bg-success">{{ $nt->status }}</span></td>
                    @endif

                    {{-- Kolom Keterangan --}}
                    @if($nt->assigned == "ya" AND $nt->status == "created" OR $nt->assigned == "ya" AND $nt->status == "pending")
                    <td><span class="badge bg-primary">direct assign</span></td>
                    @else
                        @if($nt->is_queue == "ya" AND $nt->status == "created")
                        <td><span class="badge bg-success">dalam antrian</span></td>
                        @elseif($nt->is_queue == "tidak" AND $nt->status == "created")
                            @if($nt->role == "service desk")
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
            <h5 class="card-title">Ticket Sedang Diproses</h5>

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
                    @foreach($onProcess as $op)
                    <tr>
                    <td>{{ $op->no_ticket }}</td>
                    <td>{{ $op->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $op->detail_kendala }}</td>

                    {{-- Kolom Dibuat Pada --}}
                    @if($op->jam_kerja == 'ya')
                    <td>{{ date('d-M-Y H:i:s', strtotime($op->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                    @else
                    <td>{{ date('d-M-Y H:i:s', strtotime($op->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                    @endif

                    {{-- Kolom PIC --}}
                    @if($op->agent->nama_agent == auth()->user()->nama)
                    <td>{{ $op->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $op->agent->nama_agent }}</td>
                    @endif

                    {{-- Kolom Status --}}
                    @if($op->status == 'created')
                    <td><span class="badge bg-secondary">{{ $op->status }}</span></td>
                    @elseif($op->status == 'onprocess')
                    <td><span class="badge bg-warning">{{ $op->status }}</span></td>
                    @elseif($op->status == 'pending')
                    <td><span class="badge bg-danger">{{ $op->status }}</span></td>
                    @elseif($op->status == 'resolved')
                    <td><span class="badge bg-primary">{{ $op->status }}</span></td>
                    @elseif($op->status == 'finished')
                    <td><span class="badge bg-success">{{ $op->status }}</span></td>
                    @endif

                    {{-- Kolom Keterangan --}}
                    @if($op->assigned == "ya" AND $op->status == "created" OR $op->assigned == "ya" AND $op->status == "pending")
                    <td><span class="badge bg-primary">direct assign</span></td>
                    @else
                        @if($op->is_queue == "ya" AND $op->status == "created")
                        <td><span class="badge bg-success">dalam antrian</span></td>
                        @elseif($op->is_queue == "tidak" AND $op->status == "created")
                            @if($op->role == "service desk")
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