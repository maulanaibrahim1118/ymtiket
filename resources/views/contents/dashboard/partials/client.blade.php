<div class="col-xxl-12 col-md-12 pb-0">
    <div class="card">

    <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
            <h6>Filter</h6>
        </li>

        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
        <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
        </ul>
    </div>
    <div class="card-body">
        <h5 class="card-title border-bottom"><i class="bi bi-house-door me-2"></i>Dashboard</h5>
    </div>

    </div>
</div>

<div class="col-xxl-3 col-md-6">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Total Ticket</h5>

        <div class="d-flex align-items-center">
        <a href="#">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        </a>
        <div class="ps-3">
            <h6>{{ $total }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Secondary Card -->

<div class="col-xxl-3 col-md-6">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Sedang Diproses</h5>

        <div class="d-flex align-items-center">
        <a href="#">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        </a>
        <div class="ps-3">
            <h6>{{ $onProcess }}</h6>
            <span class="text-warning small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Warning Card -->

<div class="col-xxl-3 col-md-6">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Pending</h5>

        <div class="d-flex align-items-center">
        <a href="#">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        </a>
        <div class="ps-3">
            <h6>{{ $pending }}</h6>
            <span class="text-danger small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Danger Card -->

<div class="col-xxl-3 col-md-6">
    <div class="card info-card success-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Selesai</h5>

        <div class="d-flex align-items-center">
        <a href="#">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        </a>
        <div class="ps-3">
            <h6>{{ $finished }}</h6>
            <span class="text-success small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
</div><!-- End Success Card -->

<div class="col-12">
    <div class="card info-table">

        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bx bxs-chevron-down"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
                <h6>Filter</h6>
            </li>

            <li><a class="dropdown-item" href="#">Hari Ini</a></li>
            <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
            <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
            </ul>
        </div>

        <div class="card-body">
            <h5 class="card-title">Ticket Belum Di Close</h5>

            <table class="table datatable">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">NO. TICKET</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">DETAIL KENDALA</th>
                    <th scope="col">DIBUAT PADA</th>
                    <th scope="col">PIC</th>
                    <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($unClosed as $ticket)
                    <tr>
                    <td>{{ $ticket->no_ticket }}</td>
                    <td>{{ $ticket->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $ticket->detail_kendala }}</td>

                    {{-- Kolom Dibuat Pada --}}
                    @if($ticket->jam_kerja == 'ya')
                    <td>{{ date('d-M-Y H:i:s', strtotime($ticket->created_at)) }} <span class="badge bg-success">JAM KERJA</span></td>
                    @else
                    <td>{{ date('d-M-Y H:i:s', strtotime($ticket->created_at)) }} <span class="badge bg-warning">BUKAN JAM KERJA</span></td>
                    @endif

                    {{-- Kolom PIC --}}
                    @if($ticket->agent->nama_agent == auth()->user()->nama)
                    <td>{{ $ticket->agent->nama_agent }} <span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $ticket->agent->nama_agent }}</td>
                    @endif

                    {{-- Kolom Status --}}
                    @if($ticket->status == 'created')
                    <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
                    @elseif($ticket->status == 'onprocess')
                    <td><span class="badge bg-warning">{{ $ticket->status }}</span></td>
                    @elseif($ticket->status == 'pending')
                    <td><span class="badge bg-danger">{{ $ticket->status }}</span></td>
                    @elseif($ticket->status == 'resolved')
                    <td><span class="badge bg-primary">{{ $ticket->status }}</span></td>
                    @elseif($ticket->status == 'finished')
                    <td><span class="badge bg-success">{{ $ticket->status }}</span></td>
                    @endif
                    <tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Info Table -->