<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>.:: GCITOP | {{ $title }}</title>
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
    <link href="dist/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="dist/vendor/quill/quill.bubble.css" rel="stylesheet">
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
    <div id="preloader" class="d-flex align-items-center">
        <div id="loader"></div>
        <strong id="status" role="status" class="position-absolute text-primary" style="top: 60%; left: 45%;">Memuat Halaman...</strong>
    </div>
    <div style="display:none;" id="content" class="fade-in">
        <main>
            <div class="container">
                <section class="section dashboard mt-5">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card info-card mb-4">
                                <div class="card-body pb-0">
                                    <h5 class="card-title border-bottom mb-3"><i class="bi bi-ticket-perforated me-2"></i>{{ $title }} <span class="text-secondary"> | {{ $ticket->no_ticket }}</span></h5>
                                    
                                    <div class="row g-3 mb-3 pt-3" style="font-size: 14px">
                                        <div class="col-md-2 m-0">
                                            <label for="tanggal" class="form-label fw-bold">Tanggal/Waktu</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            @if($ticket->jam_kerja == "ya")
                                            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-success">Jam Kerja</span></label>
                                            @elseif($ticket->jam_kerja == "tidak")
                                            <label for="jam_kerja" class="form-label">: {{ date('d/m/Y H:i:s', strtotime($ticket->created_at)) }} | <span class="badge bg-warning">Diluar Jam Kerja</span></label>
                                            @endif
                                        </div>
                                        <div class="col-md-2 m-0">
                                            <label for="no_asset" class="form-label fw-bold">Waktu Estimasi</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            <label for="no_asset" class="form-label">: {{ $ticket->estimated }}</label>
                                        </div>
                                        <div class="col-md-2 m-0">
                                            <label for="agent" class="form-label fw-bold">Ditujukan Pada</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            <label for="agent" class="form-label">: {{ ucwords($ticket->agent->location->nama_lokasi) }}</label>
                                        </div>
                                        <div class="col-md-2 m-0">
                                            <label for="estimated" class="form-label fw-bold">Ticket Pending</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            @php
                                                $carbonInstance = \Carbon\Carbon::parse($ticket->pending_time);
                                            @endphp
                                            @if($ticket->pending_time >= 3600)
                                            <label for="estimated" class="form-label">: {{ $carbonInstance->hour }} jam {{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</label>
                                            @elseif($ticket->pending_time >= 60)
                                            <label for="estimated" class="form-label">: {{ $carbonInstance->minute }} menit {{ $carbonInstance->second }} detik</label>
                                            @elseif($ticket->pending_time == 0)
                                            <label for="estimated" class="form-label">: 0 detik</label>
                                            @else
                                            <label for="estimated" class="form-label">: {{ $carbonInstance->second }} detik</label>
                                            @endif
                                        </div>
                                        <div class="col-md-2 m-0">
                                            <label for="kendala" class="form-label fw-bold">Kendala</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            <label for="kendala" class="form-label">: {{ ucwords($ticket->kendala) }}</label>
                                        </div>
                                        <div class="col-md-2 m-0">
                                            <label for="status" class="form-label fw-bold">Status</label>
                                        </div>
                                        <div class="col-md-4 m-0">
                                            @if($ticket->status == 'created')
                                            <label for="tanggal" class="form-label">: <span class="badge bg-secondary">{{ ucwords($ticket->status) }}</span></label>
                                            @elseif($ticket->status == 'onprocess')
                                            <label for="tanggal" class="form-label">: <span class="badge bg-warning">{{ ucwords($ticket->status) }}</span></label>
                                            @elseif($ticket->status == 'pending')
                                            <label for="tanggal" class="form-label">: <span class="badge bg-danger">{{ ucwords($ticket->status) }}</span></label>
                                            @elseif($ticket->status == 'resolved')
                                            <label for="tanggal" class="form-label">: <span class="badge bg-primary">{{ ucwords($ticket->status) }}</span></label>
                                            @elseif($ticket->status == 'finished')
                                            <label for="tanggal" class="form-label">: <span class="badge bg-success">{{ ucwords($ticket->status) }}</span></label>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-2 m-0">
                                            <label for="tanggal" class="form-label fw-bold">Detail Kendala</label>
                                        </div>
                                        <div class="col-md-10 m-0">
                                            <label for="tanggal" class="form-label">: {{ ucfirst($ticket->detail_kendala) }}</label>
                                        </div>
    
                                        <div class="col-md-12">
                                            <p class="border-bottom mt-1 mb-0"></p>
                                        </div>
                            
                                        <div class="col-md-12" style="font-size: 14px">
                                            <table class="table table-bordered text-center">
                                                <thead class="fw-bold bg-light">
                                                    <tr>
                                                    <td>Jenis Ticket</td>
                                                    <td>Kategori Ticket</td>
                                                    <td>Sub Kategori Ticket</td>
                                                    <td>Biaya</td>
                                                    <td>PIC Agent</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-capitalize">
                                                    @foreach($ticket_details as $td)
                                                    <tr>
                                                    <td>{{ $td->jenis_ticket }}</td>
                                                    <td>{{ $td->sub_category_ticket->category_ticket->nama_kategori }}</td>
                                                    <td>{{ $td->sub_category_ticket->nama_sub_kategori }}</td>
                                                    <td>IDR. {{ number_format($td->biaya,2,'.',',') }}</td>
                                                    <td>{{ $td->agent->nama_agent }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-12">
                                            {{-- Tombol Kembali --}}
                                            <a href="{{ url()->previous() }}"><button type="button" class="btn btn-sm btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Kembali</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Copyright Footer -->
                            <div class="credits text-center">
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
    <!-- Vendor JS Files -->
    <script src="dist/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="dist/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/vendor/chart.js/chart.min.js"></script>
    <script src="dist/vendor/echarts/echarts.min.js"></script>
    <script src="dist/vendor/quill/quill.min.js"></script>
    <script src="dist/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="dist/vendor/tinymce/tinymce.min.js"></script>
    <script src="dist/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="dist/js/main.js"></script>
</body>
</html>