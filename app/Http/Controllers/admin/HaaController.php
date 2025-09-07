<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HaaController extends Controller
{
    public function index()
    {
        return view('admin.haa.dashboard');
    }

    public function academicAffairs()
    {
        return view('admin.haa.academic-affairs');
    }
}