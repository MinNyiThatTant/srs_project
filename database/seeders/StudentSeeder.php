<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Clear existing students
        DB::table('students')->delete();

        $students = [
            [
                'student_id' => 'WYTU20240001',
                'name' => 'John Doe',
                'email' => 'john.doe@student.wytu.edu.mm',
                'phone' => '09123456789',
                'password' => Hash::make('password123'),
                'department' => 'Computer Engineering and Information Technology',
                'academic_year' => '2024-2025',
                'date_of_birth' => '2000-05-15',
                'gender' => 'male',
                'nrc_number' => '12/ABCD(N)123456',
                'address' => '123 Main Street, Yangon, Myanmar',
                'status' => 'active',
                'registration_date' => now(),
                'needs_password_change' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 'WYTU20240002',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@student.wytu.edu.mm',
                'phone' => '09123456780',
                'password' => Hash::make('password123'),
                'department' => 'Civil Engineering',
                'academic_year' => '2024-2025',
                'date_of_birth' => '2001-08-20',
                'gender' => 'female',
                'nrc_number' => '12/EFGH(N)123457',
                'address' => '456 Oak Avenue, Mandalay, Myanmar',
                'status' => 'active',
                'registration_date' => now(),
                'needs_password_change' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 'WYTU20240003',
                'name' => 'Aung Aung',
                'email' => 'aung.aung@student.wytu.edu.mm',
                'phone' => '09123456781',
                'password' => Hash::make('password123'),
                'department' => 'Electronics Engineering',
                'academic_year' => '2024-2025',
                'date_of_birth' => '2000-12-10',
                'gender' => 'male',
                'nrc_number' => '12/IJKL(N)123458',
                'address' => '789 Pine Road, Naypyidaw, Myanmar',
                'status' => 'active',
                'registration_date' => now(),
                'needs_password_change' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('Sample students created successfully!');
        $this->command->info('Student 1: WYTU20240001 / password123');
        $this->command->info('Student 2: WYTU20240002 / password123');
        $this->command->info('Student 3: WYTU20240003 / password123');
    }
}