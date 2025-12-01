<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - WYTU University</title>
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
        .password-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('student.profile') }}">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                        <a class="nav-link" href="{{ route('student.payments') }}">
                            <i class="bi bi-credit-card"></i> Payment History
                        </a>
                        <a class="nav-link active" href="{{ route('student.password.change') }}">
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
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card password-card">
                                <div class="card-header bg-primary text-white text-center py-4">
                                    <h4 class="mb-0">
                                        <i class="bi bi-shield-lock me-2"></i>Change Password
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show">
                                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show">
                                            @foreach($errors->all() as $error)
                                                <i class="bi bi-exclamation-triangle me-2"></i>{{ $error }}<br>
                                            @endforeach
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('student.password.change.submit') }}">
                                        @csrf
                                        
                                        <div class="mb-4">
                                            <label for="current_password" class="form-label fw-bold">Current Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-lock text-primary"></i>
                                                </span>
                                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                       id="current_password" name="current_password" required
                                                       placeholder="Enter your current password">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="new_password" class="form-label fw-bold">New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-key text-primary"></i>
                                                </span>
                                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                       id="new_password" name="new_password" required
                                                       placeholder="Enter new password (min. 8 characters)">
                                            </div>
                                            <div class="form-text">
                                                Password must be at least 8 characters long
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="new_password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-key-fill text-primary"></i>
                                                </span>
                                                <input type="password" class="form-control" 
                                                       id="new_password_confirmation" name="new_password_confirmation" required
                                                       placeholder="Confirm your new password">
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                                <i class="bi bi-shield-check me-2"></i>Update Password
                                            </button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-4">
                                        <a href="{{ route('student.dashboard') }}" class="text-decoration-none text-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                                        </a>
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