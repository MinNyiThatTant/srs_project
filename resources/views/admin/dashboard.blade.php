{{-- resources/views/admin/global/dashboard.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Global Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Overview</h1>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Applications</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_applications'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-files fa-2x text-gray-300"></i>
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
                            Payment Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['payment_pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Approved Applications</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved_applications'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                            Completed Payments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_payments'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-credit-card fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Applications -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Applications</h6>
                <a href="{{ route('admin.global.applications') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Application ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $application)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.global.applications.view', $application->id) }}">
                                        {{ $application->application_id }}
                                    </a>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->application_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $application->status_badge }}">
                                        {{ $application->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $application->payment_status_badge }}">
                                        {{ $application->payment_status_text }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.global.applications', ['status' => 'payment_pending']) }}" 
                       class="btn btn-warning btn-block">
                        <i class="bi bi-credit-card me-2"></i>Verify Payments
                        <span class="badge bg-danger">{{ $stats['payment_pending'] }}</span>
                    </a>
                    <a href="{{ route('admin.global.applications', ['status' => 'payment_verified']) }}" 
                       class="btn btn-info btn-block">
                        <i class="bi bi-check-circle me-2"></i>Academic Review
                        <span class="badge bg-danger">{{ $stats['payment_verified'] }}</span>
                    </a>
                    <a href="{{ route('admin.global.applications', ['status' => 'academic_approved']) }}" 
                       class="btn btn-success btn-block">
                        <i class="bi bi-award me-2"></i>Final Approval
                        <span class="badge bg-danger">{{ $stats['academic_approved'] }}</span>
                    </a>
                    <a href="{{ route('admin.global.payments', ['status' => 'pending']) }}" 
                       class="btn btn-secondary btn-block">
                        <i class="bi bi-clock-history me-2"></i>Pending Payments
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
            </div>
            <div class="card-body">
                @foreach($recentPayments as $payment)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                    <div>
                        <small class="text-muted">{{ $payment->application->name ?? 'N/A' }}</small>
                        <br>
                        <strong>{{ $payment->transaction_id }}</strong>
                    </div>
                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection