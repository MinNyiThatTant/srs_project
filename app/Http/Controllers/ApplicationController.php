<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Student;
use App\Models\Payment;
use App\Models\StudentAcademicHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Show new student application form
     */
    public function newStudentForm()
    {
        try {
            $departments = [
                'Civil Engineering',
                'Computer Engineering and Information Technology',
                'Electronic Engineering',
                'Electrical Power Engineering',
                'Architecture',
                'Biotechnology',
                'Textile Engineering',
                'Mechanical Engineering',
                'Chemical Engineering',
                'Automobile Engineering',
                'Mechatronic Engineering',
                'Metallurgy Engineering'
            ];

            return view('applications.new-student', compact('departments'));
        } catch (\Exception $e) {
            Log::error('Error loading new student form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load application form. Please try again.');
        }
    }

    /**
     * Show old student application form
     */
    public function oldStudentForm()
    {
        try {
            $departments = [
                'Civil Engineering',
                'Computer Engineering and Information Technology',
                'Electronic Engineering',
                'Electrical Power Engineering',
                'Architecture',
                'Biotechnology',
                'Textile Engineering',
                'Mechanical Engineering',
                'Chemical Engineering',
                'Automobile Engineering',
                'Mechatronic Engineering',
                'Metallurgy Engineering'
            ];

            $applicationPurposes = [
                'course_registration' => 'Course Registration',
                're_examination' => 'Re-examination',
                'transfer' => 'Transfer',
                'other' => 'Other'
            ];

            // Check if student is verified from session
            $student = session('verified_student');

            // Clear session after use
            session()->forget('verified_student');

            return view('applications.old-student', compact('departments', 'applicationPurposes', 'student'));
        } catch (\Exception $e) {
            Log::error('Error loading old student form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load application form. Please try again.');
        }
    }

    /**
     * Check if NRC already exists (AJAX)
     */
    public function checkNrc(Request $request)
    {
        try {
            $request->validate([
                'nrc_number' => 'required|string'
            ]);

            $nrc = $request->nrc_number;

            // Check in applications table
            $applicationExists = Application::where('nrc_number', $nrc)
                ->whereIn('status', [
                    'pending',
                    'payment_pending',
                    'payment_verified',
                    'academic_approved',
                    'approved'
                ])
                ->exists();

            // Check in students table (for approved applications)
            $studentExists = Student::where('nrc_number', $nrc)
                ->where('status', 'active')
                ->exists();

            $exists = $applicationExists || $studentExists;

            Log::info('NRC check result', [
                'nrc' => $nrc,
                'application_exists' => $applicationExists,
                'student_exists' => $studentExists,
                'total_exists' => $exists
            ]);

            return response()->json([
                'exists' => $exists,
                'message' => $exists ?
                    'An application or student with this NRC number already exists.' :
                    'NRC number is available.'
            ]);
        } catch (\Exception $e) {
            Log::error('NRC check error: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'message' => 'Error checking NRC number.'
            ], 500);
        }
    }

    /**
     * Check if email already exists (AJAX)
     */
    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;

            // Check in applications table
            $applicationExists = Application::where('email', $email)
                ->whereIn('status', [
                    'pending',
                    'payment_pending',
                    'payment_verified',
                    'academic_approved',
                    'approved'
                ])
                ->exists();

            // Check in students table (for approved applications)
            $studentExists = Student::where('email', $email)
                ->where('status', 'active')
                ->exists();

            $exists = $applicationExists || $studentExists;

            Log::info('Email check result', [
                'email' => $email,
                'application_exists' => $applicationExists,
                'student_exists' => $studentExists,
                'total_exists' => $exists
            ]);

            return response()->json([
                'exists' => $exists,
                'message' => $exists ?
                    'An application or student with this email already exists.' :
                    'Email is available.'
            ]);
        } catch (\Exception $e) {
            Log::error('Email check error: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'message' => 'Error checking email.'
            ], 500);
        }
    }

    /**
     * Submit application (for both new and old students)
     */
    public function submitApplication(Request $request)
    {
        Log::info('=== SUBMIT APPLICATION START ===', $request->all());

        try {
            // Enhanced validation with custom duplicate checking
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        // Check if email exists in applications
                        $appExists = Application::where('email', $value)
                            ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved'])
                            ->exists();

                        // Check if email exists in students
                        $studentExists = Student::where('email', $value)
                            ->where('status', 'active')
                            ->exists();

                        if ($appExists || $studentExists) {
                            $fail('This email address is already associated with an existing application or student.');
                        }
                    }
                ],
                'phone' => 'required|string|max:20',
                'nrc_number' => [
                    'required',
                    'string',
                    'max:20',
                    function ($attribute, $value, $fail) {
                        // Check if NRC exists in applications
                        $appExists = Application::where('nrc_number', $value)
                            ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved'])
                            ->exists();

                        // Check if NRC exists in students
                        $studentExists = Student::where('nrc_number', $value)
                            ->where('status', 'active')
                            ->exists();

                        if ($appExists || $studentExists) {
                            $fail('This NRC number is already associated with an existing application or student.');
                        }
                    }
                ],
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'nationality' => 'required|string|max:100',
                'address' => 'required|string',
                'application_type' => 'required|in:new,old',
                // Department preferences validation
                'first_priority_department' => 'required|string|max:255',
                'second_priority_department' => 'required|string|max:255',
                'third_priority_department' => 'required|string|max:255',
                'fourth_priority_department' => 'nullable|string|max:255',
                'fifth_priority_department' => 'nullable|string|max:255',
                // Educational background
                'high_school_name' => 'required_if:application_type,new|string|max:255',
                'high_school_address' => 'required_if:application_type,new|string',
                'graduation_year' => 'required_if:application_type,new|integer|min:1900|max:' . date('Y'),
                'matriculation_score' => 'required_if:application_type,new|numeric|min:0|max:600',
                'previous_qualification' => 'required_if:application_type,new|string|max:255',
                'student_id' => 'required_if:application_type,old|string|max:50',
                'terms' => 'required|accepted'
            ], [
                'first_priority_department.required' => 'Please select your first priority department.',
                'second_priority_department.required' => 'Please select your second priority department.',
                'third_priority_department.required' => 'Please select your third priority department.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed: ', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            // Generate application ID
            $applicationId = 'APP' . strtoupper(Str::random(8)) . date('Ymd');
            Log::info('Generated application ID: ' . $applicationId);

            // Determine student type
            $studentType = ($request->application_type === 'new') ? 'freshman' : 'continuing';

            // Create application
            $application = Application::create([
                'application_id' => $applicationId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'nrc_number' => $request->nrc_number,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'application_type' => $request->application_type,
                'student_type' => $studentType,
                // Department preferences
                'first_priority_department' => $request->first_priority_department,
                'second_priority_department' => $request->second_priority_department,
                'third_priority_department' => $request->third_priority_department,
                'fourth_priority_department' => $request->fourth_priority_department,
                'fifth_priority_department' => $request->fifth_priority_department,
                // Set initial department (will be updated during approval)
                'department' => $request->first_priority_department,
                // Educational background
                'high_school_name' => $request->high_school_name,
                'high_school_address' => $request->high_school_address,
                'graduation_year' => $request->graduation_year,
                'matriculation_score' => $request->matriculation_score,
                'previous_qualification' => $request->previous_qualification,
                'existing_student_id' => ($request->application_type === 'old') ? $request->student_id : null,
                'status' => 'payment_pending',
                'payment_status' => 'pending',
            ]);

            DB::commit();

            Log::info('Application created successfully', [
                'application_db_id' => $application->id,
                'application_display_id' => $application->application_id,
                'status' => $application->status,
                'payment_status' => $application->payment_status
            ]);

            // Send confirmation email
            $this->sendApplicationConfirmation($application);

            // Redirect to success page
            return redirect()->route('application.success', $application->id)
                ->with('success', 'Application submitted successfully! Please complete your payment.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Application submission failed: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Application submission failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Submit application for NEW students (legacy method for backward compatibility)
     */
    public function submitNewApplication(Request $request)
    {
        return $this->submitApplication($request);
    }

    /**
     * Submit application for EXISTING students (legacy method for backward compatibility)
     */
    public function submitExistingApplication(Request $request)
    {
        Log::info('=== EXISTING STUDENT APPLICATION START ===', $request->all());

        try {
            // Step 1: Initial student verification
            $student = Student::where('student_id', $request->student_id)
                ->where('email', $request->email)
                ->first();

            if (!$student) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Student verification failed. Please check your student ID and email.');
            }

            // Enhanced validation for existing students with student context
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->name !== $value) {
                            $fail('Name does not match student records.');
                        }
                    }
                ],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->email !== $value) {
                            $fail('Email does not match student records.');
                        }
                    }
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:20',
                    function ($attribute, $value, $fail) use ($student, $request) {
                        // Allow phone number update if different, but require confirmation
                        if ($student->phone !== $value && empty($request->phone_confirmation)) {
                            $fail('Phone number changed. Please confirm your new phone number.');
                        }
                    }
                ],
                'student_id' => [
                    'required',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->student_id !== $value) {
                            $fail('Invalid student ID.');
                        } elseif ($student->status !== 'active') {
                            $fail('Your student account is not active. Please contact administration.');
                        }
                    }
                ],
                'date_of_birth' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->date_of_birth->format('Y-m-d') !== $value) {
                            $fail('Date of birth does not match student records.');
                        }
                    }
                ],
                'gender' => [
                    'required',
                    'in:male,female,other',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->gender !== $value) {
                            $fail('Gender does not match student records.');
                        }
                    }
                ],
                'address' => 'required|string|max:500',
                'current_year' => [
                    'required',
                    'in:1,2,3,4,5',
                    function ($attribute, $value, $fail) use ($student) {
                        $currentYearNumber = $this->getYearNumber($student->current_year);
                        $applyingYear = intval($value);

                        if ($applyingYear <= $currentYearNumber) {
                            $fail('You can only apply for the next academic year.');
                        }

                        if ($applyingYear > ($currentYearNumber + 1)) {
                            $fail('You cannot skip academic years. Please apply for year ' . ($currentYearNumber + 1));
                        }
                    }
                ],
                'department' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($student) {
                        if ($student->department !== $value) {
                            $fail('Department does not match student records.');
                        }
                    }
                ],
                'application_purpose' => 'required|in:course_registration,re_examination,transfer,other',
                'reason_for_application' => 'required|string|min:50|max:1000',
                'cgpa' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:4',
                    function ($attribute, $value, $fail) use ($student) {
                        $studentCgpa = floatval($student->cgpa);
                        $inputCgpa = floatval($value);

                        if (abs($studentCgpa - $inputCgpa) > 0.2) {
                            $fail('CGPA significantly different from student records. Please verify your CGPA.');
                        }
                    }
                ],
                'previous_year_status' => 'required|in:passed,failed,retake',
                'phone_confirmation' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->phone !== $value) {
                            $fail('Phone numbers do not match.');
                        }
                    }
                ],
                'terms' => 'required|accepted',
                'declaration' => 'required|accepted',
            ], [
                'reason_for_application.min' => 'Please provide a detailed reason (at least 50 characters).',
                'reason_for_application.max' => 'Reason should not exceed 1000 characters.',
                'cgpa.required' => 'Please enter your current CGPA.',
                'cgpa.numeric' => 'CGPA must be a valid number.',
                'cgpa.min' => 'CGPA cannot be less than 0.',
                'cgpa.max' => 'CGPA cannot exceed 4.0.',
                'previous_year_status.required' => 'Please select your previous year academic status.',
                'declaration.accepted' => 'You must agree to the declaration.',
                'phone_confirmation.required_if' => 'Please confirm your new phone number.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed: ', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Additional student status verification
            if ($student->status !== 'active') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Your student account is not active. Status: ' . $student->status);
            }

            // Check academic eligibility
            $currentYearNumber = $this->getYearNumber($student->current_year);
            $applyingYear = intval($request->current_year);

            if ($applyingYear !== ($currentYearNumber + 1)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid application year. You are currently in ' .
                        $this->getYearNameFromNumber($currentYearNumber) .
                        '. Please apply for ' . $this->getYearNameFromNumber($currentYearNumber + 1) . '.');
            }

            // Check if student has already applied for this academic year
            $academicYear = $this->getCurrentAcademicYear();

            $existingApplication = Application::where('existing_student_id', $student->student_id)
                ->where('academic_year', $academicYear)
                ->where('current_year', $applyingYear)
                ->whereIn('status', ['payment_pending', 'payment_verified', 'academic_approved', 'approved', 'pending'])
                ->first();

            if ($existingApplication) {
                $statusText = $this->getApplicationStatusText($existingApplication->status);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'You already have a ' . $statusText . ' application for ' .
                        $this->getYearNameFromNumber($applyingYear) . ' in academic year ' . $academicYear . '.');
            }

            // Verify academic eligibility for progression
            if (!$this->checkAcademicEligibility($student, $applyingYear)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Academic eligibility check failed. You have not met the requirements for year progression.');
            }

            DB::beginTransaction();

            try {
                // Generate application ID
                $applicationId = 'APP-OLD-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

                Log::info('Generated old student application ID: ' . $applicationId, [
                    'student_id' => $student->student_id,
                    'applying_year' => $applyingYear,
                    'current_year' => $student->current_year
                ]);

                // Get academic history
                $academicHistory = $this->getStudentAcademicHistory($student->id);

                // Calculate fees
                $feeAmount = $this->calculateApplicationFee($request->application_purpose, $applyingYear);

                // Get next academic year
                $nextAcademicYear = $this->getNextAcademicYear($student->academic_year);

                // Update student's phone if changed and confirmed
                $phoneToSave = $request->phone;
                if ($student->phone !== $phoneToSave && $request->phone_confirmation === $phoneToSave) {
                    $student->update(['phone' => $phoneToSave]);
                }

                // Create application
                $applicationData = [
                    'application_id' => $applicationId,
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $phoneToSave,
                    'nrc_number' => $student->nrc_number,
                    'date_of_birth' => $student->date_of_birth,
                    'gender' => $student->gender,
                    'address' => $request->address,
                    'application_type' => 'old',
                    'student_type' => 'continuing',
                    'department' => $student->department,
                    'academic_year' => $academicYear,
                    'current_year' => $applyingYear,
                    'next_academic_year' => $nextAcademicYear,
                    'existing_student_id' => $student->student_id,
                    'student_original_id' => $student->id,
                    'application_purpose' => $request->application_purpose,
                    'reason_for_application' => $request->reason_for_application,
                    'cgpa' => $request->cgpa,
                    'previous_year_status' => $request->previous_year_status,
                    'academic_history' => json_encode($academicHistory),
                    'status' => 'payment_pending',
                    'payment_status' => 'pending',
                    'payment_amount' => $feeAmount,
                    'needs_academic_approval' => true,
                    'academic_approval_status' => 'pending',
                    'submitted_at' => now(),
                    'terms_accepted' => $request->has('terms'),
                    'declaration_accepted' => $request->has('declaration'),
                ];

                // Add optional fields if they exist
                if ($request->has('phone_confirmation')) {
                    $applicationData['phone_confirmed'] = true;
                }

                $application = Application::create($applicationData);

                DB::commit();

                Log::info('Old student application created successfully', [
                    'application_id' => $application->id,
                    'display_id' => $application->application_id,
                    'student_id' => $student->student_id,
                    'applying_year' => $applyingYear,
                    'status' => $application->status,
                    'payment_amount' => $application->payment_amount,
                    'fee_breakdown' => [
                        'base_fee' => 30000,
                        'purpose_additional' => $this->getPurposeAdditionalFee($request->application_purpose),
                        'year_multiplier' => $this->getYearMultiplier($applyingYear),
                        'total' => $feeAmount
                    ]
                ]);

                // Send confirmation email
                $emailSent = $this->sendOldStudentApplicationConfirmation($application, $student);

                // Redirect to success page with payment option
                return redirect()->route('application.success', $application->id)
                    ->with([
                        'success' => 'Application submitted successfully! Please complete your payment to proceed.',
                        'application_id' => $application->application_id,
                        'email_sent' => $emailSent ? 'Confirmation email sent to ' . $student->email : 'Email notification failed'
                    ]);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Old student application creation failed: ' . $e->getMessage(), [
                    'student_id' => $student->student_id ?? 'unknown',
                    'request_data' => $request->except(['_token', 'password', 'terms', 'declaration']),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Application submission failed. Please try again. Error: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Old student application process error: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token', 'password']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Get year name from year number
     */
    private function getYearNameFromNumber($yearNumber)
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

    /**
     * Get application status text
     */
    private function getApplicationStatusText($status)
    {
        $statusMap = [
            'pending' => 'pending',
            'payment_pending' => 'pending payment',
            'payment_verified' => 'payment verified',
            'academic_approved' => 'academically approved',
            'approved' => 'approved',
            'rejected' => 'rejected',
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * Get current academic year
     */
    private function getCurrentAcademicYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    /**
     * Get next academic year
     */
    private function getNextAcademicYear($currentAcademicYear = null)
    {
        if (!$currentAcademicYear) {
            return $this->getCurrentAcademicYear();
        }

        $parts = explode('-', $currentAcademicYear);
        if (count($parts) === 2) {
            $nextStart = intval($parts[1]);
            $nextEnd = $nextStart + 1;
            return $nextStart . '-' . $nextEnd;
        }

        return $this->getCurrentAcademicYear();
    }

    /**
     * Get student academic history
     */
    private function getStudentAcademicHistory($studentId)
    {
        try {
            // First check StudentAcademicHistory model if it exists
            if (class_exists('App\Models\StudentAcademicHistory')) {
                $history = \App\Models\StudentAcademicHistory::where('student_id', $studentId)
                    ->orderBy('academic_year', 'desc')
                    ->orderBy('year', 'desc')
                    ->get()
                    ->map(function ($record) {
                        return [
                            'academic_year' => $record->academic_year,
                            'year' => $record->year,
                            'status' => $record->status,
                            'cgpa' => $record->cgpa,
                            'remarks' => $record->remarks,
                            'approved_at' => $record->approved_at?->format('Y-m-d'),
                        ];
                    })
                    ->toArray();

                if (!empty($history)) {
                    return $history;
                }
            }

            // Fallback: Check applications for previous approvals
            $applications = Application::where('student_original_id', $studentId)
                ->where('application_type', 'old')
                ->where('academic_approval_status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($app) {
                    return [
                        'academic_year' => $app->academic_year,
                        'year' => $app->current_year - 1, // Previous year
                        'status' => 'passed',
                        'cgpa' => $app->cgpa,
                        'remarks' => 'Approved via application',
                        'approved_at' => $app->academic_verified_at?->format('Y-m-d'),
                    ];
                })
                ->toArray();

            return $applications;
        } catch (\Exception $e) {
            Log::error('Error fetching academic history: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate application fee
     */
    private function calculateApplicationFee($purpose, $year)
    {
        $baseFee = 30000; // Base fee for existing students

        $additionalFees = [
            'course_registration' => 0,
            're_examination' => 10000,
            'transfer' => 20000,
            'other' => 5000,
        ];

        $yearMultipliers = [
            1 => 1.0,
            2 => 1.0,
            3 => 1.1,
            4 => 1.2,
            5 => 1.3,
        ];

        $additionalFee = $additionalFees[$purpose] ?? 0;
        $multiplier = $yearMultipliers[$year] ?? 1.0;

        return intval(($baseFee + $additionalFee) * $multiplier);
    }

    /**
     * Get purpose additional fee
     */
    private function getPurposeAdditionalFee($purpose)
    {
        $fees = [
            'course_registration' => 0,
            're_examination' => 10000,
            'transfer' => 20000,
            'other' => 5000,
        ];

        return $fees[$purpose] ?? 0;
    }

    /**
     * Get year multiplier
     */
    private function getYearMultiplier($year)
    {
        $multipliers = [
            1 => 1.0,
            2 => 1.0,
            3 => 1.1,
            4 => 1.2,
            5 => 1.3,
        ];

        return $multipliers[$year] ?? 1.0;
    }

    /**
     * Send old student application confirmation email
     */
    private function sendOldStudentApplicationConfirmation($application, $student)
    {
        try {
            Mail::send('emails.old-student-application-confirmation', [
                'application' => $application,
                'student' => $student,
                'payment_url' => route('payment.show', $application->id),
                'fee' => number_format($application->payment_amount) . ' MMK',
                'year_progression' => 'Year ' . ($application->current_year - 1) . ' to Year ' . $application->current_year,
                'academic_year' => $application->academic_year,
            ], function ($message) use ($application, $student) {
                $message->to($application->email)
                    ->cc(config('mail.admin_email', 'admin@wytu.edu.mm'))
                    ->subject('Existing Student Application Submitted - ' . $student->department . ' - WYTU University');
            });

            Log::info('Old student application confirmation email sent', [
                'application_id' => $application->id,
                'email' => $application->email
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send old student application confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check academic eligibility for next year
     */
    private function checkAcademicEligibility($student, $nextYear)
    {
        try {
            $currentYear = $nextYear - 1;

            if ($currentYear < 1) {
                return true; // First year students (no previous year to check)
            }

            // Check academic history for the previous year
            $academicHistory = $this->getStudentAcademicHistory($student->id);

            foreach ($academicHistory as $record) {
                if ($record['year'] == $currentYear && $record['status'] === 'passed') {
                    return true;
                }
            }

            // If no academic history found, check if student is in good standing
            if ($student->cgpa >= 2.0 && $student->status === 'active') {
                // For first time progression (first to second year)
                if ($currentYear == 1) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Academic eligibility check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get previous academic year
     */
    private function getPreviousAcademicYear()
    {
        $currentYear = date('Y') - 1;
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    /**
     * Get year name
     */
    private function getYearName($yearNumber)
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

    /**
     * Verify existing student before application
     */
    public function verifyExistingStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check student credentials
        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid student ID or password'
            ], 401);
        }

        // Check if student is active
        if ($student->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact administration.'
            ], 403);
        }

        // Check if student already has a pending application
        $existingApplication = Application::where('existing_student_id', $student->student_id)
            ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved'])
            ->first();

        if ($existingApplication) {
            $statusMap = [
                'pending' => 'pending review',
                'payment_pending' => 'waiting for payment',
                'payment_verified' => 'payment verified',
                'academic_approved' => 'academically approved',
                'approved' => 'approved'
            ];

            return response()->json([
                'success' => false,
                'message' => 'You already have an application that is ' .
                    ($statusMap[$existingApplication->status] ?? $existingApplication->status)
            ], 409);
        }

        // Get current year from academic year
        $currentYear = $this->getCurrentYearFromAcademicYear($student->academic_year);

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'department' => $student->department,
                'academic_year' => $student->academic_year,
                'current_year' => $currentYear,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'nrc_number' => $student->nrc_number,
                'address' => $student->address,
                'cgpa' => $student->cgpa ?? 3.5,
                'academic_standing' => $this->calculateAcademicStanding($student->cgpa ?? 3.5)
            ]
        ]);
    }

    /**
     * Show application success page
     */
    public function applicationSuccess($id)
    {
        try {
            Log::info('Application success method called', ['id' => $id]);

            // Find application by primary key (id)
            $application = Application::findOrFail($id);

            Log::info('Application found for success page', [
                'application_id' => $application->id,
                'display_id' => $application->application_id,
                'status' => $application->status
            ]);

            return view('applications.success', compact('application'));
        } catch (\Exception $e) {
            Log::error('Application success page error: ' . $e->getMessage(), ['id' => $id]);
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Show payment page for application
     */
    public function paymentPage($id)
    {
        try {
            $application = Application::findOrFail($id);

            // Calculate fee based on application type
            $fee = ($application->application_type === 'old') ? 30000 : 50000;

            return view('applications.payment', compact('application', 'fee'));
        } catch (\Exception $e) {
            Log::error('Payment page error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Check application status
     */
    public function checkStatus($id)
    {
        try {
            $application = Application::findOrFail($id);
            return view('applications.status', compact('application'));
        } catch (\Exception $e) {
            Log::error('Status check error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Show application details
     */
    public function show($id)
    {
        try {
            $application = Application::findOrFail($id);
            return view('applications.show', compact('application'));
        } catch (\Exception $e) {
            Log::error('Show application error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Helper method: Get current year from academic year
     */
    private function getCurrentYearFromAcademicYear($academicYear)
    {
        if (!$academicYear) return 'first_year';

        try {
            // Parse academic year like "2024-2025"
            $parts = explode('-', $academicYear);
            if (count($parts) < 2) return 'first_year';

            $startYear = intval($parts[0]);
            $currentYear = date('Y') - $startYear + 1;

            $yearMap = [
                1 => 'first_year',
                2 => 'second_year',
                3 => 'third_year',
                4 => 'fourth_year',
                5 => 'fifth_year',
                6 => 'sixth_year',
            ];

            return $yearMap[$currentYear] ?? 'first_year';
        } catch (\Exception $e) {
            return 'first_year';
        }
    }

    /**
     * Helper method: Calculate academic standing based on CGPA
     */
    private function calculateAcademicStanding($cgpa)
    {
        if ($cgpa >= 3.5) return 'excellent';
        if ($cgpa >= 2.5) return 'good';
        if ($cgpa >= 2.0) return 'warning';
        return 'probation';
    }

    /**
     * Send application confirmation email
     */
    private function sendApplicationConfirmation($application)
    {
        try {
            Mail::send('emails.application-confirmation', [
                'application' => $application,
                'payment_url' => route('payment.show', $application->id)
            ], function ($message) use ($application) {
                $message->to($application->email)
                    ->subject('Application Submitted Successfully - WYTU University');
            });

            Log::info('Application confirmation email sent', [
                'application_id' => $application->id,
                'email' => $application->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send application confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Helper method: Get year number from year name
     */
    private function getYearNumber($yearName)
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

    
    /**
     * Verify student credentials
     */
    public function verifyStudent(Request $request)
    {
        Log::info('=== VERIFY STUDENT START ===', $request->all());

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|string',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            Log::error('Verification validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check student credentials
        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            Log::warning('Student not found:', ['student_id' => $request->student_id]);
            return response()->json([
                'success' => false,
                'message' => 'Student ID not found: ' . $request->student_id
            ], 404);
        }

        // Check password
        if (!Hash::check($request->password, $student->password)) {
            Log::warning('Invalid password for student:', ['student_id' => $student->student_id]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid password'
            ], 401);
        }

        // DEBUG: Log all date information
        Log::info('=== DATE VERIFICATION DEBUG ===', [
            'input_dob_raw' => $request->date_of_birth,
            'input_dob_type' => gettype($request->date_of_birth),
            'student_dob_raw' => $student->date_of_birth,
            'student_dob_type' => gettype($student->date_of_birth),
            'student_dob_class' => get_class($student->date_of_birth),
        ]);

        try {
            // Parse input date
            $inputDate = Carbon::parse($request->date_of_birth);
            $inputFormatted = $inputDate->format('Y-m-d');

            // Parse student date
            $studentDate = Carbon::parse($student->date_of_birth);
            $studentFormatted = $studentDate->format('Y-m-d');

            Log::info('=== DATE COMPARISON ===', [
                'input_parsed' => $inputFormatted,
                'student_parsed' => $studentFormatted,
                'input_timestamp' => $inputDate->timestamp,
                'student_timestamp' => $studentDate->timestamp,
                'dates_equal' => $inputFormatted === $studentFormatted,
            ]);

            // Compare dates
            if ($inputFormatted !== $studentFormatted) {
                Log::error('Date mismatch:', [
                    'expected' => $studentFormatted,
                    'received' => $inputFormatted,
                    'student_id' => $student->student_id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Date of birth does not match. ' .
                        'Student records show: ' . $studentFormatted . ' (January 1, 1999). ' .
                        'You entered: ' . $inputFormatted
                ], 401);
            }

            Log::info('Date verification PASSED for student:', [
                'student_id' => $student->student_id,
                'dob' => $studentFormatted
            ]);
        } catch (\Exception $e) {
            Log::error('Date parsing exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_id' => $student->student_id,
                'input_dob' => $request->date_of_birth
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid date format. Please use YYYY-MM-DD format. Error: ' . $e->getMessage()
            ], 400);
        }

        // Check if student is active
        if ($student->status !== 'active') {
            Log::warning('Student not active:', [
                'student_id' => $student->student_id,
                'status' => $student->status
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Status: ' . $student->status
            ], 403);
        }

        // Store student in session for the form
        session(['verified_student' => $student]);

        Log::info('=== VERIFY STUDENT SUCCESS ===', [
            'student_id' => $student->student_id,
            'name' => $student->name,
            'email' => $student->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student verified successfully!',
            'student' => [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'department' => $student->department,
                'academic_year' => $student->academic_year,
                'current_year' => $student->current_year,
                'date_of_birth' => $studentFormatted,
                'gender' => $student->gender,
                'nrc_number' => $student->nrc_number,
                'address' => $student->address,
                'cgpa' => $student->cgpa ?? 0.00,
                'status' => $student->status
            ]
        ]);
    }
}
