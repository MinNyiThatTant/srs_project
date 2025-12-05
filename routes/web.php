<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\FaController;
use App\Http\Controllers\Admin\GlobalAdminController;
use App\Http\Controllers\Admin\HaaController;
use App\Http\Controllers\Admin\HodController;
use App\Http\Controllers\Admin\HsaController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ApplicationApprovalController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('department', [HomeController::class, 'department'])->name('department');
Route::get('courses', [HomeController::class, 'courses'])->name('courses');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('about', [HomeController::class, 'about'])->name('about');

// ========== AUTHENTICATION ROUTES ==========

// Main login route for students (public)
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

// Student registration routes
Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('process-register', [LoginController::class, 'processRegister'])->name('processRegister');

// Admin login routes for guests
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// ========== APPLICATION ROUTES ==========

// New Student Application
Route::get('new-student-apply', [ApplicationController::class, 'newStudentForm'])->name('new.student.apply');
Route::post('submit-application', [ApplicationController::class, 'submitApplication'])->name('submit.application');

// Old Student Application
Route::get('old-student-apply', [ApplicationController::class, 'oldStudentForm'])->name('old.student.apply');
Route::post('student/verify', [ApplicationController::class, 'verifyStudent'])->name('student.verify');
Route::post('old-student/submit', [ApplicationController::class, 'submitExistingApplication'])->name('old.student.submit');

// Application success and status routes
Route::get('application/success/{id}', [ApplicationController::class, 'applicationSuccess'])->name('application.success');
Route::get('applications/{applicationId}', [ApplicationController::class, 'show'])->name('applications.show');
Route::get('applications/{applicationId}/pay', [ApplicationController::class, 'paymentPage'])->name('applications.payment');
Route::get('applications/{applicationId}/status', [ApplicationController::class, 'checkStatus'])->name('applications.status');



// Clear verification
Route::get('clear-verification', [ApplicationController::class, 'clearVerification'])->name('old.student.clear');

// ========== TEST ROUTES ==========

