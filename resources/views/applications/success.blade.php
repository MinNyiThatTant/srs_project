@extends('layouts.master')

@section('title', 'Application Submitted - WYTU')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Application Submitted Successfully</h4>
                        <span class="badge bg-light text-success fs-6">Application ID: {{ $application->application_id }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Success Message -->
                    <div class="alert alert-success border-0 mb-4">
                        <div class="d-flex">
                            <i class="fas fa-check-circle fa-2x me-3 mt-1 text-success"></i>
                            <div>
                                <h5 class="alert-heading mb-2">Congratulations!</h5>
                                <p class="mb-2">Your application has been submitted successfully and is now ready for payment.</p>
                                <p class="mb-0"><strong>NRC: {{ $application->nrc_number }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="payment-card bg-light rounded p-4 mb-4">
                        <h5 class="text-primary mb-3"><i class="fas fa-credit-card me-2"></i>Complete Your Admission</h5>
                        <p class="text-muted mb-4">Pay the admission fee to proceed with your application review.</p>
                        
                        <div class="payment-details mb-4 p-3 bg-white rounded">
                            <div class="row text-center">
                                <div class="col-md-6 border-end">
                                    <h3 class="text-primary">50,000 MMK</h3>
                                    <p class="text-muted mb-0">Admission Fee</p>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="text-success">Instant</h3>
                                    <p class="text-muted mb-0">Processing</p>
                                </div>
                            </div>
                        </div>

                        <!-- PAY NOW BUTTON - THIS IS THE IMPORTANT PART -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('payment.show', $application->id) }}" class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-lock me-2"></i>Pay Now - 50,000 MMK
                            </a>
                            <a href="{{ route('applications.status', $application->application_id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-clock me-2"></i>I'll Pay Later
                            </a>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading mb-3"><i class="fas fa-info-circle me-2"></i>What happens next?</h6>
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">1</div>
                                <h6>Payment</h6>
                                <p class="small text-muted mb-0">Complete admission fee payment</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">2</div>
                                <h6>Academic Review</h6>
                                <p class="small text-muted mb-0">Application review by academic team</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">3</div>
                                <h6>Final Approval</h6>
                                <p class="small text-muted mb-0">Department head final approval</p>
                            </div>
                        </div>
                    </div>

                    <!-- Support Information -->
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">
                            Need help? Contact us at 
                            <a href="mailto:admissions@wytu.edu.mm">admissions@wytu.edu.mm</a> 
                            or call <strong>09-123456789</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-number {
    font-weight: bold;
    font-size: 1.1rem;
}
.payment-card {
    border-left: 4px solid #0d6efd;
}
</style>
@endsection