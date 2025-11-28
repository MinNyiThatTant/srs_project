<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->unique()->nullable(); // Make it nullable for now
                $table->foreignId('application_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone');
                $table->string('password');
                $table->string('department');
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['male', 'female'])->nullable();
                $table->string('nrc_number')->unique()->nullable();
                $table->text('address')->nullable();
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->timestamp('registration_date')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};