<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('home.index');
    }

    public function department()
    {
        return view('home.department'); 
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function about()
    {
        return view('home.about'); // Make sure this view exists
    }

    public function courses()
    {
        return view('home.courses'); // Make sure this view exists
    }

}
