@extends('admin.layouts.master')

@section('title', 'Payments Management - Global Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payments Management</h1>
        <div class="d-flex">
            <button class="btn btn-primary shadow-sm mr-2" data-toggle="modal" data-target="#exportPaymentsModal">
                <i class="fas fa-download fa-sm text-white-50"></i> Export
            </button>
            <a href="{{ route('admin.global.reports') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-chart-bar fa-sm text-white-50"></i> Reports
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_payments'] }}</div>
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
                                Completed Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Pending Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_payments'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Payments</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.global.payments') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Payment Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="">All Methods</option>
                                <option value="kpay" {{ request('payment_method') == 'kpay' ? 'selected' : '' }}>KPay</option>
                                <option value="wavepay" {{ request('payment_method') == 'wavepay' ? 'selected' : '' }}>WavePay</option>
                                <option value="cbpay" {{ request('payment_method') == 'cbpay' ? 'selected' : '' }}>CB Pay</option>
                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Transaction ID, Application ID, or Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount_range">Amount Range</label>
                            <select class="form-control" id="amount_range" name="amount_range">
                                <option value="">All Amounts</option>
                                <option value="0-50000" {{ request('amount_range') == '0-50000' ? 'selected' : '' }}>0 - 50,000 MMK</option>
                                <option value="50000-100000" {{ request('amount_range') == '50000-100000' ? 'selected' : '' }}>50,000 - 100,000 MMK</option>
                                <option value="100000-200000" {{ request('amount_range') == '100000-200000' ? 'selected' : '' }}>100,000 - 200,000 MMK</option>
                                <option value="200000+" {{ request('amount_range') == '200000+' ? 'selected' : '' }}>200,000+ MMK</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.global.payments') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Payments List</h6>
            <span class="badge badge-primary">Total: {{ $payments->total() }}</span>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="paymentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Application</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Verified At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $payment->transaction_id }}</strong>
                                @if($payment->gateway_transaction_id)
                                <br><small class="text-muted">Gateway: {{ $payment->gateway_transaction_id }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.global.applications.view', $payment->application_id) }}" class="text-info">
                                    {{ $payment->application->application_id }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $payment->application->name }}</strong>
                                <br><small class="text-muted">{{ $payment->application->email }}</small>
                            </td>
                            <td>
                                <strong>{{ number_format($payment->amount, 2) }} MMK</strong>
                            </td>
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
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($payment->status === 'pending' && $payment->application->status === 'payment_pending')
                                    <button type="button" class="btn btn-success verify-payment-btn"
                                            data-payment-id="{{ $payment->id }}"
                                            data-transaction-id="{{ $payment->transaction_id }}"
                                            title="Verify Payment">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif

                                    @if($payment->status === 'completed' && !$payment->refunded_at)
                                    <button type="button" class="btn btn-warning refund-btn"
                                            data-payment-id="{{ $payment->id }}"
                                            data-transaction-id="{{ $payment->transaction_id }}"
                                            title="Refund Payment">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    @endif

                                    @if($payment->refunded_at)
                                    <span class="badge badge-secondary">Refunded</span>
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
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                </div>
                <div>
                    {{ $payments->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-3x text-gray-300 mb-3"></i>
                <h4 class="text-gray-500">No Payments Found</h4>
                <p class="text-gray-500">There are no payments matching your criteria.</p>
                @if(request()->anyFilled(['status', 'payment_method', 'date_from', 'date_to', 'search']))
                <a href="{{ route('admin.global.payments') }}" class="btn btn-primary">
                    <i class="fas fa-redo mr-2"></i> Clear Filters
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue by Payment Method</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($revenueByMethod as $method)
                        <span class="mr-3">
                            <i class="fas fa-circle text-{{ $method->color }}"></i> {{ $method->payment_method }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Trend</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportPaymentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Payments</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exportPaymentsFormat">Export Format</label>
                    <select class="form-control" id="exportPaymentsFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exportPaymentsRange">Data Range</label>
                    <select class="form-control" id="exportPaymentsRange">
                        <option value="all">All Payments</option>
                        <option value="filtered">Currently Filtered</option>
                        <option value="last_month">Last Month</option>
                        <option value="last_quarter">Last Quarter</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportPaymentsBtn">Export</button>
            </div>
        </div>
    </div>
</div>

<!-- Verify Payment Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="verifyPaymentForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Verify Payment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Verify payment for transaction <strong id="verifyTransactionId"></strong>?</p>
                    <div class="form-group">
                        <label for="verification_notes">Verification Notes (Optional)</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes" 
                                  rows="3" placeholder="Add any notes about this payment verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Verify Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="refundForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Refund Payment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        This action will refund the payment and cannot be undone.
                    </div>
                    <p>Refund payment for transaction <strong id="refundTransactionId"></strong>?</p>
                    <div class="form-group">
                        <label for="refund_reason">Refund Reason</label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" 
                                  rows="3" required placeholder="Enter reason for refund..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#paymentsTable').DataTable({
        "pageLength": 25,
        "order": [[6, 'desc']],
        "language": {
            "search": "Search payments:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });

    // Verify Payment Button
    $('.verify-payment-btn').click(function() {
        const paymentId = $(this).data('payment-id');
        const transactionId = $(this).data('transaction-id');
        
        $('#verifyTransactionId').text(transactionId);
        $('#verifyPaymentForm').attr('action', "{{ url('admin/payments') }}/" + paymentId + "/verify");
        $('#verifyPaymentModal').modal('show');
    });

    // Refund Button
    $('.refund-btn').click(function() {
        const paymentId = $(this).data('payment-id');
        const transactionId = $(this).data('transaction-id');
        
        $('#refundTransactionId').text(transactionId);
        $('#refundForm').attr('action', "{{ url('admin/payments') }}/" + paymentId + "/refund");
        $('#refundModal').modal('show');
    });

    // Export Button
    $('#exportPaymentsBtn').click(function() {
        const format = $('#exportPaymentsFormat').val();
        const range = $('#exportPaymentsRange').val();
        
        // Implement export functionality
        alert('Exporting payments in ' + format + ' format for ' + range + ' range.');
        $('#exportPaymentsModal').modal('hide');
    });

    // Charts
    @if(isset($revenueByMethod) && count($revenueByMethod) > 0)
    // Payment Method Chart
    const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const methodChart = new Chart(methodCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($revenueByMethod->pluck('payment_method')) !!},
            datasets: [{
                data: {!! json_encode($revenueByMethod->pluck('revenue')) !!},
                backgroundColor: {!! json_encode($revenueByMethod->pluck('color')) !!},
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

    @if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
    // Revenue Trend Chart
    const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: "Revenue",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
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
                        maxTicksLimit: 6
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return '₦' + value.toLocaleString();
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
                        return 'Revenue: ₦' + tooltipItem.yLabel.toLocaleString();
                    }
                }
            },
        }
    });
    @endif
});
</script>
@endpush