<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\FaController;
use App\Http\Controllers\admin\GlobalAdminController;
use App\Http\Controllers\admin\HaaController;
use App\Http\Controllers\admin\HodController;
use App\Http\Controllers\admin\HsaController;
use App\Http\Controllers\admin\TeacherController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\ApplicationApprovalController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('department', [HomeController::class, 'department'])->name('department');
Route::get('courses', [HomeController::class, 'courses'])->name('courses');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('about', [HomeController::class, 'about'])->name('about');

// Application routes
Route::get('new-student-apply', [ApplicationController::class, 'newStudentForm'])->name('new.student.apply');
Route::get('old-student-apply', [ApplicationController::class, 'oldStudentForm'])->name('old.student.apply');
Route::post('submit-application', [ApplicationController::class, 'submitApplication'])->name('submit.application');
Route::get('application/success/{id}', [ApplicationController::class, 'applicationSuccess'])->name('application.success');

// Application status routes
Route::get('applications/{applicationId}', [ApplicationController::class, 'show'])->name('applications.show');
Route::get('applications/{applicationId}/pay', [ApplicationController::class, 'paymentPage'])->name('applications.payment');
Route::get('applications/{applicationId}/status', [ApplicationController::class, 'checkStatus'])->name('applications.status');

// Payment Routes
Route::get('payment/show/{applicationId}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('payment/process/{applicationId}', [PaymentController::class, 'process'])->name('payment.process');
Route::get('payment/success/{applicationId}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/cancel/{applicationId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('payment/webhook/gateway/{gateway}', [PaymentController::class, 'webhook'])->name('payment.webhook.gateway');

// Additional payment routes
Route::post('applications/{applicationId}/initiate-payment', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::post('payment/handle-webhook', [PaymentController::class, 'handleWebhook'])->name('payment.handle.webhook');
Route::get('payment/check-status/{transactionId}', [PaymentController::class, 'checkStatus'])->name('payment.check.status');
Route::get('payment/callback-handler', [PaymentController::class, 'callback'])->name('payment.callback.handler');
Route::post('/payment/callback/kbz', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::get('applications/{applicationId}/retry-payment', [PaymentController::class, 'retryPayment'])->name('payment.retry');

// Simulation routes (remove in production)
if (app()->environment('local', 'testing')) {
    Route::get('payment/simulate/success/{payment}', [PaymentController::class, 'simulateSuccess'])->name('payment.simulate.success');
    Route::get('payment/simulate/failure/{payment}', [PaymentController::class, 'simulateFailure'])->name('payment.simulate.failure');
}

// Validation routes
Route::post('check-nrc', [ApplicationController::class, 'checkNrc'])->name('check.nrc');
Route::post('check-student-id', [ApplicationController::class, 'checkStudentId'])->name('check.student.id');

// Department routes
Route::get('department1', [DeptController::class, 'department1'])->name('home.department1');

// Course routes
Route::get('c1', [DeptController::class, 'c1'])->name('home.coursecode');
Route::get('c2', [DeptController::class, 'c2'])->name('home.coursecode1');
Route::get('c3', [DeptController::class, 'c3'])->name('home.coursecode2');
Route::get('c4', [DeptController::class, 'c4'])->name('home.coursecode3');
Route::get('c5', [DeptController::class, 'c5'])->name('home.coursecode4');
Route::get('c6', [DeptController::class, 'c6'])->name('home.coursecode5');
Route::get('c7', [DeptController::class, 'c7'])->name('home.coursecode6');

// University info
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

// Student routes (guest middleware)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::post('process-register', [LoginController::class, 'processRegister'])->name('processRegister');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
});



// Student Auth Routes
Route::get('student/login', [LoginController::class, 'studentLogin'])->name('student.login');
Route::post('student/login', [LoginController::class, 'processStudentLogin'])->name('student.login.process');
Route::get('student/logout', [LoginController::class, 'logout'])->name('student.logout');

// Student Dashboard Routes (protected by student session)
Route::middleware(['student.auth'])->group(function () {
    Route::get('student/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('student/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::get('student/application-status', [StudentDashboardController::class, 'applicationStatus'])->name('student.application.status');
});

// Update main dashboard to handle both admin and student
Route::get('dashboard', function () {
    if (session('student')) {
        return redirect()->route('student.dashboard');
    }
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');



// Student Registration Routes
Route::get('/student/register', [StudentRegistrationController::class, 'showRegistrationForm'])->name('student.register');
Route::post('/student/register', [StudentRegistrationController::class, 'register'])->name('student.register.process');
Route::get('/student/registration-success', [StudentRegistrationController::class, 'registrationSuccess'])->name('student.registration.success');

// Student Authentication Routes
Route::get('/student/login', [App\Http\Controllers\Auth\LoginController::class, 'showStudentLoginForm'])->name('student.login');
Route::post('/student/login', [App\Http\Controllers\Auth\LoginController::class, 'studentLogin'])->name('student.login.process');

// Student Dashboard Routes (protected)
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('student.profile');
    Route::post('/student/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('student.profile.update');
});

// Admin Student Management Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/students/pending', [App\Http\Controllers\Admin\StudentController::class, 'pending'])->name('admin.students.pending');
    Route::get('/students/approved', [App\Http\Controllers\Admin\StudentController::class, 'approved'])->name('admin.students.approved');
    Route::post('/students/{id}/approve-finance', [App\Http\Controllers\Admin\StudentController::class, 'approveFinance'])->name('admin.students.approve.finance');
    Route::post('/students/{id}/approve-haa', [App\Http\Controllers\Admin\StudentController::class, 'approveHaa'])->name('admin.students.approve.haa');
    Route::post('/students/{id}/reject', [App\Http\Controllers\Admin\StudentController::class, 'reject'])->name('admin.students.reject');
});



// ========== ADMIN ROUTES ==========

// Admin login routes for guests
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// Main admin dashboard route - This should be the first route after login
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // MAIN DASHBOARD - This route MUST be defined
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // ========== GLOBAL ADMIN ROUTES ==========
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

    // ========== FINANCE ADMIN ROUTES ==========
    Route::get('finance-dashboard', [FaController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('applications/finance', [FaController::class, 'financeApplications'])->name('applications.finance');
    Route::post('verify-payment/{id}', [FaController::class, 'verifyPayment'])->name('applications.verify-payment');
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

    // ========== ACADEMIC ADMIN ROUTES ==========
    Route::get('academic-dashboard', [HaaController::class, 'dashboard'])->name('academic.dashboard');
    Route::get('applications/academic', [HaaController::class, 'academicApplications'])->name('applications.academic');
    Route::post('academic-approve/{id}', [HaaController::class, 'academicApprove'])->name('applications.academic-approve');
    Route::post('academic-reject/{id}', [HaaController::class, 'academicReject'])->name('applications.academic-reject');
    Route::get('haa', [HaaController::class, 'dashboard'])->name('haa');
    Route::get('academic-affairs', [HaaController::class, 'academicAffairs'])->name('academic-affairs');
    Route::get('course-management', [HaaController::class, 'courseManagement'])->name('course.management');
    Route::post('approve-academic/{id}', [HaaController::class, 'approveAcademic'])->name('approve.academic');

    // ========== HOD ADMIN ROUTES ==========
    // Route::prefix('admin')->group(function () {
    // Route::get('hod-dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    // Route::get('hod', [HodController::class, 'dashboard'])->name('hod');
    // HOD Dashboard
    Route::get('hod-dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('hod', [HodController::class, 'dashboard'])->name('hod');

    // HOD Applications
    Route::get('applications/hod', [HodController::class, 'hodApplications'])->name('applications.hod');
    Route::post('final-approve/{id}', [HodController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve.final');

    // Department Management
    Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
    Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');

    // Staff Management - ADD THESE EXACT ROUTES
    Route::get('hod/staff', [HodController::class, 'staffIndex'])->name('hod.staff.index');
    Route::post('hod/staff', [HodController::class, 'staffStore'])->name('hod.staff.store');
    Route::put('hod/staff/{id}', [HodController::class, 'staffUpdate'])->name('hod.staff.update');
    Route::delete('hod/staff/{id}', [HodController::class, 'staffDestroy'])->name('hod.staff.destroy');
    // );

    // ========== HSA ADMIN ROUTES ==========
    Route::get('hsa-dashboard', [HsaController::class, 'dashboard'])->name('hsa.dashboard');
    Route::get('hsa', [HsaController::class, 'dashboard'])->name('hsa');
    Route::get('staff-management', [HsaController::class, 'staffManagement'])->name('staff.management');
    Route::get('teacher-management', [HsaController::class, 'teacherManagement'])->name('teacher.management');
    Route::post('assign-teacher/{id}', [HsaController::class, 'assignTeacher'])->name('assign.teacher');
    
    // ========== TEACHER ADMIN ROUTES ==========
    Route::get('teacher-dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('teacher', [TeacherController::class, 'dashboard'])->name('teacher');
    Route::get('my-students', [TeacherController::class, 'myStudents'])->name('my.students');
    Route::get('student-progress/{id}', [TeacherController::class, 'studentProgress'])->name('student.progress');

    // ========== COMMON ADMIN ROUTES (accessible by all admins) ==========
    Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('applications/{id}', [ApplicationApprovalController::class, 'viewApplication'])->name('applications.view');
});

// ========== LEGACY ROUTES (Keep for backward compatibility) ==========
Route::get('choose-login', function () {
    return redirect('/');
})->name('choose.login');

// ========== DEBUG ROUTES (Remove in production) ==========
if (app()->environment('local')) {
    Route::get('/test-application', function () {
        try {
            $app = App\Models\Application::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '09123456789',
                'father_name' => 'Test Father',
                'mother_name' => 'Test Mother',
                'date_of_birth' => '2000-01-01',
                'gender' => 'male',
                'nationality' => 'Myanmar',
                'nrc_number' => '12/TEST(N)123457',
                'address' => 'Test Address',
                'application_type' => 'new',
                'department' => 'Computer Engineering and Information Technology',
                'high_school_name' => 'Test School',
                'high_school_address' => 'Test School Address',
                'graduation_year' => 2023,
                'matriculation_score' => 450.00,
                'previous_qualification' => 'High School',
                'status' => 'payment_pending',
                'payment_status' => 'pending'
            ]);

            return "Test application created successfully! ID: " . $app->id;
        } catch (\Exception $e) {
            return "Error creating test application: " . $e->getMessage();
        }
    });

    Route::get('/debug-admin-routes', function () {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return "No admin logged in. <a href='/admin/login'>Login here</a>";
        }

        echo "<h3>Current Admin:</h3>";
        echo "Name: " . $admin->name . "<br>";
        echo "Email: " . $admin->email . "<br>";
        echo "Role: " . $admin->role . "<br>";
        echo "ID: " . $admin->id . "<br>";

        echo "<h3>Available Routes:</h3>";
        echo "<a href='" . route('admin.dashboard') . "'>Main Dashboard</a><br>";

        if ($admin->role === 'global_admin') {
            echo "<a href='" . route('admin.global.dashboard') . "'>Global Dashboard</a><br>";
            echo "<a href='" . route('admin.global') . "'>Legacy Global Route</a><br>";
        }

        echo "<h3>Session Data:</h3>";
        dump(session()->all());
    });
}

// ========== PAYMENT DEBUG ROUTES ==========
Route::get('/check-payments', function () {
    $payments = App\Models\Payment::with('application')->get();

    echo "<h3>Payments in Database:</h3>";
    foreach ($payments as $payment) {
        echo "ID: {$payment->id}<br>";
        echo "Transaction: {$payment->transaction_id}<br>";
        echo "Application: {$payment->application->name} ({$payment->application->application_id})<br>";
        echo "Amount: {$payment->amount}<br>";
        echo "Status: {$payment->status}<br>";
        echo "Method: {$payment->payment_method}<br>";
        echo "Created: {$payment->created_at}<br>";
        echo "Paid At: " . ($payment->paid_at ? $payment->paid_at : 'Not paid') . "<br>";
        echo "<hr>";
    }
});

Route::get('/check-payment-config', function () {
    echo "<h3>Payment Configuration:</h3>";

    $configs = [
        'payment.admission_fee',
        'payment.kpay_base_url',
        'payment.kpay_merchant_id',
        'payment.kpay_secret_key',
    ];

    foreach ($configs as $config) {
        $value = config($config);
        echo "{$config}: " . ($value ? $value : 'NOT SET') . "<br>";
    }
});









//////////////////////////////////////////////////////////////////////////////////////////////
// Debug route - add this temporarily
Route::get('/check-hod-routes', function () {
    echo "Checking HOD routes...<br><br>";

    $routes = [
        'hod.dashboard',
        'applications.hod',
        'hod.staff.index',
        'hod.staff.store',
        'hod.staff.update',
        'hod.staff.destroy'
    ];

    foreach ($routes as $routeName) {
        if (Route::has($routeName)) {
            echo "✓ Route '$routeName' EXISTS<br>";
        } else {
            echo "✗ Route '$routeName' NOT FOUND<br>";
        }
    }
});
/////////////////////////////////////////////////////////////////////////////////////////////
