@extends('admin.layouts.master')

<<<<<<< HEAD
@section('title', 'Academic Applications - WYTU University')
@section('page-title', 'Applications for Academic Review & Department Assignment')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-clipboard-list me-2"></i>Applications Pending Academic Review & Department Assignment
        </h4>
        <p class="mb-0 mt-2 text-white-50">
            Review applications and assign appropriate departments based on student preferences and qualifications
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

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
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
                        <th>Department Preferences</th>
                        <th>Status</th>
                        <th>Quick Actions</th>
=======
@section('title', 'Academic Applications')
@section('page-title', 'Applications for Academic Review')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Applications Pending Academic Approval</h4>
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
                        <th>Email</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
<<<<<<< HEAD
                    @php
                        $priorities = $application->getDepartmentPriorities();
                    @endphp
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
                            <div class="student-preferences">
                                @foreach($priorities as $priority => $department)
                                    <div class="priority-item mb-2 p-2 border rounded 
                                        @if($priority === 'First Priority') border-primary
                                        @elseif($priority === 'Second Priority') border-success
                                        @elseif($priority === 'Third Priority') border-info
                                        @elseif($priority === 'Fourth Priority') border-warning
                                        @else border-secondary
                                        @endif">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge 
                                                    @if($priority === 'First Priority') bg-primary
                                                    @elseif($priority === 'Second Priority') bg-success
                                                    @elseif($priority === 'Third Priority') bg-info
                                                    @elseif($priority === 'Fourth Priority') bg-warning
                                                    @else bg-secondary
                                                    @endif me-2">
                                                    {{ str_replace(' Priority', '', $priority) }}
                                                </span>
                                                <strong>{{ $department }}</strong>
                                            </div>
                                            <form action="{{ route('admin.academic.quick-assign', $application->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="priority_department" value="{{ $department }}">
                                                <button type="submit" 
                                                        class="btn btn-sm 
                                                            @if($priority === 'First Priority') btn-outline-primary
                                                            @elseif($priority === 'Second Priority') btn-outline-success
                                                            @elseif($priority === 'Third Priority') btn-outline-info
                                                            @elseif($priority === 'Fourth Priority') btn-outline-warning
                                                            @else btn-outline-secondary
                                                            @endif"
                                                        onclick="return confirm('Assign {{ $priority }}: {{ $department }} to {{ $application->name }}?')"
                                                        title="Assign {{ $priority }}">
                                                    <i class="fas fa-check me-1"></i>Assign
                                                </button>
                                            </form>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ $priority }}</small>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($application->assigned_department)
                                <div class="assigned-department mt-2 p-2 bg-success text-white rounded">
                                    <small class="fw-bold">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Assigned: {{ $application->assigned_department }}
                                    </small>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Payment Verified
                            </span>
                            <br>
                            <small class="text-muted">Ready for Review</small>
                        </td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                <!-- View Details -->
                                <a href="{{ route('admin.applications.view', $application->id) }}" 
                                   class="btn btn-sm btn-primary mb-1" 
                                   title="View Full Application Details">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                
                                <!-- Custom Department Assignment -->
                                <button type="button" 
                                        class="btn btn-sm btn-warning mb-1 assign-department-btn"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#assignDepartmentModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        data-priorities="{{ json_encode($priorities) }}"
                                        title="Assign Custom Department">
                                    <i class="fas fa-edit me-1"></i>Custom Assign
                                </button>
                                
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
=======
                    <tr>
                        <td>{{ $application->application_id }}</td>
                        <td>{{ $application->name }}</td>
                        <td>{{ $application->department }}</td>
                        <td>{{ $application->email }}</td>
                        <td>
                            <span class="badge bg-success">Verified</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.academic.application.view', $application->id) }}" 
                                   class="btn btn-sm btn-primary">View</a>
                                <form action="{{ route('admin.academic.approve-application', $application->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('Approve this application?')">Approve</button>
                                </form>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
<<<<<<< HEAD

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
            <h4 class="text-muted">No Applications Pending Review</h4>
            <p class="text-muted">All applications have been processed and assigned to departments.</p>
=======
        @else
        <div class="alert alert-info">
            No applications pending academic review.
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        </div>
        @endif
    </div>
</div>
<<<<<<< HEAD

