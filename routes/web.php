<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\FaController;
use App\Http\Controllers\admin\GlobalAdminController;
use App\Http\Controllers\admin\HaaController;
use App\Http\Controllers\admin\HodController;
use App\Http\Controllers\admin\HsaController;
use App\Http\Controllers\admin\TeacherController;
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

// Admin authenticated routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // Global admin routes
    Route::middleware(['global.admin'])->group(function () {
        Route::get('admin/global', [GlobalAdminController::class, 'index'])->name('admin.global');
        Route::get('admin/users', [GlobalAdminController::class, 'users'])->name('admin.users');
    });    
    
    // HOD admin routes
    Route::middleware(['hod.admin'])->group(function () {
        Route::get('admin/hod', [HodController::class, 'index'])->name('admin.hod.dashboard');
        Route::get('admin/my-department', [HodController::class, 'myDepartment'])->name('admin.my-department');
    });

    // HAA admin routes
    Route::middleware(['haa.admin'])->group(function () {
        Route::get('admin/haa', [HaaController::class, 'index'])->name('admin.haa');
        Route::get('admin/academic-affairs', [HaaController::class, 'academicAffairs'])->name('admin.academic-affairs');
    });

    // HSA admin routes
    Route::middleware(['hsa.admin'])->group(function () {
        Route::get('admin/hsa', [HsaController::class, 'index'])->name('admin.hsa');
        Route::get('admin/staff-management', [HsaController::class, 'staffManagement'])->name('admin.staff-management');
    });

    // Teacher admin routes
    Route::middleware(['teacher.admin'])->group(function () {
        Route::get('admin/teacher', [TeacherController::class, 'index'])->name('admin.teacher');
        Route::get('admin/teacher-management', [TeacherController::class, 'teacherManagement'])->name('admin.teacher-management');
    });

    // Finance admin routes
    Route::middleware(['fa.admin'])->group(function () {
        Route::get('admin/fa', [FaController::class, 'index'])->name('admin.fa');
        Route::get('admin/financial-reports', [FaController::class, 'financialReports'])->name('admin.financial-reports');
    });
});

// Other home routes
Route::get('choose-login', function () {
    return view('home.choose-login');
})->name('choose.login');