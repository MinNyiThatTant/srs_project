<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'teacher_admin') {
            abort(403, 'Access denied. Teacher admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-teacher', compact('stats'));
    }

    private function getDashboardStats()
    {
        $stats = [
            'total_students' => User::count(),
            'my_students' => 25, // Mock data - replace with actual logic
            'pending_evaluations' => 8, // Mock data
            'completed_evaluations' => 45 // Mock data
        ];

        return $stats;
    }

    public function myStudents()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'teacher_admin') {
            abort(403, 'Access denied. Teacher admin only.');
        }

        // Get students assigned to this teacher
        $students = User::with('applications')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.teacher.students', compact('students'));
    }

    public function studentProgress($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'teacher_admin') {
            abort(403, 'Access denied. Teacher admin only.');
        }

        $student = User::with(['applications', 'applications.payments'])->findOrFail($id);
        return view('admin.teacher.student-progress', compact('student'));
    }
}