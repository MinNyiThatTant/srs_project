<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $primaryKey = 'academic_year_id';
    protected $fillable = ['year', 'start_date', 'end_date'];
    
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'academic_year_id');
    }
}