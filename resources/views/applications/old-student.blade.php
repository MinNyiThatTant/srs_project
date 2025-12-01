@extends('layouts.master')

@section('title', 'Existing Student Application - West Yangon Technological University')

@section('body-class', 'light-blue-bg')

@section('content')
<style>
    .application-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    .step {
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .step-number {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        color: #6c757d;
    }
    .step.active .step-number {
        background: #0d6efd;
        color: white;
    }
    .step.completed .step-number {
        background: #198754;
        color: white;
    }
    .step-label {
        font-size: 14px;
        color: #6c757d;
    }
    .step.active .step-label {
        color: #0d6efd;
        font-weight: bold;
    }
    .form-section {
        display: none;
    }
    .form-section.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .student-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
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
    .duplicate-warning {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 10px;
        margin-top: 5px;
    }
    .priority-badge {
        font-size: 0.7rem;
        margin-right: 5px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="application-card p-4 p-md-5">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="h2 fw-bold text-primary">
                        <i class="bi bi-person-check me-2"></i>
                        Existing Student Application
                    </h1>
                    <p class="text-muted">Apply for next academic year continuation</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Verify Identity</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Academic Details</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>

                <!-- Step 1: Student Verification -->
                <div class="form-section active" id="section1">
                    <div class="mb-4">
                        <h4 class="mb-3">
                            <i class="bi bi-shield-lock text-primary me-2"></i>
                            Student Verification
                        </h4>
                        <p class="text-muted">Please enter your current student credentials to verify your identity.</p>
                    </div>

                    <form id="verifyStudentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label required">Student ID</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="student_id" 
                                           name="student_id" 
                                           required
                                           placeholder="e.g., WYTU20240001">
                                </div>
                                <div class="form-text">Your current student ID</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label required">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Your student account password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger d-none" id="errorAlert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <span id="errorMessage"></span>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('index') }}'">
                                <i class="bi bi-arrow-left me-1"></i> Back to Home
                            </button>
                            <button type="submit" class="btn btn-primary" id="verifyBtn">
                                <i class="bi bi-check-circle me-1"></i>
                                Verify & Continue
                                <span class="spinner-border spinner-border-sm d-none ms-2" id="verifySpinner"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Academic Details -->
                <div class="form-section" id="section2">
                    <div class="student-info-card">
                        <h5 id="studentName"></h5>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small>Student ID:</small>
                                <div class="fw-bold" id="displayStudentId"></div>
                            </div>
                            <div class="col-6">
                                <small>Current Department:</small>
                                <div class="fw-bold" id="currentDepartment"></div>
                            </div>
                        </div>
                    </div>

                    <form id="academicDetailsForm">
                        @csrf
                        <input type="hidden" id="verifiedStudentId" name="student_id">
                        <input type="hidden" name="application_type" value="existing">
                        
                        <!-- Academic Information Section -->
                        <h4 class="section-title">
                            <i class="fas fa-graduation-cap me-2"></i>Academic Information
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="applied_year" class="form-label required">Year Applying For</label>
                                <select class="form-select" id="applied_year" name="applied_year" required>
                                    <option value="">Select Year</option>
                                    <option value="first_year">First Year</option>
                                    <option value="second_year">Second Year</option>
                                    <option value="third_year">Third Year</option>
                                    <option value="fourth_year">Fourth Year</option>
                                    <option value="fifth_year">Fifth Year</option>
                                    <option value="sixth_year">Sixth Year</option>
                                </select>
                                <div class="form-text">Select the academic year you want to continue to</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="applied_department" class="form-label required">Department to Continue</label>
                                <select class="form-select" id="applied_department" name="applied_department" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}">{{ $department }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cgpa" class="form-label required">Current CGPA</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="cgpa" 
                                           name="cgpa" 
                                           step="0.01" 
                                           min="0" 
                                           max="4" 
                                           required
                                           placeholder="e.g., 3.75">
                                    <span class="input-group-text">/ 4.0</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="academic_standing" class="form-label required">Academic Standing</label>
                                <select class="form-select" id="academic_standing" name="academic_standing" required>
                                    <option value="">Select Standing</option>
                                    <option value="good">Good Standing</option>
                                    <option value="warning">Academic Warning</option>
                                    <option value="probation">Academic Probation</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reason_for_continuation" class="form-label required">
                                Reason for Continuing Studies
                            </label>
                            <textarea class="form-control" 
                                      id="reason_for_continuation" 
                                      name="reason_for_continuation" 
                                      rows="4" 
                                      required
                                      placeholder="Explain why you want to continue your studies and your academic goals..." 
                                      maxlength="1000"></textarea>
                            <div class="form-text">Please provide a brief explanation (max 1000 characters)</div>
                            <div class="text-end">
                                <small><span id="charCount">0</span>/1000 characters</small>
                            </div>
                        </div>

                        <!-- Payment Information Section -->
                        <h4 class="section-title">
                            <i class="fas fa-credit-card me-2"></i>Payment Information
                        </h4>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Continuation Fee: 30,000 MMK</h6>
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
                            <input class="form-check-input" type="checkbox" id="agreement" name="agreement" required>
                            <label class="form-check-label" for="agreement">
                                I certify that all information provided is true and accurate. 
                                I understand that providing false information may result in rejection of my application.
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" onclick="previousStep()">
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-send me-1"></i>
                                Submit Application
                                <span class="spinner-border spinner-border-sm d-none ms-2" id="submitSpinner"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Confirmation -->
                <div class="form-section" id="section3">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">Application Submitted Successfully!</h3>
                        <p class="text-muted mb-4">
                            Your application for continuation has been submitted. 
                            Please proceed to payment to complete the process.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-1"></i> Go to Home
                            </a>
                            <a href="#" id="paymentLink" class="btn btn-success">
                                <i class="bi bi-credit-card me-1"></i> Proceed to Payment
                            </a>
                        </div>
                    </div>
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
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions - Continuation Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Continuation Process</h6>
                <ul>
                    <li>All continuation applications are subject to approval by the academic committee</li>
                    <li>Submission of false academic information will result in immediate rejection</li>
                    <li>Students on academic probation may have restricted application rights</li>
                    <li>The continuation fee is non-refundable once paid</li>
                </ul>

                <h6>Academic Requirements</h6>
                <ul>
                    <li>Minimum CGPA requirement for continuation: 2.0/4.0</li>
                    <li>Students must have no outstanding fees from previous semesters</li>
                    <li>Good academic standing is required for automatic approval</li>
                    <li>Students with warnings may require additional documentation</li>
                </ul>

                <h6>Payment Terms</h6>
                <ul>
                    <li>Payment must be completed within 7 days of application submission</li>
                    <li>Digital payments are processed securely through our payment partners</li>
                    <li>Payment confirmation may take up to 24 hours to reflect in the system</li>
                </ul>

                <h6>Data Privacy</h6>
                <ul>
                    <li>Your academic and personal information will be kept confidential</li>
                    <li>We comply with data protection regulations</li>
                    <li>Information is used solely for continuation assessment purposes</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let studentData = null;

    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Show error message
    function showError(message) {
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        
        errorMessage.textContent = message;
        errorAlert.classList.remove('d-none');
    }

    // Hide error message
    function hideError() {
        document.getElementById('errorAlert').classList.add('d-none');
    }

    // Update step indicator
    function updateStepIndicator() {
        for (let i = 1; i <= 3; i++) {
            const step = document.getElementById(`step${i}`);
            if (i < currentStep) {
                step.className = 'step completed';
            } else if (i === currentStep) {
                step.className = 'step active';
            } else {
                step.className = 'step';
            }
        }
    }

    // Show current section
    function showSection(step) {
        // Hide all sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Show current section
        document.getElementById(`section${step}`).classList.add('active');
    }

    // Next step
    function nextStep() {
        if (currentStep < 3) {
            currentStep++;
            updateStepIndicator();
            showSection(currentStep);
        }
    }

    // Previous step
    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepIndicator();
            showSection(currentStep);
        }
    }

    // Character counter for reason textarea
    const reasonTextarea = document.getElementById('reason_for_continuation');
    const charCount = document.getElementById('charCount');
    
    if (reasonTextarea) {
        reasonTextarea.addEventListener('input', function() {
            const length = this.value.length;
            if (charCount) charCount.textContent = length;
            
            if (length > 1000) {
                this.value = this.value.substring(0, 1000);
                if (charCount) charCount.textContent = 1000;
            }
        });
    }

    // Handle student verification
    document.getElementById('verifyStudentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const verifyBtn = document.getElementById('verifyBtn');
        const verifySpinner = document.getElementById('verifySpinner');
        
        verifyBtn.disabled = true;
        verifySpinner.classList.remove('d-none');
        hideError();

        try {
            // Check if student exists and verify credentials
            const response = await fetch('{{ route("verify.existing.student") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                studentData = data.student;
                
                // Fill student info in next step
                document.getElementById('studentName').textContent = studentData.name;
                document.getElementById('displayStudentId').textContent = studentData.student_id;
                document.getElementById('currentDepartment').textContent = studentData.department;
                document.getElementById('verifiedStudentId').value = studentData.student_id;
                
                // Set CGPA if available
                const cgpaInput = document.getElementById('cgpa');
                if (cgpaInput && studentData.cgpa) {
                    cgpaInput.value = studentData.cgpa;
                }
                
                // Set academic standing if available
                const academicStandingSelect = document.getElementById('academic_standing');
                if (academicStandingSelect && studentData.academic_standing) {
                    academicStandingSelect.value = studentData.academic_standing;
                }
                
                // Set default applied year (next year)
                const nextYearMap = {
                    'first_year': 'second_year',
                    'second_year': 'third_year',
                    'third_year': 'fourth_year',
                    'fourth_year': 'fifth_year',
                    'fifth_year': 'sixth_year'
                };
                
                const appliedYearSelect = document.getElementById('applied_year');
                if (appliedYearSelect) {
                    if (studentData.current_year && nextYearMap[studentData.current_year]) {
                        appliedYearSelect.value = nextYearMap[studentData.current_year];
                    } else {
                        appliedYearSelect.value = 'second_year';
                    }
                }
                
                // Set current department as default
                const appliedDeptSelect = document.getElementById('applied_department');
                if (appliedDeptSelect) {
                    appliedDeptSelect.value = studentData.department;
                }
                
                // Auto-fill reason with template if empty
                if (reasonTextarea && !reasonTextarea.value) {
                    reasonTextarea.value = `I wish to continue my studies in the ${studentData.department} department for the next academic year to further my knowledge and skills in this field.`;
                    if (charCount) charCount.textContent = reasonTextarea.value.length;
                }
                
                nextStep();
            } else {
                showError(data.message || 'Verification failed. Please check your credentials.');
            }
        } catch (error) {
            console.error('Verification error:', error);
            showError('An error occurred while connecting to the server. Please check your internet connection and try again.');
        } finally {
            verifyBtn.disabled = false;
            verifySpinner.classList.add('d-none');
        }
    });

    // Handle academic details submission
    const academicDetailsForm = document.getElementById('academicDetailsForm');
    if (academicDetailsForm) {
        academicDetailsForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate CGPA
            const cgpaInput = document.getElementById('cgpa');
            if (cgpaInput) {
                const cgpa = parseFloat(cgpaInput.value);
                if (cgpa < 0 || cgpa > 4) {
                    alert('CGPA must be between 0 and 4.0');
                    return;
                }
                
                // Validate academic standing for probation students
                const academicStandingSelect = document.getElementById('academic_standing');
                if (academicStandingSelect) {
                    const academicStanding = academicStandingSelect.value;
                    if (academicStanding === 'probation' && cgpa < 1.5) {
                        alert('Students on academic probation with CGPA below 1.5 may not be eligible for continuation.');
                        return;
                    }
                }
            }
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const submitSpinner = document.getElementById('submitSpinner');
            
            submitBtn.disabled = true;
            submitSpinner.classList.remove('d-none');

            try {
                const response = await fetch('{{ route("submit.existing.application") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success) {
                        // Update payment link
                        const paymentLink = document.getElementById('paymentLink');
                        if (paymentLink) {
                            paymentLink.href = data.payment_url || '#';
                        }
                        
                        // Show success step
                        currentStep = 3;
                        updateStepIndicator();
                        showSection(3);
                    } else {
                        alert(data.message || 'Submission failed. Please try again.');
                        submitBtn.disabled = false;
                    }
                } else {
                    const error = await response.json();
                    alert(error.message || 'Submission failed. Please check your information.');
                    submitBtn.disabled = false;
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
                console.error('Submission error:', error);
                submitBtn.disabled = false;
            } finally {
                submitSpinner.classList.add('d-none');
            }
        });
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time validation for required fields
        const inputs = document.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
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
        
        // CGPA validation
        const cgpaInput = document.getElementById('cgpa');
        if (cgpaInput) {
            cgpaInput.addEventListener('blur', function() {
                const value = parseFloat(this.value);
                if (value < 0 || value > 4) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }
        
        // Initialize character counter
        if (charCount && reasonTextarea) {
            charCount.textContent = reasonTextarea.value.length;
        }
    });
</script>
@endsection