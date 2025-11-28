@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Complete Your Payment</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($application) && $application)
                        <div class="application-info mb-4">
                            <h5>Application Details</h5>
                            <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
                            <p><strong>Name:</strong> {{ $application->name }}</p>
                            <p><strong>NRC:</strong> {{ $application->nrc_number }}</p>
                            <p><strong>Department:</strong> {{ $application->department }}</p>
                        </div>

                        <div class="payment-details mb-4">
                            <h5>Payment Details</h5>
                            <div class="alert alert-info">
                                <h4 class="text-center">50,000 MMK</h4>
                                <p class="text-center mb-0">Admission Fee</p>
                            </div>
                        </div>

                        <form action="{{ route('payment.process', $application->id) }}" method="POST">
                            @csrf
                            
                            <div class="payment-methods mb-4">
                                <h5>Select Payment Method</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="kpay" value="kpay" checked>
                                    <label class="form-check-label" for="kpay">
                                        KPay
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="wavepay" value="wavepay">
                                    <label class="form-check-label" for="wavepay">
                                        WavePay
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="ayapay" value="ayapay">
                                    <label class="form-check-label" for="ayapay">
                                        AYA Pay
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="okpay" value="okpay">
                                    <label class="form-check-label" for="okpay">
                                        OK Dollar
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                                    <label class="form-check-label" for="card">
                                        Credit/Debit Card
                                    </label>
                                </div>
                            </div>

                            @if(app()->environment('local'))
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="test_mode" id="test_mode" value="1">
                                <label class="form-check-label" for="test_mode">
                                    Test Mode (Skip real payment)
                                </label>
                            </div>
                            @endif

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Pay Now - 50,000 MMK
                                </button>
                                <a href="{{ route('payment.cancel', $application->id) }}" class="btn btn-outline-secondary">
                                    Cancel Payment
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            Application information not available. Please contact support.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection