<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
        // Check if student is logged in
        if (!session('student')) {
            return redirect()->route('student.login')->with('error', 'Please login as student');
        }

        $student = session('student');
        $application = Application::where('student_id', $student['student_id'])->first();

        return view('student.dashboard', compact('student', 'application'));
    }

    public function profile()
    {
        if (!session('student')) {
            return redirect()->route('student.login');
        }

        $student = session('student');
        $application = Application::where('student_id', $student['student_id'])->first();

        return view('student.profile', compact('student', 'application'));
    }

    public function applicationStatus()
    {
        if (!session('student')) {
            return redirect()->route('student.login');
        }

        $student = session('student');
        $application = Application::where('student_id', $student['student_id'])->first();

        return view('student.application-status', compact('student', 'application'));
    }
}