// Test route to create student
Route::get('/test/create-student', function () {
    try {
        // Check if student exists
        $exists = DB::table('students')
            ->where('student_id', 'WYTU202400001')
            ->exists();
        
        if ($exists) {
            return "✅ Test student already exists!<br>
                   Student ID: WYTU202400001<br>
                   Password: password123<br>
                   DOB: 2000-01-01<br>
                   <a href='/old-student-apply'>Go to application</a>";
        }
        
        // Create test student
        DB::table('students')->insert([
            'student_id' => 'WYTU202400001',
            'name' => 'John Doe',
            'email' => 'john@student.com',
            'phone' => '09123456789',
            'password' => Hash::make('password123'),
            'department' => 'Computer Engineering and Information Technology',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'nrc_number' => '12/ABC(N)123456',
            'address' => 'Test Address, Yangon',
            'current_year' => 'first_year',
            'academic_year' => '2024-2025',
            'cgpa' => 3.5,
            'status' => 'active',
            'registration_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return "✅ Test student created successfully!<br>
               Student ID: WYTU202400001<br>
               Password: password123<br>
               DOB: 2000-01-01<br>
               <a href='/old-student-apply'>Go to application</a>";
               
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});


Route::get('/test-form-submit', function() {
    // Simulate a POST request to test
    $response = Http::post(url('/old-student/submit'), [
        '_token' => csrf_token(),
        'current_year' => '2',
        'previous_year_status' => 'passed',
        'cgpa' => '3.5',
        'application_purpose' => 'course_registration',
        'phone' => '09123456789',
        'address' => 'Test address',
        'reason' => 'This is a test reason for continuing studies at WYTU University.',
        'emergency_contact' => 'Emergency Person',
        'emergency_phone' => '09123456788',
        'declaration_accuracy' => '1',
        'declaration_fee' => '1',
        'declaration_rules' => '1',
        'signature' => 'Test Student'
    ]);
    
    return $response->body();
});











// ========== STUDENT PASSWORD ROUTES ==========
Route::get('student/forgot-password', [StudentController::class, 'forgotPassword'])->name('student.forgot-password');
Route::post('student/send-password-reset', [StudentController::class, 'sendPasswordReset'])->name('student.send.password.reset');

// ========== PAYMENT ROUTES ==========

Route::get('/payment/{applicationId}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/process/{applicationId}', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success/{applicationId}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel/{applicationId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/status/{applicationId}', [PaymentController::class, 'checkStatus'])->name('payment.status');
Route::get('/payment/verify/{transactionId}', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

// Additional payment routes
Route::post('applications/{applicationId}/initiate-payment', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::post('payment/handle-webhook', [PaymentController::class, 'handleWebhook'])->name('payment.handle.webhook');
Route::get('payment/check-status/{transactionId}', [PaymentController::class, 'checkStatus'])->name('payment.check.status');
Route::get('payment/callback-handler', [PaymentController::class, 'callback'])->name('payment.callback.handler');
Route::post('/payment/callback/kbz', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::get('applications/{applicationId}/retry-payment', [PaymentController::class, 'retryPayment'])->name('payment.retry');

// ========== VALIDATION ROUTES ==========

Route::post('check-nrc', [ApplicationController::class, 'checkNrc'])->name('check.nrc');
Route::post('check-email', [ApplicationController::class, 'checkEmail'])->name('check.email');

// ========== DEPARTMENT & COURSE ROUTES ==========

Route::get('department1', [DeptController::class, 'department1'])->name('home.department1');
Route::get('c1', [DeptController::class, 'c1'])->name('home.coursecode');
Route::get('c2', [DeptController::class, 'c2'])->name('home.coursecode1');
Route::get('c3', [DeptController::class, 'c3'])->name('home.coursecode2');
Route::get('c4', [DeptController::class, 'c4'])->name('home.coursecode3');
Route::get('c5', [DeptController::class, 'c5'])->name('home.coursecode4');
Route::get('c6', [DeptController::class, 'c6'])->name('home.coursecode5');
Route::get('c7', [DeptController::class, 'c7'])->name('home.coursecode6');

// ========== UNIVERSITY INFO ==========

Route::get('univ-info', function () {
    $universityInfo = [
        'name' => 'West Yangon Technological University',
        'motto' => 'Innovation Through Technology',
        'founded' => 1999,
        'location' => 'Yangon, Myanmar',
        'website' => 'https://www.wytu.edu.mm',
        'students' => 25000,
        'faculty' => 200,
        'logo' => 'images/logo.png',
        'accreditations' => ['MOE Accredited', 'ASEAN University Network'],
        'departments' => [
            'Civil',
            'CEIT',
            'Electronics',
            'Electrical Power',
            'Architecture',
            'Biotechnology',
            'Textile',
            'Mechanical',
            'Chemical'
        ]
    ];
    return view('home.univ-info', compact('universityInfo'));
})->name('univ-info');

// ========== STUDENT AUTH ROUTES ==========

Route::prefix('student')->name('student.')->group(function () {
    // Login Routes
    Route::middleware(['guest:student'])->group(function () {
        Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [StudentAuthController::class, 'login'])->name('login.submit');
        Route::get('forgot-password', [StudentAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('forgot-password', [StudentAuthController::class, 'sendResetLink'])->name('forgot-password.submit');
        Route::get('reset-password/{token}', [StudentAuthController::class, 'showResetPasswordForm'])->name('reset.password');
        Route::post('reset-password', [StudentAuthController::class, 'resetPassword'])->name('reset.password.submit');
    });

    // Protected Student Routes
    Route::middleware(['auth:student'])->group(function () {
        // Auth routes
        Route::post('logout', [StudentAuthController::class, 'logout'])->name('logout');

        // Dashboard & Profile
        Route::get('dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::post('profile/update', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');

        // Password Management
        Route::get('change-password', [StudentAuthController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('change-password', [StudentAuthController::class, 'changePassword'])->name('password.change.submit');

        // Academic & Financial
        Route::get('payments', [StudentDashboardController::class, 'paymentHistory'])->name('payments');
        Route::get('academic-info', [StudentDashboardController::class, 'academicInfo'])->name('academic.info');
        Route::get('fees-info', [StudentDashboardController::class, 'feesInfo'])->name('fees.info');
        Route::get('documents', [StudentDashboardController::class, 'documents'])->name('documents');
    });
});

// ========== AUTHENTICATED USER ROUTES ==========

Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ========== ADMIN ROUTES ==========

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // MAIN DASHBOARD
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // GLOBAL ADMIN ROUTES
    Route::get('global-dashboard', [GlobalAdminController::class, 'dashboard'])->name('global.dashboard');
    Route::get('global', [GlobalAdminController::class, 'dashboard'])->name('global');
    Route::get('applications/all', [GlobalAdminController::class, 'allApplications'])->name('applications.all');
    Route::get('applications/view/{id}', [GlobalAdminController::class, 'viewApplication'])->name('applications.view');
    Route::get('users', [GlobalAdminController::class, 'users'])->name('users');
    Route::get('users/{id}', [GlobalAdminController::class, 'viewUser'])->name('users.view');
    Route::get('payments/global', [GlobalAdminController::class, 'payments'])->name('global.payments');
    Route::get('reports/global', [GlobalAdminController::class, 'reports'])->name('global.reports');
    Route::get('teachers', [GlobalAdminController::class, 'teachers'])->name('teachers.index');
    Route::post('applications/{id}/verify-payment', [GlobalAdminController::class, 'verifyPayment'])->name('applications.verify-payment');
    Route::post('applications/{id}/academic-approve', [GlobalAdminController::class, 'academicApprove'])->name('applications.academic-approve');
    Route::post('applications/{id}/final-approve', [GlobalAdminController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('applications/{id}/reject', [GlobalAdminController::class, 'rejectApplication'])->name('applications.reject');
    Route::post('bulk-actions', [GlobalAdminController::class, 'bulkActions'])->name('bulk-actions');

    // FINANCE ADMIN ROUTES
    Route::get('finance-dashboard', [FaController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('applications/finance', [FaController::class, 'financeApplications'])->name('applications.finance');
    Route::get('verify-payment/{id}', [FaController::class, 'verifyPayment'])->name('applications.verify-payment');
    Route::get('fa', [FaController::class, 'dashboard'])->name('fa');
    Route::get('financial-reports', [FaController::class, 'financialReports'])->name('financial-reports');
    Route::get('fee-management', [FaController::class, 'feeManagement'])->name('fee.management');
    Route::post('mark-fee-paid/{id}', [FaController::class, 'markFeePaid'])->name('fee.paid');
    Route::get('payment/{id}', [FaController::class, 'viewPayment'])->name('payment.view');
    Route::get('payment-statistics', [FaController::class, 'paymentStatistics'])->name('payment.statistics');
    Route::get('pending-payments', [FaController::class, 'pendingPayments'])->name('pending.payments');
    Route::post('update-payment-status/{id}', [FaController::class, 'updatePaymentStatus'])->name('payment.status.update');
    Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payment-details/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('payment-refund/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('payments.refund');
    Route::get('payments/export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    Route::post('verify-payment/{id}', [FaController::class, 'verifyPayment'])->name('applications.verify-payment');

    // ACADEMIC ADMIN ROUTES
    Route::get('academic-dashboard', [HaaController::class, 'dashboard'])->name('academic.dashboard');
    Route::get('applications/academic', [HaaController::class, 'academicApplications'])->name('applications.academic');
    Route::get('applications/assigned', [HaaController::class, 'assignedApplications'])->name('applications.assigned');
    Route::get('application/view/{id}', [HaaController::class, 'viewApplication'])->name('applications.view');
    Route::post('academic/assign-department/{id}', [HaaController::class, 'assignDepartment'])->name('academic.assign-department');
    Route::post('academic/quick-assign/{id}', [HaaController::class, 'quickAssign'])->name('academic.quick-assign');
    Route::post('academic-approve/{id}', [HaaController::class, 'academicApprove'])->name('applications.academic-approve');
    Route::post('academic-reject/{id}', [HaaController::class, 'academicReject'])->name('applications.academic-reject');
    Route::get('haa', [HaaController::class, 'dashboard'])->name('haa');
    Route::get('academic-affairs', [HaaController::class, 'academicAffairs'])->name('academic-affairs');
    Route::get('academic/affairs', [HaaController::class, 'academicAffairs'])->name('academic.affairs');
    Route::get('course-management', [HaaController::class, 'courseManagement'])->name('course.management');
    Route::post('approve-academic/{id}', [HaaController::class, 'approveAcademic'])->name('approve.academic');
    Route::post('final-approve/{id}', [HaaController::class, 'finalApprove'])->name('applications.final-approve');
    Route::get('/old-student-applications', [HaaController::class, 'oldStudentApplications'])->name('old-student.applications');
    Route::get('/old-application/{id}', [HaaController::class, 'viewOldApplication'])->name('old-application.view');
    Route::post('/old-application/{id}/verify', [HaaController::class, 'verifyOldStudent'])->name('old-student.verify');

    // HOD ROUTES
    Route::get('hod-dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('hod', [HodController::class, 'dashboard'])->name('hod');
    Route::get('applications/hod', [HodController::class, 'hodApplications'])->name('applications.hod');
    Route::post('final-approve/{id}', [HodController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve.final');
    Route::post('applications/hod-reject/{id}', [HodController::class, 'hodReject'])->name('applications.hod-reject');
    Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
    Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');
    Route::get('department-students', [HodController::class, 'departmentStudents'])->name('department.students');
    Route::get('hod/staff', [HodController::class, 'staffIndex'])->name('hod.staff.index');
    Route::post('hod/staff', [HodController::class, 'staffStore'])->name('hod.staff.store');
    Route::put('hod/staff/{id}', [HodController::class, 'staffUpdate'])->name('hod.staff.update');
    Route::delete('hod/staff/{id}', [HodController::class, 'staffDestroy'])->name('hod.staff.destroy');

    // HSA ADMIN ROUTES
    Route::get('hsa-dashboard', [HsaController::class, 'dashboard'])->name('hsa.dashboard');
    Route::get('hsa', [HsaController::class, 'dashboard'])->name('hsa');
    Route::get('staff-management', [HsaController::class, 'staffManagement'])->name('staff.management');
    Route::get('teacher-management', [HsaController::class, 'teacherManagement'])->name('teacher.management');
    Route::post('assign-teacher/{id}', [HsaController::class, 'assignTeacher'])->name('assign.teacher');

    // TEACHER ADMIN ROUTES
    Route::get('teacher-dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('teacher', [TeacherController::class, 'dashboard'])->name('teacher');
    Route::get('my-students', [TeacherController::class, 'myStudents'])->name('my.students');
    Route::get('student-progress/{id}', [TeacherController::class, 'studentProgress'])->name('student.progress');

    // COMMON ADMIN ROUTES
    Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings');
});

// ========== LEGACY ROUTES ==========

Route::get('choose-login', function () {
    return redirect('/');
})->name('choose.login');