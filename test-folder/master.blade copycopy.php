<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SRS Admin</title>
    
    <link rel="shortcut icon" href="{{ asset('./adminassets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('./adminassets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('./adminassets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('./adminassets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('./adminassets/compiled/css/table-datatable.css') }}">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 0.25rem;
        }
        .bg-pending { background-color: #ffc107; color: #000; }
        .bg-verified { background-color: #17a2b8; color: #fff; }
        .bg-approved { background-color: #28a745; color: #fff; }
        .bg-rejected { background-color: #dc3545; color: #fff; }
        .card-stat { transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
        
        /* Custom styles for admin dashboard */
        .sidebar-item.active {
            background-color: #e9ecef;
            border-left: 4px solid #007bff;
        }
        .sidebar-item.active .sidebar-link {
            color: #007bff !important;
        }
        .sidebar-item.has-sub.active .sidebar-link {
            color: #007bff !important;
        }
    </style>
</head>

<body>
    <script src="{{ asset('adminassets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <h4>SRS Admin</h4>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <!-- Dashboard -->
                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.*.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-speedometer"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Applications -->
                        <li class="sidebar-item has-sub {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-files"></i>
                                <span>Applications</span>
                            </a>
                            <ul class="submenu">
                                @if(auth()->guard('admin')->user()->role === 'global_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.applications.all') ? 'active' : '' }}">
                                    <a href="{{ route('admin.applications.all') }}">All Applications</a>
                                </li>
                                @endif
                                
                                @if(auth()->guard('admin')->user()->role === 'fa_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.applications.finance') ? 'active' : '' }}">
                                    <a href="{{ route('admin.applications.finance') }}">Payment Verification</a>
                                </li>
                                @endif
                                
                                @if(auth()->guard('admin')->user()->role === 'haa_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.applications.academic') ? 'active' : '' }}">
                                    <a href="{{ route('admin.applications.academic') }}">Academic Review</a>
                                </li>
                                @endif
                                
                                @if(auth()->guard('admin')->user()->role === 'hod_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.applications.hod') ? 'active' : '' }}">
                                    <a href="{{ route('admin.applications.hod') }}">Final Approval</a>
                                </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Staff Management -->
                        @if(in_array(auth()->guard('admin')->user()->role, ['global_admin', 'hod_admin']))
                        <li class="sidebar-item has-sub {{ request()->routeIs('admin.staff.*') || request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-badge"></i>
                                <span>Staff Management</span>
                            </a>
                            <ul class="submenu">
                                @if(auth()->guard('admin')->user()->role === 'hod_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.staff.index') }}">Department Staff</a>
                                </li>
                                @endif
                                
                                @if(auth()->guard('admin')->user()->role === 'global_admin')
                                <li class="submenu-item {{ request()->routeIs('admin.teachers.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.teachers.index') }}">All Teachers</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        <!-- Users -->
                        @if(in_array(auth()->guard('admin')->user()->role, ['global_admin', 'hsa_admin']))
                        <li class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        @endif

                        <!-- Payments -->
                        @if(in_array(auth()->guard('admin')->user()->role, ['global_admin', 'fa_admin']))
                        <li class="sidebar-item {{ request()->routeIs('admin.global.payments') || request()->routeIs('admin.payments.index') ? 'active' : '' }}">
                            <a href="{{ auth()->guard('admin')->user()->role === 'global_admin' ? route('admin.global.payments') : route('admin.payments.index') }}" class='sidebar-link'>
                                <i class="bi bi-credit-card"></i>
                                <span>Payments</span>
                            </a>
                        </li>
                        @endif

                        <!-- Reports -->
                        @if(in_array(auth()->guard('admin')->user()->role, ['global_admin', 'fa_admin']))
                        <li class="sidebar-item {{ request()->routeIs('admin.global.reports') || request()->routeIs('admin.financial-reports') ? 'active' : '' }}">
                            <a href="{{ auth()->guard('admin')->user()->role === 'global_admin' ? route('admin.global.reports') : route('admin.financial-reports') }}" class='sidebar-link'>
                                <i class="bi bi-graph-up"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        @endif

                        <!-- Department (HOD Only) -->
                        @if(auth()->guard('admin')->user()->role === 'hod_admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.my-department') ? 'active' : '' }}">
                            <a href="{{ route('admin.my-department') }}" class='sidebar-link'>
                                <i class="bi bi-building"></i>
                                <span>My Department</span>
                            </a>
                        </li>
                        @endif

                        <!-- Student Affairs (HSA Only) -->
                        @if(auth()->guard('admin')->user()->role === 'hsa_admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.staff.management') ? 'active' : '' }}">
                            <a href="{{ route('admin.staff.management') }}" class='sidebar-link'>
                                <i class="bi bi-person-gear"></i>
                                <span>Staff Management</span>
                            </a>
                        </li>
                        @endif

                        <!-- Profile & Settings -->
                        <li class="sidebar-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                            <a href="{{ route('admin.profile') }}" class='sidebar-link'>
                                <i class="bi bi-person"></i>
                                <span>Profile</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}" class='sidebar-link'>
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                        </li>

                        <!-- Logout -->
                        <li class="sidebar-item">
                            <a href="{{ route('admin.logout') }}" class='sidebar-link' onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>@yield('page-title', 'Dashboard')</h3>
                            <p class="text-subtitle text-muted">@yield('page-subtitle', 'Admin Panel')</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Home')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="page-content">
                <section class="section">
                    <!-- Alerts -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>{{ date('Y') }} &copy; SRS - Student Registration System</p>
                    </div>
                    <div class="float-end">
                        <p>Welcome, {{ Auth::guard('admin')->user()->name }} ({{ Auth::guard('admin')->user()->role }})</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('adminassets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('adminassets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('adminassets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('adminassets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('adminassets/static/js/pages/simple-datatables.js') }}"></script>

    @stack('scripts')
</body>
</html>