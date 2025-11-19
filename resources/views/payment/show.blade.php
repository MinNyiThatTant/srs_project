@extends('layouts.master')

@section('title', 'Complete Payment - WYTU Admission')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Complete Your Payment</h4>
                        <span class="badge bg-light text-primary fs-6">NRC: {{ $application->nrc_number }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Payment Information -->
                    <div class="payment-info text-center mb-5 p-4 bg-light rounded">
                        <h2 class="text-primary mb-3">Admission Fee: 50,000 MMK</h2>
                        <p class="lead text-muted mb-4">
                            <i class="fas fa-shield-alt me-2"></i>
                            Pay securely using KPay, WavePay, AYA Pay, OK Pay, or Credit Card
                        </p>
                    </div>

                    <form action="{{ route('payment.process', $application->id) }}" method="POST" id="payment-form">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div class="payment-methods mb-5">
                            <h5 class="mb-4"><i class="fas fa-wallet me-2"></i>Select Payment Method</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="payment_method" value="kpay" id="kpay" required>
                                    <label class="btn btn-outline-primary w-100 p-3 payment-method-label" for="kpay">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-mobile-alt fa-2x me-3 text-primary"></i>
                                            <div class="text-start">
                                                <h6 class="mb-1">KPay</h6>
                                                <small class="text-muted">Fast and secure mobile payment</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="payment_method" value="wavepay" id="wavepay">
                                    <label class="btn btn-outline-primary w-100 p-3 payment-method-label" for="wavepay">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-wave-square fa-2x me-3 text-primary"></i>
                                            <div class="text-start">
                                                <h6 class="mb-1">WavePay</h6>
                                                <small class="text-muted">Popular digital wallet</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="payment_method" value="ayapay" id="ayapay">
                                    <label class="btn btn-outline-primary w-100 p-3 payment-method-label" for="ayapay">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-university fa-2x me-3 text-primary"></i>
                                            <div class="text-start">
                                                <h6 class="mb-1">AYA Pay</h6>
                                                <small class="text-muted">Bank-integrated payment</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="payment_method" value="okpay" id="okpay">
                                    <label class="btn btn-outline-primary w-100 p-3 payment-method-label" for="okpay">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-money-bill-wave fa-2x me-3 text-primary"></i>
                                            <div class="text-start">
                                                <h6 class="mb-1">OK Pay</h6>
                                                <small class="text-muted">Convenient payment solution</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <input type="radio" class="btn-check" name="payment_method" value="card" id="card">
                                    <label class="btn btn-outline-primary w-100 p-3 payment-method-label" for="card">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-credit-card fa-2x me-3 text-primary"></i>
                                            <div class="text-start">
                                                <h6 class="mb-1">Credit/Debit Card</h6>
                                                <small class="text-muted">Visa, MasterCard, UnionPay</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Application Workflow -->
                        <div class="application-workflow mb-5">
                            <h5 class="mb-4"><i class="fas fa-tasks me-2"></i>Application Workflow</h5>
                            <div class="steps">
                                <div class="step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="step-label">Academic Review</span>
                                </div>
                                <div class="step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="step-label">Head of Department</span>
                                </div>
                                <div class="step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="step-label">Department Approval</span>
                                </div>
                                <div class="step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="step-label">Academic Affairs</span>
                                </div>
                                <div class="step current">
                                    <div class="step-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <span class="step-label">Payment</span>
                                </div>
                            </div>
                        </div>

                        <!-- Current Step Info -->
                        <div class="alert alert-info border-0">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-2x me-3 mt-1 text-info"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Current Step: Payment Processing</h6>
                                    <p class="mb-2">Please complete the admission fee payment to proceed with academic review.</p>
                                    <p class="mb-0"><strong>Once payment is verified, your application will move to academic review.</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fs-5" id="pay-now-btn">
                                <i class="fas fa-lock me-2"></i>Pay Now - 50,000 MMK
                            </button>
                            <a href="{{ route('payment.cancel', $application->id) }}" class="btn btn-outline-secondary py-2">
                                <i class="fas fa-times me-2"></i>Cancel Payment
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-method-label {
    border: 2px solid #dee2e6 !important;
    transition: all 0.3s ease;
    height: 100%;
}

.btn-check:checked + .payment-method-label {
    border-color: #0d6efd !important;
    background-color: #e3f2fd !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 2rem 0;
}

.steps::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    border: 3px solid;
}

.step.completed .step-icon {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.step.current .step-icon {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
    animation: pulse 2s infinite;
}

.step-label {
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    color: #6c757d;
}

.step.completed .step-label,
.step.current .step-label {
    color: #495057;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}

#pay-now-btn {
    font-weight: 600;
    transition: all 0.3s ease;
}

#pay-now-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const payBtn = document.getElementById('pay-now-btn');
    
    form.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!selectedMethod) {
            e.preventDefault();
            alert('Please select a payment method.');
            return false;
        }
        
        // Disable button to prevent double submission
        payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    });
    
    // Add animation to payment method selection
    const paymentLabels = document.querySelectorAll('.payment-method-label');
    paymentLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });
        
        label.addEventListener('mouseleave', function() {
            if (!this.previousElementSibling.checked) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
    });
});
</script>
@endpush