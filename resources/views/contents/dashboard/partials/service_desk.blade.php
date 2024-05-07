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
                    <form action="/dashboard/filter" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Pilih filter berdasarkan :</p>
                        <div class="row">
                        <div class="col-md-6 mb-3">
                            <select class="form-select" name="filter1" id="filter1">
                                <option selected value="">Semua Agent</option>
                                @foreach($agents as $agent)
                                    @if(old('filter1', $filterArray[0]) == $agent->id)
                                        <option selected value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                                    @else
                                        <option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
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
@if($filterArray[0] == "")
<div class="col-md-3">
@else
<div class="col-md-2">
@endif
<a href="{{ route('ticket.dashboard', ['status' => 'all', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
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
    <a href="{{ route('ticket.dashboard', ['status' => 'unprocess', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
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
    <a href="{{ route('ticket.dashboard', ['status' => 'onprocess', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
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
    <a href="{{ route('ticket.dashboard', ['status' => 'pending', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
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

@if($filterArray[0] == "")
<div class="col-md-3">
@else
<div class="col-md-2">
@endif
    <a href="{{ route('ticket.dashboard', ['status' => 'selesai', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
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

@if($filterArray[0] != "")
<div class="col-md-2">
    <a href="{{ route('ticket.dashboard', ['status' => 'assign', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Tidak Selesai</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[5] }}</h6>
            <span class="text-danger small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Danger Card -->
@endif

<div class="col-md-3">
    <a href="{{ route('ticket.dashboard', ['status' => 'workday', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card success-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Jam Kerja</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[6] }}</h6>
            <span class="text-success small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->

<div class="col-md-3">
    <a href="{{ route('ticket.dashboard', ['status' => 'offday', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Di Luar Jam Kerja</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[7] }}</h6>
            <span class="text-warning small pt-1 fw-bold">Ticket</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Secondary Card -->



<div class="col-md-3">
    <a href="{{ route('asset.dashboard', ['status' => 'berkendala', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card primary-card">

    <div class="card-body">
        <h5 class="card-title">Asset Berkendala</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-box2"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[8] }}</h6>
            <span class="text-primary small pt-1 fw-bold">Asset</span>
        </div>
        </div>
    </div>

    </div>
    </a>
</div><!-- End Primary Card -->

<div class="col-md-3">
    <a href="{{ route('kendala.dashboard', ['status' => 'kategori', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Kategori Kendala</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ui-radios-grid"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[9] }}</h6>
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

            <div class="table-responsive">
                <table class="table table-hover" id="performaAgentDatatable">
                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                        <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">NAMA AGENT</th>
                        @can('isIT')
                        <th scope="col">SUB DIVISI</th>
                        @endcan
                        <th scope="col">TOTAL TICKET</th>
                        <th scope="col">SELESAI</th>
                        <th scope="col">SISA TICKET</th>
                        <th scope="col">TIDAK SELESAI</th>
                        {{-- <th scope="col">WORKING DAY</th> --}}
                        <th scope="col">AVERAGE /DAY</th>
                        <th scope="col">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                        @foreach($data1 as $data)
                        <tr>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->nama_agent }}</td>
                        @can('isIT')
                        <td>{{ $data->sub_divisi }}</td>
                        @endcan
                        <td>{{ $data->total_ticket }}</td>
                        <td>{{ $data->ticket_finish }}</td>
                        <td class="sisa">{{ $data->total_ticket-$data->ticket_finish }}</td>
                        <td>{{ $data->assigned }}</td>
                        {{-- <td>{{ $data->working_days }}</td> --}}
                        @php
                            if ($data->working_days == 0) {
                                $worktime = 0;
                            } else {
                                $worktime = $data->sum/$data->working_days;
                            }

                            $workload = \Carbon\Carbon::parse($worktime);
                            // $average = \Carbon\Carbon::parse($data->avg);
                        @endphp

                        @if( $worktime >= 3600)
                        <td>{{ $workload->hour }} Jam {{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                        @elseif( $worktime >= 60)
                        <td>{{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                        @else
                        <td>{{ $workload->second }} Detik</td>
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

            <div class="table-responsive">
                <table class="table datatable table-hover">
                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                        <tr>
                        <th scope="col">DIBUAT PADA</th>
                        <th scope="col">NO. TICKET</th>
                        <th scope="col">KENDALA</th>
                        <th scope="col">DETAIL KENDALA</th>
                        <th scope="col">PIC</th>
                        @can('isIT')
                        <th scope="col">SUB DIVISI</th>
                        @endcan
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
                        @can('isIT')
                        <td>{{ $data->sub_divisi_agent }}</td>
                        @endcan
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

            <div class="table-responsive">
                <table class="table datatable table-hover">
                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                        <tr>
                        <th scope="col">DIBUAT PADA</th>
                        <th scope="col">NO. TICKET</th>
                        <th scope="col">KENDALA</th>
                        <th scope="col">DETAIL KENDALA</th>
                        @can('isIT')
                        <th scope="col">SUB DIVISI</th>
                        @endcan
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
                        @can('isIT')
                        <td>{{ $data->sub_divisi_agent }}</td>
                        @endcan
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
            </div>r
        </div>
    </div>
</div><!-- End Info Table -->

<script>
    $(document).ready(function() {
        $('.sisa').each(function() {
            let sisaValue = $(this).text();
            if (sisaValue == '0') {
                $(this).css('backgroundColor', '#c8ffce');
            } else {
                $(this).css('backgroundColor', '#ffdfdf');
            }
        });
    });
</script>