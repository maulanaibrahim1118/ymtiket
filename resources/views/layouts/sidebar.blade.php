<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}" href="/dashboard{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboards -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('tickets*') ? '' : 'collapsed' }}" href="/tickets{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
                <i class="bi bi-ticket-perforated"></i>
                <span>Ticket</span>
            </a>
        </li><!-- End Ticket -->

        <li class="nav-heading pt-3">MASTER DATA</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('clients*') ? '' : 'collapsed' }}" href="/clients">
                <i class="bi bi-people"></i>
                <span>Client</span>
            </a>
        </li><!-- End Pengguna -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('users*') ? '' : 'collapsed' }}" href="/users">
                <i class="bi bi-person-circle"></i>
                <span>Pengguna</span>
            </a>
        </li><!-- End Pengguna -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('locations*') ? '' : 'collapsed' }}" href="/locations">
                <i class="bi bi-geo-alt"></i>
                <span>Lokasi</span>
            </a>
        </li><!-- End Lokasi -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('assets*') ? '' : 'collapsed' }}" href="/assets">
                <i class="bi bi-box2"></i>
                <span>Asset</span>
            </a>
        </li><!-- End Asset -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('category*') ? '' : 'collapsed' }}" data-bs-target="#category" data-bs-toggle="collapse" href="#">
                <i class="bi bi-ui-radios-grid"></i><span>Kategori</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="category" class="nav-content collapse {{ Request::is('category*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/category-assets" class="{{ Request::is('category-assets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Kategori Asset</span>
                    </a>
                </li>
                <li>
                    <a href="/category-tickets" class="{{ Request::is('category-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Kategori Ticket</span>
                    </a>
                </li>
                <li>
                    <a href="/category-sub-tickets" class="{{ Request::is('category-sub-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Sub Kategori Ticket</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Master Data -->

        <li class="nav-heading pt-3">PAGES</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('setting*') ? '' : 'collapsed' }}" data-bs-target="#setting" data-bs-toggle="collapse" href="#">
                <i class="bx bx-cog"></i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="setting" class="nav-content collapse {{ Request::is('setting*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            </ul>
        </li><!-- End Setting -->
    </ul>
</aside><!-- End Sidebar-->