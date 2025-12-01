<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->foreignId('application_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->string('department');
            $table->string('academic_year')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('nrc_number')->unique();
            $table->text('address');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->dateTime('registration_date');
            $table->dateTime('last_login_at')->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('needs_password_change')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // Create password reset table for students
        Schema::create('student_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_password_resets');
        Schema::dropIfExists('students');
    }
};