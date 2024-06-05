<div class="col-md-12 pb-0">
    <div class="card">

    <div class="filter w-50">
        <div class="row float-end col-md-12">
            <form class="search-form d-flex align-items-center" action="{{ route('dashboard.filter') }}" method="POST">
                @csrf
                <div style="width:55%;padding:0px;"></div>
                <input name="filter1" value="{{ $filterArray[0] }}" hidden>
                <select class="form-select form-select-sm ms-1" style="width:30%;" name="filter2" id="filter2">
                    <option value="" @if($filterArray[1] == "") selected @endif>Semua Periode</option>
                    <option value="today" @if($filterArray[1] == "today") selected @endif>Hari Ini</option>
                    <option value="monthly" @if($filterArray[1] == "monthly") selected @endif>Bulan Ini</option>
                    <option value="yearly" @if($filterArray[1] == "yearly") selected @endif>Tahun Ini</option>
                </select>
                <button type="submit" class="btn btn-primary ms-1 me-3" style="width:15%;"><i class="bi bi-funnel me-1"></i>Filter</button>
            </form>
        </div>
        {{-- <a class="icon" href="#" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="bx bx-filter"></i></a>
        <div class="modal fade" id="filterModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="modalContent4">
                    <div class="modal-header">
                        <h5 class="modal-title">.:: Filter Report Dashboard</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/dashboard/filter" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Pilih filter berdasarkan :</p>
                        <div class="row">
                        <input name="filter1" value="{{ $filterArray[0] }}" hidden>
                        <div class="col-md-6">
                            <select class="form-select" name="filter2" id="filter2">
                                <option value="" @if($filterArray[1] == "") selected @endif>Semua Periode</option>
                                <option value="today" @if($filterArray[1] == "today") selected @endif>Hari Ini</option>
                                <option value="monthly" @if($filterArray[1] == "monthly") selected @endif>Bulan Ini</option>
                                <option value="yearly" @if($filterArray[1] == "yearly") selected @endif>Tahun Ini</option>
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
        </div><!-- End Filter Modal--> --}}
    </div>
    <div class="card-body pb-0">
        <h5 class="card-title"><i class="bi bi-house-door me-2"></i>Dashboard</h5>
    </div>

    </div>
</div>

<div class="col-md-2">
    <a href="{{ route('ticket.dashboard', ['status' => 'all', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Assigned</h5>

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
    <a href="{{ route('ticket.dashboard', ['status' => 'selesai', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Resolved</h5>

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
    <a href="{{ route('ticket.dashboard', ['status' => 'assign', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Participant</h5>

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