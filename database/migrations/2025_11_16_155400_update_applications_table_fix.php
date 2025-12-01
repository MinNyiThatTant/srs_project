<?php
// database/migrations/2024_01_01_000003_update_applications_table_fix.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('applications', 'application_id')) {
                $table->string('application_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('applications', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('applications', 'student_id')) {
                $table->string('student_id')->nullable()->after('previous_qualification');
            }
            // Add other missing columns as needed
        });
    }

    public function down()
    {
        // We don't drop columns to avoid data loss
    }
};