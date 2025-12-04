<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_academic_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->integer('year')->comment('1=First Year, 2=Second Year, etc.');
            $table->enum('status', ['passed', 'failed', 'retake'])->default('passed');
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->json('subjects_passed')->nullable();
            $table->json('subjects_failed')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'academic_year', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_academic_histories');
    }
};