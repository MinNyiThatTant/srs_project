<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;

class GlobalAdminController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalDepartments' => Department::count(),
            'totalHods' => User::where('role', 'hod_admin')->count(),
            'totalStudents' => User::where('role', 'student')->count()
        ];
        
        return view('admin.global.dashboard', $stats);
    }

    public function users()
    {
        $users = User::with('department')->get();
        return view('admin.global.users', compact('users'));
    }
}