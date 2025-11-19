<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add student_id column for old students
            if (!Schema::hasColumn('applications', 'student_id')) {
                $table->string('student_id')->nullable()->after('previous_qualification');
            }
            
            // Add other missing columns if needed
            if (!Schema::hasColumn('applications', 'current_year')) {
                $table->integer('current_year')->nullable()->after('student_id');
            }
            
            if (!Schema::hasColumn('applications', 'application_purpose')) {
                $table->string('application_purpose')->nullable()->after('current_year');
            }
            
            if (!Schema::hasColumn('applications', 'reason_for_application')) {
                $table->text('reason_for_application')->nullable()->after('application_purpose');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'current_year', 'application_purpose', 'reason_for_application']);
        });
    }
};