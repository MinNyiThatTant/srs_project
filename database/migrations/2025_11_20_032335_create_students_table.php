<?php
// database/migrations/2024_01_01_000001_create_students_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->string('student_number')->unique()->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('program')->nullable();
            $table->string('status')->default('pending'); // pending, finance_approved, haa_approved, rejected, active
            $table->text('finance_notes')->nullable();
            $table->text('haa_notes')->nullable();
            $table->timestamp('finance_approved_at')->nullable();
            $table->timestamp('haa_approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}