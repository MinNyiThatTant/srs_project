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
        'father_name',
        'mother_name',
        'date_of_birth',
        'gender',
        'nationality',
        'nrc_number',
        'address',
        'application_type',
<<<<<<< HEAD
        // Department fields
        'department', // Keep for backward compatibility
        'first_priority_department',
        'second_priority_department',
        'third_priority_department',
        'fourth_priority_department',
        'fifth_priority_department',
        'assigned_department',
        // Educational background
=======
        'department',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        'high_school_name',
        'high_school_address',
        'graduation_year',
        'matriculation_score',
        'previous_qualification',
        'student_id',
        'current_year',
        'application_purpose',
        'reason_for_application',
        'status',
        'application_date',
        'approved_at',
        'approved_by',
        'notes',
        'payment_status',
        'payment_verified_by',
        'payment_verified_at',
        'academic_approved_by',
        'academic_approved_at',
        'final_approved_by',
        'final_approved_at',
<<<<<<< HEAD
        'department_assigned_by', // NEW
        'department_assigned_at', // NEW
=======
        'rejection_reason',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        'password',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'application_date' => 'datetime',
        'approved_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'academic_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
<<<<<<< HEAD
        'department_assigned_at' => 'datetime', // NEW
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        'graduation_year' => 'integer',
        'matriculation_score' => 'decimal:2',
        'current_year' => 'integer',
        'gateway_response' => 'array'
    ];

<<<<<<< HEAD
    // Status constants - UPDATE THIS SECTION ONLY
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_DEPARTMENT_ASSIGNED = 'department_assigned'; 
=======
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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

    // Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
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

<<<<<<< HEAD
    public function scopeDepartmentAssigned($query) // NEW SCOPE
    {
        return $query->where('status', self::STATUS_DEPARTMENT_ASSIGNED);
    }

=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    public function scopeAcademicApproved($query)
    {
        return $query->where('status', self::STATUS_ACADEMIC_APPROVED);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
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
<<<<<<< HEAD
                self::STATUS_DEPARTMENT_ASSIGNED, // ADDED
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
        $query = static::where('student_id', $studentId);

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
<<<<<<< HEAD
     * Mark as department assigned
     */
    public function markAsDepartmentAssigned($adminId)
{
    $this->update([
        'status' => 'department_assigned',
        'department_assigned_by' => $adminId,
        'department_assigned_at' => now(),
    ]);
}


    /**
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
     * Mark as academically approved
     */
    public function markAsAcademicApproved($approvedBy = null)
    {
        $this->update([
            'status' => self::STATUS_ACADEMIC_APPROVED,
            'academic_approved_by' => $approvedBy,
            'academic_approved_at' => now()
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
            'rejected_at' => now()
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

<<<<<<< HEAD
    public static function checkDuplicate($email, $nrcNumber, $excludeId = null)
    {
        $emailQuery = static::where('email', $email)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED, // ADDED
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_APPROVED
            ]);

        $nrcQuery = static::where('nrc_number', $nrcNumber)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_DEPARTMENT_ASSIGNED, // ADDED
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

=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    /**
     * Get the status badge class for Bootstrap
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_PAYMENT_PENDING => 'bg-warning',
            self::STATUS_PAYMENT_VERIFIED => 'bg-info',
<<<<<<< HEAD
            self::STATUS_DEPARTMENT_ASSIGNED => 'bg-primary', // ADDED
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            self::STATUS_ACADEMIC_APPROVED => 'bg-primary',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
        ];

        return $statusClasses[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get payment status badge class for Bootstrap
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            self::PAYMENT_PENDING => 'bg-warning',
            self::PAYMENT_COMPLETED => 'bg-info',
            self::PAYMENT_VERIFIED => 'bg-success',
        ];

        return $statuses[$this->payment_status] ?? 'bg-secondary';
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
<<<<<<< HEAD
            self::STATUS_DEPARTMENT_ASSIGNED => 'Department Assigned', // ADDED
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
     * Get formatted application ID
     */
    public function getFormattedApplicationIdAttribute()
    {
        return $this->application_id ?: 'WYTU-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted application date
     */
    public function getFormattedApplicationDateAttribute()
    {
        return $this->application_date ? $this->application_date->format('M d, Y H:i') : 'N/A';
    }

    /**
     * Check if application requires payment
     */
    public function requiresPayment()
    {
        return $this->status === self::STATUS_PAYMENT_PENDING &&
            in_array($this->payment_status, [self::PAYMENT_PENDING]);
    }

    /**
     * Check if application is ready for academic approval
     */
    public function readyForAcademicApproval()
    {
        return $this->status === self::STATUS_PAYMENT_VERIFIED;
    }

    /**
<<<<<<< HEAD
     * Check if department is assigned
     */
    public function isDepartmentAssigned() // NEW METHOD
    {
        return $this->status === self::STATUS_DEPARTMENT_ASSIGNED;
    }

    /**
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
<<<<<<< HEAD
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
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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

            if (empty($model->application_date)) {
                $model->application_date = now();
            }

            // Set initial status based on application type
            if (empty($model->status)) {
                $model->status = $model->application_type === self::TYPE_NEW ?
                    self::STATUS_PAYMENT_PENDING : self::STATUS_PENDING;
            }

            if (empty($model->payment_status) && $model->application_type === self::TYPE_NEW) {
                $model->payment_status = self::PAYMENT_PENDING;
            }
        });
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
