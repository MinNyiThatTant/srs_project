<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('student')) {
            return redirect()->route('student.login')->with('error', 'Please login as student');
        }

        return $next($request);
    }
}