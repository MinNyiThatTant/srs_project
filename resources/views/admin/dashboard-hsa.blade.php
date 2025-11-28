@extends('admin.layouts.master')

@section('title', 'HSA Dashboard')
@section('page-title', 'HSA Dashboard')
@section('page-subtitle', 'Staff & Student Affairs Management')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Students</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_students'] ?? 0 }}</h4>
                            <p class="text-success small mb-0">
                                <i class="bi bi-arrow-up"></i> {{ $stats['student_growth'] ?? 0 }}% growth
                            </p>
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
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Active Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['active_applications'] ?? 0 }}</h4>
                            <p class="text-warning small mb-0">
                                <i class="bi bi-clock"></i> {{ $stats['pending_review'] ?? 0 }} pending
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-files text-white"></i>
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
                            <h6 class="text-muted">Pending Issues</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_issues'] ?? 0 }}</h4>
                            <p class="text-danger small mb-0">
                                <i class="bi bi-exclamation-triangle"></i> {{ $stats['urgent_issues'] ?? 0 }} urgent
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-flag text-white"></i>
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
                            <h6 class="text-muted">Resolved Today</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['resolved_today'] ?? 0 }}</h4>
                            <p class="text-info small mb-0">
                                <i class="bi bi-check-circle"></i> {{ $stats['resolution_rate'] ?? 0 }}% rate
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-check-lg text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-lg-8">
        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header">
                <h4>Recent Activities</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($stats['recent_activities']) && count($stats['recent_activities']) > 0)
                                @foreach($stats['recent_activities'] as $activity)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $activity->activity_type }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->name }}</td>
                                    <td>{{ $activity->department }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity->status_color }}">
                                            {{ $activity->status }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent activities</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Department Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Department Applications</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(isset($stats['department_stats']))
                        <div class="col-6 col-md-3 text-center mb-3">
                            <h5 class="text-primary">{{ $stats['department_stats']['ceit'] ?? 0 }}</h5>
                            <p class="text-muted mb-0">CEIT</p>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <h5 class="text-success">{{ $stats['department_stats']['civil'] ?? 0 }}</h5>
                            <p class="text-muted mb-0">Civil</p>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <h5 class="text-warning">{{ $stats['department_stats']['electronics'] ?? 0 }}</h5>
                            <p class="text-muted mb-0">Electronics</p>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <h5 class="text-info">{{ $stats['department_stats']['mechanical'] ?? 0 }}</h5>
                            <p class="text-muted mb-0">Mechanical</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <!-- System Alerts -->
        @if(isset($stats['system_alerts']) && count($stats['system_alerts']) > 0)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>System Alerts</h4>
                <span class="badge bg-danger">{{ $stats['alerts_count'] ?? 0 }}</span>
            </div>
            <div class="card-body">
                @foreach($stats['system_alerts'] as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                    <strong>{{ $alert['title'] }}</strong><br>
                    {{ $alert['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/staff-management" class="btn btn-outline-primary text-start">
                        <i class="bi bi-people me-2"></i> Staff Management
                    </a>
                    <a href="/admin/teacher-management" class="btn btn-outline-success text-start">
                        <i class="bi bi-person-badge me-2"></i> Teacher Management
                    </a>
                    <a href="/admin/users" class="btn btn-outline-info text-start">
                        <i class="bi bi-person me-2"></i> Student Management
                    </a>
                    <a href="/admin/applications/all" class="btn btn-outline-warning text-start">
                        <i class="bi bi-files me-2"></i> View Applications
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        @if(isset($stats['recent_notifications']) && count($stats['recent_notifications']) > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h4>Recent Notifications</h4>
            </div>
            <div class="card-body">
                @foreach($stats['recent_notifications'] as $notification)
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm bg-{{ $notification['type'] }} rounded-circle">
                            <i class="bi bi-{{ $notification['icon'] }} text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ $notification['title'] }}</h6>
                        <p class="text-muted mb-0 small">{{ $notification['message'] }}</p>
                        <small class="text-muted">{{ $notification['time'] }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Admin Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Admin Information</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong>
                    <p class="mb-1">{{ Auth::guard('admin')->user()->name ?? 'Not assigned' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Role:</strong>
                    <p class="mb-1">Student Affairs Admin</p>
                </div>
                <div class="mb-3">
                    <strong>Last Login:</strong>
                    <p class="mb-1">{{ Auth::guard('admin')->user()->last_login_at ? Auth::guard('admin')->user()->last_login_at->format('M d, Y H:i') : 'First login' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
</style>
@endpush