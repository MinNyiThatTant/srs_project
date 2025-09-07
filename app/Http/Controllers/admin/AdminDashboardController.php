<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
{
    $user = Auth::guard('admin')->user();
    
    switch ($user->role) {
        case 'global_admin':
            return view('admin.dashboard-global');
        case 'hod_admin':
            return view('admin.dashboard-hod', ['department' => $user->department]);
        case 'haa_admin':
            return view('admin.haa.dashboard');
        case 'hsa_admin':
            return view('admin.hsa.dashboard');
        case 'teacher_admin':
            return view('admin.teacher.dashboard');
        case 'fa_admin':
            return view('admin.fa.dashboard');
        default:
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Unauthorized access');
    }
}
}