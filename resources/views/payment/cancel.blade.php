@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Payment Cancelled</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4>Payment Cancelled</h4>
                        <p>Your payment process was cancelled. You can try again anytime.</p>
                    </div>

                    @if(isset($application) && $application)
                        <div class="application-info mb-4">
                            <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
                            <p><strong>Name:</strong> {{ $application->name }}</p>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('payment.show', $application->id) }}" class="btn btn-primary">
                                Try Payment Again
                            </a>
                            <a href="/" class="btn btn-outline-secondary">
                                Return to Home
                            </a>
                        </div>
                    @else
                        <div class="d-grid gap-2">
                            <a href="/" class="btn btn-primary">Return to Home</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection