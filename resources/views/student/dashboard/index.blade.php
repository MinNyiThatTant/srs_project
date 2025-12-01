@extends('student.layouts.master')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card welcome-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-1">Welcome back, {{ $student->name }}!</h3>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person-badge me-1"></i>{{ $student->student_id }} 
                            | <i class="bi bi-building me-1"></i>{{ $student->department }}
                            @if($student->needs_password_change)
                                <span class="badge bg-warning ms-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Change Password
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="avatar-lg mx-auto">
                            <img src="{{ $student->profile_picture_url }}" 
                                 alt="Profile" class="img-thumbnail rounded-circle" 
                                 style="width: 80px; height: 80px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Stats -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Department</h6>
                            <h4 class="font-extrabold mb-0">{{ Str::limit($student->department, 12) }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-building text-white"></i>
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
                            <h6 class="text-muted">Status</h6>
                            <h4 class="font-extrabold mb-0">
                                <span class="badge bg-success">Active</span>
                            </h4>
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
                            <h6 class="text-muted">Registration</h6>
                            <h4 class="font-extrabold mb-0">{{ $student->registration_date->format('M Y') }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-calendar-check text-white"></i>
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
                            <h6 class="text-muted">Last Login</h6>
                            <h4 class="font-extrabold mb-0">
                                @if($student->last_login_at)
                                    {{ $student->last_login_at->diffForHumans() }}
                                @else
                                    First time
                                @endif
                            </h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-clock-history text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('student.profile') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="bi bi-person fs-2 d-block mb-2"></i>
                            My Profile
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('student.academic.info') }}" class="btn btn-outline-info w-100 h-100 py-3">
                            <i class="bi bi-journal-text fs-2 d-block mb-2"></i>
                            Academic Info
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('student.fees.info') }}" class="btn btn-outline-success w-100 h-100 py-3">
                            <i class="bi bi-credit-card fs-2 d-block mb-2"></i>
                            Fee Payments
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('student.documents') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="bi bi-folder fs-2 d-block mb-2"></i>
                            Documents
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('student.password.change') }}" class="btn btn-outline-danger w-100 h-100 py-3">
                            <i class="bi bi-shield-lock fs-2 d-block mb-2"></i>
                            Change Password
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-3">
                        <a href="{{ route('index') }}" class="btn btn-outline-secondary w-100 h-100 py-3">
                            <i class="bi bi-house fs-2 d-block mb-2"></i>
                            University Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Recent Activities</h4>
            </div>
            <div class="card-body">
                @if(count($recentActivities) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-{{ $activity['color'] }}-subtle text-{{ $activity['color'] }}">
                                            <i class="{{ $activity['icon'] }}"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{ $activity['action'] }}</h6>
                                    <p class="text-muted fs-12 mb-0">{{ $activity['date'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-info-circle text-muted fs-1"></i>
                        <p class="text-muted mt-2">No recent activities</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($student->needs_password_change)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Security Recommendation:</strong> Please change your password for security purposes.
            <a href="{{ route('student.password.change') }}" class="alert-link">Change Password Now</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif
@endsection