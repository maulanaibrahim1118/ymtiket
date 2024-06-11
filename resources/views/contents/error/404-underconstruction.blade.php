<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>.:: GCITOP | Error 404 - Under Construction</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('dist/img/favicon1.ico') }}" rel="icon">
    <link href="{{ asset('dist/img/favicon1.ico') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="{{ asset('dist/css/google-fonts.css') }}" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('dist/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">

    <!-- Javascript -->
    <script src="{{ asset('dist/js/sweetalert.min.js') }}"></script>
    
    <script type="text/javascript">
        var myVar;

        // Mengatur waktu loading halaman
        function preloader() {
            myVar = setTimeout(showPage, 300);
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
    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <div id="preloader">
            <div class="position-absolute top-50 start-50 translate-middle">
                <div id="loader"></div>
                <div class="loading"><strong id="status" role="status" class="text-primary">Loading Page...</strong></div>
            </div>
        </div>
        <div style="display:none;" id="content" class="fade-in">

        <main>
            <div class="container">

            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <h1>404</h1>
                <h2>This page is under construction.</h2>
                <a class="btn" href="/dashboard">Back to Dashboard</a>
                <div class="credits">
                </div>
            </section>

            </div>
        </main><!-- End #main -->

        <!-- Back To Top -->
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        </div>
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