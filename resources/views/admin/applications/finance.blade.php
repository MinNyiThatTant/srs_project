@extends('admin.master')

@section('title', 'Payment Verification - Finance Admin')
@section('page-title', 'Payment Verification')
@section('page-subtitle', 'Verify student payments')
@section('breadcrumb', 'Finance Applications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications Pending Payment Verification</h4>
                <p class="text-muted">Verify payments before moving to academic review</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="table1">
                        <thead>
                            <tr>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Application Type</th>
                                <th>Payment Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->application_type) }} Student
                                    </span>
                                </td>
                                <td>
                                    @if($application->payment_status === 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending Verification</span>
                                    @endif
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($application->payment_status === 'pending')
                                        <a href="{{ route('admin.applications.verify-payment', $application->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Verify payment for {{ $application->name }}? This will move application to academic review.')"
                                           title="Verify Payment">
                                            <i class="bi bi-check-circle"></i> Verify
                                        </a>
                                        @else
                                        <button class="btn btn-sm btn-outline-success" disabled title="Already Verified">
                                            <i class="bi bi-check-circle"></i> Verified
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $applications->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No applications pending payment verification.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Payment Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-stat bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $stats['pending_verifications'] ?? 0 }}</h3>
                                        <p>Pending Verification</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock-history fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stat bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $stats['verified_today'] ?? 0 }}</h3>
                                        <p>Verified Today</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stat bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $stats['total_verified'] ?? 0 }}</h3>
                                        <p>Total Verified</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-send-check fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stat bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>₹{{ number_format($stats['total_payments'] ?? 0) }}</h3>
                                        <p>Total Revenue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-currency-dollar fs-1"></i>
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
@endsection