@extends('admin.layouts.master')

@section('title', 'Global Admin Dashboard')
@section('page-title', 'Global Admin Dashboard')
@section('page-subtitle', 'System Overview & Management')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Total Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_applications'] }}</h4>
                            <p class="text-success small mb-0">
                                <i class="bi bi-arrow-up"></i> {{ $stats['pending_applications'] }} pending
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
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
                            <h6 class="text-muted">Total Users</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_users'] }}</h4>
                            <p class="text-info small mb-0">
                                <i class="bi bi-people"></i> {{ $stats['total_admins'] }} admins
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
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
                            <h6 class="text-muted">Total Payments</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($stats['total_payments']) }} MMK</h4>
                            <p class="text-warning small mb-0">
                                <i class="bi bi-credit-card"></i> {{ $stats['payment_stats']['completed'] }} completed
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-currency-dollar text-white"></i>
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
                            <h6 class="text-muted">System Admins</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_admins'] }}</h4>
                            <p class="text-primary small mb-0">
                                <i class="bi bi-person-gear"></i> {{ $stats['active_admins'] }} active
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-person-badge text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Recent Applications</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.applications.all') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_applications'] as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $application->department }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : ($application->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                        {{ $application->payment_status }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.applications.view', $application->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
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
    
    <div class="col-12 col-lg-4">
        <!-- Payment Statistics -->
        <div class="card">
            <div class="card-header">
                <h4>Payment Statistics</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Completed</span>
                        <span class="fw-bold text-success">{{ $stats['payment_stats']['completed'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ ($stats['payment_stats']['completed'] / max(array_sum($stats['payment_stats']), 1)) * 100 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Pending</span>
                        <span class="fw-bold text-warning">{{ $stats['payment_stats']['pending'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($stats['payment_stats']['pending'] / max(array_sum($stats['payment_stats']), 1)) * 100 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Failed</span>
                        <span class="fw-bold text-danger">{{ $stats['payment_stats']['failed'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: {{ ($stats['payment_stats']['failed'] / max(array_sum($stats['payment_stats']), 1)) * 100 }}%"></div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <h5>Total Revenue</h5>
                    <h3 class="text-success">{{ number_format($stats['total_payments']) }} MMK</h3>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.applications.all') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-files me-2"></i> Manage Applications
                    </a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-success text-start">
                        <i class="bi bi-people me-2"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.global.payments') }}" class="btn btn-outline-info text-start">
                        <i class="bi bi-credit-card me-2"></i> Payment Reports
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-warning text-start">
                        <i class="bi bi-person-badge me-2"></i> Teacher Management
                    </a>
                    <a href="{{ route('admin.global.reports') }}" class="btn btn-outline-dark text-start">
                        <i class="bi bi-graph-up me-2"></i> System Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection