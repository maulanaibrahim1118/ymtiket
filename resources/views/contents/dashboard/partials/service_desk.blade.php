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
                        <div class="col-md-6 mb-3">
                            <select class="form-select" name="filter1" id="filter1">
                                <option value="">Semua Agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                                @endforeach
                            </select>
                        </div>
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

<div class="col-md-3">
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
    <a href="/tickets/{{ encrypt('unprocess') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Belum Di Proses</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[1] }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-md-2">
    <a href="/tickets/{{ encrypt('onprocess') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">Sedang Di Proses</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[2] }}</h6>
            <span class="text-warning small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Warning Card -->

<div class="col-md-2">
    <a href="/tickets/{{ encrypt('pending') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Sedang Di Pending</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[3] }}</h6>
            <span class="text-danger small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Danger Card -->

<div class="col-md-3">
    <a href="/tickets/{{ encrypt('selesai') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Sudah Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[4] }}</h6>
            <span class="text-primary small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-md-3">
    <a href="/tickets/{{ encrypt('workday') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card success-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Jam Kerja</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[5] }}</h6>
            <span class="text-success small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-md-3">
    <a href="/tickets/{{ encrypt('offday') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Luar Jam Kerja</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[6] }}</h6>
            <span class="text-warning small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->



<div class="col-md-3">
    <a href="/assets/{{ encrypt('berkendala') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Asset Berkendala</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-box2"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[7] }}</h6>
            <span class="text-primary small pt-1 fw-bold">Asset</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-md-3">
    <a href="/category-sub-tickets/{{ encrypt('kategori') }}-{{ encrypt($filterArray[0]) }}-{{ encrypt($filterArray[1]) }}-{{encrypt(auth()->user()->location_id) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Kategori Kendala</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ui-radios-grid"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[8] }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Kategori</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-12">
    <div class="card info-table">
        <div class="card-body">
            @if($pathFilter == "Semua")
            <h5 class="card-title">Performa Agent</h5>
            @else
            <h5 class="card-title">Performa Agent <span>| {{ $pathFilter }}</span></h5>
            @endif

            <table class="table datatable table-hover">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">NIK</th>
                    <th scope="col">NAMA AGENT</th>
                    <th scope="col">SUB DIVISI</th>
                    <th scope="col">TOTAL TICKET</th>
                    <th scope="col">BELUM DIPROSES</th>
                    <th scope="col">SEDANG DIPROSES</th>
                    <th scope="col">SELESAI</th>
                    <th scope="col">WORKLOAD</th>
                    <th scope="col">RATA-RATA</th>
                    <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($data1 as $data)
                    <tr>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->nama_agent }}</td>
                    <td></td>
                    <td>{{ $data->total_ticket }}</td>
                    <td>{{ $data->ticket_unprocessed }}</td>
                    <td>{{ $data->ticket_onprocess }}</td>
                    <td>{{ $data->ticket_finish }}</td>
                    @php
                        $workload = \Carbon\Carbon::parse($data->processed_time-$data->pending_time);
                        $average = \Carbon\Carbon::parse($data->avg);
                    @endphp
                    @if( $data->processed_time-$data->pending_time >= 3600)
                    <td>{{ $workload->hour }} Jam {{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                    @elseif( $data->processed_time-$data->pending_time >= 60)
                    <td>{{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                    @else
                    <td>{{ $workload->second }} Detik</td>
                    @endif

                    @if( $data->avg >= 3600)
                    <td>{{ $average->hour }} Jam {{ $average->minute }} Menit {{ $average->second }} Detik</td>
                    @elseif( $data->avg >= 60)
                    <td>{{ $average->minute }} Menit {{ $average->second }} Detik</td>
                    @elseif( $data->avg == 0)
                    <td>0 Detik</td>
                    @else
                    <td>{{ $average->second }} Detik</td>
                    @endif
                    @if($data->status == "present")
                    <td><span class="badge bg-primary">HADIR</span></td>
                    @else
                    <td><span class="badge bg-secondary">TIDAK HADIR</span></td>
                    @endif
                    </tr>
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
            <h5 class="card-title">Ticket Belum Ada Tindakan</h5>
            @else
            <h5 class="card-title">Ticket Belum Ada Tindakan <span>| {{ $pathFilter }}</span></h5>
            @endif

            <table class="table datatable table-hover">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">DIBUAT PADA</th>
                    <th scope="col">NO. TICKET</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">DETAIL KENDALA</th>
                    <th scope="col">PIC</th>
                    <th scope="col">SUB DIVISI</th>
                    <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($data2 as $data)
                    <tr>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->no_ticket }}</td>
                    <td>{{ $data->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $data->detail_kendala }}</td>
                    @if($data->agent->nama_agent == auth()->user()->nama)
                    <td><span class="badge bg-info">saya</span></td>
                    @else
                    <td>{{ $data->agent->nama_agent }}</td>
                    @endif
                    <td>{{ $data->sub_divisi }}</td>
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
                    @else
                    <td><span class="badge bg-danger">{{ $data->status }}</span></td>
                    @endif
                    </tr>
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
            <h5 class="card-title">Ticket Dalam Antrian</h5>
            @else
            <h5 class="card-title">Ticket Dalam Antrian <span>| {{ $pathFilter }}</span></h5>
            @endif

            <table class="table datatable table-hover">
                <thead class="bg-light" style="height: 45px;font-size:14px;">
                    <tr>
                    <th scope="col">DIBUAT PADA</th>
                    <th scope="col">NO. TICKET</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">DETAIL KENDALA</th>
                    <th scope="col">SUB DIVISI</th>
                    <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                    @foreach($data3 as $data)
                    <tr>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->no_ticket }}</td>
                    <td>{{ $data->kendala }}</td>
                    <td class="col-2 text-truncate" style="max-width: 50px;">{{ $data->detail_kendala }}</td>
                    <td>{{ $data->sub_divisi }}</td>
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
                    @else
                    <td><span class="badge bg-danger">{{ $data->status }}</span></td>
                    @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Info Table -->