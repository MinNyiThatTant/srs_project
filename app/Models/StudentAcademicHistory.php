<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year',
        'year',
        'semester',
        'cgpa',
        'status',
        'remarks',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'cgpa' => 'decimal:2'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    // Status constants
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_RETAKE = 'retake';
    const STATUS_IMPROVEMENT = 'improvement';

    // Year constants
    const YEAR_FIRST = 1;
    const YEAR_SECOND = 2;
    const YEAR_THIRD = 3;
    const YEAR_FOURTH = 4;
    const YEAR_FIFTH = 5;

    // Methods
    public function getYearNameAttribute()
    {
        $yearNames = [
            self::YEAR_FIRST => 'First Year',
            self::YEAR_SECOND => 'Second Year',
            self::YEAR_THIRD => 'Third Year',
            self::YEAR_FOURTH => 'Fourth Year',
            self::YEAR_FIFTH => 'Fifth Year',
        ];

        return $yearNames[$this->year] ?? 'Unknown Year';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PASSED => 'badge bg-success',
            self::STATUS_FAILED => 'badge bg-danger',
            self::STATUS_RETAKE => 'badge bg-warning',
            self::STATUS_IMPROVEMENT => 'badge bg-info',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            self::STATUS_PASSED => 'Passed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_RETAKE => 'Retake',
            self::STATUS_IMPROVEMENT => 'Improvement',
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }
}