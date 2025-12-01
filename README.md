```bash
composer create-project laravel/laravel^12.0 srs_project
<<<<<<< HEAD
composer install
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan migrate
php artisan serve

=======
php artisan migrate
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
php artisan migrate:fresh

php artisan make:middleware AdminRedirect
php artisan make:middleware AdminAuthenticate

php artisan make:controller LoginController
php artisan make:view login

<<<<<<< HEAD

php artisan migrate
=======
///
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:clear
php artisan optimize:clear
composer dump-autoload





php artisan make:migration add_is_active_to_admins_table --table=admins
php artisan migrate
php artisan route:list --name=applications
php artisan db:seed --class=AdminSeeder
php artisan migrate:fresh --seed
php artisan make:seeder DepartmentSeeder
findstr /s "departments" database\*.php
php artisan make:model Staff -m
php artisan make:migration update_payment_method_column_length_in_payments_table
php artisan migrate
php artisan make:migration add_student_id_to_students_table


php artisan tinker
$app = \App\Models\Application::latest()->first();
echo "Status: " . $app->status . "\n";
echo "Payment Status: " . $app->payment_status . "\n";
exit;
```




http://localhost:8000/admin/academic-dashboard


http://localhost:8000/admin/applications/academic




php artisan tinker
\Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns('payments');

``bash
php artisan route:list --name=hod
php artisan route:list | findstr "hsa"
php artisan route:list | grep academic
php artisan route:list | findstr academic
```
--------------------------------


Mail::raw('Test email', function($message) {
    $message->to('royalmntt@gmail.com')->subject('Test');
});

<<<<<<< HEAD
------------------------------------------------------------------
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

__________________________
http://localhost/srs_project/public/admin/finance-applications
http://localhost/srs_project/public/admin/fa
http://localhost/srs_project/public/admin/pending-payments

<<<<<<< HEAD
------------------------------------------------------------------


#roles account sample data
=======
```bash
php artisan db:seed
```




//roles account sample data
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (3, 'Global Administrator', 'global.admin@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'global_admin', NULL, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (4, 'CS HOD Admin', 'cs.hod@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'hod_admin', 1, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (5, 'EE HOD Admin', 'ee.hod@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'hod_admin', 2, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (6, 'ME HOD Admin', 'me.hod@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'hod_admin', 3, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (7, 'HAA Administrator', 'haa.admin@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'haa_admin', NULL, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (8, 'HSA Administrator', 'hsa.admin@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'hsa_admin', NULL, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (9, 'Teacher Administrator', 'teacher.admin@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'teacher_admin', 1, NOW(), NOW(), NOW());
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `email_verified_at`, `created_at`, `updated_at`) VALUES (10, 'Finance Administrator', 'fa.admin@wytu.edu.mm', '$2y$12$bYBKQoJSewCbRnBaabqSJO3l3iYIeWoYVE9Cz4FlCJdA1gZ85aPU.', 'fa_admin', NULL, NOW(), NOW(), NOW());
__________________________

php artisan db:seed --class=UsersTableSeeder

//builtin auth
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev

//IDE helper
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate

```


```bash
php artisan make:migration create_departments_table
php artisan make:migration create_users_table
php artisan make:migration create_semesters_table
php artisan make:migration create_academic_years_table
php artisan make:migration create_teachers_table
php artisan make:migration create_courses_table
php artisan make:migration create_students_table
php artisan make:migration create_class_schedules_table
php artisan make:migration create_registrations_table
php artisan make:migration create_finances_table
```
______________________________________________________________________________________________________________
## 🧑‍🎓Student Registration System(SRS) created by <span style="color: blue;">E-service-Group(1/2025)</span>

SRS is a web-based application designed to streamline the process of student registration and course management for Technological University targeting WYTU(sample).

## 🎯 Goal

- SRS is to facilitate the registration process for students, whether they are new or returning. 
- To provide a user-friendly interface that allows students to register for courses, update their personal information, and track their academic progress. 
- For administrators, SRS offers tools to manage departments, courses, and student registrations efficiently.

## 🛠️ Laravel Implementation

### 📦 Contents
1. 📢[Overview](#overview)
2. ℹ️[Features](#features)
3. 💻[Installation](#installation)
<!-- 4. [Database Schema](#database-schema) -->
<!-- 5. [Implementation Steps](#implementation-steps) -->
<!-- 6. [Additional Functions](#additional-functions) -->

## 📢 Overview <a name="overview"></a>
SRS built with Laravel framework that handles both new and old student registrations with department management, course management and class scheduling.

## ℹ️ Features <a name="features"></a>
1. **Student Management**
   - New student registration and profile
   - Old student registration and profile
   - Student status 

2. **Academic Management**
   - Department creation and management
   - Course creation and assignment
   - Semester and academic year configuration

3. **Administration**
   - Role management
   - Class scheduling
   - Registration approval workflow

4. **Financial Status**
   - Payment records
   - Financial status monitoring

## 💻 Installation <a name="installation"></a>

### ⚙️ Prerequisites
- [PHP 8.0 or higher](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [MySQL](https://dev.mysql.com/downloads/mysql/)
- [XAMPP](https://www.techspot.com/downloads/7358-xampp.html)
- [npm](https://icons.getbootstrap.com/) (if need, node_module for bootstrap theme & icons)
```bash
npm i bootstrap-icons
```

### 👣 Installation Steps

1. **Install Laravel Framework**
```bash
composer global require laravel/installer
```
- go to your project directory and run artisan

2. **If you need dependencies**
```bash
composer install
npm install
```

3. **Generate key**
```bash
php artisan key:generate
```

4. **Run Migration**
```bash
php artisan migrate
```

5. **To serve your project**
```bash
php artisan serve
```
______________________________________________________________________________________________________________