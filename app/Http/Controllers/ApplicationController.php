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

            return view('applications.old-student', compact('departments', 'applicationPurposes'));
            
        } catch (\Exception $e) {
            Log::error('Error loading old student form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load application form. Please try again.');
        }
    }


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
            'application_type' => 'required|in:new,old',
            // Department preferences validation (REPLACED the old 'department' field)
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
            'application_type' => $request->application_type,
            // Department preferences (REPLACED the old 'department' field)
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
            'student_id' => $request->student_id,
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

        // Redirect to success page first, then to payment
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

    
}
