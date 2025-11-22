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

// ========== PUBLIC ROUTES ==========

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

// Student password setup routes
Route::get('setup-password/{applicationId}', [LoginController::class, 'setupPassword'])->name('setup.password');
Route::post('setup-password/{applicationId}', [LoginController::class, 'processSetupPassword'])->name('setup.password.process');

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

// ========== ADMIN ROUTES ==========

// Admin login routes for guests
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// Main admin dashboard route - NO CUSTOM MIDDLEWARE
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // MAIN DASHBOARD
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
    // NO MIDDLEWARE - accessible by any logged in admin for now
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('dashboard', [FaController::class, 'dashboard'])->name('dashboard');
        
        // Payment Verification
        Route::get('pending-verifications', [FaController::class, 'pendingVerifications'])->name('pending-verifications');
        Route::get('pending-payments', [FaController::class, 'pendingPayments'])->name('pending-payments');
        Route::post('verify-payment/{id}', [FaController::class, 'verifyPayment'])->name('verify-payment');
        
        // Application Approval
        Route::get('payment-verified', [FaController::class, 'paymentVerifiedApplications'])->name('payment-verified');
        Route::post('approve-application/{id}', [FaController::class, 'approveApplication'])->name('approve-application');
        Route::post('reject-application/{id}', [FaController::class, 'rejectApplication'])->name('reject-application');
        
        // Views
        Route::get('application/{id}/view', [FaController::class, 'viewApplication'])->name('application.view');
        
        // Reports
        Route::get('financial-reports', [FaController::class, 'financialReports'])->name('financial-reports');
        
        // Legacy routes
        Route::get('applications', [FaController::class, 'financeApplications'])->name('applications');
        Route::get('payment-statistics', [FaController::class, 'paymentStatistics'])->name('payment-statistics');
        Route::get('fee-management', [FaController::class, 'feeManagement'])->name('fee-management');
    });

    // ========== HAA ADMIN ROUTES ==========
    // NO MIDDLEWARE - accessible by any logged in admin for now
    Route::prefix('academic')->name('academic.')->group(function () {
        Route::get('dashboard', [HaaController::class, 'dashboard'])->name('dashboard');
        
        // Application Approval
        Route::get('applications', [HaaController::class, 'academicApplications'])->name('applications');
        Route::post('approve-application/{id}', [HaaController::class, 'approveApplication'])->name('approve-application');
        Route::post('final-approve-application/{id}', [HaaController::class, 'finalApproveApplication'])->name('final-approve-application');
        Route::post('reject-application/{id}', [HaaController::class, 'rejectApplication'])->name('reject-application');
        
        // Views
        Route::get('application/{id}/view', [HaaController::class, 'viewApplication'])->name('application.view');
        
        // Student Management
        Route::get('students', [HaaController::class, 'studentManagement'])->name('students');
        
        // Academic Affairs
        Route::get('affairs', [HaaController::class, 'academicAffairs'])->name('affairs');
        Route::get('courses', [HaaController::class, 'courseManagement'])->name('courses');
    });

    // ========== HOD ADMIN ROUTES ==========
    Route::prefix('hod')->name('hod.')->group(function () {
        Route::get('dashboard', [HodController::class, 'dashboard'])->name('dashboard');
        Route::get('applications', [HodController::class, 'hodApplications'])->name('applications');
        Route::post('final-approve/{id}', [HodController::class, 'finalApprove'])->name('final-approve');
        Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve-final');

        // Department Management
        Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
        Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department-applications');

        // Staff Management
        Route::get('staff', [HodController::class, 'staffIndex'])->name('staff.index');
        Route::post('staff', [HodController::class, 'staffStore'])->name('staff.store');
        Route::put('staff/{id}', [HodController::class, 'staffUpdate'])->name('staff.update');
        Route::delete('staff/{id}', [HodController::class, 'staffDestroy'])->name('staff.destroy');
    });

    // ========== HSA ADMIN ROUTES ==========
    Route::prefix('hsa')->name('hsa.')->group(function () {
        Route::get('dashboard', [HsaController::class, 'dashboard'])->name('dashboard');
        Route::get('staff-management', [HsaController::class, 'staffManagement'])->name('staff-management');
        Route::get('teacher-management', [HsaController::class, 'teacherManagement'])->name('teacher-management');
        Route::post('assign-teacher/{id}', [HsaController::class, 'assignTeacher'])->name('assign-teacher');
    });

    // ========== TEACHER ADMIN ROUTES ==========
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('my-students', [TeacherController::class, 'myStudents'])->name('my-students');
        Route::get('student-progress/{id}', [TeacherController::class, 'studentProgress'])->name('student-progress');
    });

    // ========== COMMON ADMIN ROUTES ==========
    Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('applications/{id}', [ApplicationApprovalController::class, 'viewApplication'])->name('applications.view');
});

// ========== DEBUG ROUTES ==========
if (app()->environment('local')) {
    // Simulation routes
    Route::get('payment/simulate/success/{payment}', [PaymentController::class, 'simulateSuccess'])->name('payment.simulate.success');
    Route::get('payment/simulate/failure/{payment}', [PaymentController::class, 'simulateFailure'])->name('payment.simulate.failure');
    
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

    Route::get('/debug-routes', function () {
        echo "<h3>Testing Finance Routes:</h3>";
        echo "<a href='" . route('admin.finance.pending-verifications') . "'>Finance Pending Verifications</a><br>";
        echo "<a href='" . route('admin.finance.dashboard') . "'>Finance Dashboard</a><br>";
        echo "<a href='" . route('admin.dashboard') . "'>Main Admin Dashboard</a><br>";
        
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            echo "<h3>Current Admin:</h3>";
            echo "Name: " . $admin->name . "<br>";
            echo "Email: " . $admin->email . "<br>";
            echo "Role: " . $admin->role . "<br>";
        } else {
            echo "<p>No admin logged in. <a href='/admin/login'>Login here</a></p>";
        }
    });

    // Clear cache route
    Route::get('/clear-cache', function () {
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        return "Cache cleared successfully!";
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