@extends('admin.layouts.master')

@section('title', 'Application Details - ' . $application->application_id)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Application Details</h1>
        <div class="d-flex">
            <a href="{{ route('admin.global.applications') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
            @if(in_array($application->status, ['pending', 'payment_pending', 'payment_verified', 'academic_approved']))
            <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                <i class="fas fa-times fa-sm text-white-50"></i> Reject
            </button>
            @endif
        </div>
    </div>

    <!-- Application Header -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Application: {{ $application->application_id }}
                    </h6>
                    <div>
                        <span class="badge {{ $application->status_badge }} mr-2">
                            {{ $application->status_text }}
                        </span>
                        <span class="badge {{ $application->payment_status_badge }}">
                            {{ $application->payment_status_text }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-primary mb-3">Personal Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Full Name</th>
                                    <td>{{ $application->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email Address</th>
                                    <td>{{ $application->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ $application->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{ $application->date_of_birth?->format('M d, Y') ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ ucfirst($application->gender) ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nationality</th>
                                    <td>{{ $application->nationality ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>NRC Number</th>
                                    <td>{{ $application->nrc_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $application->address ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-primary mb-3">Family Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Father's Name</th>
                                    <td>{{ $application->father_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mother's Name</th>
                                    <td>{{ $application->mother_name ?? 'N/A' }}</td>
                                </tr>
                            </table>

                            <h5 class="font-weight-bold text-primary mb-3 mt-4">Application Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Application Type</th>
                                    <td>
                                        <span class="badge badge-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                            {{ ucfirst($application->application_type) }} Student
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ $application->department }}</td>
                                </tr>
                                <tr>
                                    <th>Applied Date</th>
                                    <td>{{ $application->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($application->student_id)
                                <tr>
                                    <th>Student ID</th>
                                    <td>
                                        <span class="badge badge-success">{{ $application->student_id }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="font-weight-bold text-primary mb-3">Academic Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="20%">High School Name</th>
                                        <td>{{ $application->high_school_name ?? 'N/A' }}</td>
                                        <th width="20%">Graduation Year</th>
                                        <td>{{ $application->graduation_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>High School Address</th>
                                        <td colspan="3">{{ $application->high_school_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Matriculation Score</th>
                                        <td>
                                            @if($application->matriculation_score)
                                            {{ $application->matriculation_score }}/600
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <th>Previous Qualification</th>
                                        <td>{{ $application->previous_qualification ?? 'N/A' }}</td>
                                    </tr>
                                    @if($application->application_type === 'old')
                                    <tr>
                                        <th>Current Year</th>
                                        <td>{{ $application->current_year ?? 'N/A' }}</td>
                                        <th>Application Purpose</th>
                                        <td>{{ $application->application_purpose ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reason for Application</th>
                                        <td colspan="3">{{ $application->reason_for_application ?? 'N/A' }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Application Timeline -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="font-weight-bold text-primary mb-3">Application Timeline</h5>
                            <div class="timeline">
                                <div class="timeline-item {{ $application->application_date ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Application Submitted</h6>
                                        <p class="text-muted">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item {{ $application->payment_verified_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Payment Verification</h6>
                                        <p class="text-muted">
                                            @if($application->payment_verified_at)
                                            {{ $application->payment_verified_at->format('M d, Y H:i') }}
                                            @else
                                            Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-item {{ $application->academic_approved_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Academic Approval</h6>
                                        <p class="text-muted">
                                            @if($application->academic_approved_at)
                                            {{ $application->academic_approved_at->format('M d, Y H:i') }}
                                            @else
                                            Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="timeline-item {{ $application->final_approved_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Final Approval</h6>
                                        <p class="text-muted">
                                            @if($application->final_approved_at)
                                            {{ $application->final_approved_at->format('M d, Y H:i') }}
                                            @else
                                            Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    @if($application->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="font-weight-bold text-primary mb-3">Admin Notes</h5>
                            <div class="alert alert-info">
                                {{ $application->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Rejection Reason -->
                    @if($application->rejection_reason)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="font-weight-bold text-danger mb-3">Rejection Reason</h5>
                            <div class="alert alert-danger">
                                {{ $application->rejection_reason }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        @if($application->status === 'payment_pending')
                        <form action="{{ route('admin.global.applications.verify-payment', $application->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success mr-2" 
                                    onclick="return confirm('Verify payment for {{ $application->name }}?')">
                                <i class="fas fa-check-circle mr-2"></i>Verify Payment
                            </button>
                        </form>
                        @endif

                        @if($application->status === 'payment_verified')
                        <form action="{{ route('admin.global.applications.academic-approve', $application->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary mr-2" 
                                    onclick="return confirm('Academic approve {{ $application->name }}?')">
                                <i class="fas fa-graduation-cap mr-2"></i>Academic Approve
                            </button>
                        </form>
                        @endif

                        @if($application->status === 'academic_approved')
                        <form action="{{ route('admin.global.applications.final-approve', $application->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success mr-2" 
                                    onclick="return confirm('Final approve {{ $application->name }}? This will generate student ID.')">
                                <i class="fas fa-award mr-2"></i>Final Approve
                            </button>
                        </form>
                        @endif

                        @if(in_array($application->status, ['pending', 'payment_pending', 'payment_verified', 'academic_approved']))
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times mr-2"></i>Reject Application
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.global.applications.reject', $application->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Application</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        You are about to reject this application. This action cannot be undone.
                    </div>
                    <p>Reject application for <strong>{{ $application->name }}</strong>?</p>
                    <div class="form-group">
                        <label for="rejection_reason" class="font-weight-bold">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" required placeholder="Enter detailed rejection reason..."></textarea>
                        <small class="form-text text-muted">This reason will be communicated to the student.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-2"></i>Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e0e0e0;
    border: 3px solid #fff;
}
.timeline-item.completed .timeline-marker {
    background: #28a745;
}
.timeline-content {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}
</style>
@endpush