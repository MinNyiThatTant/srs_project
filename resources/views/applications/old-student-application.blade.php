<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - WYTU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .container {
            max-width: 1000px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .student-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .required:after {
            content: " *";
            color: #dc3545;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .invalid-feedback {
            display: none;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Continuing Student Application</h4>
                    <a href="/clear-verification" class="btn btn-light btn-sm">
                        <i class="fas fa-sync me-1"></i>Change Student
                    </a>
                </div>
                <p class="mb-0">Academic Year {{ $currentAcademicYear }}</p>
            </div>

            <div class="card-body p-4">
                <!-- Student Info -->
                <div class="student-info p-3 rounded mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0"><i class="fas fa-user-check text-success me-2"></i>Student Verified</h5>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-shield-check me-1"></i> VERIFIED
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <strong>Student ID:</strong><br>
                            <span class="text-primary">{{ $student['student_id'] }}</span>
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Name:</strong><br>
                            {{ $student['name'] }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Department:</strong><br>
                            {{ $student['department'] }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>CGPA:</strong><br>
                            <span class="fw-bold {{ $student['cgpa'] >= 2.0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($student['cgpa'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Application Form -->
                <form method="POST" action="{{ route('old.student.submit') }}" id="applicationForm">
                    @csrf

                    <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-graduation-cap me-2"></i>Academic Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Applying for Year</label>
                            <select name="current_year" class="form-control" required>
                                <option value="">Select Year</option>
                                @php
                                    // Calculate next year
                                    $yearMap = [
                                        'first_year' => 1,
                                        'second_year' => 2,
                                        'third_year' => 3,
                                        'fourth_year' => 4,
                                        'fifth_year' => 5,
                                    ];
                                    $currentYearNum = $yearMap[$student['current_year'] ?? 'first_year'] ?? 1;
                                    $nextYear = $currentYearNum + 1;
                                @endphp
                                <option value="1" {{ $nextYear == 1 ? 'selected' : '' }}>First Year</option>
                                <option value="2" {{ $nextYear == 2 ? 'selected' : '' }}>Second Year</option>
                                <option value="3" {{ $nextYear == 3 ? 'selected' : '' }}>Third Year</option>
                                <option value="4" {{ $nextYear == 4 ? 'selected' : '' }}>Fourth Year</option>
                                <option value="5" {{ $nextYear == 5 ? 'selected' : '' }}>Fifth Year</option>
                            </select>
                            <small class="text-muted">
                                @php
                                    $yearNames = [
                                        1 => 'First Year',
                                        2 => 'Second Year',
                                        3 => 'Third Year',
                                        4 => 'Fourth Year',
                                        5 => 'Fifth Year',
                                    ];
                                    $currentYearName = $yearNames[$currentYearNum] ?? 'Unknown Year';
                                    $nextYearName = $yearNames[$nextYear] ?? 'Unknown Year';
                                @endphp
                                You are currently in {{ $currentYearName }}. You can apply for {{ $nextYearName }}.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Previous Year Status</label>
                            <select name="previous_year_status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="passed">Passed</option>
                                <option value="failed">Failed</option>
                                <option value="retake">Retake</option>
                                <option value="improvement">Improvement</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Current CGPA</label>
                            <input type="number" name="cgpa" class="form-control" step="0.01" min="0"
                                max="4" required value="{{ $student['cgpa'] }}" oninput="validateCGPA(this)">
                            <div class="invalid-feedback">Please enter a valid CGPA (0.00 - 4.00)</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Application Purpose</label>
                            <select name="application_purpose" class="form-control" required>
                                <option value="">Select Purpose</option>
                                @foreach ($applicationPurposes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="fas fa-address-book me-2"></i>Contact Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" pattern="[0-9]{10,11}" required
                                value="{{ $student['phone'] }}">
                            <div class="invalid-feedback">Please enter a valid 10-11 digit phone number</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Current Address</label>
                            <textarea name="address" class="form-control" rows="2" required>{{ $student['address'] }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Reason for Continuing Study</label>
                        <textarea name="reason" class="form-control" rows="3"
                            placeholder="Please explain your academic goals and reasons for continuing..." maxlength="500" required></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span>/500 characters
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Emergency Contact Person</label>
                            <input type="text" name="emergency_contact" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Emergency Contact Phone</label>
                            <input type="tel" name="emergency_phone" class="form-control" pattern="[0-9]{10,11}"
                                required>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="fas fa-file-signature me-2"></i>Declaration
                    </h5>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-money-bill-wave me-2"></i>Application Fee: <span
                                id="feeAmount">30,000</span> MMK</h6>
                        <p class="mb-0">Fee will be calculated based on your selections.</p>
                    </div>

                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="declaration_accuracy"
                                id="declaration_accuracy" required>
                            <label class="form-check-label" for="declaration_accuracy">
                                I declare that all information provided is true and accurate.
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="declaration_fee"
                                id="declaration_fee" required>
                            <label class="form-check-label" for="declaration_fee">
                                I agree to pay the application fee as specified.
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="declaration_rules"
                                id="declaration_rules" required>
                            <label class="form-check-label" for="declaration_rules">
                                I agree to abide by all university rules and regulations.
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label required">Electronic Signature</label>
                        <input type="text" name="signature" class="form-control"
                            placeholder="Type your full name as electronic signature" required>
                        <div class="form-text">By typing your name, you electronically sign this application</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-submit text-white btn-lg px-5 py-3" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded - Form debugging started');

            // Character counter
            const reasonTextarea = document.querySelector('textarea[name="reason"]');
            const charCount = document.getElementById('charCount');

            if (reasonTextarea) {
                reasonTextarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
                // Initialize counter
                charCount.textContent = reasonTextarea.value.length;
            }

            // CGPA validation function
            window.validateCGPA = function(input) {
                const value = parseFloat(input.value);
                if (isNaN(value) || value < 0 || value > 4) {
                    input.classList.add('is-invalid');
                    return false;
                } else {
                    input.classList.remove('is-invalid');
                    return true;
                }
            }

            // Phone validation
            function validatePhone(input) {
                const phonePattern = /^[0-9]{10,11}$/;
                if (!phonePattern.test(input.value)) {
                    input.classList.add('is-invalid');
                    return false;
                } else {
                    input.classList.remove('is-invalid');
                    return true;
                }
            }

            // Add phone validation
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validatePhone(this);
                });
            });

            // Form validation and submission
            const form = document.getElementById('applicationForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form) {
                console.log('Form found:', form);

                // Add form submit event listener
                form.addEventListener('submit', function(e) {
                    console.log('Form submit event triggered');
                    e.preventDefault(); // Prevent default first for debugging

                    // Validate CGPA
                    const cgpaInput = document.querySelector('input[name="cgpa"]');
                    let isValid = true;

                    if (!validateCGPA(cgpaInput)) {
                        isValid = false;
                        alert('Please enter a valid CGPA between 0.00 and 4.00');
                        cgpaInput.focus();
                    }

                    // Validate phones
                    phoneInputs.forEach(input => {
                        if (!validatePhone(input)) {
                            isValid = false;
                            alert('Please enter valid 10-11 digit phone numbers');
                            input.focus();
                        }
                    });

                    // Check checkboxes
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][required]');
                    checkboxes.forEach(checkbox => {
                        if (!checkbox.checked) {
                            isValid = false;
                            alert('Please accept all declarations');
                            checkbox.focus();
                        }
                    });

                    // Check reason length
                    if (reasonTextarea && reasonTextarea.value.length < 20) {
                        isValid = false;
                        alert('Please provide a detailed reason (at least 20 characters)');
                        reasonTextarea.focus();
                    }

                    if (!isValid) {
                        console.log('Form validation failed');
                        return false;
                    }

                    console.log('Form validation passed, submitting...');

                    // Show loading
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

                    // Submit form programmatically
                    const formData = new FormData(form);

                    // Debug: Log form data
                    console.log('Form data:', Object.fromEntries(formData));

                    // Submit via fetch to see response
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);
                            return response.text();
                        })
                        .then(text => {
                            console.log('Response text:', text);

                            // Try to parse as JSON
                            try {
                                const data = JSON.parse(text);
                                console.log('Parsed JSON:', data);

                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else if (data.success) {
                                    alert('Application submitted successfully!');
                                    window.location.href = '/application/success/' + data
                                    .application_id;
                                } else {
                                    alert('Error: ' + (data.message || 'Unknown error'));
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                }
                            } catch (e) {
                                // If not JSON, it's probably an HTML response (redirect)
                                console.log('Response is HTML, likely a redirect');
                                // Allow default form submission
                                form.submit();
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            alert('Network error. Please try again.');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });

                    return false; // Prevent default submission
                });

                // Also log form action
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
            } else {
                console.error('Form not found!');
            }

            // Test if CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                console.log('CSRF token found');
            } else {
                console.error('CSRF token not found!');
            }
        });
    </script>
</body>

</html>
