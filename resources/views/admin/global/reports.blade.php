@extends('admin.layouts.master')

@section('title', 'Reports & Analytics - Global Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reports & Analytics</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" id="generateReportBtn">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#scheduleReportModal">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Schedule Report
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Report Period</h6>
        </div>
        <div class="card-body">
            <form id="reportFilterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="report_type">Report Type</label>
                            <select class="form-control" id="report_type" name="report_type">
                                <option value="applications">Applications Report</option>
                                <option value="payments">Payments Report</option>
                                <option value="admissions">Admissions Report</option>
                                <option value="department">Department-wise Report</option>
                                <option value="comprehensive">Comprehensive Report</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_range">Date Range</label>
                            <select class="form-control" id="date_range" name="date_range">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last_7_days" selected>Last 7 Days</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 custom-date-range" style="display: none;">
                        <div class="form-group">
                            <label for="custom_start">Start Date</label>
                            <input type="date" class="form-control" id="custom_start" name="custom_start">
                        </div>
                    </div>
                    <div class="col-md-3 custom-date-range" style="display: none;">
                        <div class="form-group">
                            <label for="custom_end">End Date</label>
                            <input type="date" class="form-control" id="custom_end" name="custom_end">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="department_filter">Department (Optional)</label>
                            <select class="form-control" id="department_filter" name="department_filter">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="format">Export Format</label>
                            <select class="form-control" id="format" name="format">
                                <option value="pdf">PDF Document</option>
                                <option value="excel">Excel Spreadsheet</option>
                                <option value="csv">CSV File</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary" id="applyFiltersBtn">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetFiltersBtn">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['total_applications'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approval Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['approval_rate'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($metrics['total_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg. Processing Time</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['avg_processing_days'] }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Applications Trend -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Applications Trend</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="applicationsTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($statusDistribution as $status)
                        <span class="mr-2">
                            <i class="fas fa-circle text-{{ $status->color }}"></i> {{ $status->status }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Performance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="departmentPerformanceTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Applications</th>
                                    <th>Approved</th>
                                    <th>Approval Rate</th>
                                    <th>Pending</th>
                                    <th>Revenue</th>
                                    <th>Avg. Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentPerformance as $dept)
                                <tr>
                                    <td><strong>{{ $dept->department }}</strong></td>
                                    <td>{{ $dept->total_applications }}</td>
                                    <td>{{ $dept->approved_applications }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $dept->approval_rate >= 70 ? 'success' : ($dept->approval_rate >= 50 ? 'warning' : 'danger') }}" 
                                                 role="progressbar" style="width: {{ $dept->approval_rate }}%">
                                                {{ $dept->approval_rate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $dept->pending_applications }}</td>
                                    <td>${{ number_format($dept->revenue, 2) }}</td>
                                    <td>{{ number_format($dept->avg_score, 1) }}/600</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detailed Report Data</h6>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" id="toggleApplicationsView">
                            <i class="fas fa-table"></i> Applications
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="togglePaymentsView">
                            <i class="fas fa-credit-card"></i> Payments
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Applications Report -->
                    <div id="applicationsReportView">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="applicationsReportTable">
                                <thead>
                                    <tr>
                                        <th>Application ID</th>
                                        <th>Student Name</th>
                                        <th>Department</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Processing Time</th>
                                        <th>Matriculation Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailedApplications as $application)
                                    <tr>
                                        <td>{{ $application->application_id }}</td>
                                        <td>{{ $application->name }}</td>
                                        <td>{{ $application->department }}</td>
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
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($application->final_approved_at)
                                            {{ $application->created_at->diffInDays($application->final_approved_at) }} days
                                            @else
                                            In Progress
                                            @endif
                                        </td>
                                        <td>{{ $application->matriculation_score ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payments Report -->
                    <div id="paymentsReportView" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="paymentsReportTable">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Application ID</th>
                                        <th>Student Name</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                        <th>Verified At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailedPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ $payment->application->application_id }}</td>
                                        <td>{{ $payment->application->name }}</td>
                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ strtoupper($payment->payment_method) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($payment->paid_at)
                                            {{ $payment->paid_at->format('M d, Y H:i') }}
                                            @else
                                            <span class="text-muted">Not paid</span>
                                            @endif
                                        </td>
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
</div>

<!-- Schedule Report Modal -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Automated Report</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="scheduleReportForm">
                    <div class="form-group">
                        <label for="schedule_report_type">Report Type</label>
                        <select class="form-control" id="schedule_report_type" name="report_type">
                            <option value="daily_applications">Daily Applications Report</option>
                            <option value="weekly_payments">Weekly Payments Report</option>
                            <option value="monthly_admissions">Monthly Admissions Report</option>
                            <option value="quarterly_comprehensive">Quarterly Comprehensive Report</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schedule_frequency">Frequency</label>
                        <select class="form-control" id="schedule_frequency" name="frequency">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient_email">Recipient Email</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email" 
                               placeholder="Enter email address" required>
                    </div>
                    <div class="form-group">
                        <label for="schedule_format">Format</label>
                        <select class="form-control" id="schedule_format" name="format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveScheduleBtn">Save Schedule</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Date range toggle
    $('#date_range').change(function() {
        if ($(this).val() === 'custom') {
            $('.custom-date-range').show();
        } else {
            $('.custom-date-range').hide();
        }
    });

    // Toggle report views
    $('#toggleApplicationsView').click(function() {
        $('#applicationsReportView').show();
        $('#paymentsReportView').hide();
        $(this).addClass('btn-primary').removeClass('btn-outline-primary');
        $('#togglePaymentsView').addClass('btn-outline-success').removeClass('btn-success');
    });

    $('#togglePaymentsView').click(function() {
        $('#applicationsReportView').hide();
        $('#paymentsReportView').show();
        $(this).addClass('btn-success').removeClass('btn-outline-success');
        $('#toggleApplicationsView').addClass('btn-outline-primary').removeClass('btn-primary');
    });

    // Initialize DataTables
    $('#applicationsReportTable').DataTable({
        "pageLength": 10,
        "order": [[5, 'desc']]
    });

    $('#paymentsReportTable').DataTable({
        "pageLength": 10,
        "order": [[6, 'desc']]
    });

    $('#departmentPerformanceTable').DataTable({
        "pageLength": 10,
        "order": [[1, 'desc']]
    });

    // Generate Report
    $('#generateReportBtn').click(function() {
        const reportType = $('#report_type').val();
        const dateRange = $('#date_range').val();
        const format = $('#format').val();
        
        // Simulate report generation
        alert('Generating ' + reportType + ' report for ' + dateRange + ' in ' + format + ' format...');
        
        // In real implementation, this would trigger a download
        // window.location.href = "{{ route('admin.global.reports.export') }}?type=" + reportType + "&range=" + dateRange + "&format=" + format;
    });

    // Save Schedule
    $('#saveScheduleBtn').click(function() {
        const formData = $('#scheduleReportForm').serialize();
        alert('Report schedule saved successfully!');
        $('#scheduleReportModal').modal('hide');
    });

    // Charts
    @if(isset($applicationsTrend) && count($applicationsTrend) > 0)
    // Applications Trend Chart
    const trendCtx = document.getElementById('applicationsTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($applicationsTrend->pluck('date')) !!},
            datasets: [{
                label: "Applications",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: {!! json_encode($applicationsTrend->pluck('count')) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 5,
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
            }
        }
    });
    @endif

    @if(isset($statusDistribution) && count($statusDistribution) > 0)
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusDistribution->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($statusDistribution->pluck('count')) !!},
                backgroundColor: {!! json_encode($statusDistribution->pluck('color')) !!},
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
    @endif
});
</script>
@endpush