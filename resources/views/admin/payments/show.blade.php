@extends('admin.layouts.master')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-receipt me-2"></i>Payment Details
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Payment Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Transaction ID</th>
                                    <td><code>{{ $payment->transaction_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Gateway Reference</th>
                                    <td>{{ $payment->gateway_reference ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td><strong>{{ number_format($payment->amount) }} MMK</strong></td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->payment_method_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Paid At</th>
                                    <td>
                                        @if($payment->paid_at)
                                            {{ $payment->paid_at->format('M d, Y H:i:s') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6>Application Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Application ID</th>
                                    <td>
                                        <a href="{{ route('admin.applications.view', $payment->application->id) }}">
                                            {{ $payment->application->application_id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Student Name</th>
                                    <td>{{ $payment->application->name }}</td>
                                </tr>
                                <tr>
                                    <th>NRC Number</th>
                                    <td>{{ $payment->application->nrc_number }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $payment->application->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $payment->application->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td>{{ $payment->application->department }}</td>
                                </tr>
                                <tr>
                                    <th>Application Status</th>
                                    <td>
                                        <span class="badge {{ $payment->application->status_badge }}">
                                            {{ $payment->application->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->gateway_response)
                    <div class="mt-4">
                        <h6>Gateway Response</h6>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0"><code>{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    @endif

                    @if($payment->notes)
                    <div class="mt-4">
                        <h6>Notes</h6>
                        <div class="alert alert-info">
                            {{ $payment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        
                        @if($payment->status === 'completed')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#refundModal">
                            <i class="fas fa-undo me-2"></i>Process Refund
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
@if($payment->status === 'completed')
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to refund payment <strong>{{ $payment->transaction_id }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Refund Notes</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Reason for refund..." required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This will refund the payment and reset the application status to payment pending.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection