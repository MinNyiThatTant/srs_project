<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - WYTU University</title>
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
        .profile-header {
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
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link active" href="{{ route('student.profile') }}">
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
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2">My Profile</h2>
                                <p class="mb-0">Manage your personal information and account settings</p>
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

                    <!-- Profile Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-person-gear me-2"></i>Personal Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('student.profile.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="{{ old('name', $student->name) }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="email" 
                                                       value="{{ $student->email }}" readonly>
                                                <small class="form-text text-muted">Email cannot be changed</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" 
                                                       value="{{ old('phone', $student->phone) }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                                       value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="department" class="form-label">Department</label>
                                                <input type="text" class="form-control" id="department" 
                                                       value="{{ $student->department }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="academic_year" class="form-label">Academic Year</label>
                                                <input type="text" class="form-control" id="academic_year" 
                                                       value="{{ $student->academic_year }}" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" 
                                                      required>{{ old('address', $student->address) }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gender</label>
                                                <input type="text" class="form-control" 
                                                       value="{{ ucfirst($student->gender) }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">NRC Number</label>
                                                <input type="text" class="form-control" 
                                                       value="{{ $student->nrc_number }}" readonly>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bi bi-check-circle me-2"></i>Update Profile
                                            </button>
                                        </div>
                                    </form>
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