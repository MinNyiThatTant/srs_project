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
                                    <span class="status-badge bg-{{ $application->payment_status === 'completed' ? 'verified' : 'pending' }}">
                                        {{ ucfirst($application->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($application->payment_status === 'completed' && $application->status === 'payment_pending')
                                        <form action="{{ route('admin.applications.verify-payment', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Verify payment for {{ $application->name }}?')"
                                                    title="Verify Payment">
                                                <i class="bi bi-check-circle"></i> Verify
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                                        <h3>{{ $applications->where('payment_status', 'pending')->count() }}</h3>
                                        <p>Pending Payments</p>
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
                                        <h3>{{ $applications->where('payment_status', 'completed')->count() }}</h3>
                                        <p>Verified Payments</p>
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
                                        <h3>{{ $applications->where('status', 'payment_verified')->count() }}</h3>
                                        <p>Ready for Academic</p>
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
                                        <h3>{{ $applications->count() }}</h3>
                                        <p>Total in Queue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-list-task fs-1"></i>
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