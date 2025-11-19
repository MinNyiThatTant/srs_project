<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'phone',
        'status',
        'last_login_at',
        'last_login_ip'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Role-based scopes
    public function scopeGlobalAdmins($query)
    {
        return $query->where('role', 'global_admin');
    }

    public function scopeFinanceAdmins($query)
    {
        return $query->where('role', 'fa_admin');
    }

    public function scopeAcademicAdmins($query)
    {
        return $query->where('role', 'haa_admin');
    }

    public function scopeHodAdmins($query)
    {
        return $query->where('role', 'hod_admin');
    }

    public function scopeHsaAdmins($query)
    {
        return $query->where('role', 'hsa_admin');
    }

    public function scopeTeacherAdmins($query)
    {
        return $query->where('role', 'teacher_admin');
    }

    // Role check methods
    public function isGlobalAdmin()
    {
        return $this->role === 'global_admin';
    }

    public function isFinanceAdmin()
    {
        return $this->role === 'fa_admin';
    }

    public function isAcademicAdmin()
    {
        return $this->role === 'haa_admin';
    }

    public function isHodAdmin()
    {
        return $this->role === 'hod_admin';
    }

    public function isHsaAdmin()
    {
        return $this->role === 'hsa_admin';
    }

    public function isTeacherAdmin()
    {
        return $this->role === 'teacher_admin';
    }

    // Check if admin is active
    public function isActive()
    {
        return $this->status === 'active';
    }
}