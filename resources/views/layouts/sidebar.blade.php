<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}" href="/dashboard">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboards -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('tickets*') ? '' : 'collapsed' }}" href="/tickets">
                <i class="bi bi-ticket-perforated"></i>
                <span>Ticket</span>
            </a>
        </li><!-- End Ticket -->

        @can('isServiceDesk')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('agents*') ? '' : 'collapsed' }}" href="/agents">
                <i class="bi bi-person-workspace"></i>
                <span>Agent</span>
            </a>
        </li><!-- End Agent -->
        @endcan

        @can('manage-ticket')
        <li class="nav-heading pt-3">MASTER DATA</li>
        @endcan

        @can('isServiceDesk')
        @can('isIT')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('users*') ? '' : 'collapsed' }}" href="/users">
                <i class="bi bi-person-check"></i>
                <span>User</span>
            </a>
        </li><!-- End User -->
        @endcan
        @endcan

        {{-- <li class="nav-item">
            <a class="nav-link {{ Request::is('clients*') ? '' : 'collapsed' }}" href="/clients">
                <i class="bi bi-people"></i>
                <span>Client</span>
            </a>
        </li><!-- End Client --> --}}

        @can('client')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('assets*') ? '' : 'collapsed' }}" href="/assets">
                <i class="bi bi-gem"></i>
                <span>Asset</span>
            </a>
        </li><!-- End Asset -->
        @endcan

        @can('isServiceDesk')
        @can('isIT')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('asset*') ? '' : 'collapsed' }}" data-bs-target="#assetSideBar" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gem"></i><span>Asset</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="assetSideBar" class="nav-content collapse {{ Request::is('asset*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/asset-items" class="{{ Request::is('lasset-items*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Item</span>
                    </a>
                </li>
                <li>
                    <a href="/assets" class="{{ Request::is('assets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Asset</span>
                    </a>
                </li>
                <li>
                    <a href="/asset-categories" class="{{ Request::is('asset-categories*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Asset Category</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Asset -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('location*') ? '' : 'collapsed' }}" data-bs-target="#locationSideBar" data-bs-toggle="collapse" href="#">
                <i class="bi bi-geo-alt"></i><span>Location</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="locationSideBar" class="nav-content collapse {{ Request::is('location*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/locations" class="{{ Request::is('locations*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Store & Division</span>
                    </a>
                </li>
                <li>
                    <a href="/location-sub-divisions" class="{{ Request::is('location-sub-divisions*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Sub Division</span>
                    </a>
                </li>
                <li>
                    <a href="/location-wilayahs" class="{{ Request::is('location-wilayahs*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Wilayah</span>
                    </a>
                </li>
                <li>
                    <a href="/location-regionals" class="{{ Request::is('location-regionals*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Regional</span>
                    </a>
                </li>
                <li>
                    <a href="/location-areas" class="{{ Request::is('location-areas*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Area</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Location -->
        @endcan

        <li class="nav-item">
            <a class="nav-link {{ Request::is('category*') ? '' : 'collapsed' }}" data-bs-target="#category" data-bs-toggle="collapse" href="#">
                <i class="bi bi-ui-radios-grid"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="category" class="nav-content collapse {{ Request::is('category*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/category-tickets" class="{{ Request::is('category-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Ticket Category</span>
                    </a>
                </li>
                <li>
                    <a href="/category-sub-tickets" class="{{ Request::is('category-sub-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Ticket Sub Category</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Master Data -->
        @endcan

        <li class="nav-heading pt-3">PAGES</li>

        @can('isServiceDesk')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('report*') ? '' : 'collapsed' }}" data-bs-target="#report" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-bar-graph"></i><span>Report</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="report" class="nav-content collapse {{ Request::is('report*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/report-agents" class="{{ Request::is('report-agents*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Agent</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('report.subCategory') }}" class="{{ Request::is('report-sub-categories*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Sub Category</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('report.location') }}" class="{{ Request::is('report-locations*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Store & Division</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Report -->
        @endcan

        {{-- <li class="nav-item">
            <a class="nav-link {{ Request::is('settings*') ? '' : 'collapsed' }}" data-bs-target="#setting" data-bs-toggle="collapse" href="#">
                <i class="bx bx-cog"></i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="setting" class="nav-content collapse {{ Request::is('settings*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/profile" class="{{ Request::is('profile*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="/settings-change-password" class="{{ Request::is('settings-change-password') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Change Password</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Setting --> --}}

        <li class="nav-item">
            <a class="nav-link {{ Request::is('profile*') ? '' : 'collapsed' }}" href="/profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile -->
    </ul>
</aside><!-- End Sidebar-->