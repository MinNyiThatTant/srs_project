<?php
// app/Models/Student.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';
    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'gender',
        'phone_number',
        'address',
        'registration_date',
        'status'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id');
    }

    public function finances()
    {
        return $this->hasMany(Finance::class, 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}