<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role constants
    const ROLE_GLOBAL_ADMIN = 'global_admin';
    const ROLE_HOD_ADMIN = 'hod_admin';
    const ROLE_HAA_ADMIN = 'haa_admin';
    const ROLE_HSA_ADMIN = 'hsa_admin';
    const ROLE_TEACHER_ADMIN = 'teacher_admin';
    const ROLE_FA_ADMIN = 'fa_admin';
    const ROLE_STUDENT = 'student';

    // Check methods for all roles
    public function isGlobalAdmin() { return $this->role === self::ROLE_GLOBAL_ADMIN; }
    public function isHodAdmin() { return $this->role === self::ROLE_HOD_ADMIN; }
    public function isHaaAdmin() { return $this->role === self::ROLE_HAA_ADMIN; }
    public function isHsaAdmin() { return $this->role === self::ROLE_HSA_ADMIN; }
    public function isTeacherAdmin() { return $this->role === self::ROLE_TEACHER_ADMIN; }
    public function isFaAdmin() { return $this->role === self::ROLE_FA_ADMIN; }
    public function isStudent() { return $this->role === self::ROLE_STUDENT; }

    // Get all possible roles
    public static function getRoles()
    {
        return [
            self::ROLE_GLOBAL_ADMIN => 'Global Administrator',
            self::ROLE_HOD_ADMIN => 'Head of Department',
            self::ROLE_HAA_ADMIN => 'Head of Academic Affairs',
            self::ROLE_HSA_ADMIN => 'Head of Staff Affairs',
            self::ROLE_TEACHER_ADMIN => 'Teacher Administrator',
            self::ROLE_FA_ADMIN => 'Finance Administrator',
            self::ROLE_STUDENT => 'Student',
        ];
    }

    // Relationship to department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}