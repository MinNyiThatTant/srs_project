@extends('admin.layouts.master')

@section('title', 'Academic Admin Dashboard')
@section('page-title', 'Academic Affairs Dashboard')
@section('page-subtitle', 'Application Review & Approval')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
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
                            <h6 class="text-muted">Total Reviewed</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_reviewed'] }}</h4>
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
                            <h6 class="text-muted">Recent Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['recent_applications']->count() }}</h4>
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
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications Pending Academic Review</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Matriculation Score</th>
                                <th>Payment Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_applications'] as $application)
                            <tr>
                                <td>{{ $application->application_id ?? 'N/A' }}</td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>{{ $application->matriculation_score ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : 'warning' }}">
                                        {{ $application->payment_status }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.applications.academic-approve', $application->id) }}" 
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('Approve this application?')">
                                            <i class="bi bi-check"></i> Approve
                                        </a>
                                    </div>
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
@endsection