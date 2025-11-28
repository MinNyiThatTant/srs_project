<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Update last login
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);

        // Redirect based on admin role to their specific dashboard
        switch ($admin->role) {
            case 'global_admin':
                return redirect()->route('admin.global.dashboard');
            case 'fa_admin':
                return redirect()->route('admin.finance.dashboard');
            case 'haa_admin':
                return redirect()->route('admin.academic.dashboard');
            case 'hod_admin':
                return redirect()->route('admin.hod.dashboard');
            case 'hsa_admin':
                return redirect()->route('admin.hsa.dashboard');
            case 'teacher_admin':
                return redirect()->route('admin.teacher.dashboard');
            default:
                // Fallback to basic dashboard
                $stats = $this->getBasicStats();
                return view('admin.dashboard', compact('stats'));
        }
    }

    private function getBasicStats()
    {
        return [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'total_users' => User::count(),
        ];
    }

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}