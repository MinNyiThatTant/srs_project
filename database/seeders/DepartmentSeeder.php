<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'name' => 'Civil Engineering',
                'code' => 'CIV',
                'description' => 'Civil Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Computer Engineering and Information Technology',
                'code' => 'CEIT',
                'description' => 'CEIT Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electronics Engineering',
                'code' => 'ELEC',
                'description' => 'Electronics Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electrical Power Engineering',
                'code' => 'EP',
                'description' => 'Electrical Power Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Architecture',
                'code' => 'ARCH',
                'description' => 'Architecture Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Biotechnology',
                'code' => 'BIO',
                'description' => 'Biotechnology Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Textile Engineering',
                'code' => 'TEX',
                'description' => 'Textile Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Engineering',
                'code' => 'MECH',
                'description' => 'Mechanical Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chemical Engineering',
                'code' => 'CHEM',
                'description' => 'Chemical Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Automobile Engineering',
                'code' => 'AE',
                'description' => 'Automobile Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechatronic Engineering',
                'code' => 'MCE',
                'description' => 'Mechatronic Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Metallurgy Engineering',
                'code' => 'MET',
                'description' => 'Metallurgy Engineering Department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Use DB facade to ensure all fields are explicitly set
        foreach ($departments as $department) {
            DB::table('departments')->insertOrIgnore($department);
        }
    }
}