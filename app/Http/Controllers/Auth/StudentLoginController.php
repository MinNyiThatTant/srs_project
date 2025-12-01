<?php
// app/Http/Controllers/Auth/StudentLoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentLoginController extends Controller
{
    /**
     * Show student login form
     */
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to authenticate student
        if (Auth::guard('student')->attempt([
            'student_id' => $request->student_id, 
            'password' => $request->password
        ])) {
            $student = Auth::guard('student')->user();
            
            if ($student->isApproved()) {
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'));
            } else {
                Auth::guard('student')->logout();
                return redirect()->back()->with('error', 'Your account is not yet activated.');
            }
        }

        return redirect()->back()
            ->with('error', 'Invalid student ID or password.')
            ->withInput();
    }

    /**
     * Student logout
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')->with('success', 'You have been logged out successfully.');
    }
}