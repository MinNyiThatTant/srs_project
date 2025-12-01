<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'name' => 'Global Administrator',
                'email' => 'global@admin.com',
                'password' => Hash::make('password'),
                'role' => 'global_admin',
                'department' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Finance Admin',
                'email' => 'finance@admin.com',
                'password' => Hash::make('password'),
                'role' => 'fa_admin',
                'department' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Academic Admin',
                'email' => 'academic@admin.com',
                'password' => Hash::make('password'),
                'role' => 'haa_admin',
                'department' => null,
                'status' => 'active',
            ],
<<<<<<< HEAD
            // HOD Accounts - Make sure department names match exactly with your system
=======
            // HOD Accounts - Make sure department names match exactly with DepartmentSeeder
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            [
                'name' => 'HOD - Computer Engineering',
                'email' => 'hod.ceit@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Computer Engineering and Information Technology',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Civil Engineering',
                'email' => 'hod.ce@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Civil Engineering',
                'status' => 'active',
            ],
            [
<<<<<<< HEAD
                'name' => 'HOD - Electronic Engineering', 
                'email' => 'hod.ee@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Electronic Engineering', 
=======
                'name' => 'HOD - Electronics Engineering',
                'email' => 'hod.ee@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Electronics Engineering',
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Electrical Power Engineering',
                'email' => 'hod.epe@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Electrical Power Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Architecture',
                'email' => 'hod.arch@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Architecture',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Biotechnology',
                'email' => 'hod.bio@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Biotechnology',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Textile Engineering',
                'email' => 'hod.te@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Textile Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Mechanical Engineering',
                'email' => 'hod.me@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Mechanical Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Chemical Engineering',
                'email' => 'hod.che@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Chemical Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Automobile Engineering',
                'email' => 'hod.ae@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Automobile Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Mechatronic Engineering',
                'email' => 'hod.mce@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Mechatronic Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HOD - Metallurgy Engineering',
                'email' => 'hod.met@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hod_admin',
                'department' => 'Metallurgy Engineering',
                'status' => 'active',
            ],
            [
                'name' => 'HSA Admin',
                'email' => 'hsa@admin.com',
                'password' => Hash::make('password'),
                'role' => 'hsa_admin',
                'department' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Teacher Admin',
                'email' => 'teacher@admin.com',
                'password' => Hash::make('password'),
                'role' => 'teacher_admin',
                'department' => null,
                'status' => 'active',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}