<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,  // This must be first
            AdminSeeder::class,       // This depends on departments
            // Add other seeders here
        ]);
    }
}




// public function run()
//     {
//         // Create admin user
//         DB::table('users')->insert([
//             'name' => 'admin',
//             'email' => 'admin@wytu.edu',
//             'password' => Hash::make('password'),
//             'email_verified_at' => now(),
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);

//         // Create sample departments
//         $departments = [
//             'Computer Engineering and Information Technology',
//             'Electrical Engineering', 
//             'Mechanical Engineering',
//             'Civil Engineering',
//             'Business Administration',
//             'Computer Science',
//             'Information Technology'
//         ];

//         foreach ($departments as $department) {
//             DB::table('departments')->insert([
//                 'name' => $department,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }
//     }