<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'needs_password_change')) {
                $table->boolean('needs_password_change')->default(true)->after('registration_date');
            }
            
            if (!Schema::hasColumn('students', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('needs_password_change');
            }
            
            if (!Schema::hasColumn('students', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('profile_picture');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['needs_password_change', 'profile_picture', 'last_login_at']);
        });
    }
};