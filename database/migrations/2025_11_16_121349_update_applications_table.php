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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('applications', 'father_name')) {
                $table->string('father_name')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('applications', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            
            if (!Schema::hasColumn('applications', 'nationality')) {
                $table->string('nationality')->nullable()->after('gender');
            }
            
            if (!Schema::hasColumn('applications', 'nrc_number')) {
                $table->string('nrc_number')->nullable()->after('nationality');
            }
            
            if (!Schema::hasColumn('applications', 'high_school_name')) {
                $table->string('high_school_name')->nullable()->after('department');
            }
            
            if (!Schema::hasColumn('applications', 'high_school_address')) {
                $table->text('high_school_address')->nullable()->after('high_school_name');
            }
            
            if (!Schema::hasColumn('applications', 'graduation_year')) {
                $table->integer('graduation_year')->nullable()->after('high_school_address');
            }
            
            if (!Schema::hasColumn('applications', 'matriculation_score')) {
                $table->decimal('matriculation_score', 5, 2)->nullable()->after('graduation_year');
            }
            
            if (!Schema::hasColumn('applications', 'previous_qualification')) {
                $table->string('previous_qualification')->nullable()->after('matriculation_score');
            }
            
            if (!Schema::hasColumn('applications', 'student_id')) {
                $table->string('student_id')->nullable()->after('previous_qualification');
            }
            
            if (!Schema::hasColumn('applications', 'current_year')) {
                $table->integer('current_year')->nullable()->after('student_id');
            }
            
            if (!Schema::hasColumn('applications', 'application_purpose')) {
                $table->string('application_purpose')->nullable()->after('current_year');
            }
            
            if (!Schema::hasColumn('applications', 'reason_for_application')) {
                $table->text('reason_for_application')->nullable()->after('application_purpose');
            }
            
            if (!Schema::hasColumn('applications', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('reason_for_application');
            }
            
            if (!Schema::hasColumn('applications', 'approved_by')) {
                $table->string('approved_by')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('applications', 'notes')) {
                $table->text('notes')->nullable()->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't drop columns in the down method to avoid data loss
        // You can manually remove columns if needed
    }
};