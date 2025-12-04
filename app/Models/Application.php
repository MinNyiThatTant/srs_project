<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'email',
        'phone',
        'nrc_number',
        'father_name',
        'mother_name',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'application_type',
        'student_type',
        'first_priority_department',
        'second_priority_department',
        'third_priority_department',
        'fourth_priority_department',
        'fifth_priority_department',
        'department',
        'assigned_department',
        'high_school_name',
        'high_school_address',
        'graduation_year',
        'matriculation_score',
        'previous_qualification',
        'existing_student_id',
        'student_original_id',
        'academic_year',
        'current_year',
        'next_academic_year',
        'application_purpose',
        'reason_for_application',
        'cgpa',
        'previous_year_status',
        'academic_history',
        'status',
        'payment_status',
        'payment_amount',
        'needs_academic_approval',
        'academic_approval_status',
        'academic_verified_by',
        'academic_verified_at',
        'verification_remarks',
        'conditions',
        'next_year_gpa_requirement',
        'required_subjects',
        'department_assigned_by',
        'department_assigned_at',
        'academic_approved_by',
        'academic_approved_at',
        'final_approved_by',
        'final_approved_at',
        'payment_verified_by',
        'payment_verified_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'student_id',
        'submitted_at',
        'terms_accepted',
        'declaration_accepted',
    ];

    protected $casts = [
        'academic_history' => 'array',
        'conditions' => 'array',
        'required_subjects' => 'array',
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
        'academic_verified_at' => 'datetime',
        'department_assigned_at' => 'datetime',
        'academic_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'needs_academic_approval' => 'boolean',
        'terms_accepted' => 'boolean',
        'declaration_accepted' => 'boolean',
        'payment_amount' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_DEPARTMENT_ASSIGNED = 'department_assigned';
    const STATUS_ACADEMIC_APPROVED = 'academic_approved';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Payment status constants  
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_VERIFIED = 'verified';

    // Application types
    const TYPE_NEW = 'new';
    const TYPE_OLD = 'old';

    // Student types
    const STUDENT_FRESHMAN = 'freshman';
    const STUDENT_CONTINUING = 'continuing';

    // Academic approval status
    const ACADEMIC_PENDING = 'pending';
    const ACADEMIC_APPROVED = 'approved';
    const ACADEMIC_REJECTED = 'rejected';

    // Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relationship with Student (for old students)
     */
    public function studentRecord()
    {
        return $this->belongsTo(Student::class, 'student_original_id');
    }

    /**
     * Relationship with Admin (Academic Verifier)
     */
    public function academicVerifier()
    {
        return $this->belongsTo(Admin::class, 'academic_verified_by');
    }

    /**
     * Relationship with Admin (Department Assigner)
     */
    public function departmentAssigner()
    {
        return $this->belongsTo(Admin::class, 'department_assigned_by');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'application_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // Scopes
    public function scopeNewStudents($query)
    {
        return $query->where('application_type', self::TYPE_NEW);
    }

    public function scopeOldStudents($query)
    {
        return $query->where('application_type', self::TYPE_OLD);
    }

    public function scopeNeedsAcademicApproval($query)
    {
        return $query->where('needs_academic_approval', true)
                    ->where('academic_approval_status', self::ACADEMIC_PENDING);
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING)
            ->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopePaymentCompleted($query)
    {
        return $query->where('payment_status', self::PAYMENT_COMPLETED)
            ->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopeReadyForVerification($query)
    {
        return $query->where('payment_status', self::PAYMENT_COMPLETED)
            ->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopePaymentVerified($query)
    {
        return $query->where('payment_status', self::PAYMENT_VERIFIED)
            ->where('status', self::STATUS_PAYMENT_VERIFIED);
    }

    public function scopeReadyForAcademicReview($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_VERIFIED);
    }

    public function scopeDepartmentAssigned($query)
    {
        return $query->where('status', self::STATUS_DEPARTMENT_ASSIGNED);
    }

    public function scopeAcademicApproved($query)
    {
        return $query->where('status', self::STATUS_ACADEMIC_APPROVED);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeAcademicPending($query)
    {
        return $query->where('academic_approval_status', self::ACADEMIC_PENDING);
    }

    // Methods

    /**
     * Check if application with NRC number already exists
     */
    public static function nrcExists($nrcNumber, $excludeId = null)
    {
        $query = static::where('nrc_number', $nrcNumber)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_APPROVED
            ]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if application with student ID already exists
     */
    public static function studentIdExists($studentId, $excludeId = null)
    {
        $query = static::where('existing_student_id', $studentId)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_APPROVED
            ]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Mark payment as verified by finance admin
     */
    public function markPaymentAsVerified($verifiedBy = null)
    {
        $this->update([
            'payment_status' => self::PAYMENT_VERIFIED,
            'payment_verified_by' => $verifiedBy,
            'payment_verified_at' => now(),
            'status' => self::STATUS_PAYMENT_VERIFIED
        ]);
    }

    /**
     * Mark as department assigned
     */
    public function markAsDepartmentAssigned($adminId)
    {
        $this->update([
            'status' => self::STATUS_DEPARTMENT_ASSIGNED,
            'department_assigned_by' => $adminId,
            'department_assigned_at' => now(),
        ]);
    }

    /**
     * Mark as academically approved
     */
    public function markAsAcademicApproved($approvedBy = null)
    {
        $this->update([
            'status' => self::STATUS_ACADEMIC_APPROVED,
            'academic_approved_by' => $approvedBy,
            'academic_approved_at' => now(),
            'academic_approval_status' => self::ACADEMIC_APPROVED,
        ]);
    }

    /**
     * Mark as finally approved and generate student credentials
     */
    public function markAsFinalApproved($approvedBy = null)
    {
        $studentId = $this->generateStudentId();

        $this->update([
            'status' => self::STATUS_APPROVED,
            'final_approved_by' => $approvedBy,
            'final_approved_at' => now(),
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'student_id' => $studentId,
        ]);

        return $studentId;
    }

    /**
     * Mark as rejected
     */
    public function markAsRejected($reason, $rejectedBy = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'rejected_by' => $rejectedBy,
            'rejected_at' => now(),
            'academic_approval_status' => self::ACADEMIC_REJECTED,
        ]);
    }

    /**
     * Generate student ID in WYTU202500001 format
     */
    public function generateStudentId()
    {
        if (!$this->student_id) {
            $year = date('Y');
            
            // Get the last student ID for this year
            $lastStudent = Student::where('student_id', 'like', "WYTU{$year}%")
                ->orderBy('student_id', 'desc')
                ->first();

            if ($lastStudent) {
                $lastNumber = intval(substr($lastStudent->student_id, -5));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $studentId = "WYTU{$year}" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
            return $studentId;
        }
        return $this->student_id;
    }

    /**
     * Check for duplicate applications
     */
    public static function checkDuplicate($email, $nrcNumber, $excludeId = null)
    {
        $emailQuery = static::where('email', $email)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_APPROVED
            ]);

        $nrcQuery = static::where('nrc_number', $nrcNumber)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_APPROVED
            ]);

        if ($excludeId) {
            $emailQuery->where('id', '!=', $excludeId);
            $nrcQuery->where('id', '!=', $excludeId);
        }
        
        return [
            'email_exists' => $emailQuery->exists(),
            'nrc_exists' => $nrcQuery->exists()
        ];
    }

    /**
     * Get the status badge class for Bootstrap
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            self::STATUS_PENDING => 'badge bg-warning',
            self::STATUS_PAYMENT_PENDING => 'badge bg-secondary',
            self::STATUS_PAYMENT_VERIFIED => 'badge bg-info',
            self::STATUS_DEPARTMENT_ASSIGNED => 'badge bg-primary',
            self::STATUS_ACADEMIC_APPROVED => 'badge bg-success',
            self::STATUS_APPROVED => 'badge bg-success',
            self::STATUS_REJECTED => 'badge bg-danger',
        ];

        return $statusClasses[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        $statusTexts = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAYMENT_PENDING => 'Payment Pending',
            self::STATUS_PAYMENT_VERIFIED => 'Payment Verified',
            self::STATUS_DEPARTMENT_ASSIGNED => 'Department Assigned',
            self::STATUS_ACADEMIC_APPROVED => 'Academic Approved',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];

        return $statusTexts[$this->status] ?? 'Unknown';
    }

    /**
     * Get payment status text
     */
    public function getPaymentStatusTextAttribute()
    {
        $statusTexts = [
            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_COMPLETED => 'Completed',
            self::PAYMENT_VERIFIED => 'Verified',
        ];

        return $statusTexts[$this->payment_status] ?? 'Unknown';
    }

    /**
     * Get academic approval status text
     */
    public function getAcademicApprovalStatusTextAttribute()
    {
        $statusTexts = [
            self::ACADEMIC_PENDING => 'Pending',
            self::ACADEMIC_APPROVED => 'Approved',
            self::ACADEMIC_REJECTED => 'Rejected',
        ];

        return $statusTexts[$this->academic_approval_status] ?? 'Unknown';
    }

    /**
     * Get academic approval status badge
     */
    public function getAcademicApprovalStatusBadgeAttribute()
    {
        $badges = [
            self::ACADEMIC_PENDING => 'badge bg-warning',
            self::ACADEMIC_APPROVED => 'badge bg-success',
            self::ACADEMIC_REJECTED => 'badge bg-danger',
        ];

        return $badges[$this->academic_approval_status] ?? 'badge bg-secondary';
    }

    /**
     * Get formatted application ID
     */
    public function getFormattedApplicationIdAttribute()
    {
        return $this->application_id ?: 'APP-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted application date
     */
    public function getFormattedApplicationDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y H:i') : 'N/A';
    }

    /**
     * Check if application requires payment
     */
    public function requiresPayment()
    {
        return $this->status === self::STATUS_PAYMENT_PENDING &&
            in_array($this->payment_status, [self::PAYMENT_PENDING, self::PAYMENT_COMPLETED]);
    }

    /**
     * Check if application is ready for academic approval
     */
    public function readyForAcademicApproval()
    {
        return $this->status === self::STATUS_PAYMENT_VERIFIED && 
               $this->needs_academic_approval &&
               $this->academic_approval_status === self::ACADEMIC_PENDING;
    }

    /**
     * Check if department is assigned
     */
    public function isDepartmentAssigned()
    {
        return $this->status === self::STATUS_DEPARTMENT_ASSIGNED && !empty($this->assigned_department);
    }

    /**
     * Check if payment is verified
     */
    public function isPaymentVerified()
    {
        return $this->payment_status === self::PAYMENT_VERIFIED;
    }

    /**
     * Check if application is approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if application is rejected
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get department priorities as an array
     */
    public function getDepartmentPriorities()
    {
        $priorities = [];
        
        if ($this->first_priority_department) {
            $priorities['First Priority'] = $this->first_priority_department;
        }
        if ($this->second_priority_department) {
            $priorities['Second Priority'] = $this->second_priority_department;
        }
        if ($this->third_priority_department) {
            $priorities['Third Priority'] = $this->third_priority_department;
        }
        if ($this->fourth_priority_department) {
            $priorities['Fourth Priority'] = $this->fourth_priority_department;
        }
        if ($this->fifth_priority_department) {
            $priorities['Fifth Priority'] = $this->fifth_priority_department;
        }
        
        return $priorities;
    }

    /**
     * Get assigned department (falls back to first priority if not assigned)
     */
    public function getAssignedDepartmentAttribute()
    {
        return $this->attributes['assigned_department'] ?? $this->first_priority_department;
    }

    /**
     * Get application type text
     */
    public function getApplicationTypeTextAttribute()
    {
        $types = [
            self::TYPE_NEW => 'New Student',
            self::TYPE_OLD => 'Existing Student',
        ];

        return $types[$this->application_type] ?? 'Unknown';
    }

    /**
     * Get student type text
     */
    public function getStudentTypeTextAttribute()
    {
        $types = [
            self::STUDENT_FRESHMAN => 'Freshman',
            self::STUDENT_CONTINUING => 'Continuing',
        ];

        return $types[$this->student_type] ?? 'Unknown';
    }

    /**
     * Get formatted payment amount
     */
    public function getFormattedPaymentAmountAttribute()
    {
        return $this->payment_amount ? number_format($this->payment_amount, 2) . ' MMK' : 'N/A';
    }

    /**
     * Get next year name
     */
    public function getNextYearNameAttribute()
    {
        $yearNames = [
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
            5 => 'Fifth Year',
        ];

        return $yearNames[$this->current_year] ?? 'Unknown Year';
    }

    /**
     * Get current year name
     */
    public function getCurrentYearNameAttribute()
    {
        $currentYear = $this->current_year - 1;
        $yearNames = [
            0 => 'First Year (Completed)',
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
            5 => 'Fifth Year',
        ];

        return $yearNames[$currentYear] ?? 'Unknown Year';
    }

    /**
     * Boot method for generating application ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->application_id)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count();
                $model->application_id = 'APP' . $year . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            }

            if (empty($model->submitted_at)) {
                $model->submitted_at = now();
            }

            // Set initial status based on application type
            if (empty($model->status)) {
                if ($model->application_type === self::TYPE_NEW) {
                    $model->status = self::STATUS_PAYMENT_PENDING;
                    $model->student_type = self::STUDENT_FRESHMAN;
                } elseif ($model->application_type === self::TYPE_OLD) {
                    $model->status = self::STATUS_PAYMENT_PENDING;
                    $model->student_type = self::STUDENT_CONTINUING;
                    $model->needs_academic_approval = true;
                    $model->academic_approval_status = self::ACADEMIC_PENDING;
                }
            }

            if (empty($model->payment_status)) {
                $model->payment_status = self::PAYMENT_PENDING;
            }

            if (empty($model->academic_approval_status) && $model->application_type === self::TYPE_OLD) {
                $model->academic_approval_status = self::ACADEMIC_PENDING;
            }

            // Set department from first priority if not set
            if (empty($model->department) && !empty($model->first_priority_department)) {
                $model->department = $model->first_priority_department;
            }

            // Set academic year if not set
            if (empty($model->academic_year)) {
                $currentYear = date('Y');
                $nextYear = $currentYear + 1;
                $model->academic_year = "{$currentYear}-{$nextYear}";
            }

            // Set next academic year for old students
            if ($model->application_type === self::TYPE_OLD && empty($model->next_academic_year)) {
                $parts = explode('-', $model->academic_year);
                if (count($parts) === 2) {
                    $nextStart = intval($parts[1]);
                    $nextEnd = $nextStart + 1;
                    $model->next_academic_year = "{$nextStart}-{$nextEnd}";
                }
            }
        });
    }
}