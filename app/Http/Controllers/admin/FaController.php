<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaController extends Controller
{
    public function index()
    {
        return view('admin.fa.dashboard');
    }

    public function financialReports()
    {
        return view('admin.fa.financial-reports');
    }
}