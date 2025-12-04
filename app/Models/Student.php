<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'current_year',
        'academic_year',
        'academic_standing',
        'cgpa',
        'status',
        'registration_date',
        'last_login_at',
        'profile_picture',
        'needs_password_change',
        'remember_token',
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

    protected $appends = [
        'department_name',
        'formatted_registration_date',
        'formatted_date_of_birth',
        'profile_picture_url',
        'year_name',
        'status_badge',
        'year_number',
        'next_academic_year',
        'next_year_name',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship with application (the application that created this student)
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Relationship with all applications (for old students)
     */
    public function applications()
    {
        return $this->hasMany(Application::class, 'student_original_id');
    }

    /**
     * Relationship with Academic History
     */
    public function academicHistory()
    {
        return $this->hasMany(StudentAcademicHistory::class);
    }

    /**
     * Relationship with payments through application
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class, 
            Application::class, 
            'student_original_id', // Foreign key on Application table
            'application_id',      // Foreign key on Payment table
            'id',                  // Local key on Student table
            'id'                   // Local key on Application table
        );
    }

    // ==================== AUTHENTICATION METHODS ====================

    /**
     * Get username for authentication
     */
    public function getAuthIdentifierName()
    {
        return 'student_id';
    }

    public function getAuthIdentifier()
    {
        return $this->student_id;
    }

    // ==================== ATTRIBUTE ACCESSORS ====================

    /**
     * Get department name
     */
    public function getDepartmentNameAttribute()
    {
        $departments = [
            'Civil Engineering' => 'CE',
            'Computer Engineering and Information Technology' => 'CEIT',
            'Electronic Engineering' => 'EE',
            'Electrical Power Engineering' => 'EPE',
            'Architecture' => 'ARCH',
            'Biotechnology' => 'BIO',
            'Textile Engineering' => 'TEX',
            'Mechanical Engineering' => 'ME',
            'Chemical Engineering' => 'CHE',
            'Automobile Engineering' => 'AE',
            'Mechatronic Engineering' => 'MCE',
            'Metallurgy Engineering' => 'MET',
        ];

        return $departments[$this->department] ?? $this->department;
    }

    /**
     * Get full department name with code
     */
    public function getFullDepartmentAttribute()
    {
        $code = $this->department_name;
        return $code ? "{$this->department} ({$code})" : $this->department;
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
        // Use a default avatar from a CDN
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
     * Get age
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Get year name
     */
    public function getYearNameAttribute()
    {
        $yearNames = [
            'first_year' => 'First Year',
            'second_year' => 'Second Year',
            'third_year' => 'Third Year',
            'fourth_year' => 'Fourth Year',
            'fifth_year' => 'Fifth Year',
        ];

        return $yearNames[$this->current_year] ?? 'Unknown Year';
    }

    /**
     * Get year number
     */
    public function getYearNumberAttribute()
    {
        $yearNumbers = [
            'first_year' => 1,
            'second_year' => 2,
            'third_year' => 3,
            'fourth_year' => 4,
            'fifth_year' => 5,
        ];

        return $yearNumbers[$this->current_year] ?? 1;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'badge bg-success',
            'inactive' => 'badge bg-secondary',
            'suspended' => 'badge bg-danger',
            'graduated' => 'badge bg-info',
            'pending' => 'badge bg-warning',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
            'graduated' => 'Graduated',
            'pending' => 'Pending',
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Get next academic year
     */
    public function getNextAcademicYearAttribute()
    {
        if (!$this->academic_year) {
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            return $currentYear . '-' . $nextYear;
        }

        $parts = explode('-', $this->academic_year);
        if (count($parts) === 2) {
            $nextStart = intval($parts[1]);
            $nextEnd = $nextStart + 1;
            return $nextStart . '-' . $nextEnd;
        }

        return $this->academic_year;
    }

    /**
     * Get next year name
     */
    public function getNextYearNameAttribute()
    {
        $yearNames = [
            'first_year' => 'second_year',
            'second_year' => 'third_year',
            'third_year' => 'fourth_year',
            'fourth_year' => 'fifth_year',
            'fifth_year' => 'graduated',
        ];

        return $yearNames[$this->current_year] ?? 'first_year';
    }

    /**
     * Get CGPA class for display
     */
    public function getCgpaClassAttribute()
    {
        if (!$this->cgpa) {
            return 'text-muted';
        }

        if ($this->cgpa >= 3.5) {
            return 'text-success fw-bold';
        } elseif ($this->cgpa >= 2.5) {
            return 'text-primary';
        } elseif ($this->cgpa >= 2.0) {
            return 'text-warning';
        } else {
            return 'text-danger';
        }
    }

    /**
     * Get academic standing
     */
    public function getAcademicStandingAttribute()
    {
        if (!$this->cgpa) {
            return 'Not Available';
        }

        if ($this->cgpa >= 3.5) {
            return 'Excellent';
        } elseif ($this->cgpa >= 2.5) {
            return 'Good';
        } elseif ($this->cgpa >= 2.0) {
            return 'Warning';
        } else {
            return 'Probation';
        }
    }

    // ==================== QUERY SCOPES ====================

    /**
     * Scope active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('current_year', $year);
    }

    /**
     * Scope by academic year
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope graduated students
     */
    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }

    /**
     * Scope with good academic standing
     */
    public function scopeGoodAcademicStanding($query)
    {
        return $query->where('cgpa', '>=', 2.5);
    }

    // ==================== BUSINESS LOGIC METHODS ====================

    /**
     * Check if student is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if student has passed a specific year
     */
    public function hasPassedYear($year)
    {
        $academicHistory = $this->academicHistory()
            ->where('year', $year)
            ->where('status', 'passed')
            ->first();

        return $academicHistory !== null;
    }

    /**
     * Check if student is eligible for next year
     */
    public function isEligibleForNextYear()
    {
        $currentYearNumber = $this->year_number;
        
        if ($currentYearNumber >= 5) {
            return false; // Already at final year
        }

        // Check if student has passed current year
        return $this->hasPassedYear($currentYearNumber);
    }

    /**
     * Get student's next year application if exists
     */
    public function getNextYearApplication()
    {
        $nextYear = $this->year_number + 1;
        $nextAcademicYear = $this->next_academic_year;

        return $this->applications()
            ->where('current_year', $nextYear)
            ->where('academic_year', $nextAcademicYear)
            ->first();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Check if password needs to be changed
     */
    public function needsPasswordChange()
    {
        return $this->needs_password_change;
    }

    /**
     * Mark password as changed
     */
    public function markPasswordAsChanged()
    {
        $this->needs_password_change = false;
        $this->save();
    }

    /**
     * Get academic performance summary
     */
    public function getAcademicSummary()
    {
        $history = $this->academicHistory()
            ->orderBy('academic_year', 'desc')
            ->orderBy('year', 'desc')
            ->get();

        return [
            'total_years_completed' => $history->where('status', 'passed')->count(),
            'current_cgpa' => $this->cgpa,
            'academic_standing' => $this->academic_standing,
            'history' => $history->map(function ($record) {
                return [
                    'year' => $record->year,
                    'academic_year' => $record->academic_year,
                    'status' => $record->status,
                    'cgpa' => $record->cgpa,
                    'academic_standing' => $record->academic_standing,
                ];
            }),
        ];
    }

    /**
     * Get upcoming application deadline
     */
    public function getUpcomingDeadline()
    {
        $nextYear = $this->year_number + 1;
        
        if ($nextYear > 5) {
            return null; // No more years to apply for
        }

        // Default deadline: End of current academic year
        $deadline = now()->addMonths(3); // 3 months from now
        
        return [
            'next_year' => $nextYear,
            'deadline_date' => $deadline->format('Y-m-d'),
            'deadline_days_left' => now()->diffInDays($deadline, false),
            'is_past_deadline' => now()->greaterThan($deadline),
        ];
    }

    /**
     * Check if student can apply for next year
     */
    public function canApplyForNextYear()
    {
        // Check if student is active
        if (!$this->isActive()) {
            return [
                'can_apply' => false,
                'reason' => 'Student account is not active.',
            ];
        }

        // Check if already at final year
        if ($this->year_number >= 5) {
            return [
                'can_apply' => false,
                'reason' => 'Already at final year.',
            ];
        }

        // Check academic eligibility
        if (!$this->isEligibleForNextYear()) {
            return [
                'can_apply' => false,
                'reason' => 'Not eligible for year progression.',
            ];
        }

        // Check if already applied
        $existingApplication = $this->getNextYearApplication();
        if ($existingApplication) {
            return [
                'can_apply' => false,
                'reason' => 'Already applied for next year.',
                'application_status' => $existingApplication->status,
            ];
        }

        // Check deadline
        $deadlineInfo = $this->getUpcomingDeadline();
        if ($deadlineInfo && $deadlineInfo['is_past_deadline']) {
            return [
                'can_apply' => false,
                'reason' => 'Application deadline has passed.',
                'deadline' => $deadlineInfo['deadline_date'],
            ];
        }

        return [
            'can_apply' => true,
            'next_year' => $this->year_number + 1,
            'deadline_info' => $deadlineInfo,
        ];
    }

    /**
     * Get student dashboard statistics
     */
    public function getDashboardStats()
    {
        $currentApplication = $this->applications()
            ->where('current_year', $this->year_number)
            ->latest()
            ->first();

        return [
            'current_year' => $this->year_name,
            'department' => $this->department_name,
            'cgpa' => $this->cgpa,
            'academic_standing' => $this->academic_standing,
            'has_pending_application' => $currentApplication && in_array($currentApplication->status, ['pending', 'payment_pending', 'payment_verified']),
            'pending_application' => $currentApplication ? [
                'status' => $currentApplication->status,
                'application_id' => $currentApplication->application_id,
                'submitted_at' => $currentApplication->created_at->format('Y-m-d'),
            ] : null,
            'can_apply_next_year' => $this->canApplyForNextYear(),
            'total_applications' => $this->applications()->count(),
            'academic_history_count' => $this->academicHistory()->count(),
        ];
    }
}