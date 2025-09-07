<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        // Insert sample departments
        DB::table('departments')->insert([
            ['name' => 'Civil Engineering', 'code' => 'CIV', 'description' => 'Civil Engineering Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Computer Engineering and Information Technology', 'code' => 'CEIT', 'description' => 'CEIT Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electronics Engineering', 'code' => 'ELEC', 'description' => 'Electronics Engineering Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electrical Power Engineering', 'code' => 'EP', 'description' => 'Electrical Power Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Architecture', 'code' => 'ARCH', 'description' => 'Architecture Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biotechnology', 'code' => 'BIO', 'description' => 'Biotechnology Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Textile Engineering', 'code' => 'TEX', 'description' => 'Textile Engineering Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mechanical Engineering', 'code' => 'MECH', 'description' => 'Mechanical Engineering Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemical Engineering', 'code' => 'CHEM', 'description' => 'Chemical Engineering Department', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};