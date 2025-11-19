@extends('admin.layouts.master')

@section('title', 'Department Head Dashboard - HOD')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Department Head Dashboard</h1>
        <div class="d-flex">
            <a href="{{ route('admin.applications.hod') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-check-double fa-sm text-white-50"></i> Final Approval
            </a>
            <a href="{{ route('admin.my-department') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-building fa-sm text-white-50"></i> My Department
            </a>
        </div>
    </div>

    <!-- Department Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department: {{ Auth::guard('admin')->user()->department }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="border-right pr-3">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departmentInfo['total_students'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-right pr-3">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departmentInfo['active_students'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border-right pr-3">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Faculty Members</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departmentInfo['faculty_count'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Approvals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departmentInfo['pending_approvals'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <!-- Pending Final Approval Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Final Approval</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_final_approval'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved_this_month'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Capacity Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Capacity Utilization</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['capacity_utilization'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg. Processing Time Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Avg. Processing Time</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avg_processing_days'] }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stopwatch fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Applications for Final Approval -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Applications Pending Final Approval</h6>
                    <a href="{{ route('admin.applications.hod') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Application ID</th>
                                    <th>Student Name</th>
                                    <th>Matriculation Score</th>
                                    <th>Academic Approved</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingApplications as $application)
                                <tr>
                                    <td>
                                        <strong>{{ $application->application_id }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $application->name }}</strong>
                                        <br><small class="text-muted">{{ $application->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $application->matriculation_score >= 400 ? 'success' : ($application->matriculation_score >= 300 ? 'warning' : 'danger') }}">
                                            {{ $application->matriculation_score }}/600
                                        </span>
                                    </td>
                                    <td>
                                        {{ $application->academic_approved_at->format('M d, Y') }}
                                        <br><small class="text-muted">by {{ $application->academic_approved_by }}</small>
                                    </td>
                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.applications.view', $application->id) }}" 
                                               class="btn btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-success final-approve-btn"
                                                    data-application-id="{{ $application->id }}"
                                                    data-student-name="{{ $application->name }}"
                                                    title="Final Approve">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <button class="btn btn-warning request-info-btn"
                                                    data-application-id="{{ $application->id }}"
                                                    data-student-name="{{ $application->name }}"
                                                    title="Request More Info">
                                                <i class="fas fa-question-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Department Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.applications.hod') }}" class="btn btn-warning btn-block text-left">
                            <i class="fas fa-check-double mr-2"></i>Final Approval
                            <span class="badge badge-danger badge-pill ml-2">{{ $stats['pending_final_approval'] }}</span>
                        </a>
                        <a href="{{ route('admin.my-department') }}" class="btn btn-info btn-block text-left">
                            <i class="fas fa-building mr-2"></i>Department Overview
                        </a>
                        <a href="{{ route('admin.department.applications') }}" class="btn btn-success btn-block text-left">
                            <i class="fas fa-list mr-2"></i>All Department Applications
                        </a>
                        <button class="btn btn-primary btn-block text-left" data-toggle="modal" data-target="#departmentReportModal">
                            <i class="fas fa-chart-bar mr-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Department Performance -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Performance</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Approval Rate</small>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $departmentStats['approval_rate'] }}%">
                                {{ $departmentStats['approval_rate'] }}%
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Student Retention</small>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ $departmentStats['retention_rate'] }}%">
                                {{ $departmentStats['retention_rate'] }}%
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Faculty Utilization</small>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $departmentStats['faculty_utilization'] }}%">
                                {{ $departmentStats['faculty_utilization'] }}%
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Last updated: {{ now()->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Department Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Department Activities</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Action By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->student_name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $activity->status_color }}">
                                            {{ $activity->status }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->action_by }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Final Approve Modal -->
<div class="modal fade" id="finalApproveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="finalApproveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Final Approval</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        This will generate student ID and complete the admission process.
                    </div>
                    <p>Grant final approval to <strong id="finalApproveStudentName"></strong>?</p>
                    <div class="form-group">
                        <label for="final_approval_notes">Approval Notes (Optional)</label>
                        <textarea class="form-control" id="final_approval_notes" name="notes" 
                                  rows="3" placeholder="Add any final approval notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Final Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Request Info Modal -->
<div class="modal fade" id="requestInfoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="requestInfoForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Additional Information</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Request additional information from <strong id="requestInfoStudentName"></strong>?</p>
                    <div class="form-group">
                        <label for="information_request">Information Required *</label>
                        <textarea class="form-control" id="information_request" name="information_request" 
                                  rows="4" required placeholder="Specify what additional information is required..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline for Response</label>
                        <input type="date" class="form-control" id="deadline" name="deadline">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Department Report Modal -->
<div class="modal fade" id="departmentReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Department Report</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="report_type">Report Type</label>
                    <select class="form-control" id="report_type">
                        <option value="admissions">Admissions Report</option>
                        <option value="performance">Performance Report</option>
                        <option value="faculty">Faculty Report</option>
                        <option value="comprehensive">Comprehensive Report</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="report_period">Time Period</label>
                    <select class="form-control" id="report_period">
                        <option value="last_month">Last Month</option>
                        <option value="this_quarter">This Quarter</option>
                        <option value="last_quarter">Last Quarter</option>
                        <option value="this_year">This Year</option>
                        <option value="last_year">Last Year</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="report_format">Format</label>
                    <select class="form-control" id="report_format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="generateReportBtn">Generate Report</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Final approve button
    $('.final-approve-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const studentName = $(this).data('student-name');
        
        $('#finalApproveStudentName').text(studentName);
        $('#finalApproveForm').attr('action', "{{ url('admin/final-approve') }}/" + applicationId);
        $('#finalApproveModal').modal('show');
    });

    // Request info button
    $('.request-info-btn').click(function() {
        const applicationId = $(this).data('application-id');
        const studentName = $(this).data('student-name');
        
        $('#requestInfoStudentName').text(studentName);
        $('#requestInfoForm').attr('action', "{{ url('admin/request-info') }}/" + applicationId);
        $('#requestInfoModal').modal('show');
    });

    // Generate report
    $('#generateReportBtn').click(function() {
        const reportType = $('#report_type').val();
        const reportPeriod = $('#report_period').val();
        const reportFormat = $('#report_format').val();
        
        alert('Generating ' + reportType + ' report for ' + reportPeriod + ' in ' + reportFormat + ' format...');
        $('#departmentReportModal').modal('hide');
    });
});
</script>
@endpush