<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="/dashboard" class="logo d-flex align-items-center">
            <img src="dist/img/logo/logo.png" alt="">
            <span class="d-none d-lg-block pt-1"><b>GC-ITOP</b></span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown float-end">
                <p class="nav-link d-block float-end m-0" style="font-size: 15px;"><b>{{ ucwords(auth()->user()->nama) }}</b></p><br>
                <p class="nav-link d-block float-end m-0" style="font-size: 12px;">{{ ucwords(auth()->user()->location->nama_lokasi) }} - {{ ucwords(auth()->user()->position->nama_jabatan) }}</p>
            </li><!-- End User Profile -->

            <li class="nav-item dropdown pe-3">
                <div class="nav-link nav-profile d-flex align-items-center ms-3 me-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="dist/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                    <span class="position-absolute bottom-0 ms-4 p-1 border border-light rounded-circle" style="background-color: rgb(22, 224, 22)">
                        <span class="visually-hidden">Online</span>
                    </span>
                    </a>

                    <ul class="dropdown-menu mt-2">
                        <li class="dropdown-header">
                        <h6>{{ ucwords(auth()->user()->nama) }}</h6>
                        </li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                        <i class="bi bi-power"></i>
                        <span>Logout</span>
                        </a>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </div><!-- End Profile Iamge Icon -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->
</header><!-- End Header -->