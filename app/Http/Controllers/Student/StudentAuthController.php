<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    /**
     * Show student login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        return view('student.auth.login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'student_id.required' => 'Student ID is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Log::info('Student login attempt', [
            'student_id' => $request->student_id,
            'ip' => $request->ip()
        ]);

        $credentials = [
            'student_id' => $request->student_id,
            'password' => $request->password,
            'status' => 'active'
        ];

        if (Auth::guard('student')->attempt($credentials, $request->remember)) {
            $student = Auth::guard('student')->user();
            
            // Update last login
            $student->update([
                'last_login_at' => now()
            ]);

            Log::info('Student login successful', [
                'student_id' => $student->student_id,
                'name' => $student->name
            ]);

            return redirect()->intended(route('student.dashboard'))
                ->with('success', 'Welcome back, ' . $student->name . '!');
        }

        Log::warning('Student login failed', [
            'student_id' => $request->student_id,
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'student_id' => 'Invalid student ID or password.',
        ])->withInput($request->only('student_id', 'remember'));
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        $student = Auth::guard('student')->user();
        return view('student.auth.change-password', compact('student'));
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $student->update([
            'password' => Hash::make($request->new_password),
            'needs_password_change' => false
        ]);

        Log::info('Student password changed', [
            'student_id' => $student->student_id
        ]);

        return redirect()->route('student.dashboard')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('student.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::where('email', $request->email)->active()->first();

        if (!$student) {
            return back()->withErrors(['email' => 'No active student found with this email address.']);
        }

        // Generate reset token
        $token = \Str::random(60);
        
        // Store token in database
        \DB::table('student_password_resets')->updateOrInsert(
            ['email' => $student->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send reset email
        try {
            \Mail::send('student.auth.reset-password-email', [
                'student' => $student,
                'token' => $token,
                'reset_url' => route('student.reset.password', ['token' => $token, 'email' => $student->email])
            ], function ($message) use ($student) {
                $message->to($student->email)
                        ->subject('Password Reset Request - WYTU Student Portal');
            });

            Log::info('Password reset email sent', [
                'student_id' => $student->student_id,
                'email' => $student->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'student_id' => $student->student_id,
                'error' => $e->getMessage()
            ]);
        }

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
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify token
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
        $student = Student::where('email', $request->email)->active()->first();
        if ($student) {
            $student->update([
                'password' => Hash::make($request->password),
                'needs_password_change' => false
            ]);

            // Delete used token
            \DB::table('student_password_resets')->where('email', $request->email)->delete();

            Log::info('Student password reset successful', [
                'student_id' => $student->student_id
            ]);

            return redirect()->route('student.login')
                ->with('success', 'Password reset successfully. You can now login with your new password.');
        }

        return back()->withErrors(['email' => 'Student not found.']);
    }

    /**
     * Handle student logout
     */
    public function logout(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Student logged out', [
            'student_id' => $student->student_id ?? 'Unknown'
        ]);

        return redirect()->route('student.login')
            ->with('success', 'You have been logged out successfully.');
    }
}