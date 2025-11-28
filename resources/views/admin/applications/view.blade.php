@extends('admin.layouts.master')

@section('title', 'Application Details - WYTU')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>Application Details - WYTU-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Personal Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Full Name</th>
                                    <td>{{ $application->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $application->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $application->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Applied Date</th>
                                    <td>{{ $application->application_date->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Academic Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Department</th>
                                    <td>{{ $application->department }}</td>
                                </tr>
                                <tr>
                                    <th>Matriculation Score</th>
                                    <td>{{ $application->matriculation_score }}/600</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $application->status_badge }}">
                                            {{ $application->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Student ID</th>
                                    <td>
                                        @if($application->student_id)
                                            <span class="badge bg-success">{{ $application->student_id }}</span>
                                        @else
                                            <span class="badge bg-secondary">Not Generated</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Application Timeline</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Payment Verification</th>
                                    <td>{{ $application->payment_verified_at?->format('M d, Y H:i') ?? 'Pending' }}</td>
                                </tr>
                                <tr>
                                    <th>Academic Approval</th>
                                    <td>{{ $application->academic_approved_at?->format('M d, Y H:i') ?? 'Pending' }}</td>
                                </tr>
                                <tr>
                                    <th>HOD Approval</th>
                                    <td>{{ $application->hod_approved_at?->format('M d, Y H:i') ?? 'Pending' }}</td>
                                </tr>
                                <tr>
                                    <th>Final Approval</th>
                                    <td>{{ $application->final_approved_at?->format('M d, Y H:i') ?? 'Pending' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($application->rejection_reason)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-danger">Rejection Reason</h5>
                            <div class="alert alert-danger">
                                {{ $application->rejection_reason }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($application->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Admin Notes</h5>
                            <div class="alert alert-info">
                                {{ $application->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Quick Actions</h5>
                            <div class="btn-group">
                                @if($application->status === \App\Models\Application::STATUS_PAYMENT_PENDING)
                                <form action="{{ route('admin.applications.verify-payment', $application->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('Verify payment for {{ $application->name }}?')">
                                        <i class="fas fa-check"></i> Verify Payment
                                    </button>
                                </form>
                                @endif

                                @if($application->status === \App\Models\Application::STATUS_PAYMENT_VERIFIED)
                                <form action="{{ route('admin.applications.academic-approve', $application->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" 
                                            onclick="return confirm('Academic approve {{ $application->name }}?')">
                                        <i class="fas fa-graduation-cap"></i> Academic Approve
                                    </button>
                                </form>
                                @endif

                                @if($application->status === \App\Models\Application::STATUS_ACADEMIC_APPROVED)
                                <form action="{{ route('admin.applications.final-approve', $application->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('Final approve {{ $application->name }}? This will generate student ID.')">
                                        <i class="fas fa-check-double"></i> Final Approve
                                    </button>
                                </form>
                                @endif

                                @if(in_array($application->status, [\App\Models\Application::STATUS_PAYMENT_PENDING, \App\Models\Application::STATUS_PAYMENT_VERIFIED]))
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.applications.academic-reject', $application->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Reject application for <strong>{{ $application->name }}</strong>?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                  placeholder="Please provide reason for rejection..." required></textarea>
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
@endsection