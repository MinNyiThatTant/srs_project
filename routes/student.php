<?php

use App\Http\Controllers\student\DashboardController;
use App\Http\Controllers\student\LoginController;
use Illuminate\Support\Facades\Route;

// Student Authentication Routes
Route::middleware(['guest:student'])->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->name('authenticate');
    
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::post('register', [LoginController::class, 'processRegister'])->name('processRegister');
    
    // Password Reset Routes
    Route::get('forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [LoginController::class, 'sendPasswordResetLink'])->name('password.email');
    
    Route::get('reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

// Student Authenticated Routes
Route::middleware(['auth:student'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Password Change (for logged-in students)
    Route::get('change-password', [DashboardController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('change-password', [DashboardController::class, 'changePassword'])->name('password.change.post');
    
    // Academic Info
    Route::get('academic-info', [DashboardController::class, 'academicInfo'])->name('academic.info');
    
    // Fees Info
    Route::get('fees', [DashboardController::class, 'feesInfo'])->name('fees.info');
    
    // Documents
    Route::get('documents', [DashboardController::class, 'documents'])->name('documents');
    
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});