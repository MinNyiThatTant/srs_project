@extends('admin.layouts.master')

@section('title', 'Application Details - ' . $application->application_id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <i class="fas fa-file-alt"></i> Application Details: {{ $application->application_id }}
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.applications.academic') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Full Name:</strong></td>
                            <td>{{ $application->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $application->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $application->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>NRC Number:</strong></td>
                            <td>{{ $application->nrc_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td>{{ $application->date_of_birth->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>{{ ucfirst($application->gender) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nationality:</strong></td>
                            <td>{{ $application->nationality }}</td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td>{{ $application->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Department Preferences & Actions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-list-ol me-2"></i>Department Preferences & Approval
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $priorities = $application->getDepartmentPriorities();
                    @endphp
                    
                    <!-- Student's Department Preferences -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-list me-2"></i>Student's Department Preferences
                        </h6>
                        @foreach($priorities as $priority => $department)
                        <div class="priority-item mb-3 p-3 border rounded 
                            @if($priority === 'First Priority') border-primary bg-primary bg-opacity-10
                            @elseif($priority === 'Second Priority') border-success bg-success bg-opacity-10
                            @elseif($priority === 'Third Priority') border-info bg-info bg-opacity-10
                            @else border-warning bg-warning bg-opacity-10
                            @endif">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge 
                                        @if($priority === 'First Priority') bg-primary
                                        @elseif($priority === 'Second Priority') bg-success
                                        @elseif($priority === 'Third Priority') bg-info
                                        @else bg-warning
                                        @endif me-2 fs-6">
                                        {{ str_replace(' Priority', '', $priority) }}
                                    </span>
                                    <strong class="fs-6">{{ $department }}</strong>
                                </div>
                                <form action="{{ route('admin.academic.quick-assign', $application->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="priority_department" value="{{ $department }}">
                                    <button type="submit" 
                                            class="btn 
                                                @if($priority === 'First Priority') btn-primary
                                                @elseif($priority === 'Second Priority') btn-success
                                                @elseif($priority === 'Third Priority') btn-info
                                                @else btn-warning
                                                @endif"
                                            onclick="return confirm('Approve with {{ $priority }}: {{ $department }}?')">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Assign {{ str_replace(' Priority', '', $priority) }}
                                    </button>
                                </form>
                            </div>
                            <div class="mt-2 text-muted">
                                <small>{{ $priority }} Choice</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Department Assignment Form -->
                    <div class="border-top pt-3">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-edit me-2"></i>Custom Department Assignment
                        </h6>
                        <form action="{{ route('admin.academic.assign-department', $application->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="assigned_department" class="form-label fw-bold">Select Department</label>
                                <select class="form-select" id="assigned_department" name="assigned_department" required>
                                    <option value="">Choose a department...</option>
                                    <optgroup label="Student's Preferred Departments">
                                        @foreach($priorities as $priority => $department)
                                            <option value="{{ $department }}" 
                                                {{ $loop->first ? 'selected' : '' }}>
                                                {{ $department }} ({{ str_replace(' Priority', '', $priority) }} Choice)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="All Available Departments">
                                        @foreach($departments as $department)
                                            @if(!in_array($department, array_values($priorities)))
                                                <option value="{{ $department }}">
                                                    {{ $department }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Assign Department & Approve Application
                            </button>
                        </form>
                    </div>

                   <!-- Reject Application -->
<div class="border-top pt-3 mt-3">
    <h6 class="fw-bold text-danger mb-3">
        <i class="fas fa-times me-2"></i>Reject Application
    </h6>
    <form action="{{ route('admin.applications.academic-reject', $application->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="reject_notes" class="form-label fw-bold">Rejection Reason</label>
            <textarea class="form-control" id="reject_notes" name="notes" rows="3" 
                      placeholder="Provide reason for rejection..." required></textarea>
        </div>
        <button type="submit" class="btn btn-danger w-100" 
                onclick="return confirm('Are you sure you want to reject this application?')">
            <i class="fas fa-ban me-2"></i>Reject Application
        </button>
    </form>
</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Background -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i>Educational Background
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>High School:</strong></td>
                                    <td>{{ $application->high_school_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Graduation Year:</strong></td>
                                    <td>{{ $application->graduation_year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Matriculation Score:</strong></td>
                                    <td>
                                        <span class="badge bg-info fs-6">
                                            {{ $application->matriculation_score }}/600
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Previous Qualification:</strong></td>
                                    <td>{{ $application->previous_qualification }}</td>
                                </tr>
                                <tr>
                                    <td><strong>High School Address:</strong></td>
                                    <td>{{ $application->high_school_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    @if($application->payments->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Paid At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($application->payments as $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ number_format($payment->amount) }} MMK</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                    <td>{{ $payment->paid_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.priority-item {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}
.priority-item:hover {
    transform: translateX(5px);
}
.border-primary { border-left-color: #0d6efd !important; }
.border-success { border-left-color: #198754 !important; }
.border-info { border-left-color: #0dcaf0 !important; }
.border-warning { border-left-color: #ffc107 !important; }
.border-secondary { border-left-color: #6c757d !important; }
</style>
@endsection