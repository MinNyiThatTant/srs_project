<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'student_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('student_id')->unique()->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'student_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('student_id');
            });
        }
    }
};