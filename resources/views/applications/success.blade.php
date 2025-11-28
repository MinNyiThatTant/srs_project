{{-- applications/success.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Application Submitted Successfully</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h4>Congratulations!</h4>
                        <p>Your application has been submitted successfully and is now ready for payment.</p>
                    </div>

                    <div class="application-info">
                        <p><strong>Application ID:</strong> {{ $application->application_id }}</p>
                        <p><strong>NRC:</strong> {{ $application->nrc_number }}</p>
                        <p><strong>Name:</strong> {{ $application->name }}</p>
                        <p><strong>Email:</strong> {{ $application->email }}</p>
                    </div>

                    <hr>

                    <div class="payment-section">
                        <h3>Complete Your Admission</h3>
                        <p>Pay the admission fee to proceed with your application review.</p>
                        
                        <div class="payment-amount">
                            <strong>50,000 MMK Instant Admission Fee Processing</strong>
                        </div>
                        
                        {{-- DEBUG: Check what URL is being generated --}}
                        <div style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px;">
                            <small>Debug Info:</small><br>
                            <small>Application DB ID: {{ $application->id }}</small><br>
                            <small>Payment Route: {{ route('payment.show', $application->id) }}</small><br>
                            <small>Payment URL: {{ url('/payment/' . $application->id) }}</small>
                        </div>
                        
                        {{-- Payment Button --}}
                        <a href="{{ route('payment.show', $application->id) }}" class="btn btn-primary btn-lg">
                            Pay Now - 50,000 MMK
                        </a>
                        
                        <div class="pay-later mt-3">
                            <small>Till Pay Later</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection