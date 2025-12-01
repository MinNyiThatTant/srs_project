<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'student_id',
        'application_id',
        'name',
        'email',
        'phone',
        'password',
        'department',
        'date_of_birth',
        'gender',
        'nrc_number',
        'address',
        'status',
        'registration_date',
        'academic_year',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'datetime',
        // 'last_login_at' => 'datetime',
    ];

    // Relationship with application
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    // Relationship with payments through application
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Application::class, 'id', 'application_id', 'application_id', 'id');
    }

    // Get username for authentication
    public function getAuthIdentifierName()
    {
        return 'student_id';
    }

    public function getAuthIdentifier()
    {
        return $this->student_id;
    }

    /**
     * Get the department name
     */
    public function getDepartmentNameAttribute()
    {
        $departments = [
            'Computer Engineering and Information Technology' => 'CEIT',
            'Civil Engineering' => 'Civil',
            'Electronics Engineering' => 'Electronics',
            'Electrical Power Engineering' => 'Electrical Power',
            'Mechanical Engineering' => 'Mechanical',
            'Chemical Engineering' => 'Chemical',
            'Architecture' => 'Architecture',
            'Biotechnology' => 'Biotechnology',
            'Textile Engineering' => 'Textile',
        ];

        return $departments[$this->department] ?? $this->department;
    }

    /**
     * Get formatted registration date
     */
    public function getFormattedRegistrationDateAttribute()
    {
        return $this->registration_date ? $this->registration_date->format('M d, Y') : 'N/A';
    }

    /**
     * Get formatted date of birth
     */
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->format('M d, Y') : 'N/A';
    }

    /**
     * Check if student is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}