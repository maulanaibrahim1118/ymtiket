<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>.:: GCITOP | {{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../dist/img/favicon1.ico" rel="icon">
    <link href="../dist/img/favicon1.ico" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="../dist/css/google-fonts.css" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../dist/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dist/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../dist/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../dist/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../dist/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../dist/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../dist/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../dist/css/style.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="../dist/DataTables/datatables.min.css" rel="stylesheet">
    <script src="../dist/DataTables/datatables.min.js"></script>
    <script src="../dist/DataTables/DataTables-1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../dist/DataTables/DataTables-1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Javascript -->
    <script src="../dist/js/config.js"></script>
    <script src="../dist/js/jquery-3.6.3.min.js"></script>
    <script src="../dist/js/sweetalert.min.js"></script>

    <script type="text/javascript">
        var myVar;

        function myFunction() {
            myVar = setTimeout(showPage, 300);
        }

        function showPage() {
            document.getElementById("content").style.display = "block";
            document.getElementById("preloader").style.display = "none";
            document.getElementById("loader").style.display = "none";
            document.getElementById("status").style.display = "none";
        }

        $('#example').DataTable();
    </script>
</head>
<body onload="myFunction()">
    <div id="preloader" class="d-flex align-items-center">
        <div id="loader"></div>
        <strong id="status" role="status" class="position-absolute text-primary" style="top: 60%; left: 44%;">Memuat Halaman...</strong>
    </div>

    <div style="display:none;" id="content" class="animate-bottom">
        @include('header')
        @include('sidebar')
        <main id="main" class="main">
            <!-- Title Bar -->
            <div class="pagetitle">
                <nav style="--bs-breadcrumb-divider: '';">
                    <ol class="breadcrumb">
                        <h1 class="border-end border-2 pe-3 me-3"><b>{{ $path }}</b></h1>
                        <li class="breadcrumb-item" style="padding-top:5px;"><a href="/dashboard">Home</a></li><i class="bx bxs-chevron-right p-2"></i>
                        <li class="breadcrumb-item" style="padding-top:5px;">{{ $path }}</li><i class="bx bxs-chevron-right p-2"></i>
                        <li class="breadcrumb-item active" style="padding-top:5px;">{{ $path2 }}</li>
                    </ol>
                </nav>
            </div><!-- End Title Bar -->
            @yield('content')
        </main><!-- End Main Content -->

        <footer id="footer" class="footer">
            <div class="copyright">
                Copyright &copy; 2024 <a href="#">Yogya Group</a>. All Rights Reserved
            </div>
        </footer><!-- End Footer -->

        <!-- Back to top -->
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    </div>
    <!-- Vendor JS Files -->
    <script src="../dist/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../dist/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/vendor/chart.js/chart.min.js"></script>
    <script src="../dist/vendor/echarts/echarts.min.js"></script>
    <script src="../dist/vendor/quill/quill.min.js"></script>
    <script src="../dist/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../dist/vendor/tinymce/tinymce.min.js"></script>
    <script src="../dist/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../dist/js/main.js"></script>
</body>
</html>