<!-- Assign Department Modal -->
<div class="modal fade" id="assignDepartmentModal" tabindex="-1" aria-labelledby="assignDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="assignDepartmentForm" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="assignDepartmentModalLabel">
                        <i class="fas fa-edit me-2"></i>Assign Custom Department to Student
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Application Information -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Application Details</h6>
                        <div id="applicationInfo" class="mt-2"></div>
                    </div>

                    <!-- Student Preferences -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">
                            <i class="fas fa-list-ol me-2"></i>Student's Department Preferences
                        </h6>
                        <div id="preferencesInfo" class="p-3 bg-light rounded"></div>
                    </div>

                    <!-- Department Assignment -->
                    <div class="mb-3">
                        <label for="assigned_department" class="form-label fw-bold required">
                            <i class="fas fa-building me-2"></i>Select Department to Assign
                        </label>
                        <select class="form-select" id="assigned_department" name="assigned_department" required>
                            <option value="">Choose a department...</option>
                            <!-- Student's preferred departments first -->
                            <optgroup label="Student's Preferred Departments" id="preferredDepartments">
                            </optgroup>
                            <optgroup label="All Available Departments">
                                <option value="Civil Engineering">Civil Engineering</option>
                                <option value="Computer Engineering and Information Technology">Computer Engineering and Information Technology</option>
                                <option value="Electronic Engineering">Electronic Engineering</option>
                                <option value="Electrical Power Engineering">Electrical Power Engineering</option>
                                <option value="Architecture">Architecture</option>
                                <option value="Biotechnology">Biotechnology</option>
                                <option value="Textile Engineering">Textile Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                                <option value="Chemical Engineering">Chemical Engineering</option>
                                <option value="Automobile Engineering">Automobile Engineering</option>
                                <option value="Mechatronic Engineering">Mechatronic Engineering</option>
                                <option value="Metallurgy Engineering">Metallurgy Engineering</option>
                            </optgroup>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-lightbulb me-1"></i>
                            Consider the student's preferences, matriculation score, and department capacity when assigning.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Assign Department & Approve Application
                    </button>
                </div>
            </form>
        </div>
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
                                  placeholder="Please provide a clear and professional reason for rejection. This will be communicated to the student..."
                                  required></textarea>
                        <div class="form-text">
                            Be specific about why the application is being rejected (e.g., low score, incomplete information, etc.)
                        </div>
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

@push('styles')
<style>
.priority-item {
    border-left: 3px solid #dee2e6;
    padding-left: 8px;
}
.assigned-department {
    border-left: 3px solid #198754 !important;
}
.btn-group-vertical .btn {
    margin-bottom: 0.25rem;
}
.table th {
    background-color: #343a40;
    color: white;
}
.required::after {
    content: " *";
    color: #dc3545;
}
.student-preferences .priority-item {
    transition: all 0.3s ease;
}
.student-preferences .priority-item:hover {
    transform: translateX(5px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Assign Department Modal
    const assignDepartmentModal = document.getElementById('assignDepartmentModal');
    const assignDepartmentForm = document.getElementById('assignDepartmentForm');

    assignDepartmentModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const applicationId = button.getAttribute('data-application-id');
        const applicationName = button.getAttribute('data-application-name');
        const priorities = JSON.parse(button.getAttribute('data-priorities'));

        // Update application info
        document.getElementById('applicationInfo').innerHTML = `
            <strong>${applicationName}</strong><br>
            <small class="text-muted">Application ID: ${applicationId}</small>
        `;

        // Update preferences info
        let preferencesHtml = '';
        for (const [priority, department] of Object.entries(priorities)) {
            preferencesHtml += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded 
                    ${priority === 'First Priority' ? 'border-primary' : 
                      priority === 'Second Priority' ? 'border-success' : 
                      priority === 'Third Priority' ? 'border-info' : 'border-warning'}">
                    <div>
                        <span class="badge 
                            ${priority === 'First Priority' ? 'bg-primary' : 
                              priority === 'Second Priority' ? 'bg-success' : 
                              priority === 'Third Priority' ? 'bg-info' : 'bg-warning'} me-2">
                            ${priority.replace(' Priority', '')}
                        </span>
                        <strong>${department}</strong>
                    </div>
                    <small class="text-muted">${priority}</small>
                </div>
            `;
        }
        document.getElementById('preferencesInfo').innerHTML = preferencesHtml;

        // Update preferred departments in select
        const preferredGroup = document.getElementById('preferredDepartments');
        preferredGroup.innerHTML = '';
        
        for (const [priority, department] of Object.entries(priorities)) {
            const option = document.createElement('option');
            option.value = department;
            option.textContent = `${department} (${priority.replace(' Priority', '')} Choice)`;
            option.selected = priority === 'First Priority'; // Auto-select first priority
            preferredGroup.appendChild(option);
        }

        // Update form action - FIXED ROUTE
        assignDepartmentForm.action = `{{ url('admin/academic/assign-department') }}/${applicationId}`;
    });

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
        
        // FIXED ROUTE - use the correct academic reject route
        rejectForm.action = `{{ url('admin/academic-reject') }}/${applicationId}`;
    });

    // Form validation
    assignDepartmentForm.addEventListener('submit', function(e) {
        const departmentSelect = document.getElementById('assigned_department');
        if (!departmentSelect.value) {
            e.preventDefault();
            alert('Please select a department to assign.');
            departmentSelect.focus();
        }
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
=======
@endsection
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
