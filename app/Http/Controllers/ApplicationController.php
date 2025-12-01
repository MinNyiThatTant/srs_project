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

    /**
     * Submit application (for both new and old students)
     */
    public function submitApplication(Request $request)
    {
        Log::info('=== SUBMIT APPLICATION START ===', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'nrc_number' => 'required|string|max:20|unique:applications,nrc_number',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'nationality' => 'required|string|max:100',
                'address' => 'required|string',
                'application_type' => 'required|in:new,old',
                'department' => 'required|string|max:255',
                'high_school_name' => 'required_if:application_type,new|string|max:255',
                'high_school_address' => 'required_if:application_type,new|string',
                'graduation_year' => 'required_if:application_type,new|integer|min:1900|max:' . date('Y'),
                'matriculation_score' => 'required_if:application_type,new|numeric|min:0|max:600',
                'previous_qualification' => 'required_if:application_type,new|string|max:255',
                'student_id' => 'required_if:application_type,old|string|max:50',
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
                'department' => $request->department,
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

    // ...existing methods (checkNrc, checkStudentId, show, paymentPage, checkStatus)
}