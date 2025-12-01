<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->decimal('cgpa', 3, 2)->default(0.00)->after('academic_year');
            $table->enum('academic_standing', ['excellent', 'good', 'warning', 'probation'])->default('good')->after('cgpa');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['cgpa', 'academic_standing']);
        });
    }
};