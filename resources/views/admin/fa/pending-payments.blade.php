@extends('admin.layouts.master')

@section('title', 'Pending Payments - Finance Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pending Payments</h1>
        <div class="d-flex">
            <a href="{{ route('admin.applications.finance') }}" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-credit-card fa-sm text-white-50"></i> Verify Payments
            </a>
            <button class="btn btn-success shadow-sm" id="bulkVerifyBtn">
                <i class="fas fa-check-double fa-sm text-white-50"></i> Bulk Verify
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_pending'] }}</div>
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
                                Pending > 24h</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_over_24h'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Avg. Processing Time</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avg_processing_hours'] }}h</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-stopwatch fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pending Payments List</h6>
            <div class="d-flex align-items-center">
                <span class="badge badge-warning mr-3">Total: {{ $payments->total() }}</span>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="selectAllPending">
                    <label class="custom-control-label" for="selectAllPending">Select All</label>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="pendingPaymentsTable">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllHeader">
                            </th>
                            <th>Transaction ID</th>
                            <th>Student</th>
                            <th>Application ID</th>
                            <th>Department</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Pending Since</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr class="{{ $payment->pending_duration_hours > 24 ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" class="payment-checkbox" value="{{ $payment->id }}"
                                       data-application-id="{{ $payment->application_id }}">
                            </td>
                            <td>
                                <strong class="text-primary">{{ $payment->transaction_id }}</strong>
                                @if($payment->gateway_transaction_id)
                                <br><small class="text-muted">Gateway: {{ $payment->gateway_transaction_id }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $payment->application->name }}</strong>
                                <br><small class="text-muted">{{ $payment->application->email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.applications.view', $payment->application_id) }}" class="text-info">
                                    {{ $payment->application->application_id }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $payment->application->department }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($payment->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ strtoupper($payment->payment_method) }}</span>
                            </td>
                            <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->pending_duration_hours > 24 ? 'danger' : 'warning' }}">
                                    {{ $payment->pending_duration }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-success verify-single-btn"
                                            data-payment-id="{{ $payment->id }}"
                                            data-transaction-id="{{ $payment->transaction_id }}"
                                            title="Verify Payment">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button" class="btn btn-warning contact-student-btn"
                                            data-student-name="{{ $payment->application->name }}"
                                            data-student-email="{{ $payment->application->email }}"
                                            title="Contact Student">
                                        <i class="fas fa-envelope"></i>
                                    </button>

                                    @if($payment->pending_duration_hours > 24)
                                    <button type="button" class="btn btn-danger escalate-btn"
                                            data-payment-id="{{ $payment->id }}"
                                            title="Escalate Issue">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
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
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h4 class="text-success">No Pending Payments</h4>
                <p class="text-muted">All payments have been processed successfully.</p>
                <a href="{{ route('admin.fa.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt mr-2"></i> Back to Dashboard
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions Panel -->
    @if($payments->count() > 0)
    <div class="card shadow" id="bulkActionsPanel" style="display: none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bulk Actions</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><span id="selectedCount">0</span> payments selected</p>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-success mr-2" id="bulkVerifySelected">
                        <i class="fas fa-check-double mr-2"></i> Verify Selected
                    </button>
                    <button type="button" class="btn btn-secondary" id="clearSelection">
                        Clear Selection
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
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

<!-- Contact Student Modal -->
<div class="modal fade" id="contactStudentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="contactStudentForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Contact Student</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Send message to <strong id="studentName"></strong> (<span id="studentEmail"></span>)</p>
                    <div class="form-group">
                        <label for="message_subject">Subject</label>
                        <input type="text" class="form-control" id="message_subject" name="subject" 
                               value="Payment Verification Required" required>
                    </div>
                    <div class="form-group">
                        <label for="message_content">Message</label>
                        <textarea class="form-control" id="message_content" name="content" rows="5" required>
Dear Student,

We noticed that your payment is still pending verification. Please ensure that you have completed the payment process.

If you have already made the payment, please contact us immediately.

Best regards,
Finance Department
West Yangon Technological University
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#pendingPaymentsTable').DataTable({
        "pageLength": 25,
        "order": [[7, 'asc']],
        "language": {
            "search": "Search pending payments:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });

    // Selection functionality
    $('#selectAllHeader, #selectAllPending').change(function() {
        const isChecked = $(this).prop('checked');
        $('.payment-checkbox').prop('checked', isChecked);
        updateBulkActionsPanel();
    });

    $('.payment-checkbox').change(function() {
        updateBulkActionsPanel();
    });

    function updateBulkActionsPanel() {
        const selectedCount = $('.payment-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
        
        if (selectedCount > 0) {
            $('#bulkActionsPanel').show();
        } else {
            $('#bulkActionsPanel').hide();
        }
    }

    // Clear selection
    $('#clearSelection').click(function() {
        $('.payment-checkbox').prop('checked', false);
        $('#selectAllHeader').prop('checked', false);
        $('#selectAllPending').prop('checked', false);
        updateBulkActionsPanel();
    });

    // Verify single payment
    $('.verify-single-btn').click(function() {
        const paymentId = $(this).data('payment-id');
        const transactionId = $(this).data('transaction-id');
        
        $('#verifyTransactionId').text(transactionId);
        $('#verifyPaymentForm').attr('action', "{{ url('admin/verify-payment') }}/" + paymentId);
        $('#verifyPaymentModal').modal('show');
    });

    // Contact student
    $('.contact-student-btn').click(function() {
        const studentName = $(this).data('student-name');
        const studentEmail = $(this).data('student-email');
        
        $('#studentName').text(studentName);
        $('#studentEmail').text(studentEmail);
        $('#contactStudentModal').modal('show');
    });

    // Bulk verify
    $('#bulkVerifyBtn, #bulkVerifySelected').click(function() {
        const selectedPayments = $('.payment-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedPayments.length === 0) {
            alert('Please select at least one payment to verify.');
            return;
        }

        if (confirm('Verify ' + selectedPayments.length + ' selected payments?')) {
            // Implement bulk verification
            alert('Bulk verification for ' + selectedPayments.length + ' payments initiated.');
        }
    });

    // Escalate issue
    $('.escalate-btn').click(function() {
        const paymentId = $(this).data('payment-id');
        if (confirm('Escalate this payment issue to higher management?')) {
            // Implement escalation
            alert('Payment issue escalated.');
        }
    });
});
</script>
@endpush