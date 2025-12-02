<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        // Sample recent activities - you can replace this with actual activity logs
        $recentActivities = [
            [
                'action' => 'Logged in to portal',
                'date' => now()->format('M d, Y H:i'),
                'icon' => 'bi-box-arrow-in-right',
                'color' => 'primary'
            ],
            [
                'action' => 'Updated profile information',
                'date' => now()->subHours(2)->format('M d, Y H:i'),
                'icon' => 'bi-person',
                'color' => 'info'
            ],
            [
                'action' => 'Viewed academic information',
                'date' => now()->subDays(1)->format('M d, Y H:i'),
                'icon' => 'bi-journal-text',
                'color' => 'success'
            ]
        ];

        return view('student.dashboard.index', compact('student', 'recentActivities'));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.index', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($student->profile_picture) {
                Storage::delete('student-profiles/' . $student->profile_picture);
            }

            $filename = 'student_' . $student->id . '_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->storeAs('student-profiles', $filename);
            $validated['profile_picture'] = $filename;
        }

        $student->update($validated);

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show payment history
     */
    public function paymentHistory()
    {
        $student = Auth::guard('student')->user();
        $payments = $student->payments()->latest()->get();

        return view('student.payments.index', compact('student', 'payments'));
    }

    /**
     * Show academic information
     */
    public function academicInfo()
    {
        $student = Auth::guard('student')->user();
        return view('student.academic.index', compact('student'));
    }

    /**
     * Show fee information
     */
    public function feesInfo()
    {
        $student = Auth::guard('student')->user();
        return view('student.fees.index', compact('student'));
    }

    /**
     * Show documents
     */
    public function documents()
    {
        $student = Auth::guard('student')->user();
        return view('student.documents.index', compact('student'));
    }
}