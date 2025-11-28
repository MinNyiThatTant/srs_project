@extends('admin.master')

@section('title', 'Academic Review - HAA Admin')
@section('page-title', 'Academic Review')
@section('page-subtitle', 'Review student applications')
@section('breadcrumb', 'Academic Applications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Applications Pending Academic Review</h4>
                <p class="text-muted">Review applications after payment verification</p>
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
                                <th>Verified Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td><strong>{{ $application->application_id }}</strong></td>
                                <td>{{ $application->name }}</td>
                                <td>{{ $application->department }}</td>
                                <td>
                                    <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->application_type) }} Student
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Verified</span>
                                </td>
                                <td>{{ $application->payment_verified_at ? $application->payment_verified_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <form action="{{ route('applications.academic-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Approve {{ $application->name }} for academic admission?')"
                                                    title="Approve Application">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $application->id }}"
                                                title="Reject Application">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Application</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('applications.academic-reject', $application->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason</label>
                                                            <textarea class="form-control" name="notes" rows="4" 
                                                                      placeholder="Enter reason for rejection..." required></textarea>
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
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $applications->links() }}
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
@endsection