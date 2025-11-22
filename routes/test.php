<?php
// routes/web.php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\FaController;
use App\Http\Controllers\admin\GlobalAdminController;
use App\Http\Controllers\admin\HaaController;
use App\Http\Controllers\admin\HodController;
use App\Http\Controllers\admin\HsaController;
use App\Http\Controllers\admin\TeacherController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\ApplicationApprovalController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// ... your existing home routes, application routes, payment routes ...

// ========== ADMIN ROUTES ==========

// Admin login routes for guests
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// Main admin routes group
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // MAIN DASHBOARD
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // ========== GLOBAL ADMIN ROUTES ==========
    Route::get('global-dashboard', [GlobalAdminController::class, 'dashboard'])->name('global.dashboard');
    Route::get('global', [GlobalAdminController::class, 'dashboard'])->name('global');
    Route::get('applications/all', [GlobalAdminController::class, 'allApplications'])->name('applications.all');
    Route::get('applications/view/{id}', [GlobalAdminController::class, 'viewApplication'])->name('applications.view');
    Route::get('users', [GlobalAdminController::class, 'users'])->name('users');
    Route::get('users/{id}', [GlobalAdminController::class, 'viewUser'])->name('users.view');
    Route::get('payments/global', [GlobalAdminController::class, 'payments'])->name('global.payments');
    Route::get('reports/global', [GlobalAdminController::class, 'reports'])->name('global.reports');
    Route::get('teachers', [GlobalAdminController::class, 'teachers'])->name('teachers.index');
    Route::post('applications/{id}/verify-payment', [GlobalAdminController::class, 'verifyPayment'])->name('applications.verify-payment');
    Route::post('applications/{id}/academic-approve', [GlobalAdminController::class, 'academicApprove'])->name('applications.academic-approve');
    Route::post('applications/{id}/final-approve', [GlobalAdminController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('applications/{id}/reject', [GlobalAdminController::class, 'rejectApplication'])->name('applications.reject');
    Route::post('bulk-actions', [GlobalAdminController::class, 'bulkActions'])->name('bulk-actions');

    // ========== FINANCE ADMIN ROUTES ==========
    Route::middleware(['fa_admin'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('dashboard', [FaController::class, 'dashboard'])->name('dashboard');
        
        // Payment Verification
        Route::get('pending-verifications', [FaController::class, 'pendingVerifications'])->name('pending-verifications');
        Route::post('verify-payment/{id}', [FaController::class, 'verifyPayment'])->name('verify-payment');
        
        // Application Approval
        Route::get('payment-verified', [FaController::class, 'paymentVerifiedApplications'])->name('payment-verified');
        Route::post('approve-application/{id}', [FaController::class, 'approveApplication'])->name('approve-application');
        Route::post('reject-application/{id}', [FaController::class, 'rejectApplication'])->name('reject-application');
        
        // Views
        Route::get('application/{id}/view', [FaController::class, 'viewApplication'])->name('application.view');
        
        // Reports
        Route::get('financial-reports', [FaController::class, 'financialReports'])->name('financial-reports');
        Route::get('pending-payments', [FaController::class, 'pendingPayments'])->name('pending-payments');
        
        // Legacy routes for backward compatibility
        Route::get('applications', [FaController::class, 'financeApplications'])->name('applications');
        Route::get('payment-statistics', [FaController::class, 'paymentStatistics'])->name('payment-statistics');
        Route::get('fee-management', [FaController::class, 'feeManagement'])->name('fee-management');
    });

    // ========== HAA ADMIN ROUTES ==========
    Route::middleware(['haa_admin'])->prefix('academic')->name('academic.')->group(function () {
        Route::get('dashboard', [HaaController::class, 'dashboard'])->name('dashboard');
        
        // Application Approval
        Route::get('applications', [HaaController::class, 'academicApplications'])->name('applications');
        Route::post('approve-application/{id}', [HaaController::class, 'approveApplication'])->name('approve-application');
        Route::post('reject-application/{id}', [HaaController::class, 'rejectApplication'])->name('reject-application');
        
        // Views
        Route::get('application/{id}/view', [HaaController::class, 'viewApplication'])->name('application.view');
        
        // Student Management
        Route::get('students', [HaaController::class, 'studentManagement'])->name('students');
        
        // Academic Affairs
        Route::get('affairs', [HaaController::class, 'academicAffairs'])->name('affairs');
        Route::get('courses', [HaaController::class, 'courseManagement'])->name('courses');
    });

    // ========== HOD ADMIN ROUTES ==========
    Route::get('hod-dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('hod', [HodController::class, 'dashboard'])->name('hod');
    Route::get('applications/hod', [HodController::class, 'hodApplications'])->name('applications.hod');
    Route::post('final-approve/{id}', [HodController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve.final');
    Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
    Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');
    Route::get('hod/staff', [HodController::class, 'staffIndex'])->name('hod.staff.index');
    Route::post('hod/staff', [HodController::class, 'staffStore'])->name('hod.staff.store');
    Route::put('hod/staff/{id}', [HodController::class, 'staffUpdate'])->name('hod.staff.update');
    Route::delete('hod/staff/{id}', [HodController::class, 'staffDestroy'])->name('hod.staff.destroy');

    // ========== HSA ADMIN ROUTES ==========
    Route::get('hsa-dashboard', [HsaController::class, 'dashboard'])->name('hsa.dashboard');
    Route::get('hsa', [HsaController::class, 'dashboard'])->name('hsa');
    Route::get('staff-management', [HsaController::class, 'staffManagement'])->name('staff.management');
    Route::get('teacher-management', [HsaController::class, 'teacherManagement'])->name('teacher.management');
    Route::post('assign-teacher/{id}', [HsaController::class, 'assignTeacher'])->name('assign.teacher');
    
    // ========== TEACHER ADMIN ROUTES ==========
    Route::get('teacher-dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('teacher', [TeacherController::class, 'dashboard'])->name('teacher');
    Route::get('my-students', [TeacherController::class, 'myStudents'])->name('my.students');
    Route::get('student-progress/{id}', [TeacherController::class, 'studentProgress'])->name('student.progress');

    // ========== COMMON ADMIN ROUTES ==========
    Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('applications/{id}', [ApplicationApprovalController::class, 'viewApplication'])->name('applications.view');
});

// ... rest of your routes (student routes, debug routes, etc.)