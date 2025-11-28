<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // REMOVE the DB insert statements - let the seeder handle this
        // The seeder will insert the departments with the correct codes
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};