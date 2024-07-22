<div class="col-md-12 pb-0">
    <div class="card info-card pb-0">

    <div class="filter w-50">
        <div class="row float-end col-md-12">
            <form class="search-form d-flex align-items-center" action="{{ route('dashboard.filter') }}" method="POST">
                @csrf
                <div style="width:15%;padding:0px;"></div>
                <select class="form-select form-select me-2" style="width:40%;" name="filter1" id="filter1">
                    <option selected value="">All Agent</option>
                    @foreach($agents as $agent)
                        @if(old('filter1', $filterArray[0]) == $agent->id)
                            <option selected value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                        @else
                            <option value="{{ $agent->id }}">{{ ucwords($agent->nama_agent) }}</option>
                        @endif
                    @endforeach
                </select>
                <div class="ms-1"></div>
                <select class="form-select form-select ms-1" style="width:30%;" name="filter2" id="filter2">
                    <option value="" @if($filterArray[1] == "") selected @endif>All Period</option>
                    <option value="today" @if($filterArray[1] == "today") selected @endif>Today</option>
                    <option value="monthly" @if($filterArray[1] == "monthly") selected @endif>This Month</option>
                    <option value="yearly" @if($filterArray[1] == "yearly") selected @endif>This Year</option>
                </select>
                <button type="submit" class="btn btn-primary ms-1 me-3" style="width:15%;"><i class="bi bi-funnel me-1"></i>Filter</button>
            </form>
        </div>
        {{-- <a class="icon" href="#" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal">Filter <i class="bi bi-funnel-fill"></i></a>
        <div class="modal fade" id="filterModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="modalContent4">
                    <div class="modal-header">
                        <h5 class="modal-title">.:: Filter Dashboard</h5>
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
        </div><!-- End Filter Modal--> --}}
    </div>
    <div class="card-body pb-0">
        <h5 class="card-title"><i class="bi bi-house-door me-2"></i>Dashboard</h5>
    </div>

    </div>
</div>
{{-- @if($filterArray[0] == "")
<div class="col-md-3">
@else
<div class="col-md-2">
@endif
<a href="{{ route('ticket.dashboard', ['status' => 'all', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Masuk</h5>

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
</div><!-- End Secondary Card --> --}}

<p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Created At.</p>

<div class="col-md-3">
    <a href="{{ route('ticket.dashboard', ['status' => 'unprocess', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card secondary-card">

    <div class="card-body">
        <h5 class="card-title">New Ticket</h5>

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

@if($filterArray[0] == "")
<div class="col-md-3">
@else
<div class="col-md-2">
@endif
    <a href="{{ route('ticket.dashboard', ['status' => 'onprocess', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card warning-card">

    <div class="card-body">
        <h5 class="card-title">On Process</h5>

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

@if($filterArray[0] == "")
<div class="col-md-3">
@else
<div class="col-md-2">
@endif
    <a href="{{ route('ticket.dashboard', ['status' => 'pending', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Pending</h5>

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
        <h5 class="card-title">Resolved</h5>

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
<div class="col-md-3">
    <a href="{{ route('ticket.dashboard', ['status' => 'assign', 'filter1' => $filterArray[0], 'filter2' => $filterArray[1]]) }}">
    <div class="card info-card danger-card">

    <div class="card-body">
        <h5 class="card-title">Ticket Participant</h5>

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
        <h5 class="card-title">Work Day Ticket</h5>

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
        <h5 class="card-title">Off Day Ticket</h5>

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
        <h5 class="card-title">Ticket Asset</h5>

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
        <h5 class="card-title">Sub Category</h5>

        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-ui-radios-grid"></i>
        </div>
        <div class="ps-3">
            <h6>{{ $dataArray[9] }}</h6>
            <span class="text-secondary small pt-1 fw-bold">Sub Category</span>
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
            <h5 class="card-title border-bottom">Agent Information</h5>
            @else
            <h5 class="card-title border-bottom">Agent Information<span>| {{ $pathFilter }}</span></h5>
            @endif

            <p class="text-secondary"><i class="bi bi-info-circle me-2"></i>Calculated based on Ticket Processed At by the Agents.</p>
            <div class="table-responsive mt-3">
                <table class="table table-hover" id="performaAgentDatatable">
                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                        <tr>
                        <th scope="col">NIK</th>
                        <th scope="col">AGENT NAME</th>
                        @can('isIT')
                        <th scope="col">SUB DIVISION</th>
                        @endcan
                        <th scope="col">TOTAL TICKET</th>
                        <th scope="col">RESOLVED</th>
                        <th scope="col">PARTICIPANT</th>
                        <th scope="col">REMAINING</th>
                        {{-- <th scope="col">WORKING DAY</th> --}}
                        <th scope="col">HOUR /DAY</th>
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
                        <td>{{ $data->assigned }}</td>
                        <td class="sisa">{{ $data->onprocess+$data->pending+$data->created }}</td>
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

                        @if($worktime >= 3600)
                        <td>{{ str_pad($workload->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif($worktime >= 60)
                        <td>{{ str_pad($workload->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif($worktime == 0)
                        <td>00:00:00</td>
                        @else
                        <td>{{ str_pad($workload->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($workload->second, 2, "0", STR_PAD_LEFT) }}</td>
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
            <h5 class="card-title border-bottom">Ticket In Queue</h5>
            @else
            <h5 class="card-title border-bottom">Ticket In Queue <span>| {{ $pathFilter }}</span></h5>
            @endif

            <div class="table-responsive mt-3">
                <table class="table datatable table-hover">
                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                        <tr>
                        <th scope="col">CREATED AT</th>
                        <th scope="col">TICKET NUMBER</th>
                        <th scope="col">SUBMISSION</th>
                        <th scope="col">DETAIL</th>
                        @can('isIT')
                        <th scope="col">SUB DIVISION</th>
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
            </div>
        </div>
    </div>
</div><!-- End Info Table -->

<script>
    $(document).ready(function() {
        $('.sisa').each(function() {
            let sisaValue = $(this).text();
            if (sisaValue == '0') {
                $(this).css('backgroundColor', '#ffe3a7');
            }
        });
    });
</script>