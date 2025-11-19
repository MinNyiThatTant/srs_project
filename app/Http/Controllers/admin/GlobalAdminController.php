<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalAdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-global', compact('stats'));
    }

    private function getDashboardStats()
    {
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $totalUsers = User::count();
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('status', 'active')->count();

        $recentApplications = Application::with('payments')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $paymentStats = [
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return [
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'total_payments' => $totalPayments,
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'active_admins' => $activeAdmins,
            'recent_applications' => $recentApplications,
            'payment_stats' => $paymentStats,
        ];
    }

    public function allApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $applications = Application::with(['payments', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.all', compact('applications'));
    }

    public function users()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $users = User::with('applications')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function viewUser($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $user = User::with('applications.payments')->findOrFail($id);
        return view('admin.users.view', compact('user'));
    }

    public function payments()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $payments = Payment::with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
            'total_transactions' => Payment::count(),
            'success_rate' => Payment::where('status', 'completed')->count() / max(Payment::count(), 1) * 100
        ];

        return view('admin.payments.global', compact('payments', 'stats'));
    }

    public function reports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        // Generate reports data
        return view('admin.reports.global');
    }

    public function teachers()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $teachers = Admin::where('role', 'teacher_admin')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Application action methods
    public function verifyPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'payment_status' => 'verified',
            'status' => 'payment_verified'
        ]);

        return redirect()->back()->with('success', 'Payment verified successfully');
    }

    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved'
        ]);

        return redirect()->back()->with('success', 'Application academically approved');
    }

    public function finalApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Application finally approved');
    }

    public function rejectApplication($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'rejected'
        ]);

        return redirect()->back()->with('success', 'Application rejected');
    }

    public function bulkActions(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        // Handle bulk actions
        return redirect()->back()->with('success', 'Bulk action completed');
    }
}