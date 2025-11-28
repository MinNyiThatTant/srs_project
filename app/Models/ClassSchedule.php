<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';
    protected $fillable = ['course_id', 'day_of_week', 'start_time', 'end_time', 'class_room'];
    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}