<<<<<<< HEAD
@extends('admin.layouts.master')

@section('title', 'HOD Dashboard - ' . $departmentInfo['name'])
@section('page-title', $departmentInfo['name'] . ' Dashboard')
@section('page-subtitle', 'Department Head Management Panel')
@section('breadcrumb', 'HOD Dashboard')

@section('content')
<div class="row">
    <!-- Department Info Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="card-title">{{ $departmentInfo['name'] }} ({{ $departmentInfo['code'] }})</h3>
                        <p class="card-text">{{ $departmentInfo['description'] }}</p>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small><i class="bi bi-person"></i> HOD: {{ $departmentInfo['head'] }}</small>
                            </div>
                            <div class="col-6">
                                <small><i class="bi bi-telephone"></i> {{ $departmentInfo['phone'] }}</small>
                            </div>
                            <div class="col-6 mt-2">
                                <small><i class="bi bi-envelope"></i> {{ $departmentInfo['email'] }}</small>
                            </div>
                            <div class="col-6 mt-2">
                                <small><i class="bi bi-building"></i> {{ $departmentInfo['location'] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="department-stats">
                            <h2 class="display-4">{{ $stats['active_students'] }}</h2>
                            <p>Active Students</p>
                        </div>
                    </div>
=======
{{-- resources/views/admin/hod/dashboard-hod.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'HOD Dashboard - ' . ($departmentInfo['name'] ?? 'Department'))

@section('content')
<div class="container-fluid">
    <!-- Department Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">
                        <i class="fas fa-building"></i> 
                        {{ $departmentInfo['name'] ?? 'Department' }} - HOD Dashboard
                    </h1>
                    <p class="card-text text-muted">
                        Welcome, {{ $admin->name }} | Department Code: {{ $departmentInfo['code'] ?? 'N/A' }}
                    </p>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
<<<<<<< HEAD
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Pending Reviews</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_reviews'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-clock text-white"></i>
=======
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_reviews'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                        </div>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Approved Today</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['approved_today'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Approved</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_approved'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-list-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_applications'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-people text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Applications Pending Final Approval
                </h4>
                <a href="{{ route('admin.applications.hod') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i> View All Applications
                </a>
            </div>
            <div class="card-body">
                @if($stats['recent_applications']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Matriculation Score</th>
                                <th>Assigned Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_applications'] as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $application->matriculation_score }}/600</span>
                                </td>
                                <td>{{ $application->academic_approved_at ? $application->academic_approved_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="View Application Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" 
                                                    onclick="return confirm('Grant final approval to {{ $application->name }}?')"
                                                    title="Final Approval">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>No applications pending final approval.</strong>
                    <p class="mb-0 mt-2">All academically approved applications have been processed.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.applications.hod') }}" class="btn btn-outline-primary btn-lg text-start">
                        <i class="bi bi-files me-2"></i>
                        <div>
                            <strong>Pending Applications</strong>
                            <br>
                            <small class="text-muted">View applications waiting for final approval</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.department.applications') }}" class="btn btn-outline-info btn-lg text-start">
                        <i class="bi bi-list-ul me-2"></i>
                        <div>
                            <strong>All Department Applications</strong>
                            <br>
                            <small class="text-muted">View all applications in your department</small>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.hod.staff.index') }}" class="btn btn-outline-success btn-lg text-start">
                        <i class="bi bi-people me-2"></i>
                        <div>
                            <strong>Staff Management</strong>
                            <br>
                            <small class="text-muted">Manage department staff</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Department Workflow
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <h6 class="mb-1">1. Student Application</h6>
                        <small class="text-muted">Student submits application with preferences</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">2. Payment Verification</h6>
                        <small class="text-muted">Finance admin verifies payment completion</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">3. Academic Approval</h6>
                        <small class="text-muted">Academic admin assigns department & creates student account</small>
                    </div>
                    <div class="list-group-item bg-light">
                        <h6 class="mb-1">4. HOD Final Approval</h6>
                        <small class="text-muted"><strong>You are here</strong> - Review and give final approval</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">5. Student Activation</h6>
                        <small class="text-muted">Student account becomes fully active</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-stat {
    transition: transform 0.2s;
}

.card-stat:hover {
    transform: translateY(-5px);
}

.department-stats {
    border-left: 3px solid rgba(255,255,255,0.5);
    padding-left: 20px;
}
</style>
=======

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['approved_today'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_approved'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Applications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_applications'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Information -->
    @if(isset($departmentInfo))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle"></i> Department Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Department Head:</strong> {{ $departmentInfo['head'] }}</p>
                            <p><strong>Email:</strong> {{ $departmentInfo['email'] }}</p>
                            <p><strong>Phone:</strong> {{ $departmentInfo['phone'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Location:</strong> {{ $departmentInfo['location'] }}</p>
                            <p><strong>Established:</strong> {{ $departmentInfo['established'] }}</p>
                            <p><strong>Active Students:</strong> {{ $departmentInfo['active_students'] }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Description:</strong> {{ $departmentInfo['description'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Applications -->
    @if(isset($stats['recent_applications']) && $stats['recent_applications']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-clock"></i> Recent Applications Pending Review
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Application ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_applications'] as $application)
                                <tr>
                                    <td>{{ $application->application_id ?? $application->id }}</td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No applications pending review</h5>
                    <p class="text-muted">There are currently no applications waiting for your approval.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
@endsection