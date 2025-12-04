@extends('layouts.admin')

@section('title', 'Verify Old Student Application')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-user-check me-2"></i>Verify Old Student Application
                            </h4>
                            <p class="mb-0">Application ID: {{ $application->application_id }}</p>
                        </div>
                        <div>
                            <span class="{{ $application->status_badge }} fs-6">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Application Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Student ID:</th>
                                            <td>{{ $application->existing_student_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $application->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $application->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $application->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department:</th>
                                            <td>{{ $application->department }}</td>
                                        </tr>
                                        <tr>
                                            <th>Current CGPA:</th>
                                            <td>
                                                <span class="badge {{ $application->cgpa >= 3.5 ? 'bg-success' : ($application->cgpa >= 2.5 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ number_format($application->cgpa, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Current Year:</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    Year {{ $application->current_year - 1 }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Applying for:</th>
                                            <td>
                                                <span class="badge bg-success">
                                                    Year {{ $application->current_year }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Previous Year Status:</th>
                                            <td>
                                                <span class="badge {{ $application->previous_year_status === 'passed' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($application->previous_year_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Application Purpose:</th>
                                            <td>{{ ucfirst(str_replace('_', ' ', $application->application_purpose)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status:</th>
                                            <td>
                                                <span class="{{ $application->payment_status_badge }}">
                                                    {{ ucfirst($application->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic History -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Academic History</h6>
                                </div>
                                <div class="card-body">
                                    @if($academicHistory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Academic Year</th>
                                                        <th>Year</th>
                                                        <th>Status</th>
                                                        <th>CGPA</th>
                                                        <th>Approved By</th>
                                                        <th>Approved Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($academicHistory as $history)
                                                        <tr>
                                                            <td>{{ $history->academic_year }}</td>
                                                            <td>Year {{ $history->year }}</td>
                                                            <td>
                                                                <span class="badge {{ $history->status === 'passed' ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ ucfirst($history->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ number_format($history->cgpa, 2) }}</td>
                                                            <td>{{ $history->approver->name ?? 'System' }}</td>
                                                            <td>{{ $history->approved_at?->format('d/m/Y') ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No academic history found for this student.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Eligibility Check -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header {{ $eligibilityCheck ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                    <h6 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Academic Eligibility Check
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($eligibilityCheck)
                                        <div class="alert alert-success">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                                <div>
                                                    <h5 class="mb-1">Student is Eligible</h5>
                                                    <p class="mb-0">
                                                        The student has successfully completed Year {{ $application->current_year - 1 }} 
                                                        and is eligible to proceed to Year {{ $application->current_year }}.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                                <div>
                                                    <h5 class="mb-1">Student is Not Eligible</h5>
                                                    <p class="mb-0">
                                                        The student has not passed Year {{ $application->current_year - 1 }} 
                                                        or academic records are incomplete.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reason for Application -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Reason for Application</h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-light p-3 rounded">
                                        {{ $application->reason_for_application }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Academic Verification</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.old-student.verify', $application->id) }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="verification_status" class="form-label required">Verification Status</label>
                                                <select class="form-control" id="verification_status" name="verification_status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="approved">Approve</option>
                                                    <option value="rejected">Reject</option>
                                                    <option value="pending">Pending with Conditions</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="next_year_gpa_requirement" class="form-label">Next Year GPA Requirement</label>
                                                <input type="number" class="form-control" id="next_year_gpa_requirement" 
                                                       name="next_year_gpa_requirement" step="0.01" min="2" max="4"
                                                       placeholder="e.g., 3.0">
                                                <small class="text-muted">Set minimum GPA requirement for next year</small>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="remarks" class="form-label">Remarks</label>
                                                <textarea class="form-control" id="remarks" name="remarks" 
                                                          rows="3" placeholder="Enter verification remarks..."></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label class="form-label">Conditions (if any)</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="conditions[]" value="retake_subjects" id="retake_subjects">
                                                    <label class="form-check-label" for="retake_subjects">
                                                        Must retake failed subjects
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="conditions[]" value="improve_gpa" id="improve_gpa">
                                                    <label class="form-check-label" for="improve_gpa">
                                                        Must improve GPA in next semester
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="conditions[]" value="attend_tutorials" id="attend_tutorials">
                                                    <label class="form-check-label" for="attend_tutorials">
                                                        Must attend extra tutorials
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="conditions[]" value="submit_documents" id="submit_documents">
                                                    <label class="form-check-label" for="submit_documents">
                                                        Must submit additional documents
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('admin.old-student.applications') }}" class="btn btn-secondary me-2">
                                                        <i class="fas fa-arrow-left me-2"></i>Back
                                                    </a>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check-circle me-2"></i>Submit Verification
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('verification_status');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'approved') {
                if (!{{ $eligibilityCheck ? 'true' : 'false' }}) {
                    alert('Warning: Student may not be academically eligible. Please check the eligibility status.');
                }
            }
        });
    });
</script>
@endsection