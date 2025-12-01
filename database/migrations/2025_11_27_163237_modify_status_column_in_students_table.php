<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyStatusColumnInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Check the current column type and modify it
        Schema::table('students', function (Blueprint $table) {
            // Change status column to string type with appropriate length
            $table->string('status', 20)->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert back to previous state if needed
            $table->string('status', 10)->default('active')->change();
        });
    }
}