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
        'department',
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
        'rejection_reason',
        'password',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'application_date' => 'datetime',
        'approved_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'academic_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
        'graduation_year' => 'integer',
        'matriculation_score' => 'decimal:2',
        'current_year' => 'integer',
        'gateway_response' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
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

    /**
     * Get the status badge class for Bootstrap
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_PAYMENT_PENDING => 'bg-warning',
            self::STATUS_PAYMENT_VERIFIED => 'bg-info',
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
}
