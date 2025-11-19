<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HsaAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'hsa_admin') {
            return $next($request);
        }
        
        return redirect()->route('admin.dashboard')->with('error', 'Access denied. HSA admin only.');
    }
}