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
            // Add payment related columns
            if (!Schema::hasColumn('applications', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('previous_qualification');
            }
            
            if (!Schema::hasColumn('applications', 'payment_verified_by')) {
                $table->string('payment_verified_by')->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('applications', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable()->after('payment_verified_by');
            }
            
            // Add academic approval columns
            if (!Schema::hasColumn('applications', 'academic_approved_by')) {
                $table->string('academic_approved_by')->nullable()->after('payment_verified_at');
            }
            
            if (!Schema::hasColumn('applications', 'academic_approved_at')) {
                $table->timestamp('academic_approved_at')->nullable()->after('academic_approved_by');
            }
            
            // Add final approval columns
            if (!Schema::hasColumn('applications', 'final_approved_by')) {
                $table->string('final_approved_by')->nullable()->after('academic_approved_at');
            }
            
            if (!Schema::hasColumn('applications', 'final_approved_at')) {
                $table->timestamp('final_approved_at')->nullable()->after('final_approved_by');
            }
            
            // Make sure old student fields are nullable
            if (Schema::hasColumn('applications', 'father_name')) {
                $table->string('father_name')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'mother_name')) {
                $table->string('mother_name')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'nationality')) {
                $table->string('nationality')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'nrc_number')) {
                $table->string('nrc_number')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'high_school_name')) {
                $table->string('high_school_name')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'high_school_address')) {
                $table->text('high_school_address')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'graduation_year')) {
                $table->integer('graduation_year')->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'matriculation_score')) {
                $table->decimal('matriculation_score', 5, 2)->nullable()->change();
            }
            if (Schema::hasColumn('applications', 'previous_qualification')) {
                $table->string('previous_qualification')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn([
                'payment_status',
                'payment_verified_by', 
                'payment_verified_at',
                'academic_approved_by',
                'academic_approved_at',
                'final_approved_by',
                'final_approved_at'
            ]);
        });
    }
};