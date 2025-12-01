<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentAssignedFieldsToApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('department_assigned_by')->nullable()->after('final_approved_at');
            $table->timestamp('department_assigned_at')->nullable()->after('department_assigned_by');
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['department_assigned_by', 'department_assigned_at']);
        });
    }
}