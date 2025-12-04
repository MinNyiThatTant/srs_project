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
        'status',
        'cgpa',
        'subjects_passed',
        'subjects_failed',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'subjects_passed' => 'array',
        'subjects_failed' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship with User (Approver)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for passed students
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope for failed students
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for specific academic year
     */
    public function scopeForAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope for specific year level
     */
    public function scopeForYearLevel($query, $year)
    {
        return $query->where('year', $year);
    }
}