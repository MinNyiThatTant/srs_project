<?php
// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'amount',
        'payment_method',
        'transaction_id',
        'gateway_reference',
        'status',
        'gateway_response',
        'paid_at',
        'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
        'amount' => 'decimal:2'
    ];

    // Payment status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // Payment method constants
    const METHOD_KPAY = 'kpay';
    const METHOD_WAVEPAY = 'wavepay';
    const METHOD_AYAPAY = 'ayapay';
    const METHOD_OKPAY = 'okpay';
    const METHOD_CARD = 'card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';

    // Relationships
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markAsCompleted($gatewayReference = null, $response = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'gateway_reference' => $gatewayReference,
            'gateway_response' => $response,
            'paid_at' => now()
        ]);

        // Update application payment status
        if ($this->application) {
            $this->application->markPaymentAsVerified();

            // Generate student ID if this is a new student application
            if ($this->application->application_type === 'new') {
                $this->application->generateStudentId();
            }
        }
    }

    public function markAsFailed($response = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'gateway_response' => $response
        ]);

        // Update application payment status
        if ($this->application) {
            $this->application->update([
                'payment_status' => Application::PAYMENT_FAILED
            ]);
        }
    }

    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            self::METHOD_KPAY => 'KPay',
            self::METHOD_WAVEPAY => 'WavePay',
            self::METHOD_AYAPAY => 'AYA Pay',
            self::METHOD_OKPAY => 'OK Pay',
            self::METHOD_CARD => 'Credit/Debit Card',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_FAILED => 'bg-red-100 text-red-800',
            self::STATUS_REFUNDED => 'bg-blue-100 text-blue-800'
        ];
        
        return $statuses[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at ? $this->paid_at->format('M d, Y H:i') : 'N/A';
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0) . ' MMK';
    }

    public function isSuccessful()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canRetry()
    {
        return $this->status === self::STATUS_FAILED && 
               $this->created_at->gt(now()->subHours(24));
    }

    /**
     * Boot method for generating transaction ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = 'TXN' . strtoupper(\Illuminate\Support\Str::random(10)) . time();
            }
        });
    }
}