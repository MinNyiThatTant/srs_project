<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentAuthController extends Controller
{
    /**
     * Show student login form
     */
    public function showLoginForm()
    {
        return view('student.auth.login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        Log::info('Student login attempt', [
            'student_id' => $request->student_id,
            'ip' => $request->ip()
        ]);

        $credentials = $request->only('student_id', 'password');

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
            'student_id' => 'The provided credentials do not match our records.',
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

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $student->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $student->update([
            'password' => Hash::make($request->new_password),
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
        $request->validate(['email' => 'required|email']);

        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return back()->withErrors(['email' => 'No student found with this email address.']);
        }

        // In a real application, you would send an email here
        Log::info('Password reset requested', [
            'student_id' => $student->student_id,
            'email' => $student->email
        ]);

        return back()->with('status', 'If your email exists in our system, a password reset link has been sent.');
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