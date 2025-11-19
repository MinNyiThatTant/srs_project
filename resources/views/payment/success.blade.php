@extends('layouts.master')

@section('title', 'Payment Successful - WYTU Admission')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <div class="checkmark-circle">
                            <div class="checkmark draw"></div>
                        </div>
                    </div>

                    <h1 class="text-success mb-3">Payment Successful!</h1>
                    <p class="lead text-muted mb-4">
                        Your admission fee payment has been processed successfully.
                    </p>

                    <!-- Payment Details -->
                    <div class="payment-details mb-5 p-4 bg-light rounded">
                        <h4 class="mb-3">Payment Details</h4>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p><strong>Transaction ID:</strong><br>{{ $payment->transaction_id }}</p>
                                <p><strong>Application No:</strong><br>{{ $application->application_id }}</p>
                                <p><strong>NRC Number:</strong><br>{{ $application->nrc_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Amount Paid:</strong><br>{{ $payment->formatted_amount }}</p>
                                <p><strong>Payment Method:</strong><br>{{ $payment->payment_method_name }}</p>
                                <p><strong>Paid At:</strong><br>{{ $payment->formatted_paid_at }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="alert alert-info border-0 mb-4">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>What's Next?</h5>
                        <p class="mb-2">Your application has been moved to <strong>Academic Review</strong>.</p>
                        <p class="mb-0">You will be notified via email about the status of your application.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('applications.status', $application->application_id) }}" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Check Application Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.checkmark-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #28a745;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease-out;
}

.checkmark {
    width: 30px;
    height: 50px;
    border: solid white;
    border-width: 0 4px 4px 0;
    transform: rotate(45deg);
    margin-top: -5px;
}

@keyframes scaleIn {
    from { transform: scale(0); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.payment-details p {
    margin-bottom: 0.75rem;
}
</style>
@endpush