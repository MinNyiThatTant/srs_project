<?php
// app/Models/Finance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $primaryKey = 'finance_id';
    protected $fillable = [
        'student_id',
        'amount',
        'payment_date',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}