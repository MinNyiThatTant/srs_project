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
        'academic_year',
        'date_of_birth',
        'gender',
        'nrc_number',
        'address',
        'status',
        'registration_date',
        'academic_year',
        'last_login_at',
        'profile_picture',
        'needs_password_change',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'datetime',
        'last_login_at' => 'datetime',
        'needs_password_change' => 'boolean',
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
     * Get profile picture URL
     */
    public function getProfilePictureUrlAttribute()
{
    if ($this->profile_picture && Storage::exists('student-profiles/' . $this->profile_picture)) {
        return asset('storage/student-profiles/' . $this->profile_picture);
    }
    // Use a default avatar from a CDN or local file
    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
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

    /**
     * Scope active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}