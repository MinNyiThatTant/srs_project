<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

            // For existing student application, we don't need application purposes
            return view('applications.old-student', compact('departments'));
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
     * Check if student ID already exists (for old students)
     */
    public function checkStudentId(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|string'
            ]);

            $studentId = $request->student_id;

            // Check in students table
            $studentExists = Student::where('student_id', $studentId)
                ->where('status', 'active')
                ->first();

            if (!$studentExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active student found with this student ID.'
                ]);
            }

            // Check if password matches (if provided)
            if ($request->has('password')) {
                if (!Hash::check($request->password, $studentExists->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid password.'
                    ]);
                }
            }

            // Check if student already has pending application
            $pendingApplication = Application::where('existing_student_id', $studentId)
                ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved', 'approved'])
                ->first();

            if ($pendingApplication) {
                $statusMessages = [
                    'pending' => 'pending review',
                    'payment_pending' => 'waiting for payment',
                    'payment_verified' => 'payment verified',
                    'academic_approved' => 'academically approved',
                    'approved' => 'approved'
                ];

                return response()->json([
                    'success' => false,
                    'message' => 'You already have an application that is ' .
                        ($statusMessages[$pendingApplication->status] ?? $pendingApplication->status)
                ]);
            }

            // Get current year from academic year
            $currentYear = $this->getCurrentYearFromAcademicYear($studentExists->academic_year);

            return response()->json([
                'success' => true,
                'student' => [
                    'id' => $studentExists->id,
                    'student_id' => $studentExists->student_id,
                    'name' => $studentExists->name,
                    'email' => $studentExists->email,
                    'phone' => $studentExists->phone,
                    'department' => $studentExists->department,
                    'academic_year' => $studentExists->academic_year,
                    'current_year' => $currentYear,
                    'date_of_birth' => $studentExists->date_of_birth,
                    'gender' => $studentExists->gender,
                    'nrc_number' => $studentExists->nrc_number,
                    'address' => $studentExists->address
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Student ID check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking student ID.'
            ], 500);
        }
    }

    /**
     * Submit application for NEW students
     */
    public function submitNewApplication(Request $request)
    {
        Log::info('=== SUBMIT NEW STUDENT APPLICATION START ===', $request->all());

        try {
            // Enhanced validation with custom duplicate checking
            $request->validate([
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
                // Department preferences validation
                'first_priority_department' => 'required|string|max:255',
                'second_priority_department' => 'required|string|max:255',
                'third_priority_department' => 'required|string|max:255',
                'fourth_priority_department' => 'nullable|string|max:255',
                'fifth_priority_department' => 'nullable|string|max:255',
                // Educational background
                'high_school_name' => 'required|string|max:255',
                'high_school_address' => 'required|string',
                'graduation_year' => 'required|integer|min:1900|max:' . date('Y'),
                'matriculation_score' => 'required|numeric|min:0|max:600',
                'previous_qualification' => 'required|string|max:255',
                'terms' => 'required|accepted'
            ], [
                'first_priority_department.required' => 'Please select your first priority department.',
                'second_priority_department.required' => 'Please select your second priority department.',
                'third_priority_department.required' => 'Please select your third priority department.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate application ID
            $applicationId = 'APP' . strtoupper(Str::random(8)) . date('Ymd');
            Log::info('Generated application ID: ' . $applicationId);

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
                'application_type' => 'new',
                'student_type' => 'freshman',
                // Department preferences
                'first_priority_department' => $request->first_priority_department,
                'second_priority_department' => $request->second_priority_department,
                'third_priority_department' => $request->third_priority_department,
                'fourth_priority_department' => $request->fourth_priority_department,
                'fifth_priority_department' => $request->fifth_priority_department,
                // Educational background
                'high_school_name' => $request->high_school_name,
                'high_school_address' => $request->high_school_address,
                'graduation_year' => $request->graduation_year,
                'matriculation_score' => $request->matriculation_score,
                'previous_qualification' => $request->previous_qualification,
                'status' => 'payment_pending',
                'payment_status' => 'pending',
            ]);

            DB::commit();

            Log::info('New student application created successfully', [
                'application_db_id' => $application->id,
                'application_display_id' => $application->application_id,
                'status' => $application->status,
                'payment_status' => $application->payment_status
            ]);

            // Send confirmation email
            $this->sendNewApplicationConfirmation($application);

            // Redirect to success page
            return redirect()->route('application.success', $application->id)
                ->with('success', 'Application submitted successfully! Please complete your payment.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('New application submission failed: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Application submission failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Verify existing student before application
     */
    /**
     * Verify existing student before application
     */
    public function verifyExistingStudent(Request $request)
    {
        Log::info('Verifying existing student', ['student_id' => $request->student_id]);

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
            'message' => 'Student verified successfully',
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
                'cgpa' => $student->cgpa ?? 3.5, // Default for testing
                'academic_standing' => $this->calculateAcademicStanding($student->cgpa ?? 3.5)
            ]
        ]);
    }

    /**
     * Submit existing student application
     */
    public function submitExistingApplication(Request $request)
    {
        Log::info('=== SUBMIT EXISTING STUDENT APPLICATION START ===', $request->all());

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|exists:students,student_id',
            'applied_department' => 'required|string|max:255',
            'applied_year' => 'required|in:first_year,second_year,third_year,fourth_year,fifth_year,sixth_year',
            'reason_for_continuation' => 'required|string|max:1000',
            'cgpa' => 'required|numeric|min:0|max:4',
            'academic_standing' => 'required|in:good,warning,probation',
            'agreement' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.')->withInput();
        }

        // Determine current year from academic year
        $currentYear = $this->getCurrentYearFromAcademicYear($student->academic_year);

        try {
            DB::beginTransaction();

            // Generate application ID
            $applicationId = 'APP' . date('Y') . strtoupper(Str::random(6));

            // Create application
            $application = Application::create([
                'application_id' => $applicationId,
                'existing_student_id' => $student->student_id,
                'student_id' => null, // Will be filled after approval
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'nrc_number' => $student->nrc_number,
                'date_of_birth' => $student->date_of_birth,
                'gender' => $student->gender,
                'address' => $student->address,
                'application_type' => 'existing',
                'student_type' => 'continuing',
                'current_year' => $currentYear,
                'applied_year' => $request->applied_year,
                'current_department' => $student->department,
                'applied_department' => $request->applied_department,
                'department' => $request->applied_department, // Initially set to applied department
                'reason_for_continuation' => $request->reason_for_continuation,
                'cgpa' => $request->cgpa,
                'academic_standing' => $request->academic_standing,
                'status' => 'payment_pending',
                'payment_status' => 'pending',
            ]);

            DB::commit();

            Log::info('Existing student application created successfully', [
                'application_db_id' => $application->id,
                'application_display_id' => $application->application_id,
                'student_id' => $student->student_id,
                'status' => $application->status
            ]);

            // Send notification email
            $this->sendExistingApplicationConfirmation($application, $student);

            return redirect()->route('application.success', $application->id)
                ->with('success', 'Application submitted successfully! Please complete your payment.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Existing application submission failed: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Application submission failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Legacy method for backward compatibility
     */
    public function submitApplication(Request $request)
    {
        Log::info('=== LEGACY SUBMIT APPLICATION (FOR BOTH TYPES) ===', $request->all());

        // Determine application type and route to appropriate method
        if ($request->application_type === 'new') {
            return $this->submitNewApplication($request);
        } elseif ($request->application_type === 'old' || $request->application_type === 'existing') {
            return $this->submitExistingApplication($request);
        }

        return redirect()->back()->with('error', 'Invalid application type.');
    }

    /**
     * Get current year from academic year
     */
    private function getCurrentYearFromAcademicYear($academicYear)
    {
        if (!$academicYear) {
            Log::warning('Academic year is null or empty');
            return 'first_year';
        }

        Log::info('Parsing academic year', ['academic_year' => $academicYear]);

        try {
            // Handle different academic year formats

            // If it's just a single year (e.g., "2025")
            if (is_numeric($academicYear) && strlen($academicYear) === 4) {
                $startYear = intval($academicYear);
                $currentYear = date('Y') - $startYear + 1;

                Log::info('Single year format detected', [
                    'start_year' => $startYear,
                    'current_year' => $currentYear
                ]);
            }
            // If it's a range (e.g., "2024-2025")
            else if (str_contains($academicYear, '-')) {
                $parts = explode('-', $academicYear);
                if (count($parts) >= 2) {
                    $startYear = intval($parts[0]);
                    $currentYear = date('Y') - $startYear + 1;

                    Log::info('Range format detected', [
                        'start_year' => $startYear,
                        'current_year' => $currentYear
                    ]);
                } else {
                    Log::warning('Invalid academic year format', ['academic_year' => $academicYear]);
                    return 'first_year';
                }
            } else {
                Log::warning('Unknown academic year format', ['academic_year' => $academicYear]);
                return 'first_year';
            }

            $yearMap = [
                1 => 'first_year',
                2 => 'second_year',
                3 => 'third_year',
                4 => 'fourth_year',
                5 => 'fifth_year',
                6 => 'sixth_year',
            ];

            $result = $yearMap[$currentYear] ?? 'first_year';

            Log::info('Year mapping result', [
                'calculated_year' => $currentYear,
                'mapped_result' => $result
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error parsing academic year: ' . $e->getMessage(), [
                'academic_year' => $academicYear,
                'trace' => $e->getTraceAsString()
            ]);
            return 'first_year';
        }
    }



    /**
     * Calculate academic standing based on CGPA
     * Add this method to your ApplicationController class
     */
    private function calculateAcademicStanding($cgpa)
    {
        if ($cgpa >= 3.5) return 'excellent';
        if ($cgpa >= 2.5) return 'good';
        if ($cgpa >= 2.0) return 'warning';
        return 'probation';
    }


    /**
     * Get next year
     */
    private function getNextYear($currentYear)
    {
        $nextYearMap = [
            'first_year' => 'second_year',
            'second_year' => 'third_year',
            'third_year' => 'fourth_year',
            'fourth_year' => 'fifth_year',
            'fifth_year' => 'sixth_year',
            'sixth_year' => 'graduated',
        ];

        return $nextYearMap[$currentYear] ?? 'second_year';
    }

    /**
     * Send new application confirmation email
     */
    private function sendNewApplicationConfirmation($application)
    {
        try {
            Mail::send('emails.new-application-confirmation', [
                'application' => $application,
                'payment_url' => route('payment.show', $application->id)
            ], function ($message) use ($application) {
                $message->to($application->email)
                    ->subject('Application Submitted Successfully - WYTU University');
            });

            Log::info('New application confirmation email sent', [
                'application_id' => $application->id,
                'email' => $application->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send new application confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Send existing application confirmation email
     */
    private function sendExistingApplicationConfirmation($application, $student)
    {
        try {
            Mail::send('emails.existing-application-confirmation', [
                'application' => $application,
                'student' => $student,
                'payment_url' => route('payment.show', $application->id)
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Continuation Application Submitted - WYTU University');
            });

            Log::info('Existing application confirmation email sent', [
                'application_id' => $application->id,
                'student_id' => $student->student_id,
                'email' => $student->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send existing application confirmation email: ' . $e->getMessage());
        }
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
                'status' => $application->status,
                'type' => $application->application_type
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
            $fee = ($application->application_type === 'existing') ? 30000 : 50000;

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
     * Check if existing student can apply
     */
    public function canExistingStudentApply(Request $request)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return response()->json(['can_apply' => false, 'reason' => 'Not authenticated']);
        }

        // Check if student already has pending application
        $pendingApplication = Application::where('existing_student_id', $student->student_id)
            ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved'])
            ->first();

        if ($pendingApplication) {
            return response()->json([
                'can_apply' => false,
                'reason' => 'You already have a pending application',
                'application_id' => $pendingApplication->application_id,
                'status' => $pendingApplication->status
            ]);
        }

        // Check if student is eligible (good academic standing)
        if ($student->academic_standing === 'probation') {
            return response()->json([
                'can_apply' => false,
                'reason' => 'You are on academic probation and cannot apply'
            ]);
        }

        return response()->json(['can_apply' => true]);
    }
}
