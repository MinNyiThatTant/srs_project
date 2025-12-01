<?php
// app/Models/Course.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id';
    protected $fillable = [
        'course_code',
        'title',
        'description',
        'department_id',
        'teacher_id',
        'semester_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'course_id');
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'course_id');
    }
}