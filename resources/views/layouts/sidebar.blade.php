<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}" href="/dashboard/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboards -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('tickets*') ? '' : 'collapsed' }}" href="/tickets/{{ encrypt(auth()->user()->id) }}-{{encrypt(auth()->user()->role) }}">
                <i class="bi bi-ticket-perforated"></i>
                <span>Ticket</span>
            </a>
        </li><!-- End Ticket -->

        @can('isServiceDesk')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('agents*') ? '' : 'collapsed' }}" href="/agents/{{ encrypt(auth()->user()->location_id) }}">
                <i class="bi bi-person-workspace"></i>
                <span>Agent</span>
            </a>
        </li><!-- End Agent -->

        <li class="nav-heading pt-3">MASTER DATA</li>

        @can('isIT')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('users*') ? '' : 'collapsed' }}" href="/users">
                <i class="bi bi-person-check"></i>
                <span>User</span>
            </a>
        </li><!-- End User -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('clients*') ? '' : 'collapsed' }}" href="/clients">
                <i class="bi bi-people"></i>
                <span>Client</span>
            </a>
        </li><!-- End Client -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('assets*') ? '' : 'collapsed' }}" href="/assets">
                <i class="bi bi-box2"></i>
                <span>Asset</span>
            </a>
        </li><!-- End Asset -->

        <li class="nav-item">
            <a class="nav-link {{ Request::is('locations*') ? '' : 'collapsed' }}" href="/locations">
                <i class="bi bi-geo-alt"></i>
                <span>Location</span>
            </a>
        </li><!-- End Lokasi -->
        @endcan

        <li class="nav-item">
            <a class="nav-link {{ Request::is('category*') ? '' : 'collapsed' }}" data-bs-target="#category" data-bs-toggle="collapse" href="#">
                <i class="bi bi-ui-radios-grid"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="category" class="nav-content collapse {{ Request::is('category*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                @can('isIT')
                <li>
                    <a href="/category-assets" class="{{ Request::is('category-assets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Category Asset</span>
                    </a>
                </li>
                @endcan
                <li>
                    <a href="/category-tickets/{{ encrypt(auth()->user()->location_id) }}" class="{{ Request::is('category-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Category Ticket</span>
                    </a>
                </li>
                <li>
                    <a href="/category-sub-tickets/{{ encrypt(auth()->user()->location_id) }}" class="{{ Request::is('category-sub-tickets*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Sub Category Ticket</span>
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
                    <a href="/report-clients" class="{{ Request::is('report-clients*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Client</span>
                    </a>
                </li>
                <li>
                    <a href="/report-sub-categories" class="{{ Request::is('report-sub-categories*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Sub Category</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Report -->
        @endcan

        <li class="nav-item">
            <a class="nav-link {{ Request::is('settings*') ? '' : 'collapsed' }}" data-bs-target="#setting" data-bs-toggle="collapse" href="#">
                <i class="bx bx-cog"></i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="setting" class="nav-content collapse {{ Request::is('settings*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/settings-change-password" class="{{ Request::is('settings-change-password*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="/settings-mutation" class="{{ Request::is('settings-mutation*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i><span>Change Password</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Setting -->
    </ul>
</aside><!-- End Sidebar-->