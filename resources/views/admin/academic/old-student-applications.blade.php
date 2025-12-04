@extends('layouts.admin')

@section('title', 'Old Student Applications - Academic Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>Old Student Applications
                    </h4>
                    <p class="mb-0">Verify academic eligibility for next academic year</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Current Year</th>
                                    <th>Next Year</th>
                                    <th>CGPA</th>
                                    <th>Department</th>
                                    <th>Payment Status</th>
                                    <th>Academic Status</th>
                                    <th>Submitted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $application->existing_student_id }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $application->application_id }}</small>
                                        </td>
                                        <td>{{ $application->name }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $application->current_year - 1 }} Year
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                Year {{ $application->current_year }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $application->cgpa >= 3.5 ? 'bg-success' : ($application->cgpa >= 2.5 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ number_format($application->cgpa, 2) }}
                                            </span>
                                        </td>
                                        <td>{{ $application->department }}</td>
                                        <td>
                                            <span class="{{ $application->payment_status_badge }}">
                                                {{ ucfirst($application->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $application->academic_approval_status_badge }}">
                                                {{ ucfirst($application->academic_approval_status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.old-application.view', $application->id) }}" 
                                                   class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($application->academic_approval_status === 'pending')
                                                    <a href="{{ route('admin.old-application.view', $application->id) }}" 
                                                       class="btn btn-sm btn-success" title="Verify Now">
                                                        <i class="fas fa-check-circle"></i> Verify
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                                <h5>No Old Student Applications</h5>
                                                <p class="text-muted">No old student applications require academic verification at this time.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection