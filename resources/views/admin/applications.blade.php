{{-- resources/views/admin/global/applications.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Manage Applications')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Applications Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.global.applications') }}" class="btn btn-sm btn-outline-secondary">All Applications</a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="payment_verified" {{ request('status') == 'payment_verified' ? 'selected' : '' }}>Payment Verified</option>
                    <option value="academic_approved" {{ request('status') == 'academic_approved' ? 'selected' : '' }}>Academic Approved</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Final Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="payment_status" class="form-label">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-select">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('payment_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="application_type" class="form-label">Application Type</label>
                <select name="application_type" id="application_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="new" {{ request('application_type') == 'new' ? 'selected' : '' }}>New Student</option>
                    <option value="old" {{ request('application_type') == 'old' ? 'selected' : '' }}>Old Student</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.global.applications') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Applications Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>
                            <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" class="application-checkbox">
                        </td>
                        <td>
                            <strong>
                                <a href="{{ route('admin.global.applications.view', $application->id) }}">
                                    {{ $application->application_id }}
                                </a>
                            </strong>
                        </td>
                        <td>{{ $application->name }}</td>
                        <td>{{ $application->email }}</td>
                        <td>{{ $application->department }}</td>
                        <td>
                            <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                {{ ucfirst($application->application_type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $application->status_badge }}">
                                {{ $application->status_text }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $application->payment_status_badge }}">
                                {{ $application->payment_status_text }}
                            </span>
                        </td>
                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.global.applications.view', $application->id) }}" 
                                   class="btn btn-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($application->status === 'payment_pending' && $application->payment_status === 'completed')
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#verifyPaymentModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        title="Verify Payment">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                @endif
                                
                                @if($application->readyForAcademicApproval())
                                <button type="button" class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#academicApproveModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        title="Academic Approve">
                                    <i class="bi bi-award"></i>
                                </button>
                                @endif
                                
                                @if($application->readyForFinalApproval())
                                <button type="button" class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#finalApproveModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        title="Final Approve">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                @endif
                                
                                @if(in_array($application->status, ['pending', 'payment_pending', 'payment_verified', 'academic_approved']))
                                <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal"
                                        data-application-id="{{ $application->id }}"
                                        data-application-name="{{ $application->name }}"
                                        title="Reject">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Bulk Actions -->
        <div class="mt-3">
            <form id="bulk-action-form" method="POST" action="{{ route('admin.global.bulk-actions') }}">
                @csrf
                <input type="hidden" name="application_ids" id="bulk-application-ids">
                <div class="row">
                    <div class="col-md-4">
                        <select name="action" id="bulk-action" class="form-select" required>
                            <option value="">Bulk Actions</option>
                            <option value="verify_payment">Verify Payment</option>
                            <option value="academic_approve">Academic Approve</option>
                            <option value="final_approve">Final Approve</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" id="bulk-action-btn">Apply</button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Pagination -->
        <div class="mt-3">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.global.modals.verify-payment')
@include('admin.global.modals.academic-approve')
@include('admin.global.modals.final-approve')
@include('admin.global.modals.reject-application')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').change(function() {
        $('.application-checkbox').prop('checked', this.checked);
        updateBulkActionButton();
    });
    
    // Individual checkbox change
    $('.application-checkbox').change(function() {
        if (!this.checked) {
            $('#select-all').prop('checked', false);
        }
        updateBulkActionButton();
    });
    
    function updateBulkActionButton() {
        const checkedCount = $('.application-checkbox:checked').length;
        const bulkActionBtn = $('#bulk-action-btn');
        
        if (checkedCount > 0) {
            bulkActionBtn.prop('disabled', false).text(`Apply to ${checkedCount} applications`);
        } else {
            bulkActionBtn.prop('disabled', true).text('Apply');
        }
    }
    
    // Bulk action form submission
    $('#bulk-action-form').submit(function(e) {
        const checkedApplications = $('.application-checkbox:checked').map(function() {
            return this.value;
        }).get();
        
        if (checkedApplications.length === 0) {
            e.preventDefault();
            alert('Please select at least one application.');
            return false;
        }
        
        $('#bulk-application-ids').val(JSON.stringify(checkedApplications));
    });
    
    // Modal data population
    $('[data-bs-target="#verifyPaymentModal"]').click(function() {
        const appId = $(this).data('application-id');
        const appName = $(this).data('application-name');
        $('#verify-app-id').val(appId);
        $('#verify-app-name').text(appName);
    });
    
    $('[data-bs-target="#academicApproveModal"]').click(function() {
        const appId = $(this).data('application-id');
        const appName = $(this).data('application-name');
        $('#academic-app-id').val(appId);
        $('#academic-app-name').text(appName);
    });
    
    $('[data-bs-target="#finalApproveModal"]').click(function() {
        const appId = $(this).data('application-id');
        const appName = $(this).data('application-name');
        $('#final-app-id').val(appId);
        $('#final-app-name').text(appName);
    });
    
    $('[data-bs-target="#rejectModal"]').click(function() {
        const appId = $(this).data('application-id');
        const appName = $(this).data('application-name');
        $('#reject-app-id').val(appId);
        $('#reject-app-name').text(appName);
    });
});
</script>
@endpush