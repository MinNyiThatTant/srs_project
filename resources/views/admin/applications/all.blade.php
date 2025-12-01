@extends('admin.master')

@section('title', 'All Applications - Global Admin')
@section('page-title', 'All Applications')
@section('page-subtitle', 'Complete overview of all applications')
@section('breadcrumb', 'All Applications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>All Student Applications</h4>
                <p class="text-muted">Complete overview and management of all applications</p>
            </div>
            <div class="card-body">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['total'] }}</h3>
                                <p>Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['payment_pending'] }}</h3>
                                <p>Payment Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['payment_verified'] }}</h3>
                                <p>Payment Verified</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['academic_approved'] }}</h3>
                                <p>Academic Approved</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['approved'] }}</h3>
                                <p>Final Approved</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stats['rejected'] }}</h3>
                                <p>Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.applications.all') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
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
                                            <label for="department" class="form-label">Department</label>
                                            <select class="form-select" id="department" name="department">
                                                <option value="">All Departments</option>
                                                <option value="Computer Science" {{ request('department') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                                <option value="Electrical Engineering" {{ request('department') == 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                                                <option value="Mechanical Engineering" {{ request('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                                                <option value="Civil Engineering" {{ request('department') == 'Civil Engineering' ? 'selected' : '' }}>Civil Engineering</option>
                                                <option value="Business Administration" {{ request('department') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Search</label>
                                            <input type="text" class="form-control" id="search" name="search" 
                                                   value="{{ request('search') }}" placeholder="Search by name or ID...">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-filter me-1"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="applicationsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Application ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Applied Date</th>
                                <th>Student ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong class="text-primary">WYTU-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-primary me-2">
                                            <div class="avatar-content">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
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
                                    <span class="badge bg-light-primary">{{ $application->department }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $application->application_type === 'new' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->application_type) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'payment_pending' => 'warning',
                                            'payment_verified' => 'info',
                                            'academic_approved' => 'secondary',
                                            'approved' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'clock',
                                            'payment_pending' => 'credit-card',
                                            'payment_verified' => 'check-circle',
                                            'academic_approved' => 'award',
                                            'approved' => 'check-lg',
                                            'rejected' => 'x-circle'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                        <i class="bi bi-{{ $statusIcons[$application->status] ?? 'question' }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $application->payment_status === 'completed' ? 'success' : ($application->payment_status === 'verified' ? 'info' : 'warning') }}">
                                        <i class="bi bi-{{ $application->payment_status === 'completed' ? 'check-lg' : ($application->payment_status === 'verified' ? 'check' : 'clock') }} me-1"></i>
                                        {{ ucfirst($application->payment_status) }}
                                    </span>
                                    @if($application->payment_verified_at)
                                    <br><small class="text-muted">{{ $application->payment_verified_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $application->created_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $application->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($application->student_id)
                                        <span class="badge bg-success">
                                            <i class="bi bi-person-badge me-1"></i>
                                            {{ $application->student_id }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not Generated</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <!-- Quick Actions based on status -->
                                        @if($application->status === 'payment_pending')
                                        <form action="{{ route('admin.applications.verify-payment', $application->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="Verify Payment" data-bs-toggle="tooltip"
                                                    onclick="return confirm('Verify payment for {{ $application->name }}?')">
                                                <i class="bi bi-credit-card"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($application->status === 'payment_verified')
                                        <form action="{{ route('admin.applications.academic-approve', $application->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" 
                                                    title="Academic Approve" data-bs-toggle="tooltip"
                                                    onclick="return confirm('Academic approve {{ $application->name }}?')">
                                                <i class="bi bi-award"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($application->status === 'academic_approved')
                                        <form action="{{ route('admin.applications.final-approve', $application->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="Final Approve" data-bs-toggle="tooltip"
                                                    onclick="return confirm('Final approve {{ $application->name }}? This will generate student ID.')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if(in_array($application->status, ['pending', 'payment_pending', 'payment_verified', 'academic_approved']))
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $application->id }}"
                                                title="Reject Application" data-bs-toggle="tooltip">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
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
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                                            You are about to reject this application. This action cannot be undone.
                                                        </div>
                                                        <p>Reject application for <strong>{{ $application->name }}</strong>?</p>
                                                        <div class="form-group">
                                                            <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                      rows="4" required placeholder="Enter detailed rejection reason..."></textarea>
                                                            <div class="form-text">This reason will be communicated to the student.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-x-circle me-1"></i> Reject Application
                                                        </button>
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
                    <div class="avatar avatar-xl bg-light-primary mb-3">
                        <div class="avatar-content">
                            <i class="bi bi-inbox fs-2"></i>
                        </div>
                    </div>
                    <h4>No Applications Found</h4>
                    <p class="text-muted">There are no applications matching your criteria.</p>
                    @if(request()->hasAny(['status', 'department', 'search']))
                    <a href="{{ route('admin.applications.all') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Clear Filters
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Export Button -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="bi bi-download me-1"></i> Export Data
                    </button>
                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" 
                            data-bs-toggle="dropdown">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-filetype-pdf me-2"></i> PDF</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-filetype-xlsx me-2"></i> Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-filetype-csv me-2"></i> CSV</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize DataTable
        $('#applicationsTable').DataTable({
            "pageLength": 25,
            "responsive": true,
            "order": [[7, 'desc']], // Sort by applied date descending
            "language": {
                "search": "Search applications:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "previous": "<i class='bi bi-chevron-left'></i>",
                    "next": "<i class='bi bi-chevron-right'></i>"
                }
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>

<style>
.avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    width: 2.5rem;
    height: 2.5rem;
}
.avatar-xl {
    width: 4rem;
    height: 4rem;
}
.avatar-content {
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-light-primary {
    background-color: #e3f2fd;
    color: #1976d2;
}
</style>
@endpush