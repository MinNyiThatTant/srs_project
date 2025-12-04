<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // First add payment_amount column if it doesn't exist
            if (!Schema::hasColumn('applications', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_status');
            }

            // Add needs_academic_approval column
            if (!Schema::hasColumn('applications', 'needs_academic_approval')) {
                $table->boolean('needs_academic_approval')->default(false)->after('payment_status');
            }

            // Add other missing columns that are in your fillable array
            $missingColumns = [
                'student_original_id',
                'academic_year',
                'current_year',
                'next_academic_year',
                'application_purpose',
                'reason_for_application',
                'cgpa',
                'previous_year_status',
                'academic_history',
                'academic_approval_status',
                'academic_verified_by',
                'academic_verified_at',
                'verification_remarks',
                'conditions',
                'next_year_gpa_requirement',
                'required_subjects',
                'department_assigned_by',
                'department_assigned_at',
                'academic_approved_by',
                'academic_approved_at',
                'final_approved_by',
                'final_approved_at',
                'payment_verified_by',
                'payment_verified_at',
                'rejected_by',
                'rejected_at',
                'rejection_reason',
                'student_id',
                'submitted_at',
                'terms_accepted',
                'declaration_accepted',
                'assigned_department',
                'high_school_name',
                'high_school_address',
                'graduation_year',
                'matriculation_score',
                'previous_qualification',
            ];

            foreach ($missingColumns as $column) {
                if (!Schema::hasColumn('applications', $column)) {
                    // Add appropriate column types based on usage
                    switch ($column) {
                        case 'student_original_id':
                        case 'academic_verified_by':
                        case 'department_assigned_by':
                        case 'academic_approved_by':
                        case 'final_approved_by':
                        case 'payment_verified_by':
                        case 'rejected_by':
                            $table->unsignedBigInteger($column)->nullable()->after('existing_student_id');
                            break;
                        
                        case 'payment_amount':
                        case 'cgpa':
                        case 'next_year_gpa_requirement':
                            $table->decimal($column, 10, 2)->nullable()->after('payment_status');
                            break;
                        
                        case 'academic_year':
                        case 'current_year':
                        case 'next_academic_year':
                            $table->string($column, 50)->nullable()->after('student_original_id');
                            break;
                        
                        case 'academic_history':
                        case 'conditions':
                        case 'required_subjects':
                            $table->text($column)->nullable()->after('previous_year_status');
                            break;
                        
                        case 'reason_for_application':
                        case 'verification_remarks':
                        case 'rejection_reason':
                            $table->text($column)->nullable()->after('application_purpose');
                            break;
                        
                        case 'application_purpose':
                        case 'previous_year_status':
                        case 'academic_approval_status':
                            $table->string($column, 50)->nullable()->after('cgpa');
                            break;
                        
                        case 'student_id':
                            $table->string($column, 50)->nullable()->after('rejection_reason');
                            break;
                        
                        case 'academic_verified_at':
                        case 'department_assigned_at':
                        case 'academic_approved_at':
                        case 'final_approved_at':
                        case 'payment_verified_at':
                        case 'rejected_at':
                        case 'submitted_at':
                            $table->timestamp($column)->nullable()->after($column . '_by');
                            break;
                        
                        case 'terms_accepted':
                        case 'declaration_accepted':
                            $table->boolean($column)->default(false)->after('submitted_at');
                            break;
                        
                        case 'assigned_department':
                            $table->string($column, 255)->nullable()->after('department');
                            break;
                        
                        case 'high_school_name':
                        case 'high_school_address':
                        case 'previous_qualification':
                            $table->string($column, 255)->nullable()->after('student_type');
                            break;
                        
                        case 'graduation_year':
                        case 'matriculation_score':
                            $table->integer($column)->nullable()->after('high_school_address');
                            break;
                        
                        default:
                            $table->string($column, 255)->nullable();
                            break;
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Remove columns if you need to rollback
            $columnsToDrop = [
                'needs_academic_approval',
                'payment_amount',
                'student_original_id',
                'academic_year',
                'current_year',
                'next_academic_year',
                'application_purpose',
                'reason_for_application',
                'cgpa',
                'previous_year_status',
                'academic_history',
                'academic_approval_status',
                'academic_verified_by',
                'academic_verified_at',
                'verification_remarks',
                'conditions',
                'next_year_gpa_requirement',
                'required_subjects',
                'department_assigned_by',
                'department_assigned_at',
                'academic_approved_by',
                'academic_approved_at',
                'final_approved_by',
                'final_approved_at',
                'payment_verified_by',
                'payment_verified_at',
                'rejected_by',
                'rejected_at',
                'rejection_reason',
                'student_id',
                'submitted_at',
                'terms_accepted',
                'declaration_accepted',
                'assigned_department',
                'high_school_name',
                'high_school_address',
                'graduation_year',
                'matriculation_score',
                'previous_qualification',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};