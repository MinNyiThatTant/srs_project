{{-- resources/views/applications/old-student.blade.php --}}
@extends('layouts.master')

@section('title', 'Existing Student Application - West Yangon Technological University')

@section('body-class', 'light-blue-bg')

@section('content')
    <style>
        /* ========== CUSTOM STYLES ========== */
        .application-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px auto;
            max-width: 1200px;
        }

        .form-header {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #00bcd4, #4caf50);
        }

        .form-header h2 {
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .form-header p {
            margin: 5px 0;
            opacity: 0.9;
        }

        .form-body {
            padding: 40px;
        }

        .section-title {
            color: #1a237e;
            border-left: 4px solid #00bcd4;
            padding-left: 15px;
            margin: 40px 0 25px 0;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .required-label:after {
            content: " *";
            color: #dc3545;
        }

        .student-verified-card {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .student-verified-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: #28a745;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .student-info-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .student-info-item small {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .student-info-item div {
            font-weight: 600;
            color: #212529;
            margin-top: 5px;
        }

        .verification-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .verification-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 20px;
        }

        .btn-verify {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-verify:hover {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #3949ab;
            box-shadow: 0 0 0 0.25rem rgba(57, 73, 171, 0.25);
            transform: translateY(-1px);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .fee-breakdown {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 2px solid #2196f3;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }

        .fee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #90caf9;
        }

        .fee-item:last-child {
            border-bottom: none;
            border-top: 2px solid #2196f3;
            margin-top: 10px;
            padding-top: 15px;
        }

        .fee-total {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a237e;
        }

        .declaration-box {
            background: linear-gradient(135deg, #f3e5f5, #e1bee7);
            border: 2px solid #9c27b0;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }

        .declaration-title {
            color: #7b1fa2;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.2em;
            border: 2px solid #757575;
        }

        .form-check-input:checked {
            background-color: #1a237e;
            border-color: #1a237e;
        }

        .form-check-label {
            margin-left: 10px;
            color: #424242;
        }

        .btn-submit {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            padding: 15px 50px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #283593, #3949ab);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(26, 35, 126, 0.3);
        }

        .btn-submit:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #757575;
            border: 2px solid #e0e0e0;
            padding: 15px 35px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
            color: #424242;
        }

        .character-count {
            font-size: 0.9rem;
            color: #757575;
            margin-top: 5px;
        }

        .character-count.warning {
            color: #ff9800;
            font-weight: 600;
        }

        .character-count.error {
            color: #dc3545;
            font-weight: 600;
        }

        .alert-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 10000;
            min-width: 300px;
            max-width: 400px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
        }

        .verification-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            display: none;
            min-width: 500px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50px;
            right: 50px;
            height: 3px;
            background: #e0e0e0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #757575;
            position: relative;
            z-index: 2;
        }

        .step.active {
            border-color: #1a237e;
            background: #1a237e;
            color: white;
        }

        .step.completed {
            border-color: #4caf50;
            background: #4caf50;
            color: white;
        }

        .step-label {
            position: absolute;
            top: 45px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.85rem;
            color: #757575;
        }

        .step.active .step-label {
            color: #1a237e;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
            }

            .form-header {
                padding: 20px;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }

            .student-info-grid {
                grid-template-columns: 1fr;
            }

            .verification-modal {
                min-width: 90%;
                margin: 20px;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>

    <!-- Alert Container -->
    <div class="alert-container" id="alertContainer"></div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Verification Modal -->
    <div class="verification-modal" id="verificationModal">
        <div class="text-center mb-4">
            <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
            <h3>Student Verification</h3>
            <p class="text-muted">Please enter your student credentials to verify</p>
        </div>

        <form id="verificationForm">
            @csrf
            <div class="mb-3">
                <label for="student_id" class="form-label required-label">Student ID</label>
                <input type="text" class="form-control" id="modal_student_id" name="student_id"
                    placeholder="Enter your student ID" required>
                <div class="invalid-feedback" id="studentIdError"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label required-label">Password</label>
                <input type="password" class="form-control" id="modal_password" name="password"
                    placeholder="Enter your password" required>
                <div class="invalid-feedback" id="passwordError"></div>
                <small class="text-muted">
                    <a href="{{ route('student.forgot-password') }}" class="text-decoration-none">
                        Forgot password?
                    </a>
                </small>
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label required-label">Date of Birth</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" class="form-control" id="modal_dob" name="date_of_birth"
                        pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD"
                        title="Please use format: YYYY-MM-DD (e.g., 1999-01-01)" required>
                </div>
                <div class="invalid-feedback" id="dobError"></div>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle"></i> Format must be: <strong>YYYY-MM-DD</strong>
                    (e.g., 1999-01-01 for January 1, 1999)
                </small>
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="fillExampleDate()">
                        <i class="fas fa-magic"></i> Fill Example
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning ms-2" onclick="showCurrentDate()">
                        <i class="fas fa-eye"></i> Show Current Format
                    </button>
                </div>
            </div>

            <div class="modal fade" id="dateFormatHelp" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Date Format Help</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Please enter your date of birth in <strong>YYYY-MM-DD</strong> format:</p>
                            <ul>
                                <li><strong>YYYY</strong>: Year (4 digits, e.g., 2000)</li>
                                <li><strong>MM</strong>: Month (2 digits, e.g., 05 for May)</li>
                                <li><strong>DD</strong>: Day (2 digits, e.g., 15)</li>
                            </ul>
                            <p><strong>Examples:</strong></p>
                            <ul>
                                <li>January 15, 2000 → <code>2000-01-15</code></li>
                                <li>June 5, 1999 → <code>1999-06-05</code></li>
                                <li>December 25, 1998 → <code>1998-12-25</code></li>
                            </ul>
                            <p class="text-muted">If you're unsure of your date of birth in student records, please contact
                                the administration office.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5" id="verifyBtn">
                    <i class="fas fa-check-circle me-2"></i>Verify Student
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg ms-2" id="closeVerificationModal">
                    Cancel
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <div class="spinner-border text-primary d-none" id="verificationSpinner" role="status">
                <span class="visually-hidden">Verifying...</span>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container py-4">
        <div class="application-container">
            <!-- Header -->
            <div class="form-header">
                <h2><i class="fas fa-user-graduate me-2"></i>CONTINUING STUDENT APPLICATION</h2>
                <p class="mb-1">West Yangon Technological University</p>
                <p class="mb-0">Academic Year {{ $currentAcademicYear ?? '2024-2025' }}</p>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps px-4">
                <div class="step @if (isset($student) && $student) completed @else active @endif">
                    <span>1</span>
                    <div class="step-label">Verification</div>
                </div>
                <div class="step @if (isset($student) && $student) active @endif">
                    <span>2</span>
                    <div class="step-label">Application</div>
                </div>
                <div class="step">
                    <span>3</span>
                    <div class="step-label">Payment</div>
                </div>
                <div class="step">
                    <span>4</span>
                    <div class="step-label">Complete</div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="form-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Student Verification Section -->
                @if (isset($student) && $student)
                    @php
                        // Helper functions to use in Blade
                        function getYearNumber($yearName)
                        {
                            $yearMap = [
                                'first_year' => 1,
                                'second_year' => 2,
                                'third_year' => 3,
                                'fourth_year' => 4,
                                'fifth_year' => 5,
                                'sixth_year' => 6,
                            ];
                            return $yearMap[$yearName] ?? 1;
                        }

                        function getYearNameFromNumber($yearNumber)
                        {
                            $yearNames = [
                                1 => 'First Year',
                                2 => 'Second Year',
                                3 => 'Third Year',
                                4 => 'Fourth Year',
                                5 => 'Fifth Year',
                            ];
                            return $yearNames[$yearNumber] ?? 'Unknown Year';
                        }

                        function calculateAcademicStanding($cgpa)
                        {
                            if ($cgpa >= 3.5) {
                                return 'excellent';
                            }
                            if ($cgpa >= 2.5) {
                                return 'good';
                            }
                            if ($cgpa >= 2.0) {
                                return 'warning';
                            }
                            return 'probation';
                        }

                        $currentYearNum = getYearNumber($student->current_year ?? 'first_year');
                        $nextYear = $currentYearNum + 1;
                        $academicStanding = calculateAcademicStanding($student->cgpa ?? 0);
                    @endphp

                    <div class="student-verified-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Student Verified
                                    Successfully</h4>
                                <p class="mb-0 text-muted">Your student credentials have been verified. You can now proceed
                                    with your application.</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success px-3 py-2 fs-6">
                                    <i class="fas fa-shield-check me-1"></i> VERIFIED
                                </span>
                                <a href="{{ route('clear.verification') }}" class="btn btn-outline-warning btn-sm mt-2">
                                    <i class="fas fa-sync me-1"></i>Change Student
                                </a>
                            </div>
                        </div>

                        <div class="student-info-grid">
                            <div class="student-info-item">
                                <small>Student ID</small>
                                <div>{{ $student->student_id ?? 'N/A' }}</div>
                            </div>
                            <div class="student-info-item">
                                <small>Full Name</small>
                                <div>{{ $student->name ?? 'N/A' }}</div>
                            </div>
                            <div class="student-info-item">
                                <small>Department</small>
                                <div>{{ $student->department ?? 'N/A' }}</div>
                            </div>
                            <div class="student-info-item">
                                <small>Current Year</small>
                                <div>{{ getYearNameFromNumber($currentYearNum) }}</div>
                            </div>
                            <div class="student-info-item">
                                <small>CGPA</small>
                                <div class="{{ ($student->cgpa ?? 0) >= 2.0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($student->cgpa ?? 0, 2) }}
                                    @if ($student->cgpa)
                                        <span
                                            class="badge {{ $student->cgpa >= 3.5 ? 'bg-success' : ($student->cgpa >= 2.5 ? 'bg-info' : ($student->cgpa >= 2.0 ? 'bg-warning' : 'bg-danger')) }}">
                                            {{ $academicStanding == 'excellent' ? 'Excellent' : ($academicStanding == 'good' ? 'Good' : ($academicStanding == 'warning' ? 'Warning' : 'Probation')) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="student-info-item">
                                <small>Academic Status</small>
                                <div>
                                    <span class="badge bg-success">Active</span>
                                    <span class="badge bg-info ms-1">{{ ucfirst($academicStanding) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="verification-warning">
                        <div class="verification-icon">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <h4 class="mb-3">Student Verification Required</h4>
                        <p class="mb-4">Please verify your student credentials to access the application form. You need
                            to be an active student to apply for the next academic year.</p>
                        <button type="button" class="btn-verify" id="showVerificationModal">
                            <i class="fas fa-user-check me-2"></i>Verify Student Credentials
                        </button>
                        <p class="mt-3 mb-0 text-muted">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                You need your Student ID and password to verify. Forgot your password?
                                <a href="{{ route('student.forgot-password') }}" class="text-decoration-none">Reset it
                                    here</a>.
                            </small>
                        </p>
                    </div>

                    <!-- Disable form if not verified -->
                    <div id="formOverlay" style="position: relative;">
                        <div style="filter: blur(3px); opacity: 0.5; pointer-events: none;">
                            <div class="text-center py-5">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Please verify your student credentials first</h5>
                                <p class="text-muted">The application form will be enabled after successful verification
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Application Form (only enabled if student is verified) -->
                @if (isset($student) && $student)
                    <form action="{{ route('applications.submit.existing') }}" method="POST" id="oldStudentForm">
                        @csrf
                        <input type="hidden" name="application_type" value="old">

                        <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                        <input type="hidden" name="email" value="{{ $student->email }}">
                        <input type="hidden" name="name" value="{{ $student->name }}">
                        <input type="hidden" name="date_of_birth" value="{{ $student->date_of_birth }}">
                        <input type="hidden" name="gender" value="{{ $student->gender }}">
                        <input type="hidden" name="nrc_number" value="{{ $student->nrc_number }}">
                        <input type="hidden" name="department" value="{{ $student->department }}">
                        <input type="hidden" name="cgpa" id="hiddenCgpa" value="{{ $student->cgpa ?? '' }}">
                        <input type="hidden" name="academic_year" value="{{ $student->academic_year ?? '2024-2025' }}">

                        <!-- Academic Information Section -->
                        <h4 class="section-title">
                            <i class="fas fa-graduation-cap me-2"></i>Academic Information
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="current_year" class="form-label required-label">Applying for Academic
                                        Year</label>
                                    <select class="form-control @error('current_year') is-invalid @enderror"
                                        id="current_year" name="current_year" required>
                                        <option value="">Select Year</option>
                                        @php
                                            $currentYearNum = getYearNumber($student->current_year ?? 'first_year');
                                            $nextYear = $currentYearNum + 1;
                                        @endphp
                                        <option value="1"
                                            {{ old('current_year') == '1' || $nextYear == 1 ? 'selected' : '' }}>First
                                            Year</option>
                                        <option value="2"
                                            {{ old('current_year') == '2' || $nextYear == 2 ? 'selected' : '' }}>Second
                                            Year</option>
                                        <option value="3"
                                            {{ old('current_year') == '3' || $nextYear == 3 ? 'selected' : '' }}>Third
                                            Year</option>
                                        <option value="4"
                                            {{ old('current_year') == '4' || $nextYear == 4 ? 'selected' : '' }}>Fourth
                                            Year</option>
                                        <option value="5"
                                            {{ old('current_year') == '5' || $nextYear == 5 ? 'selected' : '' }}>Fifth
                                            Year</option>
                                    </select>
                                    @error('current_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        You are currently in {{ getYearNameFromNumber($currentYearNum) }}.
                                        You can apply for {{ getYearNameFromNumber($nextYear) }}.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="previous_year_status" class="form-label required-label">Previous Year
                                        Status</label>
                                    <select class="form-control @error('previous_year_status') is-invalid @enderror"
                                        id="previous_year_status" name="previous_year_status" required>
                                        <option value="">Select Status</option>
                                        <option value="passed"
                                            {{ old('previous_year_status') == 'passed' ? 'selected' : '' }}>Passed</option>
                                        <option value="failed"
                                            {{ old('previous_year_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="retake"
                                            {{ old('previous_year_status') == 'retake' ? 'selected' : '' }}>Retake Subjects
                                        </option>
                                        <option value="improvement"
                                            {{ old('previous_year_status') == 'improvement' ? 'selected' : '' }}>
                                            Improvement Exam</option>
                                    </select>
                                    @error('previous_year_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Select your academic status for the previous year</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="cgpa" class="form-label required-label">Current CGPA</label>
                                    <input type="number" class="form-control @error('cgpa') is-invalid @enderror"
                                        id="cgpa" name="cgpa" value="{{ old('cgpa', $student->cgpa ?? '') }}"
                                        step="0.01" min="0" max="4"
                                        placeholder="Enter your current CGPA" required oninput="validateCgpa(this)">
                                    @error('cgpa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Enter your cumulative GPA (0.00 - 4.00)</small>
                                    <div class="mt-2">
                                        @if ($student->cgpa)
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar 
                                            @if ($student->cgpa >= 3.5) bg-success
                                            @elseif($student->cgpa >= 2.5) bg-info
                                            @elseif($student->cgpa >= 2.0) bg-warning
                                            @else bg-danger @endif"
                                                    role="progressbar" style="width: {{ ($student->cgpa / 4) * 100 }}%">
                                                </div>
                                            </div>
                                            <small class="d-block mt-1">
                                                Current CGPA: {{ number_format($student->cgpa, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="application_purpose" class="form-label required-label">Purpose of
                                        Application</label>
                                    <select class="form-control @error('application_purpose') is-invalid @enderror"
                                        id="application_purpose" name="application_purpose" required>
                                        <option value="">Select Purpose</option>
                                        <option value="course_registration"
                                            {{ old('application_purpose') == 'course_registration' ? 'selected' : '' }}>
                                            Course Registration</option>
                                        <option value="re_examination"
                                            {{ old('application_purpose') == 're_examination' ? 'selected' : '' }}>
                                            Re-examination</option>
                                        <option value="transfer"
                                            {{ old('application_purpose') == 'transfer' ? 'selected' : '' }}>Transfer
                                            Request</option>
                                        <option value="other"
                                            {{ old('application_purpose') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('application_purpose')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Select the main purpose of your application</small>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Information -->
                        <div class="fee-breakdown" id="feeDisplay">
                            <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Application Fee Breakdown</h5>
                            <div class="fee-item">
                                <span>Base Application Fee:</span>
                                <span class="fw-bold">30,000 MMK</span>
                            </div>
                            <div class="fee-item">
                                <span id="additionalFeeLabel">Additional Fees:</span>
                                <span id="additionalFee" class="fw-bold">0 MMK</span>
                            </div>
                            <div class="fee-item">
                                <span>Year Multiplier (<span id="yearMultiplierLabel">1.0x</span>):</span>
                                <span id="yearMultiplier" class="fw-bold">0 MMK</span>
                            </div>
                            <div class="fee-item">
                                <span class="fee-total">Total Fee:</span>
                                <span class="fee-total" id="totalFee">30,000 MMK</span>
                            </div>
                            <div class="mt-3 text-muted">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Payment must be completed within 3 days of application submission.
                                </small>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h4 class="section-title">
                            <i class="fas fa-address-book me-2"></i>Contact Information
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="form-label required-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}"
                                        pattern="[0-9]{10,11}" placeholder="09XXXXXXXXX" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Enter your 10 or 11 digit Myanmar phone number</small>
                                    <div class="mt-2" id="phoneConfirmation" style="display: none;">
                                        <label for="phone_confirmation" class="form-label">Confirm Phone Number</label>
                                        <input type="tel" class="form-control" id="phone_confirmation"
                                            name="phone_confirmation" pattern="[0-9]{10,11}"
                                            placeholder="Re-enter phone number">
                                        <div class="invalid-feedback" id="phoneConfirmationError">Phone numbers do not
                                            match</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="address" class="form-label required-label">Current Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                        placeholder="Enter your current residential address" required>{{ old('address', $student->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Please provide your complete current address</small>
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <h4 class="section-title">
                            <i class="fas fa-file-alt me-2"></i>Application Details
                        </h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="reason" class="form-label required-label">Reason for Continuing
                                        Study</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4"
                                        placeholder="Please explain why you wish to continue your studies at WYTU..." maxlength="500" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="character-count mt-1" id="reasonCount">0/500 characters</div>
                                    <small class="text-muted">Describe your academic goals and reasons for
                                        continuing</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="emergency_contact" class="form-label required-label">Emergency Contact
                                        Person</label>
                                    <input type="text"
                                        class="form-control @error('emergency_contact') is-invalid @enderror"
                                        id="emergency_contact" name="emergency_contact"
                                        value="{{ old('emergency_contact') }}"
                                        placeholder="Enter full name of emergency contact" required>
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="emergency_phone" class="form-label required-label">Emergency Contact
                                        Phone</label>
                                    <input type="tel"
                                        class="form-control @error('emergency_phone') is-invalid @enderror"
                                        id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone') }}"
                                        pattern="[0-9]{10,11}" placeholder="09XXXXXXXXX" required>
                                    @error('emergency_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Declaration Section -->
                        <div class="declaration-box">
                            <div class="declaration-title">
                                <i class="fas fa-file-signature me-2"></i>Declaration
                            </div>

                            <div class="mb-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input @error('declaration_accuracy') is-invalid @enderror"
                                        type="checkbox" id="declaration_accuracy" name="declaration_accuracy"
                                        value="1" required>
                                    <label class="form-check-label" for="declaration_accuracy">
                                        I hereby declare that all information provided in this application is true,
                                        complete, and accurate to the best of my knowledge.
                                        I understand that any false statement or omission may lead to rejection of my
                                        application or subsequent dismissal from the university.
                                    </label>
                                    @error('declaration_accuracy')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input @error('declaration_fee') is-invalid @enderror"
                                        type="checkbox" id="declaration_fee" name="declaration_fee" value="1"
                                        required>
                                    <label class="form-check-label" for="declaration_fee">
                                        I understand and agree to pay the application fee as specified. I acknowledge that
                                        the fee is non-refundable once the application is processed.
                                    </label>
                                    @error('declaration_fee')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input @error('declaration_rules') is-invalid @enderror"
                                        type="checkbox" id="declaration_rules" name="declaration_rules" value="1"
                                        required>
                                    <label class="form-check-label" for="declaration_rules">
                                        I agree to abide by all rules and regulations of West Yangon Technological
                                        University.
                                        I understand that my continuation is subject to academic performance and adherence
                                        to university policies.
                                    </label>
                                    @error('declaration_rules')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-white rounded">
                                <label for="signature" class="form-label required-label">Electronic Signature</label>
                                <input type="text" class="form-control @error('signature') is-invalid @enderror"
                                    id="signature" name="signature" value="{{ old('signature') }}"
                                    placeholder="Type your full name as electronic signature" required>
                                @error('signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">By typing your name, you electronically sign this
                                    application</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-cancel w-100 py-3" id="cancelBtn">
                                    <i class="fas fa-times me-2"></i>Cancel Application
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-submit w-100 py-3" id="submitBtn"
                                    @if (!isset($student) || !$student) disabled @endif>
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const verificationModal = document.getElementById('verificationModal');
            const overlay = document.getElementById('overlay');
            const showVerificationModalBtn = document.getElementById('showVerificationModal');
            const closeVerificationModalBtn = document.getElementById('closeVerificationModal');
            const verificationForm = document.getElementById('verificationForm');

            // Show verification modal
            if (showVerificationModalBtn) {
                showVerificationModalBtn.addEventListener('click', function() {
                    verificationModal.style.display = 'block';
                    overlay.style.display = 'block';
                });
            }

            // Close verification modal
            if (closeVerificationModalBtn) {
                closeVerificationModalBtn.addEventListener('click', function() {
                    verificationModal.style.display = 'none';
                    overlay.style.display = 'none';
                });
            }

            // Overlay click to close
            overlay.addEventListener('click', function() {
                verificationModal.style.display = 'none';
                overlay.style.display = 'none';
            });

            // Form submission
            if (verificationForm) {
                verificationForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Get form data
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);

                    // Show loading
                    const verifyBtn = document.getElementById('verifyBtn');
                    const originalText = verifyBtn.innerHTML;
                    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
                    verifyBtn.disabled = true;

                    try {
                        // Send request
                        const response = await fetch('{{ route('student.verify') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Show success message
                            alert('Student verified successfully! The page will reload.');

                            // Close modal
                            verificationModal.style.display = 'none';
                            overlay.style.display = 'none';

                            // Reload page
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Show error
                            alert('Verification failed: ' + result.message);
                            verifyBtn.innerHTML = originalText;
                            verifyBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Network error. Please check your connection.');
                        verifyBtn.innerHTML = originalText;
                        verifyBtn.disabled = false;
                    }
                });
            }

            // Global functions
            window.fillExampleDate = function() {
                document.getElementById('modal_dob').value = '2000-01-15';
            };

            window.showCurrentDate = function() {
                const now = new Date();
                const formatted = now.toISOString().split('T')[0];
                alert(`Current date format: ${formatted}`);
            };
        });

        // Simple form validation
        function validateCgpa(input) {
            const value = parseFloat(input.value);
            if (isNaN(value) || value < 0 || value > 4) {
                input.classList.add('is-invalid');
                return false;
            }
            input.classList.remove('is-invalid');
            return true;
        }
    </script>
@endsection
