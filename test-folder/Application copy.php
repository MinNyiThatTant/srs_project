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

    // Status constants - Combined from both versions
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_ACADEMIC_APPROVED = 'academic_approved';
    const STATUS_FINAL_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_UNDER_REVIEW = 'under_review';

    // Payment status constants - Combined from both versions
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PROCESSING = 'processing';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_FAILED = 'failed';

    // Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // Scopes
    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    public function scopePaymentCompleted($query)
    {
        return $query->where('payment_status', self::PAYMENT_COMPLETED);
    }

    public function scopePaymentProcessing($query)
    {
        return $query->where('payment_status', self::PAYMENT_PROCESSING);
    }

    public function scopeNewStudents($query)
    {
        return $query->where('application_type', 'new');
    }

    public function scopeOldStudents($query)
    {
        return $query->where('application_type', 'old');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaymentPending($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopePaymentVerified($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_VERIFIED);
    }

    public function scopeAcademicApproved($query)
    {
        return $query->where('status', self::STATUS_ACADEMIC_APPROVED);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_FINAL_APPROVED);
    }

    public function scopeDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING, 
            self::STATUS_PAYMENT_PENDING, 
            self::STATUS_PAYMENT_VERIFIED, 
            self::STATUS_ACADEMIC_APPROVED, 
            self::STATUS_UNDER_REVIEW
        ]);
    }

    // Methods

    /**
     * Check if application with NRC number already exists
     */
    public static function nrcExists($nrcNumber)
    {
        return static::where('nrc_number', $nrcNumber)
            ->whereIn('status', [
                self::STATUS_PENDING, 
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_FINAL_APPROVED, 
                self::STATUS_UNDER_REVIEW
            ])
            ->exists();
    }

    /**
     * Check if application with student ID already exists
     */
    public static function studentIdExists($studentId, $purpose = null)
    {
        $query = static::where('student_id', $studentId)
            ->whereIn('status', [
                self::STATUS_PENDING, 
                self::STATUS_PAYMENT_PENDING,
                self::STATUS_PAYMENT_VERIFIED,
                self::STATUS_ACADEMIC_APPROVED,
                self::STATUS_FINAL_APPROVED, 
                self::STATUS_UNDER_REVIEW
            ]);
        
        if ($purpose) {
            $query->where('application_purpose', $purpose);
        }
        
        return $query->exists();
    }

    /**
     * Mark payment as verified
     */
    public function markPaymentAsVerified($verifiedBy = 'system')
    {
        $this->update([
            'payment_status' => self::PAYMENT_COMPLETED,
            'payment_verified_by' => $verifiedBy,
            'payment_verified_at' => now(),
            'status' => self::STATUS_PAYMENT_VERIFIED
        ]);
    }

    /**
     * Generate student ID
     */
    public function generateStudentId()
    {
        if (!$this->student_id) {
            $year = date('y');
            $deptCode = strtoupper(substr($this->department, 0, 3));
            $sequence = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            $this->student_id = "STU{$year}{$deptCode}{$sequence}";
            $this->save();
        }
        return $this->student_id;
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PAYMENT_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PAYMENT_VERIFIED => 'bg-blue-100 text-blue-800',
            self::STATUS_ACADEMIC_APPROVED => 'bg-purple-100 text-purple-800',
            self::STATUS_FINAL_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_UNDER_REVIEW => 'bg-blue-100 text-blue-800',
        ];

        return $statusClasses[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            self::PAYMENT_PENDING => 'bg-yellow-100 text-yellow-800',
            self::PAYMENT_PROCESSING => 'bg-blue-100 text-blue-800',
            self::PAYMENT_COMPLETED => 'bg-green-100 text-green-800',
            self::PAYMENT_FAILED => 'bg-red-100 text-red-800'
        ];
        
        return $statuses[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
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
            self::STATUS_FINAL_APPROVED => 'Final Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_UNDER_REVIEW => 'Under Review',
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
            self::PAYMENT_PROCESSING => 'Processing',
            self::PAYMENT_COMPLETED => 'Completed',
            self::PAYMENT_FAILED => 'Failed'
        ];

        return $statusTexts[$this->payment_status] ?? 'Unknown';
    }

    /**
     * Get formatted application date
     */
    public function getFormattedApplicationDateAttribute()
    {
        return $this->application_date ? $this->application_date->format('M d, Y') : 'N/A';
    }

    /**
     * Get formatted payment verified date
     */
    public function getFormattedPaymentVerifiedAtAttribute()
    {
        return $this->payment_verified_at ? $this->payment_verified_at->format('M d, Y H:i') : 'N/A';
    }

    /**
     * Check if application requires payment - UPDATED VERSION
     */
    public function requiresPayment()
    {
        return in_array($this->status, ['payment_pending', 'payment_processing']) &&
               in_array($this->payment_status, ['pending', 'processing', 'failed']);
    }

    /**
     * Check if application is ready for academic approval
     */
    public function readyForAcademicApproval()
    {
        return $this->application_type === 'new' && 
               $this->status === self::STATUS_PAYMENT_VERIFIED &&
               $this->payment_status === self::PAYMENT_COMPLETED;
    }

    /**
     * Check if application is ready for final approval
     */
    public function readyForFinalApproval()
    {
        return $this->application_type === 'new' && 
               $this->status === self::STATUS_ACADEMIC_APPROVED;
    }

    /**
     * Check if payment is completed
     */
    public function isPaymentCompleted()
    {
        return $this->payment_status === self::PAYMENT_COMPLETED;
    }

    /**
     * Check if application is approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_FINAL_APPROVED;
    }

    /**
     * Check if payment is pending
     */
    public function isPaymentPending()
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    /**
     * Check if payment is processing
     */
    public function isPaymentProcessing()
    {
        return $this->payment_status === self::PAYMENT_PROCESSING;
    }

    /**
     * Check if payment failed
     */
    public function isPaymentFailed()
    {
        return $this->payment_status === self::PAYMENT_FAILED;
    }

    /**
     * Get application workflow steps
     */
    public function getWorkflowStepsAttribute()
    {
        $steps = [
            [
                'step' => 'application_submitted',
                'status' => 'completed',
                'label' => 'Application Submitted',
                'date' => $this->application_date,
                'handled_by' => 'System'
            ],
            [
                'step' => 'payment',
                'status' => $this->isPaymentCompleted() ? 'completed' : 
                           ($this->isPaymentProcessing() ? 'processing' : 
                           ($this->requiresPayment() ? 'current' : 'pending')),
                'label' => 'Payment',
                'date' => $this->payment_verified_at,
                'handled_by' => $this->payment_verified_by ?: 'Pending'
            ],
            [
                'step' => 'academic_review',
                'status' => $this->status === self::STATUS_ACADEMIC_APPROVED ? 'completed' : 
                           ($this->readyForAcademicApproval() ? 'current' : 'pending'),
                'label' => 'Academic Review',
                'date' => $this->academic_approved_at,
                'handled_by' => $this->academic_approved_by ?: 'Academic Affairs'
            ],
            [
                'step' => 'final_approval',
                'status' => $this->isApproved() ? 'completed' : 
                           ($this->readyForFinalApproval() ? 'current' : 'pending'),
                'label' => 'Final Approval',
                'date' => $this->final_approved_at,
                'handled_by' => $this->final_approved_by ?: 'Head of Department'
            ]
        ];

        return $steps;
    }

    /**
     * Update payment status to processing
     */
    public function markPaymentAsProcessing()
    {
        $this->update([
            'payment_status' => self::PAYMENT_PROCESSING,
            'status' => 'payment_processing'
        ]);
    }

    /**
     * Update payment status to completed
     */
    public function markPaymentAsCompleted()
    {
        $this->update([
            'payment_status' => self::PAYMENT_COMPLETED,
            'payment_verified_at' => now(),
            'status' => self::STATUS_PAYMENT_VERIFIED
        ]);
    }

    /**
     * Update payment status to failed
     */
    public function markPaymentAsFailed()
    {
        $this->update([
            'payment_status' => self::PAYMENT_FAILED,
            'status' => 'payment_pending'
        ]);
    }

    /**
     * Boot method for generating application ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->application_id)) {
                $count = static::count();
                $model->application_id = 'APP' . date('Y') . str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            }
            
            if (empty($model->application_date)) {
                $model->application_date = now();
            }
            
            if (empty($model->status)) {
                $model->status = $model->application_type === 'new' ? 
                    self::STATUS_PAYMENT_PENDING : self::STATUS_PENDING;
            }
            
            if (empty($model->payment_status)) {
                $model->payment_status = self::PAYMENT_PENDING;
            }
        });
    }
}