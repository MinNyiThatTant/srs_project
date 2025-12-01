<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HodAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'hod_admin') {
            return $next($request);
        }
        
        return redirect()->route('admin.dashboard')->with('error', 'Access denied. HOD admin only.');
    }
}