<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add fields for existing students
            if (!Schema::hasColumn('applications', 'current_year')) {
                $table->enum('current_year', [
                    'first_year', 'second_year', 'third_year', 
                    'fourth_year', 'fifth_year', 'sixth_year'
                ])->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'applied_year')) {
                $table->enum('applied_year', [
                    'first_year', 'second_year', 'third_year', 
                    'fourth_year', 'fifth_year', 'sixth_year'
                ])->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'current_department')) {
                $table->string('current_department')->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'applied_department')) {
                $table->string('applied_department')->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'reason_for_continuation')) {
                $table->text('reason_for_continuation')->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'cgpa')) {
                $table->decimal('cgpa', 3, 2)->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'academic_standing')) {
                $table->enum('academic_standing', ['good', 'warning', 'probation'])->nullable();
            }
            
            if (!Schema::hasColumn('applications', 'student_type')) {
                $table->enum('student_type', ['freshman', 'continuing'])->default('freshman');
            }
            
            // Add foreign key for existing student
            if (!Schema::hasColumn('applications', 'existing_student_id')) {
                $table->string('existing_student_id')->nullable();
                $table->foreign('existing_student_id')->references('student_id')->on('students')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'current_year',
                'applied_year',
                'current_department',
                'applied_department',
                'reason_for_continuation',
                'cgpa',
                'academic_standing',
                'student_type',
                'existing_student_id'
            ]);
        });
    }
};