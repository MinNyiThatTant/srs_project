<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }

    public function scopeMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
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

        // Update application payment status only - NO EMAIL SENDING HERE
        if ($this->application) {
            $this->application->update([
                'payment_status' => 'completed'
            ]);
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
                'payment_status' => 'failed'
            ]);
        }
    }

    public function markAsRefunded($notes = null)
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'notes' => $notes
        ]);

        // Update application payment status
        if ($this->application) {
            $this->application->update([
                'payment_status' => 'refunded',
                'status' => 'payment_pending'
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

    // Bootstrap badge classes for admin views
    public function getBootstrapBadgeAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_REFUNDED => 'bg-info'
        ];
        
        return $statuses[$this->status] ?? 'bg-secondary';
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

    public function getShortTransactionIdAttribute()
    {
        return substr($this->transaction_id, 0, 8) . '...' . substr($this->transaction_id, -4);
    }

    public function isSuccessful()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function canRetry()
    {
        return $this->status === self::STATUS_FAILED && 
               $this->created_at->gt(now()->subHours(24));
    }

    public function canRefund()
    {
        return $this->status === self::STATUS_COMPLETED && 
               $this->paid_at && 
               $this->paid_at->gt(now()->subDays(30)); // Only refund payments within 30 days
    }

    public function getGatewayResponseSummaryAttribute()
    {
        if (!$this->gateway_response) {
            return 'No gateway response data';
        }

        $response = $this->gateway_response;
        
        if (isset($response['statusCode'])) {
            return "Status: {$response['statusCode']} - " . ($response['statusDesc'] ?? 'N/A');
        }
        
        if (isset($response['status'])) {
            return "Status: " . ucfirst($response['status']);
        }
        
        return json_encode($response, JSON_PRETTY_PRINT);
    }

    /**
     * Get payment duration in human readable format
     */
    public function getPaymentDurationAttribute()
    {
        if (!$this->paid_at) {
            return 'Not paid yet';
        }

        $duration = $this->created_at->diff($this->paid_at);
        
        if ($duration->h > 0 || $duration->i > 0) {
            return $duration->format('%hh %im %ss');
        }
        
        return $duration->format('%ss');
    }

    /**
     * Check if payment is overdue (pending for more than 24 hours)
     */
    public function getIsOverdueAttribute()
    {
        return $this->isPending() && $this->created_at->lt(now()->subHours(24));
    }

    /**
     * Boot method for generating transaction ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = 'TXN' . strtoupper(Str::random(10)) . time();
            }
        });

        static::updated(function ($model) {
            // Log payment status changes
            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status');
                $newStatus = $model->status;
                
                // You can add logging here if needed
                // Log::info("Payment {$model->transaction_id} status changed from {$oldStatus} to {$newStatus}");
            }
        });
    }
}