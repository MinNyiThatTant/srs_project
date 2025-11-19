@extends('admin.master')

@section('title', 'Academic Review - HAA Admin')
@section('page-title', 'Academic Review')
@section('page-subtitle', 'Review applications academically')
@section('breadcrumb', 'Academic Applications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications for Academic Review</h4>
                <p class="text-muted">Review payment-verified applications for academic qualifications</p>
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
                                <th>Matriculation Score</th>
                                <th>Previous Qualification</th>
                                <th>Payment Verified</th>
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
                                    @if($application->matriculation_score)
                                    <span class="badge bg-{{ $application->matriculation_score >= 400 ? 'success' : 'warning' }}">
                                        {{ $application->matriculation_score }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $application->previous_qualification ?? 'N/A' }}</td>
                                <td>
                                    @if($application->payment_verified_at)
                                    {{ $application->payment_verified_at->format('M d, Y') }}
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.applications.academic-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Approve {{ $application->name }} academically?')"
                                                    title="Academic Approve">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $application->id }}"
                                                title="Reject">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.applications.academic-reject', $application->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Application</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Reject application for <strong>{{ $application->name }}</strong>?</p>
                                                        <div class="form-group">
                                                            <label for="rejection_reason">Reason for Rejection</label>
                                                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required placeholder="Enter rejection reason..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Application</button>
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
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No applications pending academic review.
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
                <h5>Academic Review Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-stat bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $applications->count() }}</h3>
                                        <p>Pending Review</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-journal-text fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-stat bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $applications->where('matriculation_score', '>=', 400)->count() }}</h3>
                                        <p>High Scores (400+)</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-award fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-stat bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{ $applications->where('matriculation_score', '<', 400)->count() }}</h3>
                                        <p>Average Scores</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-graph-up fs-1"></i>
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