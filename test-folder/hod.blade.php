@extends('admin.master')

@section('title', 'Final Approval - HOD Admin')
@section('page-title', 'Final Approval')
@section('page-subtitle', 'Final approval for department applications')
@section('breadcrumb', 'HOD Applications')

@section('content')
@php
    $user = Auth::guard('admin')->user();
    $department = $user->department;
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications for Final Approval - {{ $department }}</h4>
                <p class="text-muted">Final approval for academically approved applications in your department</p>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="table1">
                        <thead>
                            <tr>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Application Type</th>
                                <th>Academic Approved</th>
                                <th>Payment Status</th>
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
                                <td>
                                    <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->application_type) }} Student
                                    </span>
                                </td>
                                <td>
                                    @if($application->academic_approved_at)
                                    {{ $application->academic_approved_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">by {{ $application->academic_approved_by }}</small>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge bg-{{ $application->payment_status === 'completed' ? 'verified' : 'pending' }}">
                                        {{ ucfirst($application->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i> Details
                                        </a>
                                        
                                        <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Grant final approval for {{ $application->name }}? This will generate student ID.')"
                                                    title="Final Approve">
                                                <i class="bi bi-check-all"></i> Final Approve
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
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No applications pending final approval in your department.
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
                <h5>Department Statistics - {{ $department }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-stat bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $applications->count() }}</h3>
                                        <p>Pending Final Approval</p>
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
                                        <h3>{{ $applications->where('application_type', 'new')->count() }}</h3>
                                        <p>New Students</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person-plus fs-1"></i>
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
                                        <h3>{{ $applications->where('application_type', 'old')->count() }}</h3>
                                        <p>Existing Students</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person-check fs-1"></i>
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
                                        <h3>{{ $applications->where('payment_status', 'completed')->count() }}</h3>
                                        <p>Paid Applications</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-credit-card fs-1"></i>
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