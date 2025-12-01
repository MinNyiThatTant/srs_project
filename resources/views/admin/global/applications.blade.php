@extends('admin.layouts.master')

@section('title', 'All Applications - Global Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">All Applications</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" data-toggle="modal" data-target="#exportModal">
                <i class="fas fa-download fa-sm text-white-50"></i> Export
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#bulkActionModal">
                <i class="fas fa-tasks fa-sm text-white-50"></i> Bulk Actions
            </button>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Applications</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.global.applications') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                <option value="payment_verified" {{ request('status') == 'payment_verified' ? 'selected' : '' }}>Payment Verified</option>
                                <option value="academic_approved" {{ request('status') == 'academic_approved' ? 'selected' : '' }}>Academic Approved</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Final Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="application_type">Application Type</label>
                            <select class="form-control" id="application_type" name="application_type">
                                <option value="">All Types</option>
                                <option value="new" {{ request('application_type') == 'new' ? 'selected' : '' }}>New Student</option>
                                <option value="old" {{ request('application_type') == 'old' ? 'selected' : '' }}>Existing Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Name, Email, or ID">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.global.applications') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Applications List</h6>
            <span class="badge badge-primary">Total: {{ $applications->total() }}</span>
        </div>
        <div class="card-body">
            @if($applications->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="applicationsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll">
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
                                <input type="checkbox" class="application-checkbox" value="{{ $application->id }}">
                            </td>
                            <td>
                                <strong class="text-primary">{{ $application->application_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-user-circle text-gray-400"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $application->name }}</strong>
                                        @if($application->phone)
                                        <br><small class="text-muted">{{ $application->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $application->email }}</td>
                            <td>
                                <span class="badge badge-light">{{ $application->department }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
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
                                @if($application->payment_verified_at)
                                <br><small class="text-muted">{{ $application->payment_verified_at->format('M d, Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.global.applications.view', $application->id) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Quick Actions based on status -->
                                    @if($application->status === 'payment_pending')
                                    <button type="button" class="btn btn-warning verify-payment-btn" 
                                            data-application-id="{{ $application->id }}"
                                            data-application-name="{{ $application->name }}"
                                            title="Verify Payment">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    @endif

                                    @if($application->status === 'payment_verified')
                                    <button type="button" class="btn btn-primary academic-approve-btn"
                                            data-application-id="{{ $application->id }}"
                                            data-application-name="{{ $application->name }}"
                                            title="Academic Approve">
                                        <i class="fas fa-graduation-cap"></i>
                                    </button>
                                    @endif

                                    @if($application->status === 'academic_approved')
                                    <button type="button" class="btn btn-success final-approve-btn"
                                            data-application-id="{{ $application->id }}"
                                            data-application-name="{{ $application->name }}"
                                            title="Final Approve">
                                        <i class="fas fa-award"></i>
                                    </button>
                                    @endif

                                    @if(in_array($application->status, ['pending', 'payment_pending', 'payment_verified', 'academic_approved']))
                                    <button type="button" class="btn btn-danger reject-btn"
                                            data-application-id="{{ $application->id }}"
                                            data-application-name="{{ $application->name }}"
                                            title="Reject Application">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} entries
                </div>
                <div>
                    {{ $applications->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                <h4 class="text-gray-500">No Applications Found</h4>
                <p class="text-gray-500">There are no applications matching your criteria.</p>
                @if(request()->anyFilled(['status', 'department', 'application_type', 'search']))
                <a href="{{ route('admin.global.applications') }}" class="btn btn-primary">
                    <i class="fas fa-redo mr-2"></i> Clear Filters
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="bulkActionForm" action="{{ route('admin.global.bulk-actions') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Actions</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulkAction">Select Action</label>
                        <select class="form-control" id="bulkAction" name="action" required>
                            <option value="">Choose action...</option>
                            <option value="verify_payment">Verify Payment</option>
                            <option value="academic_approve">Academic Approve</option>
                            <option value="final_approve">Final Approve</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>
                    <div class="form-group" id="rejectionReasonContainer" style="display: none;">
                        <label for="bulkRejectionReason">Rejection Reason</label>
                        <textarea class="form-control" id="bulkRejectionReason" name="rejection_reason" 
                                  rows="3" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                    <div class="selected-count">
                        <span id="selectedCount">0</span> applications selected
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Action</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Applications</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exportFormat">Export Format</label>
                    <select class="form-control" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exportRange">Data Range</label>
                    <select class="form-control" id="exportRange">
                        <option value="all">All Applications</option>
                        <option value="filtered">Currently Filtered</option>
                        <option value="selected">Selected Applications</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportBtn">Export</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#applicationsTable').DataTable({
        "pageLength": 25,
        "order": [[8, 'desc']],
        "language": {
            "search": "Search applications:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });

    // Select All functionality
    $('#selectAll').change(function() {
        $('.application-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });

    $('.application-checkbox').change(function() {
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        }
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const count = $('.application-checkbox:checked').length;
        $('#selectedCount').text(count);
    }

    // Bulk action form handling
    $('#bulkAction').change(function() {
        if ($(this).val() === 'reject') {
            $('#rejectionReasonContainer').show();
        } else {
            $('#rejectionReasonContainer').hide();
        }
    });

    $('#bulkActionForm').submit(function(e) {
        const selectedApplications = $('.application-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedApplications.length === 0) {
            e.preventDefault();
            alert('Please select at least one application.');
            return false;
        }

        // Add selected applications to form
        selectedApplications.forEach(function(id) {
            $(this).append('<input type="hidden" name="application_ids[]" value="' + id + '">');
        });
    });

    // Quick action buttons
    $('.verify-payment-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const applicationName = $(this).data('application-name');
        
        if (confirm('Verify payment for ' + applicationName + '?')) {
            window.location.href = "{{ url('admin/global/applications') }}/" + applicationId + "/verify-payment";
        }
    });

    $('.academic-approve-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const applicationName = $(this).data('application-name');
        
        if (confirm('Academic approve ' + applicationName + '?')) {
            window.location.href = "{{ url('admin/global/applications') }}/" + applicationId + "/academic-approve";
        }
    });

    $('.final-approve-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const applicationName = $(this).data('application-name');
        
        if (confirm('Final approve ' + applicationName + '? This will generate student ID.')) {
            window.location.href = "{{ url('admin/global/applications') }}/" + applicationId + "/final-approve";
        }
    });

    $('.reject-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const applicationName = $(this).data('application-name');
        const reason = prompt('Enter rejection reason for ' + applicationName + ':');
        
        if (reason && reason.trim() !== '') {
            window.location.href = "{{ url('admin/global/applications') }}/" + applicationId + "/reject?reason=" + encodeURIComponent(reason);
        }
    });
});
</script>
@endpush