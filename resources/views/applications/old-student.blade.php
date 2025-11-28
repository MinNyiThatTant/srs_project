@extends('layouts.master')

@section('title', 'Existing Student Application - West Yangon Technological University')

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
        background: linear-gradient(135deg, #198754, #20c997);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    .form-body {
        padding: 2rem;
    }
    .section-title {
        color: #198754;
        border-bottom: 2px solid #198754;
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
        background: linear-gradient(135deg, #198754, #20c997);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1.1rem;
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
                    <h2><i class="fas fa-user-graduate me-2"></i>EXISTING STUDENT APPLICATION</h2>
                    <p>West Yangon Technological University</p>
                    <p class="mb-0">Application for continuing students</p>
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
                        <input type="hidden" name="application_type" value="old">

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
                                    <label for="student_id" class="form-label required">Student ID</label>
                                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" id="student_id" name="student_id"
                                           value="{{ old('student_id') }}" placeholder="Enter your student ID" required>
                                    <div class="duplicate-warning" id="student-id-warning">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        <span id="student-id-warning-text">You have already submitted an application for this purpose.</span>
                                    </div>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                           value="{{ old('phone') }}" placeholder="Enter your phone number" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_year" class="form-label required">Current Academic Year</label>
                                    <select class="form-control @error('current_year') is-invalid @enderror" id="current_year" name="current_year" required>
                                        <option value="">Select Year</option>
                                        <option value="1" {{ old('current_year') == '1' ? 'selected' : '' }}>First Year</option>
                                        <option value="2" {{ old('current_year') == '2' ? 'selected' : '' }}>Second Year</option>
                                        <option value="3" {{ old('current_year') == '3' ? 'selected' : '' }}>Third Year</option>
                                        <option value="4" {{ old('current_year') == '4' ? 'selected' : '' }}>Fourth Year</option>
                                        <option value="5" {{ old('current_year') == '5' ? 'selected' : '' }}>Fifth Year</option>
                                    </select>
                                    @error('current_year')
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
                                    <label for="department" class="form-label required">Department</label>
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

                        <div class="mb-3">
                            <label for="address" class="form-label required">Current Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" 
                                      placeholder="Enter your current address" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Application Details Section -->
                        <h4 class="section-title">
                            <i class="fas fa-file-alt me-2"></i>Application Details
                        </h4>

                        <div class="mb-3">
                            <label for="application_purpose" class="form-label required">Purpose of Application</label>
                            <select class="form-control @error('application_purpose') is-invalid @enderror" id="application_purpose" name="application_purpose" required>
                                <option value="">Select Purpose</option>
                                <option value="course_registration" {{ old('application_purpose') == 'course_registration' ? 'selected' : '' }}>Course Registration</option>
                                <option value="semester_continuation" {{ old('application_purpose') == 'semester_continuation' ? 'selected' : '' }}>Semester Continuation</option>
                                <option value="transfer_request" {{ old('application_purpose') == 'transfer_request' ? 'selected' : '' }}>Transfer Request</option>
                                <option value="other" {{ old('application_purpose') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('application_purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason_for_application" class="form-label required">Reason for Application</label>
                            <textarea class="form-control @error('reason_for_application') is-invalid @enderror" id="reason_for_application" name="reason_for_application" 
                                      rows="4" placeholder="Please explain the purpose and reason for your application..." required>{{ old('reason_for_application') }}</textarea>
                            @error('reason_for_application')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Section -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-submit btn-lg" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>SUBMIT APPLICATION
                                </button>
                                <a href="{{ route('choose.login') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-times me-2"></i>CANCEL
                                </a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Fields marked with * are required. Your application will be processed within 2-3 working days.
                                        <br>
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        You can only submit one application per semester/purpose.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentIdInput = document.getElementById('student_id');
        const purposeSelect = document.getElementById('application_purpose');
        const studentIdWarning = document.getElementById('student-id-warning');
        const submitBtn = document.getElementById('submitBtn');
        let studentIdCheckTimeout;

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

        // Student ID duplicate check
        function checkStudentIdDuplicate() {
            const studentId = studentIdInput.value.trim();
            const purpose = purposeSelect.value;
            
            if (studentId.length < 3 || !purpose) {
                studentIdWarning.style.display = 'none';
                submitBtn.disabled = false;
                return;
            }

            clearTimeout(studentIdCheckTimeout);
            
            studentIdCheckTimeout = setTimeout(() => {
                fetch('/check-student-id', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        student_id: studentId,
                        application_purpose: purpose 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        studentIdWarning.style.display = 'block';
                        submitBtn.disabled = true;
                    } else {
                        studentIdWarning.style.display = 'none';
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error checking student ID:', error);
                });
            }, 500);
        }

        studentIdInput.addEventListener('input', checkStudentIdDuplicate);
        purposeSelect.addEventListener('change', checkStudentIdDuplicate);

        // Form submission prevention when duplicate exists
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const studentId = studentIdInput.value.trim();
            const purpose = purposeSelect.value;
            
            if ((studentId && purpose) && submitBtn.disabled) {
                e.preventDefault();
                alert('You have already submitted an application for this purpose. Please wait for approval.');
            }
        });
    });
</script>
@endsection