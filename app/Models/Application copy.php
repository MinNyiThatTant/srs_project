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
        'rejection_reason', // Added missing field
        'password', // Added for student login
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

    // // If you have user relationship, it should look like this:
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_ACADEMIC_APPROVED = 'academic_approved';
    const STATUS_HOD_APPROVED = 'hod_approved'; // Added HOD approval step
    const STATUS_FINAL_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_UNDER_REVIEW = 'under_review';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PROCESSING = 'processing';
    const PAYMENT_COMPLETED = 'completed';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_VERIFIED = 'verified'; // Added verified status

    // Application types
    const TYPE_NEW = 'new';
    const TYPE_OLD = 'old';

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
        return $query->where('payment_status', self::PAYMENT_PENDING)
                    ->where('status', self::STATUS_PAYMENT_PENDING);
    }

    public function scopePaymentCompleted($query)
    {
        return $query->where('payment_status', self::PAYMENT_COMPLETED);
    }

    public function scopePaymentVerified($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_VERIFIED);
    }

    public function scopeAcademicApproved($query)
    {
        return $query->where('status', self::STATUS_ACADEMIC_APPROVED);
    }

    public function scopeHodApproved($query)
    {
        return $query->where('status', self::STATUS_HOD_APPROVED);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_FINAL_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeNewStudents($query)
    {
        return $query->where('application_type', self::TYPE_NEW);
    }

    public function scopeOldStudents($query)
    {
        return $query->where('application_type', self::TYPE_OLD);
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
            self::STATUS_HOD_APPROVED,
            self::STATUS_UNDER_REVIEW
        ]);
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
                self::STATUS_HOD_APPROVED,
                self::STATUS_FINAL_APPROVED, 
                self::STATUS_UNDER_REVIEW
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
     * Mark payment as verified
     */
    public function markPaymentAsVerified($verifiedBy = null)
    {
        $this->update([
            'payment_status' => self::PAYMENT_VERIFIED,
            'payment_verified_by' => $verifiedBy ?? auth()->id(),
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
            'academic_approved_by' => $approvedBy ?? auth()->id(),
            'academic_approved_at' => now()
        ]);
    }

    /**
     * Mark as HOD approved
     */
    public function markAsHodApproved($approvedBy = null)
    {
        $this->update([
            'status' => self::STATUS_HOD_APPROVED,
            'hod_approved_by' => $approvedBy ?? auth()->id(),
            'hod_approved_at' => now()
        ]);
    }

    /**
     * Mark as finally approved and generate student credentials
     */
    public function markAsFinalApproved($approvedBy = null)
    {
        $studentId = $this->generateStudentId();
        $password = $this->generatePassword();
        
        $this->update([
            'status' => self::STATUS_FINAL_APPROVED,
            'final_approved_by' => $approvedBy ?? auth()->id(),
            'final_approved_at' => now(),
            'approved_at' => now(),
            'approved_by' => $approvedBy ?? auth()->id(),
            'student_id' => $studentId,
            'password' => bcrypt($password)
        ]);

        return [
            'student_id' => $studentId,
            'password' => $password
        ];
    }

    /**
     * Mark as rejected
     */
    public function markAsRejected($reason, $rejectedBy = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'approved_by' => $rejectedBy ?? auth()->id(),
            'approved_at' => now()
        ]);
    }

    /**
     * Generate student ID
     */
    public function generateStudentId()
    {
        if (!$this->student_id) {
            $year = date('y');
            $deptCode = $this->getDepartmentCode();
            $sequence = static::where('department', $this->department)
                            ->whereNotNull('student_id')
                            ->count() + 1;
            $this->student_id = "WYTU{$year}{$deptCode}" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }
        return $this->student_id;
    }

    /**
     * Generate random password
     */
    public function generatePassword($length = 8)
    {
        return \Illuminate\Support\Str::random($length);
    }

    /**
     * Get department code
     */
    protected function getDepartmentCode()
    {
        $codes = [
            'Civil Engineering' => 'CE',
            'Computer Engineering and IT' => 'CEIT',
            'Architectural Engineering' => 'AE',
            'Mechanical Engineering' => 'ME',
            'Electronic Engineering' => 'EC',
            'Electrical Power Engineering' => 'EPE',
            'Chemical Engineering' => 'CHE',
            'Agricultural Engineering' => 'AGRE',
            'Mechatronics Engineering' => 'MTE',
            'Textile Engineering' => 'TE',
            'Metrology Engineering' => 'METE',

        ];
        
        return $codes[$this->department] ?? 'GN';
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
            self::STATUS_HOD_APPROVED => 'bg-success',
            self::STATUS_FINAL_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_UNDER_REVIEW => 'bg-secondary',
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
            self::PAYMENT_PROCESSING => 'bg-info',
            self::PAYMENT_COMPLETED => 'bg-success',
            self::PAYMENT_VERIFIED => 'bg-success',
            self::PAYMENT_FAILED => 'bg-danger'
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
            self::STATUS_HOD_APPROVED => 'HOD Approved',
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
            self::PAYMENT_VERIFIED => 'Verified',
            self::PAYMENT_FAILED => 'Failed'
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
               in_array($this->payment_status, [self::PAYMENT_PENDING, self::PAYMENT_FAILED]);
    }

    /**
     * Check if application is ready for academic approval
     */
    public function readyForAcademicApproval()
    {
        return $this->application_type === self::TYPE_NEW && 
               $this->status === self::STATUS_PAYMENT_VERIFIED;
    }

    /**
     * Check if application is ready for HOD approval
     */
    public function readyForHodApproval()
    {
        return $this->application_type === self::TYPE_NEW && 
               $this->status === self::STATUS_ACADEMIC_APPROVED;
    }

    /**
     * Check if application is ready for final approval
     */
    public function readyForFinalApproval()
    {
        return $this->application_type === self::TYPE_NEW && 
               $this->status === self::STATUS_HOD_APPROVED;
    }

    /**
     * Check if payment is verified
     */
    public function isPaymentVerified()
    {
        return in_array($this->payment_status, [self::PAYMENT_VERIFIED, self::PAYMENT_COMPLETED]);
    }

    /**
     * Check if application is approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_FINAL_APPROVED;
    }

    /**
     * Check if application is rejected
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
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
                'description' => 'Application received by system'
            ],
            [
                'step' => 'payment',
                'status' => $this->isPaymentVerified() ? 'completed' : 
                           ($this->isPaymentProcessing() ? 'processing' : 
                           ($this->requiresPayment() ? 'current' : 'pending')),
                'label' => 'Payment Verification',
                'date' => $this->payment_verified_at,
                'description' => $this->isPaymentVerified() ? 'Payment verified by finance' : 'Awaiting payment verification'
            ],
            [
                'step' => 'academic_review',
                'status' => $this->status === self::STATUS_ACADEMIC_APPROVED ? 'completed' : 
                           ($this->readyForAcademicApproval() ? 'current' : 'pending'),
                'label' => 'Academic Review',
                'date' => $this->academic_approved_at,
                'description' => $this->status === self::STATUS_ACADEMIC_APPROVED ? 'Approved by academic affairs' : 'Pending academic review'
            ],
            [
                'step' => 'hod_approval',
                'status' => $this->status === self::STATUS_HOD_APPROVED ? 'completed' : 
                           ($this->readyForHodApproval() ? 'current' : 'pending'),
                'label' => 'HOD Approval',
                'date' => $this->hod_approved_at,
                'description' => $this->status === self::STATUS_HOD_APPROVED ? 'Approved by department head' : 'Pending HOD approval'
            ],
            [
                'step' => 'final_approval',
                'status' => $this->isApproved() ? 'completed' : 
                           ($this->readyForFinalApproval() ? 'current' : 'pending'),
                'label' => 'Final Approval',
                'date' => $this->final_approved_at,
                'description' => $this->isApproved() ? 'Final approval completed' : 'Pending final approval'
            ]
        ];

        return $steps;
    }

    /**
     * Get current workflow step
     */
    public function getCurrentStepAttribute()
    {
        foreach ($this->workflow_steps as $step) {
            if (in_array($step['status'], ['current', 'processing'])) {
                return $step;
            }
        }
        return end($this->workflow_steps);
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