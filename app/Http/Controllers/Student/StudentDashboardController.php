<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        // Get student statistics
        $stats = $this->getStudentStats($student);

        return view('student.dashboard', compact('student', 'stats'));
    }

    /**
     * Get student statistics
     */
    private function getStudentStats($student)
    {
        try {
            $paymentsCount = $student->payments()->count();
            $application = $student->application;

            return [
                'total_payments' => $paymentsCount,
                'payment_status' => $application->payment_status ?? 'N/A',
                'application_status' => $application->status ?? 'N/A',
                'department' => $student->department,
                'academic_year' => $student->academic_year,
                'registration_date' => $student->formatted_registration_date,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading student dashboard stats', [
                'student_id' => $student->student_id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'total_payments' => 0,
                'payment_status' => 'N/A',
                'application_status' => 'N/A',
                'department' => $student->department,
                'academic_year' => $student->academic_year,
                'registration_date' => $student->formatted_registration_date,
            ];
        }
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        $student->update($request->only(['name', 'phone', 'address', 'date_of_birth']));

        Log::info('Student profile updated', [
            'student_id' => $student->student_id
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show payment history
     */
    public function paymentHistory()
    {
        $student = Auth::guard('student')->user();
        
        try {
            $payments = $student->payments()
                ->with('application')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading payment history', [
                'student_id' => $student->student_id,
                'error' => $e->getMessage()
            ]);
            $payments = collect();
        }

        return view('student.payments', compact('student', 'payments'));
    }
}