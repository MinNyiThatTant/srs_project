@extends('layouts.master')

@section('title', 'New Student Application - West Yangon Technological University')

@section('body-class', 'light-blue-bg')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .application-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 2rem auto;
    }
    .form-header {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    .form-header h2 {
        margin: 0;
        font-weight: 700;
    }
    .form-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }
    .form-body {
        padding: 2rem;
    }
    .section-title {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 0.5rem;
        margin: 2rem 0 1rem 0;
        font-weight: 600;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    .required::after {
        content: " *";
        color: #dc3545;
    }
    .btn-submit {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }
    .nav-buttons {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    .duplicate-warning {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 10px;
        margin-top: 5px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="application-container">
                <!-- Header -->
                <div class="form-header">
                    <h2><i class="fas fa-user-graduate me-2"></i>NEW STUDENT APPLICATION</h2>
                    <p>West Yangon Technological University</p>
                    <p class="mb-0">Fill out the form below to apply for admission</p>
                </div>

                <!-- Form Body -->
                <div class="form-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('submit.application') }}" method="POST" id="applicationForm">
                        @csrf
                        <input type="hidden" name="application_type" value="new">

                        <!-- Personal Information Section -->
                        <h4 class="section-title">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label required">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                           value="{{ old('name') }}" placeholder="Enter your full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                           value="{{ old('email') }}" placeholder="Enter your email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                           value="{{ old('phone') }}" placeholder="09XXXXXXXXX" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label required">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth"
                                           value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label required">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nationality" class="form-label required">Nationality</label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality"
                                           value="{{ old('nationality', 'Myanmar') }}" required>
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nrc_number" class="form-label required">NRC Number</label>
                                    <input type="text" class="form-control @error('nrc_number') is-invalid @enderror" id="nrc_number" name="nrc_number"
                                           value="{{ old('nrc_number') }}" placeholder="e.g., 12/ABC(N)123456" required>
                                    <div class="duplicate-warning" id="nrc-warning">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        <span id="nrc-warning-text">An application with this NRC number already exists and is under review.</span>
                                    </div>
                                    @error('nrc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label required">Preferred Department</label>
                                    <select class="form-control @error('department') is-invalid @enderror" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department }}" 
                                                {{ old('department') == $department ? 'selected' : '' }}>
                                                {{ $department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name" class="form-label required">Father's Name</label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name"
                                           value="{{ old('father_name') }}" required>
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mother_name" class="form-label required">Mother's Name</label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name"
                                           value="{{ old('mother_name') }}" required>
                                    @error('mother_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label required">Permanent Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" 
                                      placeholder="Enter your complete address" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Educational Background Section -->
                        <h4 class="section-title">
                            <i class="fas fa-graduation-cap me-2"></i>Educational Background
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="high_school_name" class="form-label required">High School Name</label>
                                    <input type="text" class="form-control @error('high_school_name') is-invalid @enderror" id="high_school_name" name="high_school_name"
                                           value="{{ old('high_school_name') }}" required>
                                    @error('high_school_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="graduation_year" class="form-label required">Graduation Year</label>
                                    <input type="number" class="form-control @error('graduation_year') is-invalid @enderror" id="graduation_year" name="graduation_year"
                                           value="{{ old('graduation_year') }}" min="2000" max="{{ date('Y') + 1 }}" required>
                                    @error('graduation_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="high_school_address" class="form-label required">High School Address</label>
                            <textarea class="form-control @error('high_school_address') is-invalid @enderror" id="high_school_address" name="high_school_address" rows="2" required>{{ old('high_school_address') }}</textarea>
                            @error('high_school_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="matriculation_score" class="form-label required">Matriculation Score</label>
                                    <input type="number" class="form-control @error('matriculation_score') is-invalid @enderror" id="matriculation_score" name="matriculation_score"
                                           value="{{ old('matriculation_score') }}" step="0.01" min="0" max="600" 
                                           placeholder="Total score out of 600" required>
                                    @error('matriculation_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="previous_qualification" class="form-label required">Previous Qualification</label>
                                    <input type="text" class="form-control @error('previous_qualification') is-invalid @enderror" id="previous_qualification" name="previous_qualification"
                                           value="{{ old('previous_qualification') }}" 
                                           placeholder="e.g., High School Diploma" required>
                                    @error('previous_qualification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Section -->
                        <h4 class="section-title">
                            <i class="fas fa-credit-card me-2"></i>Payment Information
                        </h4>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Admission Fee: 50,000 MMK</h6>
                            <p class="mb-2">After submitting your application, you will be redirected to the payment page where you can pay using:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>KPay</li>
                                        <li>WavePay</li>
                                        <li>AYA Pay</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>OK Pay</li>
                                        <li>Credit/Debit Card</li>
                                        <li>Bank Transfer</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> 
                                and understand that the admission fee is non-refundable.
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Section -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-submit btn-lg" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>SUBMIT APPLICATION
                                </button>
                                <a href="{{ route('index') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-times me-2"></i>CANCEL
                                </a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Fields marked with * are required. Your application will be reviewed within 3-5 working days.
                                        <br>
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Duplicate applications with the same NRC number will be rejected.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Admission Process</h6>
                <ul>
                    <li>All applications are subject to approval by the admission committee</li>
                    <li>Submission of false information will result in immediate rejection</li>
                    <li>The admission fee is non-refundable once paid</li>
                </ul>
                
                <h6>Payment Terms</h6>
                <ul>
                    <li>Payment must be completed within 7 days of application submission</li>
                    <li>Digital payments are processed securely through our payment partners</li>
                    <li>Payment confirmation may take up to 24 hours to reflect in the system</li>
                </ul>
                
                <h6>Data Privacy</h6>
                <ul>
                    <li>Your personal information will be kept confidential</li>
                    <li>We comply with data protection regulations</li>
                    <li>Information is used solely for admission purposes</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nrcInput = document.getElementById('nrc_number');
        const nrcWarning = document.getElementById('nrc-warning');
        const submitBtn = document.getElementById('submitBtn');
        const termsCheckbox = document.getElementById('terms');
        let nrcCheckTimeout;

        // Real-time validation
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
            
            // Remove invalid class when user starts typing
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // NRC duplicate check
        nrcInput.addEventListener('input', function() {
            clearTimeout(nrcCheckTimeout);
            const nrcValue = this.value.trim();
            
            if (nrcValue.length < 5) {
                nrcWarning.style.display = 'none';
                submitBtn.disabled = false;
                return;
            }

            nrcCheckTimeout = setTimeout(() => {
                checkNrcDuplicate(nrcValue);
            }, 500);
        });

        function checkNrcDuplicate(nrc) {
            fetch('{{ route("check.nrc") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nrc_number: nrc })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    nrcWarning.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    nrcWarning.style.display = 'none';
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error checking NRC:', error);
            });
        }

        // Form submission validation
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const nrcValue = nrcInput.value.trim();
            
            // Check if terms are accepted
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please accept the Terms and Conditions before submitting.');
                termsCheckbox.focus();
                return;
            }
            
            if (nrcValue && submitBtn.disabled) {
                e.preventDefault();
                alert('Please fix the duplicate NRC number issue before submitting.');
                nrcInput.focus();
            }
        });

        // Age validation for date of birth
        const dobInput = document.getElementById('date_of_birth');
        dobInput.addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            const age = today.getFullYear() - dob.getFullYear();
            
            if (age < 16) {
                this.classList.add('is-invalid');
                alert('You must be at least 16 years old to apply.');
                this.value = '';
            }
        });
    });
</script>
@endsection