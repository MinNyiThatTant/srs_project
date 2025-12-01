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
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_reviews'] ?? 0 }}</h4>
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
                            <h4 class="font-extrabold mb-0">{{ $stats['approved_today'] ?? 0 }}</h4>
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
                            <h4 class="font-extrabold mb-0">{{ $stats['total_reviewed'] ?? 0 }}</h4>
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
                            <h6 class="text-muted">Total Students</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_students'] ?? 0 }}</h4>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Applications Pending Academic Review</h4>
                <a href="{{ route('admin.applications.academic') }}" class="btn btn-primary btn-sm">View All Applications</a>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Matriculation Score</th>
                                <th>Payment Status</th>
                                <th>Payment Verified At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->matriculation_score >= 80 ? 'success' : ($application->matriculation_score >= 60 ? 'warning' : 'danger') }}">
                                        {{ $application->matriculation_score ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        Verified
                                    </span>
                                </td>
                                <td>{{ $application->payment_verified_at ? $application->payment_verified_at->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.applications.academic-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    onclick="return confirm('Approve {{ $application->name }}\\'s application academically?')"
                                                    title="Academic Approve">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info" 
                                                    onclick="return confirm('Final approve {{ $application->name }}\\'s application? This will generate student credentials.')"
                                                    title="Final Approve & Generate Student ID">
                                                <i class="bi bi-award"></i> Final Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $application->id }}"
                                                title="Reject Application">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Application - {{ $application->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.applications.academic-reject', $application->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $application->id }}" class="form-label">Rejection Reason</label>
                                                            <textarea class="form-control" id="rejection_reason{{ $application->id }}" name="rejection_reason" rows="3" required placeholder="Enter reason for rejection..."></textarea>
                                                            <div class="form-text">Please provide a clear reason for rejection.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $applications->links() }}
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No applications pending academic review.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Section: Recently Approved Applications -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Recently Approved Students</h4>
            </div>
            <div class="card-body">
                @php
                    $recentlyApproved = App\Models\Application::where('status', 'approved')
                        ->with('student')
                        ->orderBy('final_approved_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($recentlyApproved->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Approved Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentlyApproved as $application)
                            <tr>
                                <td><strong>{{ $application->student_id }}</strong></td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>{{ $application->final_approved_at ? $application->final_approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">Approved</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    No recently approved students.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection