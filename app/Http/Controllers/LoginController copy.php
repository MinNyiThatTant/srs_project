<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('home.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Check if user is student (not admin)
                if (Auth::user()->role === 'student') {
                    $request->session()->regenerate();
                    return redirect()->intended(route('dashboard'));
                } else {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Please use admin login for admin access');
                }
            } else {
                return redirect()->route('login')->with('error', 'Either email or password is incorrect');
            }
        } else {
            return redirect()->route('login')
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function register(Request $request)
    {
        return view('home.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'student';
            $user->save();

            return redirect()->route('login')->with('success', 'You have registered successfully');
        } else {
            return redirect()->route('register')
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully');
    }

    public function chooseLogin()
    {

        return redirect()->route('home.choose-login');
    }
}
