<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple response for testing
        // return response()->json([
        //     'message' => 'Dashboard accessed successfully',
        //     'user' => Auth::user()->email,
        //     'authenticated' => Auth::check()
        // ]);

        return view('home.dashboard');
    }
}