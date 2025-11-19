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
            // Make family and education fields nullable
            $table->string('father_name')->nullable()->change();
            $table->string('mother_name')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->string('nrc_number')->nullable()->change();
            $table->string('high_school_name')->nullable()->change();
            $table->text('high_school_address')->nullable()->change();
            $table->integer('graduation_year')->nullable()->change();
            $table->decimal('matriculation_score', 5, 2)->nullable()->change();
            $table->string('previous_qualification')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Revert back to not nullable if needed
            $table->string('father_name')->nullable(false)->change();
            $table->string('mother_name')->nullable(false)->change();
            $table->string('nationality')->nullable(false)->change();
            $table->string('nrc_number')->nullable(false)->change();
            $table->string('high_school_name')->nullable(false)->change();
            $table->text('high_school_address')->nullable(false)->change();
            $table->integer('graduation_year')->nullable(false)->change();
            $table->decimal('matriculation_score', 5, 2)->nullable(false)->change();
            $table->string('previous_qualification')->nullable(false)->change();
        });
    }
};