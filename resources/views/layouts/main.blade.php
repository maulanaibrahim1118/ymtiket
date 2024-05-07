<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>.:: YM-Tiket | {{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('dist/img/favicon1.ico') }}" rel="icon">
    <link href="{{ asset('dist/img/favicon1.ico') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="{{ asset('dist/css/google-fonts.css') }}" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('dist/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/bootstrap/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">

    <!-- Javascript -->
    <script src="{{ asset('dist/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('dist/js/config.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.min.js') }}"></script>

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
    </script>
</head>
<body onload="myFunction()">
    <div class="container-fluid">
    <div id="preloader">
        <div class="position-absolute top-50 start-50 translate-middle">
            <div id="loader"></div>
            <div><strong id="status" role="status" class="text-primary">Memuat Halaman...</strong></div>
        </div>
    </div>

    <div style="display:none;" id="content" class="animate-bottom">
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <a href="/dashboard/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}" class="logo d-flex align-items-center">
                    <img src="{{ asset('dist/img/logo/logo.png') }}" alt="">
                    <span class="d-none d-lg-block pt-1"><b>YM-Tiket</b></span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->
        
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    <li class="nav-item dropdown me-2">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            {{-- <span class="badge bg-primary badge-number">4</span> --}}
                        </a><!-- End Notification Icon -->
                
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                            Pemberitahuan
                            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Lihat semua</span></a>
                            </li>
                            <li>
                            <hr class="dropdown-divider">
                            </li>
                
                            {{-- <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div>
                                <h4>Lorem Ipsum</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>30 min. ago</p>
                            </div>
                            </li>
                
                            <li>
                            <hr class="dropdown-divider">
                            </li>
                
                            <li class="notification-item">
                            <i class="bi bi-x-circle text-danger"></i>
                            <div>
                                <h4>Atque rerum nesciunt</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>1 hr. ago</p>
                            </div>
                            </li>
                
                            <li>
                            <hr class="dropdown-divider">
                            </li>
                
                            <li class="notification-item">
                            <i class="bi bi-check-circle text-success"></i>
                            <div>
                                <h4>Sit rerum fuga</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>2 hrs. ago</p>
                            </div>
                            </li>
                
                            <li>
                            <hr class="dropdown-divider">
                            </li> --}}
                
                            <li class="notification-item">
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <h4>Mohon maaf</h4>
                                <p>Fitur ini belum berfungsi.</p>
                            </div>
                            </li>

                            {{-- <li>
                            <hr class="dropdown-divider">
                            </li>
                            <li class="dropdown-footer">
                            <a href="#">Tampilkan semua</a>
                            </li> --}}
                
                        </ul><!-- End Notification Dropdown Items -->
                
                    </li><!-- End Notification Nav -->

                    <li class="nav-item dropdown float-end">
                        <p class="nav-link d-block float-end m-0" style="font-size: 15px;"><b>{{ ucwords(auth()->user()->nama) }}</b></p><br>
                        <p class="nav-link d-block float-end m-0" style="font-size: 12px;">{{ ucwords(auth()->user()->position->nama_jabatan) }}</p>
                    </li><!-- End User Profile -->
        
                    <li class="nav-item dropdown pe-3">
                        <div class="nav-link nav-profile d-flex align-items-center ms-3 me-3">
                            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                            <img src="{{ asset('dist/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                            <span class="position-absolute bottom-0 ms-4 p-1 border border-light rounded-circle" style="background-color: rgb(22, 224, 22)">
                                <span class="visually-hidden">Online</span>
                            </span>
                            </a>
        
                            <ul class="dropdown-menu mt-2">
                                <li class="dropdown-header">
                                <h6>{{ ucwords(auth()->user()->nama) }}</h6>
                                </li>
                                <li>
                                <hr class="dropdown-divider mb-2">
                                </li>
        
                                <li>
                                <form action="/logout" method="post">
                                @csrf
                                <a class="nav-link collapsed">
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-power"></i>
                                    <span>Logout</span>
                                    </button>
                                </a>
                                </form>
                                </li>
                            </ul><!-- End Profile Dropdown Items -->
                        </div><!-- End Profile Iamge Icon -->
                    </li><!-- End Profile Nav -->
                </ul>
            </nav><!-- End Icons Navigation -->
        </header><!-- End Header -->

        @include('layouts.sidebar')
        
        <main id="main" class="main">
            <!-- Showing notification succeded -->
            @if(session()->has('success'))
                <script>
                    swal("Berhasil!", "{{ session('success') }}", "success", {
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

            <!-- Title Bar -->
            <div class="pagetitle">
                <nav style="--bs-breadcrumb-divider: '';">
                    <ol class="breadcrumb">
                        <h1 class="border-end border-2 pe-3 me-3"><b>{{ $path }}</b></h1>
                        <li class="breadcrumb-item" style="padding-top:5px;"><a href="/dashboard/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">Home</a></li><i class="bx bxs-chevron-right p-2"></i>
                        @if($path == $path2)
                        <li class="breadcrumb-item active" style="padding-top:5px;">{{ $path }}</li>
                        @else
                        <li class="breadcrumb-item" style="padding-top:5px;">{{ $path }}</li><i class="bx bxs-chevron-right p-2"></i>
                        <li class="breadcrumb-item active" style="padding-top:5px;">{{ $path2 }}</li>
                        @endif
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
    </div>

    <script>
        $(document).ready(function(){
            $('#role').select2({
                dropdownParent: $('#role').parent() // Menentukan parent untuk dropdown
            });

            $('#position_id').select2({
                dropdownParent: $('#position_id').parent() // Menentukan parent untuk dropdown
            });

            $('#location').select2({
                dropdownParent: $('#location').parent() // Menentukan parent untuk dropdown
            });

            $('#sub_divisi').select2({
                dropdownParent: $('#sub_divisi').parent() // Menentukan parent untuk dropdown
            });

            $('#client_id').select2({
                dropdownParent: $('#client_id').parent() // Menentukan parent untuk dropdown
            });

            $('#ticket_for').select2({
                dropdownParent: $('#ticket_for').parent() // Menentukan parent untuk dropdown
            });

            $('#asset_id').select2({
                dropdownParent: $('#asset_id').parent() // Menentukan parent untuk dropdown
            });

            $('#category_asset_id').select2({
                dropdownParent: $('#category_asset_id').parent() // Menentukan parent untuk dropdown
            });

            $('#wilayah').select2({
                dropdownParent: $('#wilayah').parent() // Menentukan parent untuk dropdown
            });

            $('#regional').select2({
                dropdownParent: $('#regional').parent() // Menentukan parent untuk dropdown
            });

            $('#area').select2({
                dropdownParent: $('#area').parent() // Menentukan parent untuk dropdown
            });

            $('#category_ticket_id').select2({
                dropdownParent: $('#category_ticket_id').parent() // Menentukan parent untuk dropdown
            });

            $('#asset_change').select2({
                dropdownParent: $('#asset_change').parent() // Menentukan parent untuk dropdown
            });
        });
    </script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('dist/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/bootstrap/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('dist/js/main.js') }}"></script>
    
    <script>
        function reloadAction(){
            window.location.reload();
            return true;
        }
    </script>
</body>
</html>