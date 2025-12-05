<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        // Check if student is verified from session
        $student = session('verified_student');

        // If not verified, show verification form
        if (!$student) {
            return view('applications.old-student-verification');
        }

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

        // Get current academic year
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $currentAcademicYear = $currentYear . '-' . $nextYear;

        return view('applications.old-student-application', compact('departments', 'applicationPurposes', 'student', 'currentAcademicYear'));
    } catch (\Exception $e) {
        Log::error('Error loading old student form: ' . $e->getMessage());
        return back()->with('error', 'Unable to load application form. Please try again.');
    }
}

    /**
     * Submit application (for new students)
     */
    public function submitApplication(Request $request)
    {
        Log::info('=== SUBMIT NEW STUDENT APPLICATION START ===', $request->all());

        try {
            // Your existing new student validation and submission logic
            // ... (keep your existing new student submission code) ...

            return redirect()->route('application.success', $application->id)
                ->with('success', 'Application submitted successfully! Please complete your payment.');
        } catch (\Exception $e) {
            Log::error('New student application submission failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Application submission failed. Please try again.');
        }
    }

    /**
     * Verify student credentials (for old students) - WORKING VERSION
     */
    public function verifyStudent(Request $request)
    {
        Log::info('=== VERIFY STUDENT START ===', $request->all());

        try {
            // Simple validation
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|string',
                'password' => 'required|string',
                'date_of_birth' => 'required|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Use DB facade to avoid model issues
            $student = DB::table('students')
                ->where('student_id', $request->student_id)
                ->where('status', 'active')
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID not found or account is not active.'
                ], 404);
            }

            // Verify password
            if (!Hash::check($request->password, $student->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password. Please try again.'
                ], 401);
            }

            // Verify date of birth
            $dbDob = date('Y-m-d', strtotime($student->date_of_birth));
            if ($dbDob !== $request->date_of_birth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date of birth does not match our records.'
                ], 401);
            }

            // Store verified student in session as array
            $studentData = [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone ?? '',
                'nrc_number' => $student->nrc_number ?? '',
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender ?? '',
                'department' => $student->department,
                'current_year' => $student->current_year ?? 'first_year',
                'cgpa' => $student->cgpa ?? 0,
                'address' => $student->address ?? '',
                'academic_year' => $student->academic_year ?? date('Y') . '-' . (date('Y') + 1),
            ];

            session([
                'verified_student' => $studentData,
                'student_verified_at' => now()
            ]);

            // Force save session
            session()->save();

            Log::info('Student verified successfully', [
                'student_id' => $student->student_id,
                'session_id' => session()->getId()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student verified successfully!',
                'redirect_url' => route('old.student.apply'),
                'student' => $studentData
            ]);

        } catch (\Exception $e) {
            Log::error('Student verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

    /**
 * Submit existing student application - UPDATED VERSION
 */
public function submitExistingApplication(Request $request)
{
    Log::info('=== SUBMIT EXISTING APPLICATION START ===', $request->all());

    try {
        // Check if student is verified
        if (!session()->has('verified_student')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please verify again.'
                ], 401);
            }
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.')
                ->withInput();
        }

        $student = session('verified_student');

        // Validate
        $validator = Validator::make($request->all(), [
            'current_year' => 'required|in:1,2,3,4,5',
            'previous_year_status' => 'required|in:passed,failed,retake,improvement',
            'cgpa' => 'required|numeric|min:0|max:4',
            'application_purpose' => 'required|in:course_registration,re_examination,transfer,other',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'address' => 'required|string|max:500',
            'reason' => 'required|string|min:20|max:500',
            'emergency_contact' => 'required|string|max:100',
            'emergency_phone' => 'required|regex:/^[0-9]{10,11}$/',
            'declaration_accuracy' => 'required|accepted',
            'declaration_fee' => 'required|accepted',
            'declaration_rules' => 'required|accepted',
            'signature' => 'required|string|max:100'
        ], [
            'reason.min' => 'Please provide a detailed reason (at least 20 characters).',
            'reason.max' => 'Reason should not exceed 500 characters.',
            'cgpa.required' => 'Please enter your current CGPA.',
            'cgpa.numeric' => 'CGPA must be a valid number.',
            'cgpa.min' => 'CGPA cannot be less than 0.',
            'cgpa.max' => 'CGPA cannot exceed 4.0.',
            'previous_year_status.required' => 'Please select your previous year academic status.',
            'declaration_accuracy.accepted' => 'You must agree to the accuracy declaration.',
            'declaration_fee.accepted' => 'You must agree to the fee declaration.',
            'declaration_rules.accepted' => 'You must agree to the rules declaration.',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check year progression
        $yearMap = [
            'first_year' => 1,
            'second_year' => 2,
            'third_year' => 3,
            'fourth_year' => 4,
            'fifth_year' => 5,
        ];
        $currentYearNum = $yearMap[$student['current_year'] ?? 'first_year'] ?? 1;
        $applyingYear = intval($request->current_year);

        if ($applyingYear !== ($currentYearNum + 1)) {
            $yearNames = [
                1 => 'First Year',
                2 => 'Second Year',
                3 => 'Third Year',
                4 => 'Fourth Year',
                5 => 'Fifth Year',
            ];
            $errorMessage = 'Invalid application year. You are currently in ' .
                ($yearNames[$currentYearNum] ?? 'Unknown Year') .
                '. Please apply for ' . ($yearNames[$currentYearNum + 1] ?? 'Unknown Year') . '.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        // Check if student already has a pending application for this year
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $academicYear = $currentYear . '-' . $nextYear;
        
        $existingApplication = DB::table('applications')
            ->where('existing_student_id', $student['student_id'])
            ->where('academic_year', $academicYear)
            ->where('current_year', $applyingYear)
            ->whereIn('status', ['payment_pending', 'payment_verified', 'academic_approved', 'approved', 'pending'])
            ->first();

        if ($existingApplication) {
            $statusMap = [
                'pending' => 'pending review',
                'payment_pending' => 'waiting for payment',
                'payment_verified' => 'payment verified',
                'academic_approved' => 'academically approved',
                'approved' => 'approved',
                'rejected' => 'rejected',
            ];
            $statusText = $statusMap[$existingApplication->status] ?? $existingApplication->status;
            $errorMessage = 'You already have a ' . $statusText . ' application for this academic year.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 409);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        // Generate application ID
        $applicationId = 'APP-OLD-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

        // Calculate fee
        $baseFee = 30000;
        $additionalFees = [
            'course_registration' => 0,
            're_examination' => 10000,
            'transfer' => 20000,
            'other' => 5000,
        ];
        $yearMultipliers = [
            1 => 1.0, 2 => 1.0, 3 => 1.1, 4 => 1.2, 5 => 1.3,
        ];
        $additionalFee = $additionalFees[$request->application_purpose] ?? 0;
        $multiplier = $yearMultipliers[$applyingYear] ?? 1.0;
        $fee = intval(($baseFee + $additionalFee) * $multiplier);

        // Create application using DB facade
        $applicationInsertId = DB::table('applications')->insertGetId([
            'application_id' => $applicationId,
            'name' => $student['name'],
            'email' => $student['email'],
            'phone' => $request->phone,
            'nrc_number' => $student['nrc_number'],
            'date_of_birth' => $student['date_of_birth'],
            'gender' => $student['gender'],
            'address' => $request->address,
            'application_type' => 'old',
            'student_type' => 'continuing',
            'department' => $student['department'],
            'academic_year' => $academicYear,
            'current_year' => $applyingYear,
            'existing_student_id' => $student['student_id'],
            'student_original_id' => $student['id'],
            'application_purpose' => $request->application_purpose,
            'reason_for_application' => $request->reason,
            'cgpa' => $request->cgpa,
            'previous_year_status' => $request->previous_year_status,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'fee_amount' => $fee,
            'signature' => $request->signature,
            'status' => 'payment_pending',
            'payment_status' => 'pending',
            'needs_academic_approval' => true,
            'academic_approval_status' => 'pending',
            'declaration_accepted' => true,
            'terms_accepted' => true,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clear session
        session()->forget(['verified_student', 'student_verified_at']);

        Log::info('Old student application created successfully', [
            'application_id' => $applicationInsertId,
            'display_id' => $applicationId,
            'student_id' => $student['student_id'],
            'applying_year' => $applyingYear,
            'payment_amount' => $fee,
        ]);

        // Send confirmation email
        try {
            Mail::send('emails.old-student-application-confirmation', [
                'application_id' => $applicationId,
                'student' => (object)$student,
                'payment_url' => route('applications.payment', $applicationInsertId),
                'fee' => number_format($fee) . ' MMK',
                'academic_year' => $academicYear,
            ], function ($message) use ($student) {
                $message->to($student['email'])
                    ->cc(config('mail.admin_email', 'admin@wytu.edu.mm'))
                    ->subject('Existing Student Application Submitted - ' . $student['department'] . ' - WYTU University');
            });
            Log::info('Confirmation email sent to: ' . $student['email']);
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!',
                'application_id' => $applicationInsertId,
                'redirect' => route('application.success', $applicationInsertId)
            ]);
        }

        return redirect()->route('application.success', $applicationInsertId)
            ->with([
                'success' => 'Application submitted successfully! Please complete your payment to proceed.',
                'application_id' => $applicationId,
                'email_sent' => 'Confirmation email sent to ' . $student['email']
            ]);

    } catch (\Exception $e) {
        Log::error('Old student application process error: ' . $e->getMessage(), [
            'request_data' => $request->except(['_token']),
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'An error occurred while submitting your application. Please try again.');
    }
}

    /**
     * Check NRC number
     */
    public function checkNrc(Request $request)
    {
        try {
            $nrcNumber = $request->input('nrc_number');
            $applicationId = $request->input('application_id');

            $query = DB::table('applications')
                ->where('nrc_number', $nrcNumber)
                ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved']);

            if ($applicationId) {
                $query->where('id', '!=', $applicationId);
            }

            $exists = $query->exists();

            return response()->json([
                'exists' => $exists,
                'message' => $exists ? 'This NRC number is already associated with an existing application.' : 'NRC number is available.'
            ]);
        } catch (\Exception $e) {
            Log::error('Check NRC error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while checking NRC number.'
            ], 500);
        }
    }

    /**
     * Check email
     */
    public function checkEmail(Request $request)
    {
        try {
            $email = $request->input('email');
            $applicationId = $request->input('application_id');

            $query = DB::table('applications')
                ->where('email', $email)
                ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved']);

            if ($applicationId) {
                $query->where('id', '!=', $applicationId);
            }

            $exists = $query->exists();

            return response()->json([
                'exists' => $exists,
                'message' => $exists ? 'This email is already associated with an existing application.' : 'Email is available.'
            ]);
        } catch (\Exception $e) {
            Log::error('Check email error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while checking email.'
            ], 500);
        }
    }

    /**
     * Show application success page
     */
    public function applicationSuccess($id)
    {
        try {
            $application = DB::table('applications')->where('id', $id)->first();
            
            if (!$application) {
                throw new \Exception('Application not found');
            }
            
            return view('applications.success', compact('application'));
        } catch (\Exception $e) {
            Log::error('Application success page error: ' . $e->getMessage());
            return redirect()->route('old.student.apply')->with('error', 'Application not found.');
        }
    }

    /**
     * Show payment page for application
     */
    public function paymentPage($id)
    {
        try {
            $application = DB::table('applications')->where('id', $id)->first();
            
            if (!$application) {
                throw new \Exception('Application not found');
            }
            
            $fee = $application->fee_amount ?? 30000;
            return view('applications.payment', compact('application', 'fee'));
        } catch (\Exception $e) {
            Log::error('Payment page error: ' . $e->getMessage());
            return redirect()->route('old.student.apply')->with('error', 'Application not found.');
        }
    }

    /**
     * Check application status
     */
    public function checkStatus($id)
    {
        try {
            $application = DB::table('applications')->where('id', $id)->first();
            
            if (!$application) {
                throw new \Exception('Application not found');
            }
            
            return view('applications.status', compact('application'));
        } catch (\Exception $e) {
            Log::error('Status check error: ' . $e->getMessage());
            return redirect()->route('old.student.apply')->with('error', 'Application not found.');
        }
    }

    /**
     * Show application details
     */
    public function show($id)
    {
        try {
            $application = DB::table('applications')->where('id', $id)->first();
            
            if (!$application) {
                throw new \Exception('Application not found');
            }
            
            return view('applications.show', compact('application'));
        } catch (\Exception $e) {
            Log::error('Show application error: ' . $e->getMessage());
            return redirect()->route('old.student.apply')->with('error', 'Application not found.');
        }
    }

    /**
     * Helper methods
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

    private function getApplicationStatusText($status)
    {
        $statusMap = [
            'pending' => 'pending review',
            'payment_pending' => 'waiting for payment',
            'payment_verified' => 'payment verified',
            'academic_approved' => 'academically approved',
            'approved' => 'approved',
            'rejected' => 'rejected',
        ];
        return $statusMap[$status] ?? $status;
    }

    private function getCurrentAcademicYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    private function calculateApplicationFee($purpose, $year)
    {
        $baseFee = 30000;
        $additionalFees = [
            'course_registration' => 0,
            're_examination' => 10000,
            'transfer' => 20000,
            'other' => 5000,
        ];
        $yearMultipliers = [
            1 => 1.0, 2 => 1.0, 3 => 1.1, 4 => 1.2, 5 => 1.3,
        ];
        $additionalFee = $additionalFees[$purpose] ?? 0;
        $multiplier = $yearMultipliers[$year] ?? 1.0;
        return intval(($baseFee + $additionalFee) * $multiplier);
    }

    private function getYearNumber($yearName)
    {
        $yearMap = [
            'first_year' => 1,
            'second_year' => 2,
            'third_year' => 3,
            'fourth_year' => 4,
            'fifth_year' => 5,
        ];
        return $yearMap[$yearName] ?? 1;
    }

    private function sendOldStudentApplicationConfirmation($applicationId, $student)
    {
        try {
            // Get application data
            $application = DB::table('applications')->where('id', $applicationId)->first();
            
            if (!$application) {
                Log::error('Application not found for confirmation email: ' . $applicationId);
                return false;
            }

            Mail::send('emails.old-student-application-confirmation', [
                'application' => $application,
                'student' => (object)$student,
                'payment_url' => route('applications.payment', $applicationId),
                'fee' => number_format($application->fee_amount) . ' MMK',
                'academic_year' => $application->academic_year,
            ], function ($message) use ($application, $student) {
                $message->to($application->email)
                    ->cc(config('mail.admin_email', 'admin@wytu.edu.mm'))
                    ->subject('Existing Student Application Submitted - ' . $student['department'] . ' - WYTU University');
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear verification session
     */
    public function clearVerification()
    {
        session()->forget(['verified_student', 'student_verified_at']);
        return redirect()->route('old.student.apply')
            ->with('info', 'Verification cleared. Please verify again.');
    }
}