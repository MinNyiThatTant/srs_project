@extends('admin.layouts.master')

@section('title', 'HOD Dashboard')
@section('page-title', 'HOD Dashboard')
@section('page-subtitle', 'Department Management & Applications')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <!-- Department Statistics -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Department Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['department_applications'] ?? 0 }}</h4>
                            <p class="text-warning small mb-0">
                                <i class="bi bi-clock"></i> {{ $stats['pending_applications'] ?? 0 }} pending
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-files text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Department Staff</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['department_staff'] ?? 0 }}</h4>
                            <p class="text-info small mb-0">
                                <i class="bi bi-person-check"></i> {{ $stats['active_staff'] ?? 0 }} active
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-people text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Approved Applications</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['approved_applications'] ?? 0 }}</h4>
                            <p class="text-success small mb-0">
                                <i class="bi bi-check-circle"></i> Total approved
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-check-lg text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Pending Review</h6>
                            <h4 class="font-extrabold mb-0">{{ count($stats['pending_applications_list'] ?? []) }}</h4>
                            <p class="text-primary small mb-0">
                                <i class="bi bi-eye"></i> Need your action
                            </p>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-journal-text text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-lg-8">
        <!-- Pending Applications for Review -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Pending Applications for Final Approval</h4>
                @if(Route::has('admin.applications.hod'))
                    <a href="{{ route('admin.applications.hod') }}" class="btn btn-sm btn-primary">View All</a>
                @else
                    <a href="/admin/applications/hod" class="btn btn-sm btn-primary">View All</a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Applied Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($stats['pending_applications_list']) && count($stats['pending_applications_list']) > 0)
                                @foreach($stats['pending_applications_list'] as $application)
                                <tr>
                                    <td><strong>{{ $application->application_id ?? 'N/A' }}</strong></td>
                                    <td>{{ $application->name ?? 'N/A' }}</td>
                                    <td>{{ $application->department ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-warning">Pending Final Approval</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : ($application->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                            {{ $application->payment_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $application->created_at ? $application->created_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if(Route::has('admin.applications.final-approve'))
                                            <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline">
                                        @else
                                            <form action="/admin/final-approve/{{ $application->id }}" method="POST" class="d-inline">
                                        @endif
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Are you sure you want to approve this application?')">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No pending applications for final approval</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- Applications -->
                    @if(Route::has('admin.applications.hod'))
                        <a href="{{ route('admin.applications.hod') }}" class="btn btn-outline-primary text-start">
                    @else
                        <a href="/admin/applications/hod" class="btn btn-outline-primary text-start">
                    @endif
                        <i class="bi bi-files me-2"></i> Review Applications
                    </a>

                    <!-- My Department -->
                    @if(Route::has('admin.my-department'))
                        <a href="{{ route('admin.my-department') }}" class="btn btn-outline-info text-start">
                    @else
                        <a href="/admin/my-department" class="btn btn-outline-info text-start">
                    @endif
                        <i class="bi bi-building me-2"></i> My Department
                    </a>

                    <!-- Department Applications -->
                    @if(Route::has('admin.department.applications'))
                        <a href="{{ route('admin.department.applications') }}" class="btn btn-outline-warning text-start">
                    @else
                        <a href="/admin/department-applications" class="btn btn-outline-warning text-start">
                    @endif
                        <i class="bi bi-list-check me-2"></i> All Department Applications
                    </a>

                    <!-- Staff Management -->
                    @if(Route::has('admin.hod.staff.index'))
                        <a href="{{ route('admin.hod.staff.index') }}" class="btn btn-outline-success text-start">
                    @else
                        <a href="/admin/hod/staff" class="btn btn-outline-success text-start">
                    @endif
                        <i class="bi bi-people me-2"></i> Manage Staff
                    </a>
                </div>
            </div>
        </div>

        <!-- Department Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Department Information</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Department:</strong>
                    <p class="mb-1">{{ Auth::guard('admin')->user()->department ?? 'Not assigned' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Role:</strong>
                    <p class="mb-1">Head of Department</p>
                </div>
                <div class="mb-3">
                    <strong>Last Login:</strong>
                    <p class="mb-1">{{ Auth::guard('admin')->user()->last_login_at ? Auth::guard('admin')->user()->last_login_at->format('M d, Y H:i') : 'First login' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Staff Count:</strong>
                    <p class="mb-1">{{ $stats['department_staff'] ?? 0 }} staff members</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Quick Stats</h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-primary">{{ $stats['department_applications'] ?? 0 }}</h5>
                        <small class="text-muted">Total Apps</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">{{ $stats['approved_applications'] ?? 0 }}</h5>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Additional JavaScript for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
            this.disabled = true;
            
            // Revert after 3 seconds if still on same page
            setTimeout(() => {
                if (this.disabled) {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            }, 3000);
        });
    });
});
</script>
@endpush