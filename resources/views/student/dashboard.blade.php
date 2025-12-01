<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - WYTU University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
            color: white;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-left: 4px solid #1976d2;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="WYTU Logo" width="60" class="mb-3" onerror="this.style.display='none'">
                        <h5 class="mb-0">WYTU University</h5>
                        <small>Student Portal</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('student.profile') }}">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                        <a class="nav-link" href="{{ route('student.payments') }}">
                            <i class="bi bi-credit-card"></i> Payment History
                        </a>
                        <a class="nav-link" href="{{ route('student.password.change') }}">
                            <i class="bi bi-shield-lock"></i> Change Password
                        </a>
                        <hr class="my-3 opacity-25">
                        <form method="POST" action="{{ route('student.logout') }}" class="nav-link p-0">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-start w-100 p-0 border-0">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    <!-- Welcome Section -->
                    <div class="welcome-section">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2">Welcome back, {{ $student->name }}! 👋</h2>
                                <p class="mb-0">Here's your academic overview and important updates.</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="bg-white text-dark rounded p-3 d-inline-block">
                                    <small class="text-muted d-block">Student ID</small>
                                    <strong>{{ $student->student_id }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle p-3 me-3">
                                        <i class="bi bi-building text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Department</h6>
                                        <h4 class="mb-0">{{ $stats['department'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle p-3 me-3">
                                        <i class="bi bi-calendar text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Academic Year</h6>
                                        <h4 class="mb-0">{{ $stats['academic_year'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info rounded-circle p-3 me-3">
                                        <i class="bi bi-cash-coin text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Total Payments</h6>
                                        <h4 class="mb-0">{{ $stats['total_payments'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-info-circle me-2"></i>Application Status
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Payment Status:</strong> 
                                                <span class="badge bg-{{ $stats['payment_status'] == 'verified' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($stats['payment_status']) }}
                                                </span>
                                            </p>
                                            <p><strong>Application Status:</strong> 
                                                <span class="badge bg-{{ $stats['application_status'] == 'approved' ? 'success' : 'info' }}">
                                                    {{ ucfirst($stats['application_status']) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Registration Date:</strong> {{ $stats['registration_date'] }}</p>
                                            <p><strong>Last Login:</strong> {{ $student->last_login_at ? $student->last_login_at->diffForHumans() : 'First login' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-lightning me-2"></i>Quick Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('student.profile') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                                                <i class="bi bi-person fs-1 d-block mb-2"></i>
                                                Update Profile
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('student.payments') }}" class="btn btn-outline-success w-100 h-100 py-3">
                                                <i class="bi bi-credit-card fs-1 d-block mb-2"></i>
                                                View Payments
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('student.password.change') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                                                <i class="bi bi-shield-lock fs-1 d-block mb-2"></i>
                                                Change Password
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <form method="POST" action="{{ route('student.logout') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100 h-100 py-3">
                                                    <i class="bi bi-box-arrow-right fs-1 d-block mb-2"></i>
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>