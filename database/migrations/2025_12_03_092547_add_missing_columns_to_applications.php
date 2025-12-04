<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add missing columns based on your controller needs
            
            // 1. needs_academic_approval - after payment_status
            if (!Schema::hasColumn('applications', 'needs_academic_approval')) {
                $table->boolean('needs_academic_approval')->default(false)->after('payment_status');
            }
            
            // 2. payment_amount - after payment_status
            if (!Schema::hasColumn('applications', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_status');
            }
            
            // 3. student_original_id - for linking to student table (if missing)
            if (!Schema::hasColumn('applications', 'student_original_id')) {
                $table->unsignedBigInteger('student_original_id')->nullable()->after('existing_student_id');
            }
            
            // 4. academic_year - after student_original_id
            if (!Schema::hasColumn('applications', 'academic_year')) {
                $table->string('academic_year', 20)->nullable()->after('student_original_id');
            }
            
            // 5. next_academic_year - after academic_year
            if (!Schema::hasColumn('applications', 'next_academic_year')) {
                $table->string('next_academic_year', 20)->nullable()->after('academic_year');
            }
            
            // 6. previous_year_status - after cgpa
            if (!Schema::hasColumn('applications', 'previous_year_status')) {
                $table->string('previous_year_status', 50)->nullable()->after('cgpa');
            }
            
            // 7. academic_history - text field for storing history
            if (!Schema::hasColumn('applications', 'academic_history')) {
                $table->text('academic_history')->nullable()->after('previous_year_status');
            }
            
            // 8. academic_approval_status - after needs_academic_approval
            if (!Schema::hasColumn('applications', 'academic_approval_status')) {
                $table->enum('academic_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('needs_academic_approval');
            }
            
            // 9. academic_verified_by - after academic_approval_status
            if (!Schema::hasColumn('applications', 'academic_verified_by')) {
                $table->unsignedBigInteger('academic_verified_by')->nullable()->after('academic_approval_status');
            }
            
            // 10. academic_verified_at - after academic_verified_by
            if (!Schema::hasColumn('applications', 'academic_verified_at')) {
                $table->timestamp('academic_verified_at')->nullable()->after('academic_verified_by');
            }
            
            // 11. verification_remarks - after academic_verified_at
            if (!Schema::hasColumn('applications', 'verification_remarks')) {
                $table->text('verification_remarks')->nullable()->after('academic_verified_at');
            }
            
            // 12. conditions - after verification_remarks
            if (!Schema::hasColumn('applications', 'conditions')) {
                $table->text('conditions')->nullable()->after('verification_remarks');
            }
            
            // 13. next_year_gpa_requirement - after conditions
            if (!Schema::hasColumn('applications', 'next_year_gpa_requirement')) {
                $table->decimal('next_year_gpa_requirement', 3, 2)->nullable()->after('conditions');
            }
            
            // 14. required_subjects - after next_year_gpa_requirement
            if (!Schema::hasColumn('applications', 'required_subjects')) {
                $table->text('required_subjects')->nullable()->after('next_year_gpa_requirement');
            }
            
            // 15. rejected_by - after final_approved_at
            if (!Schema::hasColumn('applications', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('final_approved_at');
            }
            
            // 16. rejected_at - after rejected_by
            if (!Schema::hasColumn('applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            
            // 17. rejection_reason - after rejected_at
            if (!Schema::hasColumn('applications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
            
            // 18. submitted_at - after rejection_reason
            if (!Schema::hasColumn('applications', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('rejection_reason');
            }
            
            // 19. terms_accepted - after submitted_at
            if (!Schema::hasColumn('applications', 'terms_accepted')) {
                $table->boolean('terms_accepted')->default(false)->after('submitted_at');
            }
            
            // 20. declaration_accepted - after terms_accepted
            if (!Schema::hasColumn('applications', 'declaration_accepted')) {
                $table->boolean('declaration_accepted')->default(false)->after('terms_accepted');
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the columns we added
            $columnsToDrop = [
                'needs_academic_approval',
                'payment_amount',
                'student_original_id',
                'academic_year',
                'next_academic_year',
                'previous_year_status',
                'academic_history',
                'academic_approval_status',
                'academic_verified_by',
                'academic_verified_at',
                'verification_remarks',
                'conditions',
                'next_year_gpa_requirement',
                'required_subjects',
                'rejected_by',
                'rejected_at',
                'rejection_reason',
                'submitted_at',
                'terms_accepted',
                'declaration_accepted',
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};