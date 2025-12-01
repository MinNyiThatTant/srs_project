@extends('admin.layouts.master')

@section('title', 'HOD Dashboard')
@section('page-title', isset($admin) ? $admin->department . ' Department Dashboard' : 'HOD Dashboard')
@section('page-subtitle', 'Final Approval & Department Management')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Pending Final Approval</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_reviews'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-clock text-white"></i>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications Pending Final Approval</h4>
                <a href="{{ route('admin.applications.hod') }}" class="btn btn-primary btn-sm">View All Applications</a>
            </div>
            <div class="card-body">
                @if($stats['recent_applications']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Matriculation Score</th>
                                <th>Academic Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_applications'] as $application)
                            <tr>
                                <td>{{ $application->application_id ?? 'N/A' }}</td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->matriculation_score ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">Academic Approved</span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <form action="{{ route('admin.approve.final', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    onclick="return confirm('Grant final approval to this application?')">
                                                <i class="bi bi-check"></i> Final Approve
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
                    <p class="mb-0">No applications pending final approval.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.my-department') }}" class="btn btn-outline-primary">
                        <i class="bi bi-building"></i> My Department
                    </a>
                    <a href="{{ route('admin.hod.staff.index') }}" class="btn btn-outline-info">
                        <i class="bi bi-person-badge"></i> Staff Management
                    </a>
                    <a href="{{ route('admin.department.applications') }}" class="btn btn-outline-success">
                        <i class="bi bi-files"></i> All Department Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection