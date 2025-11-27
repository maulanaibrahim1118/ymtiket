<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>.:: YM-Tiket | {{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="dist/img/favicon1.ico" rel="icon">
    <link href="dist/img/favicon1.ico" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="dist/css/google-fonts.css" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="dist/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="dist/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="dist/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="dist/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="dist/css/style.css" rel="stylesheet">

    <!-- Javascript -->
    <script src="dist/js/sweetalert.min.js"></script>
    
    <script type="text/javascript">
        var myVar;

        // Mengatur waktu loading halaman
        function preloader() {
            myVar = setTimeout(showPage, 200);
        }

        // Mengatur urutan tampilan setelah loading selesai
        function showPage() {
            document.getElementById("preloader").style.display = "none";
            document.getElementById("preloader").style.display = "none";
            document.getElementById("loader").style.display = "none";
            document.getElementById("status").style.display = "none";
            document.getElementById("content").style.display = "block";
        }
    </script>
</head>
<body onload="preloader()">
    <div class="container-fluid">
        <div id="preloader">
            <div class="position-absolute top-50 start-50 translate-middle">
                <div id="loader"></div>
                <div><strong id="status" role="status" class="text-primary">Memuat Halaman...</strong></div>
            </div>
        </div>
        <div style="display:none;" id="content" class="fade-in">
            <main>
                <div class="align-middle" style="width: 75%;margin: auto;">
                    <section class="section dashboard my-5">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <a href="/" class="logo2 d-flex align-items-center">
                                      <img src="{{ asset('dist/img/logo/logoym.png') }}" alt="">
                                      <span class="d-lg-block">{{ config('app.name') }}</span>
                                    </a>
                                </div><!-- End Logo -->
                                <p class="app-version">Version {{ config('app.version') }}</p>

                                <div class="card info-card mb-4 mt-5 px-2">
                                    <div class="card-body pb-0">
                                        <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }}</h5>
                                        
                                        <div class="row g-3 mb-3" style="font-size: 14px">
                                            {{-- Left Side --}}
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-md-3 m-0">
                                                        <label for="no_ticket" class="form-label fw-bold">Ticket Number</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        <label for="no_ticket" class="form-label">: <b>{{ $ticket->no_ticket }}</b></label>
                                                    </div>
                                                    <div class="col-md-3 m-0">
                                                        <label class="form-label fw-bold">Created At</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        @if($ticket->jam_kerja == "ya")
                                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Work Day</span></label>
                                                        @elseif($ticket->jam_kerja == "tidak")
                                                        <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Off Day</span></label>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 m-0">
                                                        <label for="no_ticket" class="form-label fw-bold">Created By</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        <label for="no_ticket" class="form-label">: {{ ucwords($ticket->created_by) }} | Reference : {{ ucwords($ticket->source) }}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Right Side --}}
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-md-3 m-0">
                                                        <label for="client/lokasi" class="form-label fw-bold">Client</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        @if ($ticket->user->nama == $ticket->location->nama_lokasi)
                                                        <label for="client/lokasi" class="form-label">: <span class="badge bg-primary" style="font-size: 13px;">{{ ucwords($ticket->user->nik) }} - {{ ucwords($ticket->location_name) }} / Store</span></label>
                                                        @else
                                                        <label for="client/lokasi" class="form-label">: <span class="badge bg-primary" style="font-size: 13px;">{{ ucwords($ticket->user->nama) }} / {{ ucwords($ticket->location->nama_lokasi) }}</span></label>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 m-0">
                                                        <label for="agent" class="form-label fw-bold">Ticket For</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
                                                    </div>
                                                    <div class="col-md-3 m-0">
                                                        <label for="status" class="form-label fw-bold">Status Ticket</label>
                                                    </div>
                                                    <div class="col-md-9 m-0">
                                                        @if($ticket->status == 'created')
                                                        <label class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                                        @elseif($ticket->status == 'onprocess')
                                                        <label class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
                                                        @elseif($ticket->status == 'pending')
                                                        <label class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
                                                        @elseif($ticket->status == 'resolved')
                                                        <label class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
                                                        @elseif($ticket->status == 'finished')
                                                        <label class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <table class="table table-sm table-bordered text-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="fw-bold bg-light">Ticket Submission Details</th>
                                                            <th class="col-md-1 fw-bold bg-light">Pending Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="align-middle">
                                                        <tr>
                                                            <th class="col-md-1 fw-bold bg-light text-start ps-3">Subject</th>
                                                            <td class="col-md-8 text-start ps-3">{{ ucfirst($ticket->kendala) }}</td>
                                                            @php
                                                                $totalSeconds = $ticket->pending_time;
                                                                $hours = floor($totalSeconds / 3600);
                                                                $minutes = floor(($totalSeconds % 3600) / 60);
                                                                $seconds = $totalSeconds % 60;
                                                            @endphp
                                                            @if($totalSeconds != 0)
                                                                <td rowspan="2">
                                                                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                                                                </td>
                                                            @else
                                                                <td rowspan="2">00:00:00</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="col-md-1 fw-bold bg-light text-start ps-3">Details</th>
                                                            <td colspan="2" class="text-start ps-3">{{ ucfirst($ticket->detail_kendala) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-12">
                                                <p class="border-bottom mt-1 mb-0"></p>
                                            </div>

                                            <label class="fw-bold">Ticket Processed Details :</label>
                                
                                            <div class="col-md-12" style="font-size: 14px">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="fw-bold bg-light">
                                                            <tr>
                                                            <td>Processed At</td>
                                                            <td>Type</td>
                                                            <td>Category</td>
                                                            <td>Sub Category</td>
                                                            <td>Cost</td>
                                                            <td>Agent PIC</td>
                                                            <td>Status</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-capitalize">
                                                            {{-- Jika belum di proses --}}
                                                            @if($countDetail == 0)
                                                            <tr>
                                                                @if($ticket->status == "created")
                                                                <td colspan="7" class="text-lowercase text-secondary">-- ticket unprocessed --</td>
                                                                @else
                                                                <td colspan="7" class="text-lowercase text-secondary">-- there has been no further action from the agent --</td>
                                                                @endif
                                                            </tr>
                                                            @else
                                                            @foreach($ticket_details as $td)
                                                            <tr>
                                                            <td>{{ date('d-M-Y H:i:s', strtotime($td->process_at)) }}</td>
                                                            <td>{{ $td->jenis_ticket }}</td>
                                                            <td>{{ $td->sub_category_ticket->category_ticket->nama_kategori }}</td>
                                                            <td>{{ $td->sub_category_ticket->nama_sub_kategori }}</td>
                                                            <td>IDR. {{ number_format($td->biaya,2,'.',',') }}</td>
                                                            <td>{{ $td->agent->nama_agent }}</td>
                                                            {{-- Status --}}
                                                            @if($td->status == 'onprocess')
                                                            <td><span class="badge bg-warning">{{ $td->status }}</span></td>
                                                            @elseif($td->status == 'pending')
                                                            <td><span class="badge bg-danger">{{ $td->status }}</span></td>
                                                            @elseif($td->status == 'resolved')
                                                            <td><span class="badge bg-primary">{{ $td->status }}</span></td>
                                                            @elseif($td->status == 'assigned')
                                                            <td><span class="badge bg-danger">not resolved</span></td>
                                                            @endif
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-0">
                                                <p class="border-bottom mt-1 mb-0"></p>
                                            </div>

                                            <div class="col-md-12">
                                                {{-- Tombol Kembali --}}
                                                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-sm btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Copyright Footer -->
                                <div class="credits text-center mt-5">
                                    Copyright &copy; 2024 <a href="#">Yogya Group</a>. All Right Reserved
                                </div>
                            </div>
                        </div> <!-- End Container -->
                    </section>
                </div> <!-- End Container -->
            </main><!-- End #main -->

            <!-- Back To Top -->
            <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        </div>
    </div>
    <!-- Vendor JS Files -->
    <script src="dist/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/vendor/simple-datatables/simple-datatables.js"></script>

    <!-- Template Main JS File -->
    <script src="dist/js/main.js"></script>
</body>
</html>