<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - WYTU')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .content-wrapper {
            min-height: calc(100vh - 56px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-university"></i> WYTU Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::guard('admin')->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="fas fa-user"></i> Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                                    @csrf
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- HOD Specific Menu -->
                        @if(Auth::guard('admin')->user()->role === 'hod_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.hod.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.hod.dashboard') }}">
                                <i class="fas fa-home"></i> HOD Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.applications.hod') ? 'active' : '' }}" 
                               href="{{ route('admin.applications.hod') }}">
                                <i class="fas fa-clipboard-list"></i> Pending Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.my-department') ? 'active' : '' }}" 
                               href="{{ route('admin.my-department') }}">
                                <i class="fas fa-building"></i> My Department
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.department.applications') ? 'active' : '' }}" 
                               href="{{ route('admin.department.applications') }}">
                                <i class="fas fa-list"></i> All Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.hod.staff.index') ? 'active' : '' }}" 
                               href="{{ route('admin.hod.staff.index') }}">
                                <i class="fas fa-users"></i> Staff Management
                            </a>
                        </li>
                        @endif

                        <!-- Global Admin Menu -->
                        @if(Auth::guard('admin')->user()->role === 'global_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.global.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.global.dashboard') }}">
                                <i class="fas fa-globe"></i> Global Dashboard
                            </a>
                        </li>
                        @endif

                        <!-- Finance Admin Menu -->
                        @if(Auth::guard('admin')->user()->role === 'fa_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.finance.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.finance.dashboard') }}">
                                <i class="fas fa-money-bill"></i> Finance Dashboard
                            </a>
                        </li>
                        @endif

                        <!-- Academic Admin Menu -->
                        @if(Auth::guard('admin')->user()->role === 'haa_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.academic.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.academic.dashboard') }}">
                                <i class="fas fa-graduation-cap"></i> Academic Dashboard
                            </a>
                        </li>
                        @endif

                        <!-- Common Admin Links -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" 
                               href="{{ route('admin.profile') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" 
                               href="{{ route('admin.settings') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('header_buttons')
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Confirm before destructive actions
        function confirmAction(message) {
            return confirm(message || 'Are you sure you want to perform this action?');
        }
    </script>
    
    @stack('scripts')
</body>
</html>