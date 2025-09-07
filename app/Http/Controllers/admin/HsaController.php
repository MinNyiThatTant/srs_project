<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HsaController extends Controller
{
    public function index()
    {
        return view('admin.hsa.dashboard');
    }

    public function staffManagement()
    {
        return view('admin.hsa.staff-management');
    }
}