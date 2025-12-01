<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuth
{
    public function handle(Request $request, Closure $next, $guard = 'student')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('student.login');
        }

        return $next($request);
    }
}