<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show home page with login/apply options
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Department page
     */
    public function department()
    {
        return view('home.department');
    }

    /**
     * Courses page
     */
    public function courses()
    {
        return view('home.courses');
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return view('home.contact');
    }

    /**
     * About page
     */
    public function about()
    {
        return view('home.about');
    }
}