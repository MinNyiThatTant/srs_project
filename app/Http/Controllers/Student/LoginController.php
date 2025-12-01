<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show student login form
     */
    public function index()
    {
        return view('student.auth.login');
    }

    /**
     * Show student registration form (for application)
     */
    public function register()
    {
        return view('student.auth.register');
    }

    /**
     * Process student login
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'student_id.required' => 'Student ID is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'student_id' => $request->student_id,
            'password' => $request->password,
            'status' => 'active' // Only allow active students to login
        ];

        if (Auth::guard('student')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Log login activity
            $student = Auth::guard('student')->user();
            activity()
                ->causedBy($student)
                ->log('logged in');

            return redirect()->intended(route('student.dashboard'))
                ->with('success', 'Welcome back, ' . $student->name . '!');
        }

        return back()->withErrors([
            'student_id' => 'Invalid student ID or password.',
        ])->withInput($request->only('student_id', 'remember'));
    }

    /**
     * Process student registration from application
     */
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'nrc_number' => 'required|string|unique:students,nrc_number',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:500',
            'department' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // This would typically be handled by the application approval process
        // This method is kept for backward compatibility
        return back()->with('error', 'Student registration is only available through the application approval process.');
    }

    /**
     * Student logout
     */
    public function logout(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        if ($student) {
            activity()
                ->causedBy($student)
                ->log('logged out');
        }

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('student.auth.forgot-password');
    }

    /**
     * Process forgot password request
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return back()->withErrors(['email' => 'No student found with this email address.']);
        }

        // Generate reset token (you can use Laravel's built-in password reset if configured)
        $token = \Str::random(60);
        
        // Store token in database (you might want to create a password_resets table for students)
        \DB::table('student_password_resets')->updateOrInsert(
            ['email' => $student->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send reset email
        \Mail::send('student.auth.reset-password-email', [
            'student' => $student,
            'token' => $token,
            'reset_url' => route('student.reset.password', ['token' => $token, 'email' => $student->email])
        ], function ($message) use ($student) {
            $message->to($student->email)
                    ->subject('Password Reset Request - WYTU Student Portal');
        });

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('student.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Process password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify token (simplified implementation - consider using Laravel's built-in reset)
        $resetRecord = \DB::table('student_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is expired (1 hour)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        // Update student password
        $student = Student::where('email', $request->email)->first();
        if ($student) {
            $student->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete used token
            \DB::table('student_password_resets')->where('email', $request->email)->delete();

            return redirect()->route('student.login')
                ->with('success', 'Password reset successfully. You can now login with your new password.');
        }

        return back()->withErrors(['email' => 'Student not found.']);
    }
}