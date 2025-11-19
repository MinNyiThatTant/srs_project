<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Admin middleware
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
        'admin.redirect' => \App\Http\Middleware\AdminRedirect::class,
        
        // Admin role middleware
        'global.admin' => \App\Http\Middleware\GlobalAdmin::class,
        'fa.admin' => \App\Http\Middleware\FaAdmin::class,
        'haa.admin' => \App\Http\Middleware\HaaAdmin::class,
        'hod.admin' => \App\Http\Middleware\HodAdmin::class,
        'hsa.admin' => \App\Http\Middleware\HsaAdmin::class,
        'teacher.admin' => \App\Http\Middleware\TeacherAdmin::class,
    ];
}