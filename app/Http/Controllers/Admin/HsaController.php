<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HsaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-hsa', compact('stats')); // Changed to admin.dashboard-hsa
    }

    private function getDashboardStats()
    {
        $totalStudents = User::count();
        $lastMonthStudents = User::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $studentGrowth = $lastMonthStudents > 0 ? round(($totalStudents - $lastMonthStudents) / $lastMonthStudents * 100, 1) : 0;

        $activeApplications = Application::whereIn('status', ['pending', 'payment_verified', 'academic_approved'])->count();
        $pendingReview = Application::where('status', 'payment_verified')->count();

        // Mock data for demonstration
        $pendingIssues = 12;
        $urgentIssues = 3;

        $resolvedToday = Application::where('status', 'approved')
            ->whereDate('updated_at', today())
            ->count();

        $resolutionRate = $totalStudents > 0 ? round(($resolvedToday / $totalStudents) * 100, 1) : 0;

        // Recent activities - FIXED: Removed user relationship
        $recentApplications = Application::orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function($app) {
                return (object)[
                    'name' => $app->name,
                    'email' => $app->email,
                    'application_id' => $app->application_id,
                    'activity_type' => 'Application Submission',
                    'department' => $app->department,
                    'status' => $app->status,
                    'status_color' => $this->getStatusColor($app->status),
                    'created_at' => $app->created_at
                ];
            });

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return (object)[
                    'name' => $user->name,
                    'email' => $user->email,
                    'activity_type' => 'Student Registration',
                    'department' => 'N/A',
                    'status' => 'active',
                    'status_color' => 'success',
                    'created_at' => $user->created_at
                ];
            });

        $recentActivities = $recentApplications->merge($recentUsers)
            ->sortByDesc('created_at')
            ->take(8);

        // Department statistics
        $departmentStats = [
            'ceit' => Application::where('department', 'like', '%Computer%')->count(),
            'civil' => Application::where('department', 'like', '%Civil%')->count(),
            'electronics' => Application::where('department', 'like', '%Electronics%')->count(),
            'mechanical' => Application::where('department', 'like', '%Mechanical%')->count(),
        ];

        // System alerts
        $systemAlerts = $this->getSystemAlerts();

        // Recent notifications
        $recentNotifications = $this->getRecentNotifications();

        return [
            'total_students' => $totalStudents,
            'student_growth' => $studentGrowth,
            'active_applications' => $activeApplications,
            'pending_review' => $pendingReview,
            'pending_issues' => $pendingIssues,
            'urgent_issues' => $urgentIssues,
            'resolved_today' => $resolvedToday,
            'resolution_rate' => $resolutionRate,
            'recent_activities' => $recentActivities,
            'department_stats' => $departmentStats,
            'system_alerts' => $systemAlerts,
            'alerts_count' => count($systemAlerts),
            'recent_notifications' => $recentNotifications,
        ];
    }

    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'payment_verified' => 'info',
            'academic_approved' => 'success',
            'approved' => 'success',
            'rejected' => 'danger',
            'payment_pending' => 'secondary'
        ];

        return $colors[$status] ?? 'primary';
    }

    private function getSystemAlerts()
    {
        return [
            [
                'type' => 'warning',
                'title' => 'High Application Volume',
                'message' => '25+ applications pending review'
            ],
            [
                'type' => 'info',
                'title' => 'System Maintenance',
                'message' => 'Scheduled maintenance this weekend'
            ]
        ];
    }

    private function getRecentNotifications()
    {
        return [
            [
                'type' => 'success',
                'icon' => 'payment',
                'title' => 'Payment Verified',
                'message' => '5 payments were automatically verified',
                'time' => '2 minutes ago'
            ],
            [
                'type' => 'info',
                'icon' => 'application',
                'title' => 'New Applications',
                'message' => '3 new applications received',
                'time' => '15 minutes ago'
            ]
        ];
    }

    public function staffManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }

        $staff = Admin::whereIn('role', ['hod_admin', 'teacher_admin', 'haa_admin', 'fa_admin'])->get();
        return view('admin.staff.management', compact('staff')); // Changed to admin.staff.management
    }

    public function teacherManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }

        $teachers = Admin::where('role', 'teacher_admin')->get();
        return view('admin.teacher.management', compact('teachers')); // Changed to admin.teacher.management
    }

    public function assignTeacher(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }

        $request->validate([
            'teacher_id' => 'required|exists:admins,id'
        ]);

        // Assign teacher logic here
        // Example: Update application or course assignment

        return redirect()->back()->with('success', 'Teacher assigned successfully');
    }

    // Additional methods for HSA functionality
    public function studentManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }

        $students = User::with('applications')->latest()->paginate(20);
        return view('admin.student.management', compact('students'));
    }

    public function viewStudent($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'hsa_admin') {
            abort(403, 'Access denied. HSA admin only.');
        }

        $student = User::with('applications')->findOrFail($id);
        return view('admin.student.view', compact('student'));
    }
}