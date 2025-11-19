<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';
    protected $fillable = ['name', 'email', 'department_id'];
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
}