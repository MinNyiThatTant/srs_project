@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Payment Successful</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(isset($application) && $application && isset($payment) && $payment)
                        <div class="alert alert-success">
                            <h4>Payment Completed Successfully!</h4>
                            <p>Your admission fee payment has been processed successfully.</p>
                        </div>

                        <div class="application-info mb-4">
                            <h5>Application Details</h5>
                            <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
                            <p><strong>Name:</strong> {{ $application->name }}</p>
                            <p><strong>Department:</strong> {{ $application->department }}</p>
                        </div>

                        <div class="payment-info mb-4">
                            <h5>Payment Details</h5>
                            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                            <p><strong>Amount:</strong> {{ number_format($payment->amount) }} MMK</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
                            <p><strong>Paid At:</strong> {{ $payment->paid_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div class="next-steps">
                            <h5>Next Steps</h5>
                            <p>Your payment is being verified by the finance department. You will receive further instructions via email once your application is processed.</p>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="/" class="btn btn-primary">Return to Home</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            Payment information not available. Please contact support.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection