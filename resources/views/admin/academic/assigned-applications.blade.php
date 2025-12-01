@extends('admin.layouts.master')

@section('title', 'Assigned Applications - WYTU University')
@section('page-title', 'Applications Ready for Approval')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Applications with Assigned Departments - Ready for Approval
        </h4>
        <p class="mb-0 mt-2 text-dark">
            These applications have departments assigned and are ready for academic approval
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

        @if($applications->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>App ID</th>
                        <th>Student Information</th>
                        <th>Assigned Department</th>
                        <th>Assigned Date</th>
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
                        </td>
                        <td>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-building me-1"></i>
                                {{ $application->assigned_department }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $application->department_assigned_at->format('M d, Y H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                <!-- Approve Application -->
                                <form action="{{ route('applications.academic-approve', $application->id) }}" 
                                      method="POST" class="d-inline mb-1">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-success w-100"
                                            onclick="return confirm('Approve {{ $application->name }} with department: {{ $application->assigned_department }}?')"
                                            title="Approve Application">
                                        <i class="fas fa-check me-1"></i>Approve
                                    </button>
                                </form>
                                
                                <!-- Reassign Department -->
                                <a href="{{ route('admin.applications.view', $application->id) }}" 
                                   class="btn btn-sm btn-warning mb-1">
                                    <i class="fas fa-edit me-1"></i>Reassign
                                </a>
                                
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
            <h4 class="text-muted">No Applications Ready for Approval</h4>
            <p class="text-muted">All assigned applications have been processed or no departments have been assigned yet.</p>
            <a href="{{ route('admin.applications.academic') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Applications
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Include the same reject modal from applications.blade.php -->
@include('admin.academic.partials.reject-modal')
@endsection