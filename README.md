```bash
composer create-project laravel/laravel^12.0 srs_project
php artisan migrate
php artisan migrate:fresh

php artisan make:middleware AdminRedirect
php artisan make:middleware AdminAuthenticate

php artisan make:controller LoginController
php artisan make:view login


php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:clear
php artisan optimize:clear


//builtin auth
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev

//IDE helper
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate

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