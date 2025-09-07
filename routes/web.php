<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\GlobalAdminController;
use App\Http\Controllers\admin\HodController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('department', [HomeController::class, 'department'])->name('department');
Route::get('courses', [HomeController::class, 'courses'])->name('courses');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('about', [HomeController::class, 'about'])->name('about');

//department1
Route::get('department1',[DeptController::class,'department1'])->name('home.department1');

//courses
Route::get('/c1',[DeptController::class,'c1'])->name('home.coursecode');
Route::get('/c2',[DeptController::class,'c2'])->name('home.coursecode1');
Route::get('/c3',[DeptController::class,'c3'])->name('home.coursecode2');
Route::get('/c4',[DeptController::class,'c4'])->name('home.coursecode3');
Route::get('/c5',[DeptController::class,'c5'])->name('home.coursecode4');
Route::get('/c6',[DeptController::class,'c6'])->name('home.coursecode5');
Route::get('/c7',[DeptController::class,'c7'])->name('home.coursecode6');


// University info
Route::get('univ-info', function () {
    $universityInfo = [
        'name' => 'West Yangon Technological University',
        'motto' => 'Innovation Through Technology',
        'founded' => 1999,
        'location' => 'Yangon, Myanmar',
        'website' => 'https://www.wytu.edu.mm',
        'students' => 25000,
        'faculty' => 200,
        'logo' => 'images/logo.png',
        'accreditations' => ['MOE Accredited', 'ASEAN University Network'],
        'departments' => [
            'Civil',
            'CEIT',
            'Electronics',
            'Electrical Power',
            'Architecture',
            'Biotechnology',
            'Textile',
            'Mechanical',
            'Chemical'
        ]
    ];
    return view('home.univ-info', compact('universityInfo'));
})->name('univ-info');

// Student routes (guest middleware)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::post('process-register', [LoginController::class, 'processRegister'])->name('processRegister');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
});

// Student authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin routes (guest middleware)
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// Admin authenticated routes - main group
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Global admin only routes - nested group
    Route::middleware(['global.admin'])->group(function () {
        Route::get('admin/global', [GlobalAdminController::class, 'index'])->name('admin.global');
        Route::get('admin/users', [GlobalAdminController::class, 'users'])->name('admin.users');
    });    
    
    // HOD admin only routes - nested group
    Route::middleware(['hod.admin'])->group(function () {
        Route::get('admin/hod', [HodController::class, 'index'])->name('admin.hod');
        Route::get('admin/my-department', [HodController::class, 'myDepartment'])->name('admin.my-department');
    });
});

// Other home routes
Route::get('choose-login', function () {
    return view('home.choose-login');
})->name('choose.login');