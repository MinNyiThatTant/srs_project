<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add emergency contact columns
            if (!Schema::hasColumn('applications', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('previous_year_status');
            }
            
            if (!Schema::hasColumn('applications', 'emergency_phone')) {
                $table->string('emergency_phone')->nullable()->after('emergency_contact');
            }
            
            // Also check for other missing columns that might be needed
            if (!Schema::hasColumn('applications', 'signature')) {
                $table->string('signature')->nullable()->after('emergency_phone');
            }
            
            if (!Schema::hasColumn('applications', 'declaration_accepted')) {
                $table->boolean('declaration_accepted')->default(false)->after('signature');
            }
            
            if (!Schema::hasColumn('applications', 'terms_accepted')) {
                $table->boolean('terms_accepted')->default(false)->after('declaration_accepted');
            }
            
            if (!Schema::hasColumn('applications', 'needs_academic_approval')) {
                $table->boolean('needs_academic_approval')->default(true)->after('terms_accepted');
            }
            
            if (!Schema::hasColumn('applications', 'academic_approval_status')) {
                $table->string('academic_approval_status')->default('pending')->after('needs_academic_approval');
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Remove columns if rolling back
            $table->dropColumn([
                'emergency_contact',
                'emergency_phone',
                'signature',
                'declaration_accepted',
                'terms_accepted',
                'needs_academic_approval',
                'academic_approval_status'
            ]);
        });
    }
};