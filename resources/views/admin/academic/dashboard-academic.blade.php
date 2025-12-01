@extends('admin.layouts.master')

@section('title', 'Academic Admin Dashboard')
@section('page-title', 'Academic Affairs Dashboard')
@section('page-subtitle', 'Student Applications & Approval Management')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card card-stat">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="stats">
                            <h6 class="text-muted">Pending Reviews</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['pending_reviews'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-warning">
                            <i class="bi bi-clock text-white"></i>
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
                            <h6 class="text-muted">Approved Today</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['approved_today'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-success">
                            <i class="bi bi-check-circle text-white"></i>
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
                            <h6 class="text-muted">Total Reviewed</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_reviewed'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-info">
                            <i class="bi bi-list-check text-white"></i>
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
                            <h6 class="text-muted">Total Students</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total_students'] }}</h4>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="icon icon-lg rounded-circle bg-primary">
                            <i class="bi bi-people text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Applications Ready for Academic Review
                </h4>
                <a href="{{ route('admin.applications.academic') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i> View All Applications
                </a>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
<<<<<<< HEAD
                                <th>Preferred Majors</th>
=======
                                <th>Department</th>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                                <th>Payment Status</th>
                                <th>Verified Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
<<<<<<< HEAD
                            @php
                                $priorities = $application->getDepartmentPriorities();
                            @endphp
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                            <tr>
                                <td>
                                    <strong>{{ $application->application_id ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $application->name }}</td>
                                <td>
<<<<<<< HEAD
                                    @foreach($priorities as $priority => $department)
                                        <span class="badge 
                                            @if($priority === 'First Priority') bg-primary
                                            @elseif($priority === 'Second Priority') bg-success
                                            @elseif($priority === 'Third Priority') bg-info
                                            @else bg-secondary
                                            @endif mb-1">
                                            {{ str_replace(' Priority', '', $priority) }}: {{ $department }}
                                        </span><br>
                                    @endforeach
=======
                                    <span class="badge bg-secondary">{{ $application->department }}</span>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Verified
                                    </span>
                                </td>
                                <td>{{ $application->payment_verified_at ? $application->payment_verified_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.applications.view', $application->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="View Application Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
<<<<<<< HEAD
                                        
                                        <!-- Quick Assign for each priority -->
                                        @foreach($priorities as $priority => $department)
                                        <form action="{{ route('admin.academic.quick-assign', $application->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="priority_department" value="{{ $department }}">
                                            <button type="submit" 
                                                    class="btn btn-sm 
                                                        @if($priority === 'First Priority') btn-success
                                                        @elseif($priority === 'Second Priority') btn-info
                                                        @elseif($priority === 'Third Priority') btn-warning
                                                        @else btn-secondary
                                                        @endif"
                                                    onclick="return confirm('Assign {{ $priority }}: {{ $department }} to {{ $application->name }}?')"
                                                    title="Assign {{ $priority }}">
                                                {{ str_replace(' Priority', '', $priority) }}
                                            </button>
                                        </form>
                                        @endforeach
=======
                                        <form action="{{ route('admin.applications.academic-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info" 
                                                    onclick="return confirm('Grant academic approval to {{ $application->name }}?')"
                                                    title="Academic Approval">
                                                <i class="bi bi-check"></i> Academic
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.applications.final-approve', $application->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" 
                                                    onclick="return confirm('Final approval will create student account and send credentials. Continue with {{ $application->name }}?')"
                                                    title="Final Approval & Create Student">
                                                <i class="bi bi-check-lg"></i> Final
                                            </button>
                                        </form>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $applications->links() }}
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>No applications ready for academic review.</strong>
                    <p class="mb-0 mt-2">All payment-verified applications have been processed.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.applications.academic') }}" class="btn btn-outline-primary btn-lg text-start">
                        <i class="bi bi-files me-2"></i>
                        <div>
                            <strong>All Applications</strong>
                            <br>
                            <small class="text-muted">View all academic applications</small>
                        </div>
                    </a>
                    
<<<<<<< HEAD
                    {{-- <a href="{{ route('admin.academic.affairs') }}" class="btn btn-outline-info btn-lg text-start">
=======
                    <a href="{{ route('admin.academic.affairs') }}" class="btn btn-outline-info btn-lg text-start">
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                        <i class="bi bi-gear me-2"></i>
                        <div>
                            <strong>Academic Affairs</strong>
                            <br>
                            <small class="text-muted">Academic management settings</small>
                        </div>
<<<<<<< HEAD
                    </a> --}}
                    
                    {{-- <a href="{{ route('admin.course.management') }}" class="btn btn-outline-success btn-lg text-start">
=======
                    </a>
                    
                    <a href="{{ route('admin.course.management') }}" class="btn btn-outline-success btn-lg text-start">
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                        <i class="bi bi-book me-2"></i>
                        <div>
                            <strong>Course Management</strong>
                            <br>
                            <small class="text-muted">Manage courses and curriculum</small>
                        </div>
<<<<<<< HEAD
                    </a> --}}
=======
                    </a>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Approval Workflow
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <h6 class="mb-1">1. Payment Verification</h6>
                        <small class="text-muted">Finance admin verifies payment completion</small>
                    </div>
                    <div class="list-group-item">
<<<<<<< HEAD
                        <h6 class="mb-1">2. Department Assignment</h6>
                        <small class="text-muted">Academic admin assigns department/major</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">3. Academic Approval</h6>
=======
                        <h6 class="mb-1">2. Academic Review</h6>
                        <small class="text-muted">Academic admin reviews application</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">3. Final Approval</h6>
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                        <small class="text-muted">Create student account & send credentials</small>
                    </div>
                    <div class="list-group-item">
                        <h6 class="mb-1">4. HOD Approval (Optional)</h6>
                        <small class="text-muted">Department head gives final approval</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-stat {
    transition: transform 0.2s;
}

.card-stat:hover {
    transform: translateY(-5px);
}
</style>
@endsection