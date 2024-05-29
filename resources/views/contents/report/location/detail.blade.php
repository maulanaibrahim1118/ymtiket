@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter w-50">
                                <div class="row float-end col-md-12">
                                    <form class="search-form d-flex align-items-cente" action="{{ route('reportLocation.filter') }}" method="POST">
                                        @csrf
                                        <select class="form-select form-select-sm" style="width:40%;padding:0px;" name="wil" id="wil">
                                            <option selected value="">Semua Wilayah</option>
                                            @foreach($wilayahs as $wilayah)
                                                @if(old('wil', $filterArray[0]) == $wilayah->id)
                                                    <option selected value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
                                                @else
                                                    <option value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="date" name="start_date" id="start_date" class="form-control form-control ms-1" style="width:25%;" placeholder="Pilih Tanggal Awal" value="{{ old('start_date', $filterArray[1]) }}">
                                        <input type="date" name="end_date" id="end_date" class="form-control form-control ms-1" style="width:25%;" placeholder="Pilih Tanggal Akhir" value="{{ old('end_date', $filterArray[2]) }}">
                                        <button type="submit" class="btn btn-primary ms-1 me-3" style="width:15%;"><i class="bi bi-funnel me-1"></i> Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body border-bottom pb-0">
                                <h5 class="card-title"><i class="bi bi-geo-alt me-2"></i>{{ $title }}</h5>
                            </div>
                            <div class="accordion accordion-flush px-4" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Report #1 | Total Permintaan dan Kendala
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="accordion-body mt-3">
                                            <div class="card-body">
                                                <div id="table-container">
                                                    <form class="search-form d-flex align-items-center mb-3" action="{{ route('export.reportSubCategory') }}" method="GET">
                                                        @csrf
                                                        <input type="text" name="category2" id="category2" value="{{ old('category2', $filterArray[0]) }}" hidden>
                                                        <input type="date" name="startDate" id="startDate" value="{{ old('startDate', $filterArray[1]) }}" hidden>
                                                        <input type="date" name="endDate" id="endDate" value="{{ old('endDate', $filterArray[2]) }}" hidden>
                                                        <button type="submit" class="btn btn-success px-3 me-3" disabled><i class="bi bi-cloud-download me-2"></i>Export</button>
                                                    </form>
                                                    @include('contents.report.location.partials.report1')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div><!-- End Card Body --> --}}
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection