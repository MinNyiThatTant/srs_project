<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HaaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-academic', compact('stats'));
    }

    private function getDashboardStats()
    {
        $stats = [
            'pending_reviews' => Application::where('status', 'payment_verified')
                ->where('payment_status', 'verified')
                ->count(),
            'approved_today' => Application::where('status', 'academic_approved')
                ->whereDate('updated_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'academic_rejected'])
                ->count(),
            'recent_applications' => Application::where('status', 'payment_verified')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return $stats;
    }

    public function academicApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $applications = Application::where('status', 'payment_verified')
            ->where('payment_status', 'verified')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.academic', compact('applications'));
    }

    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved'
        ]);

        return redirect()->back()->with('success', 'Application academically approved');
    }

    public function academicReject($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_rejected'
        ]);

        return redirect()->back()->with('success', 'Application academically rejected');
    }

    public function academicAffairs()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        return view('admin.academic.affairs');
    }

    public function courseManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        return view('admin.courses.management');
    }

    public function approveAcademic($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved'
        ]);

        return redirect()->back()->with('success', 'Application academically approved');
    }
}