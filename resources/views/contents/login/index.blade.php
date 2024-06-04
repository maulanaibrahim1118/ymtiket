<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ config('app.title') }} | {{ $title }}</title>
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

        // Menampilkan dan menyembunyikan Password
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        // // Menampilkan informasi pendaftaran akun
        // function register() {
        //     swal({
        //         title: "Mohon Maaf",
        //         text: "Silakan hubungi Staff IT untuk Daftar Akun!",
        //         icon: "info",
        //     });
        // }
    </script>
</head>
<body onload="preloader()">
    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <div id="preloader">
            <div class="position-absolute top-50 start-50 translate-middle">
                <div id="loader"></div>
                <div><strong id="status" role="status" class="text-primary">Memuat Halaman...</strong></div>
            </div>
        </div>
        <div style="display:none;" id="content" class="fade-in">
            <main>
                <div class="container">
                    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center">
                        <div class="container">
                            <!-- Showing Notification Login Error -->
                            @if(session()->has('success'))
                            <script>
                                swal("Berhasil!", "{{ session('success') }}", "success", {
                                    timer: 3000
                                });
                            </script>
                            @endif
                            @if(session()->has('loginError'))
                            <script>
                                swal("Login Gagal!", "{{ session('loginError') }}", "warning", {
                                    timer: 3000
                                });
                            </script>
                            @endif
                            @if(session()->has('error'))
                            <script>
                                swal("Gagal!", "{{ session('error') }}", "warning", {
                                    timer: 3000
                                });
                            </script>
                            @endif
                            @if(session()->has('error-419'))
                            <script>
                                swal("Page Expired!", "{{ session('error-419') }}", "info", {
                                    timer: 3000
                                });
                            </script>
                            @endif
                            
                            <div class="row justify-content-center">
                                <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                    
                                    <div class="card mb-3 px-4 py-2">
                                        
                                        <div class="card-header my-3 p-0">
                                            <div class="d-flex justify-content-center">
                                                <a href="/" class="logo2 d-flex align-items-center">
                                                  <img src="{{ asset('dist/img/logo/logo4.png') }}" alt="">
                                                  <span class="d-lg-block">{{ config('app.name') }}</span>
                                                </a>
                                            </div><!-- End Logo -->
                                            <p class="app-version">Versi {{ config('app.version') }}</p>
                                        </div>

                                        <!-- Login Title -->
                                        <div class="col-12 mb-4 px-4">
                                            {{-- <h5 class="card-title text-center pb-2 fs-5">Login</h5> --}}
                                            <p class="text-center small px-4">Masukkan Username dan Password anda untuk login dan mulai bekerja.</p>
                                        </div>

                                        <div class="card-body">
                                            <!-- Login Form -->
                                            <form class="row g-3" action="/login" method="post">
                                                @csrf
                                                <div class="col-12">
                                                    <span class="border border-3 border-primary d-inline py-2" style="margin-left: -3px"></span>
                                                    <input type="number" name="nik" class="form-control rounded-0 mb-2" style="margin-top: -31px;" id="nik" placeholder="Username" required />
                                                </div>
            
                                                <div class="col-12 pb-2">
                                                    <span class="border border-3 border-primary position-relative py-2" style="margin-left: -3px"></span>
                                                    <input type="password" name="password" class="form-control rounded-0 mb-2" style="margin-top: -31px;" id="password" placeholder="Password" required />
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" onclick="myFunction()">
                                                        <label class="form-check-label" for="inlineCheckbox1">Tampilkan Password</label>
                                                    </div>
                                                </div>
            
                                                <div class="card-footer p-0"></div>
            
                                                <div class="col-12 pb-2">
                                                    <button class="btn btn-primary w-100 rounded-1" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
                                                </div>
                                                    
                                                {{-- <div class="col-12">
                                                    <p class="small mb-0 text-center">Belum punya akun? Silakan <a href="#" onclick="register()">Daftar</a></p>
                                                </div> --}}
                                            </form> <!-- End Login Form -->
                                        </div> <!-- End card-body -->
                                    </div> <!-- End card mb-3 p-4 -->
                                    
                                    <div class="col-md-12 text-center border-bottom mb-4 text-secondary">
                                        <p style="font-size: 14px;">Cari ticket tanpa perlu login? <span class="fst-italic"> Klik <a href="#" class="text-success" data-bs-toggle="modal" data-bs-target="#searchModal">disini</a></span></p>
                                    </div>
            
                                    <!-- Copyright Footer -->
                                    <div class="credits mt-3">
                                        Copyright &copy; {{ config('app.year_created') }} <a href="#">{{ config('app.company') }}</a>. All Right Reserved
                                    </div>
                                </div>
                            </div> <!-- End Row Content -->
                        </div> <!-- End Container -->
                    </section>
                </div> <!-- End Container -->
            </main><!-- End #main -->

            <!-- Back To Top -->
            <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="searchModal" style="zoom:0.75;" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">.:: Cari Ticket</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="/search-ticket" method="post">
                        @csrf
                        <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="no_ticket" class="form-control" placeholder="Tuliskan Nomor Ticket..." aria-label="Recipient's username" aria-describedby="button-addon2" required>
                            <button class="btn btn-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS Files -->
    <script src="{{ asset('dist/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('dist/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('dist/js/main.js') }}"></script>
</body>
</html>