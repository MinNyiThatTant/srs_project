<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Global Administrator',
            'email' => 'global@admin.com',
            'password' => Hash::make('password'),
            'role' => 'global_admin',
            'department' => null,
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Finance Admin',
            'email' => 'finance@admin.com',
            'password' => Hash::make('password'),
            'role' => 'fa_admin',
            'department' => null,
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Academic Admin',
            'email' => 'academic@admin.com',
            'password' => Hash::make('password'),
            'role' => 'haa_admin',
            'department' => null,
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'HOD - Computer Engineering',
            'email' => 'hod.ceit@admin.com',
            'password' => Hash::make('password'),
            'role' => 'hod_admin',
            'department' => 'Computer Engineering and Information Technology',
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'HSA Admin',
            'email' => 'hsa@admin.com',
            'password' => Hash::make('password'),
            'role' => 'hsa_admin',
            'department' => null,
            'status' => 'active',
        ]);

        Admin::create([
            'name' => 'Teacher Admin',
            'email' => 'teacher@admin.com',
            'password' => Hash::make('password'),
            'role' => 'teacher_admin',
            'department' => null,
            'status' => 'active',
        ]);
    }
}