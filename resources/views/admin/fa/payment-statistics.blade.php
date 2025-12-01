@extends('admin.layouts.master')

@section('title', 'Payment Statistics - Finance Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment Statistics</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" onclick="window.print()">
                <i class="fas fa-print fa-sm text-white-50"></i> Print Report
            </button>
            <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#exportStatsModal">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Stats
            </button>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Processed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_processed'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Success Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['success_rate'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                Avg. Transaction</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['avg_transaction'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
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
                                Peak Hour</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['peak_hour'] }}</div>
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
        <!-- Daily Revenue Trend -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Revenue Trend (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Distribution -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-3"><i class="fas fa-circle text-success"></i> Completed</span>
                        <span class="mr-3"><i class="fas fa-circle text-warning"></i> Pending</span>
                        <span class="mr-3"><i class="fas fa-circle text-danger"></i> Failed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row">
        <!-- Payment Methods Performance -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods Performance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th>Transactions</th>
                                    <th>Success Rate</th>
                                    <th>Avg. Amount</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($methodPerformance as $method)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($method->payment_method) }}</span>
                                    </td>
                                    <td>{{ $method->transaction_count }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $method->success_rate >= 90 ? 'success' : ($method->success_rate >= 80 ? 'warning' : 'danger') }}" 
                                                 role="progressbar" style="width: {{ $method->success_rate }}%">
                                                {{ $method->success_rate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($method->avg_amount, 2) }}</td>
                                    <td>${{ number_format($method->total_revenue, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hourly Distribution -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hourly Transaction Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="hourlyDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department-wise Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department-wise Revenue</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Total Applications</th>
                                    <th>Paid Applications</th>
                                    <th>Payment Rate</th>
                                    <th>Total Revenue</th>
                                    <th>Avg. Revenue per App</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentStats as $dept)
                                <tr>
                                    <td><strong>{{ $dept->department }}</strong></td>
                                    <td>{{ $dept->total_applications }}</td>
                                    <td>{{ $dept->paid_applications }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $dept->payment_rate >= 80 ? 'success' : ($dept->payment_rate >= 60 ? 'warning' : 'danger') }}" 
                                                 role="progressbar" style="width: {{ $dept->payment_rate }}%">
                                                {{ $dept->payment_rate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($dept->total_revenue, 2) }}</td>
                                    <td>${{ number_format($dept->avg_revenue_per_app, 2) }}</td>
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

<!-- Export Stats Modal -->
<div class="modal fade" id="exportStatsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Statistics</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="statsPeriod">Time Period</label>
                    <select class="form-control" id="statsPeriod">
                        <option value="last_7_days">Last 7 Days</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_quarter">This Quarter</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statsFormat">Export Format</label>
                    <select class="form-control" id="statsFormat">
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportStatsBtn">Export</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Export functionality
    $('#exportStatsBtn').click(function() {
        const period = $('#statsPeriod').val();
        const format = $('#statsFormat').val();
        alert('Exporting statistics for ' + period + ' in ' + format + ' format...');
        $('#exportStatsModal').modal('hide');
    });

    // Charts
    @if(isset($dailyRevenue))
    const revenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
            datasets: [{
                label: "Daily Revenue",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
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
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
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
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        return 'Revenue: $' + tooltipItem.yLabel.toLocaleString();
                    }
                }
            },
        }
    });
    @endif

    @if(isset($paymentStatus))
    const statusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'Failed'],
            datasets: [{
                data: {!! json_encode([$paymentStatus['completed'], $paymentStatus['pending'], $paymentStatus['failed']]) !!},
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
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

    @if(isset($hourlyDistribution))
    const hourlyCtx = document.getElementById('hourlyDistributionChart').getContext('2d');
    const hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyDistribution->pluck('hour')) !!},
            datasets: [{
                label: "Transactions",
                backgroundColor: "#36b9cc",
                hoverBackgroundColor: "#2c9faf",
                borderColor: "#36b9cc",
                data: {!! json_encode($hourlyDistribution->pluck('count')) !!},
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
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
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
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
        }
    });
    @endif
});
</script>
@endpush