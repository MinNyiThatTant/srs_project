<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Show student login page (if needed separately)
     */
    public function showLoginForm()
    {
        return view('student.login');
    }

    /**
     * Show forgot password page
     */
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Process forgot password request
     */
    public function sendPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|max:50',
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::where('student_id', $request->student_id)
            ->where('email', $request->email)
            ->first();

        if (!$student) {
            return back()->with('error', 'Student ID and email combination not found. Please check your credentials.')
                ->withInput();
        }

        if ($student->status !== 'active') {
            return back()->with('error', 'Your account is not active. Please contact the administration.')
                ->withInput();
        }

        // Generate reset token
        $resetToken = Str::random(60);
        $student->update([
            'reset_token' => $resetToken,
            'reset_token_expiry' => Carbon::now()->addHours(24)
        ]);

        // Send reset email
        try {
            Mail::send('emails.password-reset', [
                'student' => $student,
                'reset_link' => route('student.reset.password', $resetToken)
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Password Reset Request - WYTU University');
            });

            Log::info('Password reset email sent', [
                'student_id' => $student->student_id,
                'email' => $student->email
            ]);

            return back()->with('success', 'Password reset instructions have been sent to your email. Please check your inbox.');

        } catch (\Exception $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset email. Please try again later.');
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        $student = Student::where('reset_token', $token)
            ->where('reset_token_expiry', '>', Carbon::now())
            ->first();

        if (!$student) {
            return redirect()->route('student.forgot-password')
                ->with('error', 'Invalid or expired reset token. Please request a new password reset.');
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Process password reset
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::where('reset_token', $request->token)
            ->where('reset_token_expiry', '>', Carbon::now())
            ->first();

        if (!$student) {
            return redirect()->route('student.forgot-password')
                ->with('error', 'Invalid or expired reset token. Please request a new password reset.');
        }

        // Update password
        $student->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expiry' => null,
            'last_password_change' => Carbon::now()
        ]);

        // Send confirmation email
        try {
            Mail::send('emails.password-reset-success', [
                'student' => $student,
                'login_url' => route('student.login')
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Password Reset Successful - WYTU University');
            });

            Log::info('Password reset successful', [
                'student_id' => $student->student_id
            ]);

            return redirect()->route('student.login')
                ->with('success', 'Password reset successful! You can now login with your new password.');

        } catch (\Exception $e) {
            Log::error('Failed to send password reset success email: ' . $e->getMessage());
            return redirect()->route('student.login')
                ->with('success', 'Password reset successful! You can now login with your new password.')
                ->with('warning', 'Confirmation email failed to send.');
        }
    }

    /**
     * Show student profile page
     */
    public function showProfile()
    {
        // This method would typically be protected by auth middleware
        // For now, we'll check session verification
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        // Get student's applications
        $applications = Application::where('existing_student_id', $student->student_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.profile', compact('student', 'applications'));
    }

    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        // Get recent applications
        $recentApplications = Application::where('existing_student_id', $student->student_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending applications
        $pendingApplications = Application::where('existing_student_id', $student->student_id)
            ->whereIn('status', ['pending', 'payment_pending', 'payment_verified', 'academic_approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get approved applications
        $approvedApplications = Application::where('existing_student_id', $student->student_id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dashboard', compact(
            'student',
            'recentApplications',
            'pendingApplications',
            'approvedApplications'
        ));
    }

    /**
     * Show student's application history
     */
    public function applicationHistory()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        $applications = Application::where('existing_student_id', $student->student_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.application-history', compact('student', 'applications'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        return view('student.change-password');
    }

    /**
     * Process change password request
     */
    public function changePassword(Request $request)
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        // Verify current password
        if (!Hash::check($request->current_password, $student->password)) {
            return back()->with('error', 'Current password is incorrect.')->withInput();
        }

        // Update password
        $student->update([
            'password' => Hash::make($request->new_password),
            'last_password_change' => Carbon::now()
        ]);

        // Send email notification
        try {
            Mail::send('emails.password-changed', [
                'student' => $student,
                'change_time' => Carbon::now()->format('Y-m-d H:i:s')
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Password Changed - WYTU University');
            });

            Log::info('Password changed successfully', [
                'student_id' => $student->student_id
            ]);

            return back()->with('success', 'Password changed successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to send password changed email: ' . $e->getMessage());
            return back()->with('success', 'Password changed successfully!')
                ->with('warning', 'Confirmation email failed to send.');
        }
    }

    /**
     * Show academic information
     */
    public function academicInfo()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        // Get academic history if available
        $academicHistory = $this->getAcademicHistory($student);

        return view('student.academic-info', compact('student', 'academicHistory'));
    }

    /**
     * Show fees information
     */
    public function feesInfo()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        // Get fee history from applications
        $feeHistory = Application::where('existing_student_id', $student->student_id)
            ->whereNotNull('fee_amount')
            ->where('payment_status', 'verified')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.fees-info', compact('student', 'feeHistory'));
    }

    /**
     * Show documents page
     */
    public function documents()
    {
        if (!session()->has('verified_student')) {
            return redirect()->route('old.student.apply')
                ->with('error', 'Please verify your student credentials first.');
        }

        $studentData = session('verified_student');
        $student = Student::find($studentData['id']);

        if (!$student) {
            session()->forget('verified_student');
            return redirect()->route('old.student.apply')
                ->with('error', 'Student not found. Please verify again.');
        }

        return view('student.documents', compact('student'));
    }

    /**
     * Clear student verification session
     */
    public function clearVerification()
    {
        session()->forget(['verified_student', 'student_verified_at']);
        
        return redirect()->route('old.student.apply')
            ->with('info', 'Verification cleared. Please verify again to continue.');
    }

    /**
     * Helper method to get academic history
     */
    private function getAcademicHistory($student)
    {
        // This is a placeholder - implement based on your database structure
        return [
            [
                'academic_year' => $student->academic_year ?? '2024-2025',
                'year' => $student->current_year ?? 'First Year',
                'status' => 'Active',
                'cgpa' => $student->cgpa ?? 'N/A',
                'remarks' => 'Currently enrolled'
            ]
        ];
    }

    /**
     * Logout student (clear session)
     */
    public function logout()
    {
        session()->forget(['verified_student', 'student_verified_at']);
        
        return redirect()->route('old.student.apply')
            ->with('success', 'Successfully logged out.');
    }

    /**
     * Check student status (AJAX endpoint)
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student ID not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'student' => [
                'student_id' => $student->student_id,
                'name' => $student->name,
                'department' => $student->department,
                'status' => $student->status,
                'current_year' => $student->current_year,
                'academic_year' => $student->academic_year
            ]
        ]);
    }
}