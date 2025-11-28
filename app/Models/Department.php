<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    // Relationship to users (HODs)
    public function hod()
    {
        return $this->hasOne(User::class, 'department_id')->where('role', 'hod_admin');
    }

    // Relationship to students
    public function students()
    {
        return $this->hasMany(User::class, 'department_id')->where('role', 'student');
    }
}