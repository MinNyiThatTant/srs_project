<?php
// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Global Administrator',
                'email' => 'global.admin@wytu.edu.mm',
                'password' => Hash::make('password123'), // Change this password
                'role' => 'global_admin',
                'department_id' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CS HOD Admin',
                'email' => 'cs.hod@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'hod_admin',
                'department_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'EE HOD Admin',
                'email' => 'ee.hod@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'hod_admin',
                'department_id' => 2,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ME HOD Admin',
                'email' => 'me.hod@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'hod_admin',
                'department_id' => 3,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HAA Administrator',
                'email' => 'haa.admin@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'haa_admin',
                'department_id' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HSA Administrator',
                'email' => 'hsa.admin@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'hsa_admin',
                'department_id' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teacher Administrator',
                'email' => 'teacher.admin@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'teacher_admin',
                'department_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance Administrator',
                'email' => 'fa.admin@wytu.edu.mm',
                'password' => Hash::make('password123'),
                'role' => 'fa_admin',
                'department_id' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
        
        $this->command->info('Admin users seeded successfully!');
        $this->command->info('All users have password: password123');
    }
}