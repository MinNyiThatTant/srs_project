<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        return view('admin.teacher.dashboard');
    }

    public function teacherManagement()
    {
        return view('admin.teacher.management');
    }
}