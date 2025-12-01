@extends('admin.layouts.master')

@section('title', 'Pending Applications - ' . $departmentInfo['name'])
@section('page-title', $departmentInfo['name'] . ' - Applications Pending Final Approval')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-clipboard-list me-2"></i>Applications Pending Final Approval - {{ $departmentInfo['name'] }}
        </h4>
        <p class="mb-0 mt-2 text-white-50">
            Review and provide final approval for applications assigned to your department
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($applications->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>App ID</th>
                        <th>Student Information</th>
                        <th>Academic Details</th>
                        <th>Department</th>
                        <th>Academic Approved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $application->application_id }}</strong>
                            <br>
                            <small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $application->name }}</div>
                            <div class="text-muted small">{{ $application->email }}</div>
                            <div class="text-muted small">{{ $application->phone }}</div>
                            <div class="text-muted small">NRC: {{ $application->nrc_number }}</div>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-info fs-7">{{ $application->matriculation_score }}/600</span>
                                <small class="text-muted d-block">Matriculation Score</small>
                            </div>
                            <div class="small text-muted">
                                {{ $application->high_school_name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fas fa-building me-1"></i>
                                {{ $application->assigned_department }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $application->academic_approved_at ? $application->academic_approved_at->format('M d, Y') : 'N/A' }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                <!-- View Details -->
                                <a href="{{ route('admin.applications.view', $application->id) }}" 
                                   class="btn btn-sm btn-primary mb-1" 
                                   title="View Full Application Details">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                
                                <!-- Final Approve -->
                                <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline mb-1">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-success w-100"
                                            onclick="return confirm('Grant final approval to {{ $application->name }}? This will activate the student account.')"
                                            title="Final Approval">
                                        <i class="fas fa-check-circle me-1"></i>Approve
                                    </button>
                                </form>

                                <!-- Reject Application -->
                                <button type="button" 
                                        class="btn btn-sm btn-danger reject-btn"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        title="Reject Application">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} entries
            </div>
            <div>
                {{ $applications->links() }}
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No Applications Pending Final Approval</h4>
            <p class="text-muted">All applications in your department have been processed.</p>
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times me-2"></i>Reject Application
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. The student will be notified of the rejection.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Application:</label>
                        <div id="rejectApplicationInfo" class="p-3 bg-light rounded"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label fw-bold required">
                            <i class="fas fa-comment me-2"></i>Rejection Reason
                        </label>
                        <textarea class="form-control" id="reject_notes" name="notes" rows="4" 
                                  placeholder="Please provide a clear and professional reason for rejection..."
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-2"></i>Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');
    
    rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const applicationId = button.getAttribute('data-application-id');
        const applicationName = button.getAttribute('data-application-name');
        
        document.getElementById('rejectApplicationInfo').innerHTML = `
            <strong>${applicationName}</strong><br>
            <small class="text-muted">Application ID: ${applicationId}</small>
        `;
        
        rejectForm.action = `{{ url('admin/applications/hod-reject') }}/${applicationId}`;
    });

    rejectForm.addEventListener('submit', function(e) {
        const notesTextarea = document.getElementById('reject_notes');
        if (!notesTextarea.value.trim()) {
            e.preventDefault();
            alert('Please provide a rejection reason.');
            notesTextarea.focus();
        }
    });
});
</script>
@endpush