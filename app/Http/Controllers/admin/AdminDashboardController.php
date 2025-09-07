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
        
        if ($user->role === 'global_admin') {
            return view('admin.dashboard-global');
        } elseif ($user->role === 'hod_admin') {
            return view('admin.dashboard-hod', [
                'department' => $user->department
            ]);
        }
        
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('error', 'Unauthorized access');
    }
}