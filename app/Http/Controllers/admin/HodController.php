<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.hod.dashboard', [
            'department' => $user->department
        ]);
    }

    public function myDepartment()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.hod.department', [
            'department' => $user->department
        ]);
    }
}