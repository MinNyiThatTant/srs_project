<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->passes()) {
        if (Auth::guard('admin')->attempt([
            'email' => $request->email, 
            'password' => $request->password
        ])) {
            // Check if the user has any admin role
            $user = Auth::guard('admin')->user();
            $adminRoles = ['global_admin', 'hod_admin', 'haa_admin', 'hsa_admin', 'teacher_admin', 'fa_admin'];
            
            if (in_array($user->role, $adminRoles)) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'You are not authorized as admin');
            }
        } else {
            return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect');
        }
    } else {
        return redirect()->route('admin.login')
            ->withInput()
            ->withErrors($validator);
    }
}

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}