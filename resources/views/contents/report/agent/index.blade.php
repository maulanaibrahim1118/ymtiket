@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card pb-3">
                            <div class="filter w-50">
                                <div class="row float-end col-md-12">
                                    <form class="search-form d-flex align-items-center float-end" action="{{ route('reportAgent.filter') }}" method="POST">
                                        @csrf
                                        <div style="width:40%;padding:0px;"></div>
                                        <input type="date" name="start_date" id="start_date" class="form-control form-control select2 me-1" style="width:25%;" value="{{ old('start_date', $filterArray[0]) }}">
                                        <input type="date" name="end_date" id="end_date" class="form-control form-control me-1" style="width:25%;" value="{{ old('end_date', $filterArray[1]) }}">
                                        <button type="submit" class="btn btn-primary me-3" style="width:15%;"><i class="bi bi-funnel me-1"></i> Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2"></i>{{ $title }}</h5>
                            </div>
                            <div class="accordion accordion-flush px-4" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Report #1 | Total Ticket
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.agent.partials.report1')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Report #2 | Average Time Pending & Resolved
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.agent.partials.report2')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                            Report #3 | Average Ticket & Daily Working Hours
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.agent.partials.report3')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                            Report #4 | Average Time Requests & Accidents
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.agent.partials.report4')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                            Report #5 | Total Requests & Accidents
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.agent.partials.report5')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                            Report #6 | Agents Performance Pie Chart
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                {{-- <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                    @csrf
                                                    <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                    <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                    <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                </form> --}}
                                                @include('contents.report.agent.partials.report6')
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                            Report #7 | Agents Performance Column Chart
                                        </button>
                                    </h2>
                                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                {{-- <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                    @csrf
                                                    <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[0]) }}" hidden>
                                                    <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[1]) }}" hidden>
                                                    <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                </form> --}}
                                                {{-- @include('contents.report.agent.partials.report7') --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div><!-- End Card Body --> --}}
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    {{-- <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Waktu Proses Ticket</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report2')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Harian</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report3')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->

                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-body pb-0">
                                <h5 class="card-title"><i class="bi bi-person-workspace me-2">
                                    </i>Report Agent<span> | Rata-Rata Waktu Pending Per Hari</span>
                                </h5>
                                
                                <div id="table-container">
                                    @include('contents.report.agent.partials.report4')
                                </div>

                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 --> --}}
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDate = document.getElementById("start_date");
            const endDate = document.getElementById("end_date");
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');

            const maxDate = `${year}-${month}-${day}`;
            startDate.setAttribute('max', maxDate);
            endDate.setAttribute('max', maxDate);

            // Event listener for the start date change
            $("#start_date").change(function () {
                var startDate = $(this).val();
                $("#end_date").val(""); // Clear the end date
                $("#end_date").attr("min", startDate); // Set the min attribute of end date
            });
        });
    </script>
@endsection