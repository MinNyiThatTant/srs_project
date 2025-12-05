<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OldStudentApplicationController extends Controller
{
    /**
     * Show the old student application form
     */
    public function showForm()
    {
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

        return view('applications.old-student-simple', compact('departments', 'applicationPurposes', 'currentAcademicYear'));
    }

    /**
     * Verify student credentials using DB facade (no model dependency)
     */
    public function verifyStudent(Request $request)
    {
        Log::info('Old Student Verification Attempt', $request->all());

        try {
            // Simple validation
            $validated = $request->validate([
                'student_id' => 'required|string',
                'password' => 'required|string',
                'date_of_birth' => 'required|date_format:Y-m-d'
            ]);

            // Find student using DB facade
            $student = DB::table('students')
                ->where('student_id', $validated['student_id'])
                ->where('status', 'active')
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID not found or account is not active.'
                ], 404);
            }

            // Verify password
            if (!Hash::check($validated['password'], $student->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password. Please try again.'
                ], 401);
            }

            // Verify date of birth
            $dbDob = date('Y-m-d', strtotime($student->date_of_birth));
            if ($dbDob !== $validated['date_of_birth']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date of birth does not match our records.'
                ], 401);
            }

            // Store student data in session
            session([
                'old_student_verified' => true,
                'old_student_data' => [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'department' => $student->department,
                    'date_of_birth' => $student->date_of_birth,
                    'gender' => $student->gender,
                    'nrc_number' => $student->nrc_number,
                    'address' => $student->address,
                    'current_year' => $student->current_year,
                    'cgpa' => $student->cgpa,
                    'academic_year' => $student->academic_year,
                ],
                'verified_at' => now()
            ]);

            // Save session immediately
            session()->save();

            Log::info('Student verification successful', ['student_id' => $student->student_id]);

            return response()->json([
                'success' => true,
                'message' => 'Student verified successfully!',
                'redirect_url' => route('old.student.application.form')
            ]);

        } catch (\Exception $e) {
            Log::error('Student verification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Show application form after verification
     */
    public function showApplicationForm()
    {
        // Check if student is verified
        if (!session('old_student_verified') || !session('old_student_data')) {
            return redirect()->route('old.student.form')
                ->with('error', 'Please verify your student credentials first.');
        }

        $student = (object) session('old_student_data');
        
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

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $currentAcademicYear = $currentYear . '-' . $nextYear;

        return view('applications.old-student-application', compact('student', 'departments', 'applicationPurposes', 'currentAcademicYear'));
    }

    /**
     * Submit the application
     */
    public function submitApplication(Request $request)
    {
        // Check if student is verified
        if (!session('old_student_verified') || !session('old_student_data')) {
            return redirect()->route('old.student.form')
                ->with('error', 'Session expired. Please verify again.');
        }

        $studentData = session('old_student_data');

        try {
            // Validate application data
            $validated = $request->validate([
                'current_year' => 'required|in:1,2,3,4,5',
                'previous_year_status' => 'required|in:passed,failed,retake,improvement',
                'cgpa' => 'required|numeric|min:0|max:4',
                'application_purpose' => 'required|in:course_registration,re_examination,transfer,other',
                'phone' => 'required|regex:/^[0-9]{10,11}$/',
                'address' => 'required|string|max:500',
                'reason' => 'required|string|min:50|max:500',
                'emergency_contact' => 'required|string|max:100',
                'emergency_phone' => 'required|regex:/^[0-9]{10,11}$/',
                'signature' => 'required|string|max:100',
                'declaration_accuracy' => 'required|accepted',
                'declaration_fee' => 'required|accepted',
                'declaration_rules' => 'required|accepted',
            ]);

            // Check for existing application
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            $academicYear = $currentYear . '-' . $nextYear;

            $existingApplication = DB::table('applications')
                ->where('existing_student_id', $studentData['student_id'])
                ->where('academic_year', $academicYear)
                ->where('current_year', $validated['current_year'])
                ->whereIn('status', ['payment_pending', 'payment_verified', 'academic_approved', 'approved', 'pending'])
                ->first();

            if ($existingApplication) {
                return redirect()->back()
                    ->with('error', 'You already have a pending application for this academic year.')
                    ->withInput();
            }

            // Generate application ID
            $applicationId = 'APP-OLD-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

            // Calculate fee
            $fee = $this->calculateApplicationFee($validated['application_purpose'], $validated['current_year']);

            // Insert application
            $applicationId = DB::table('applications')->insertGetId([
                'application_id' => $applicationId,
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'phone' => $validated['phone'],
                'nrc_number' => $studentData['nrc_number'],
                'date_of_birth' => $studentData['date_of_birth'],
                'gender' => $studentData['gender'],
                'address' => $validated['address'],
                'application_type' => 'old',
                'student_type' => 'continuing',
                'department' => $studentData['department'],
                'academic_year' => $academicYear,
                'current_year' => $validated['current_year'],
                'existing_student_id' => $studentData['student_id'],
                'student_original_id' => $studentData['id'],
                'application_purpose' => $validated['application_purpose'],
                'reason_for_application' => $validated['reason'],
                'cgpa' => $validated['cgpa'],
                'previous_year_status' => $validated['previous_year_status'],
                'emergency_contact' => $validated['emergency_contact'],
                'emergency_phone' => $validated['emergency_phone'],
                'fee_amount' => $fee,
                'signature' => $validated['signature'],
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
            session()->forget(['old_student_verified', 'old_student_data', 'verified_at']);

            Log::info('Old student application submitted', [
                'application_id' => $applicationId,
                'student_id' => $studentData['student_id']
            ]);

            return redirect()->route('application.success', ['id' => $applicationId])
                ->with('success', 'Application submitted successfully! Please proceed with payment.');

        } catch (\Exception $e) {
            Log::error('Application submission error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error submitting application: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calculate application fee
     */
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

    /**
     * Clear verification session
     */
    public function clearVerification()
    {
        session()->forget(['old_student_verified', 'old_student_data', 'verified_at']);
        return redirect()->route('old.student.form')
            ->with('info', 'Verification cleared. Please verify again.');
    }

    /**
     * Create a test student for development
     */
    public function createTestStudent()
    {
        // Only allow in local environment
        if (app()->environment('production')) {
            abort(403);
        }

        try {
            // Check if test student already exists
            $existing = DB::table('students')
                ->where('student_id', 'WYTU202400001')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test student already exists.',
                    'student' => [
                        'student_id' => 'WYTU202400001',
                        'password' => 'password123',
                        'date_of_birth' => '2000-01-01'
                    ]
                ]);
            }

            // Create test student
            $studentId = DB::table('students')->insertGetId([
                'student_id' => 'WYTU202400001',
                'name' => 'John Doe',
                'email' => 'john@student.com',
                'phone' => '09123456789',
                'password' => Hash::make('password123'),
                'department' => 'Computer Engineering and Information Technology',
                'date_of_birth' => '2000-01-01',
                'gender' => 'male',
                'nrc_number' => '12/ABC(N)123456',
                'address' => '123 Test Street, Yangon',
                'current_year' => 'first_year',
                'academic_year' => '2024-2025',
                'cgpa' => 3.5,
                'status' => 'active',
                'registration_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test student created successfully!',
                'student' => [
                    'id' => $studentId,
                    'student_id' => 'WYTU202400001',
                    'password' => 'password123',
                    'date_of_birth' => '2000-01-01'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating test student: ' . $e->getMessage()
            ], 500);
        }
    }
}