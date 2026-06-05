<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard') }}">
                                <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user"
                                    class="user-avtar rounded-circle" style="width: 50px; height: 50px;" />
                            </a>
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <small class="text-muted text-capitalize">{{ Auth::user()->getRoleLabel() }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">

                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>

                {{-- ══ Dashboard — All Roles ══ --}}
                <li class="pc-item">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- ══ Delivery Slips — All Roles ══ --}}
                <li class="pc-item">
                    <a href="{{ route('slips.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i class="ti ti-file-text"></i>
                        </span>
                        <span class="pc-mtext">Delivery Slips</span>
                    </a>
                </li>

                {{-- ══ Admin Only ══ --}}
                @if (Auth::user()->isAdmin())
                    <li class="pc-item pc-caption">
                        <label>Administration</label>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('management.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-settings"></i>
                            </span>
                            <span class="pc-mtext">Management</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="pc-mtext">Users</span>
                        </a>
                    </li>
                @elseif (Auth::user()->isManager())
                    <li class="pc-item pc-caption">
                        <label>Administration</label>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('management.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-settings"></i>
                            </span>
                            <span class="pc-mtext">Management</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Logout ══ --}}
                <li class="pc-item pc-caption">
                    <label>Account</label>
                </li>

                <li class="pc-item">
                    <a href="#" class="pc-link text-danger"
                        onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <span class="pc-micon">
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="pc-mtext">Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
