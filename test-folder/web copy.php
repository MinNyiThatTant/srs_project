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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\admin\ApplicationApprovalController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Home routes - This is the main page now
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('department', [HomeController::class, 'department'])->name('department');
Route::get('courses', [HomeController::class, 'courses'])->name('courses');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('about', [HomeController::class, 'about'])->name('about');

// Application routes (Updated with digital payment integration)
Route::get('new-student-apply', [ApplicationController::class, 'newStudentForm'])->name('new.student.apply');
Route::get('old-student-apply', [ApplicationController::class, 'oldStudentForm'])->name('old.student.apply');
Route::post('submit-application', [ApplicationController::class, 'submitApplication'])->name('submit.application');
Route::get('application/success/{id}', [ApplicationController::class, 'applicationSuccess'])->name('application.success');

// Digital Payment Routes (New Integration)
Route::get('applications/{applicationId}', [ApplicationController::class, 'show'])->name('applications.show');
Route::get('applications/{applicationId}/pay', [ApplicationController::class, 'paymentPage'])->name('applications.payment');
Route::get('applications/{applicationId}/status', [ApplicationController::class, 'checkStatus'])->name('applications.status');

// ========== CORRECTED PAYMENT ROUTES ==========
// Enhanced Payment Processing Routes (FIXED - No conflicts)
Route::get('payment/show/{applicationId}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('payment/process/{applicationId}', [PaymentController::class, 'process'])->name('payment.process');
Route::get('payment/success/{applicationId}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/cancel/{applicationId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('payment/webhook/gateway/{gateway}', [PaymentController::class, 'webhook'])->name('payment.webhook.gateway');

// Keep existing payment routes for compatibility (RENAMED to avoid conflicts)
Route::post('applications/{applicationId}/initiate-payment', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::post('payment/handle-webhook', [PaymentController::class, 'handleWebhook'])->name('payment.handle.webhook');
Route::get('payment/check-status/{transactionId}', [PaymentController::class, 'checkStatus'])->name('payment.check.status');
Route::get('payment/callback-handler', [PaymentController::class, 'callback'])->name('payment.callback.handler');
Route::post('/payment/callback/kbz', [PaymentController::class, 'handleCallback'])->name('payment.callback');
Route::get('applications/{applicationId}/retry-payment', [PaymentController::class, 'retryPayment'])->name('payment.retry');
// ========== END PAYMENT ROUTES ==========

// Simulation routes (remove in production)
if (app()->environment('local', 'testing')) {
    Route::get('payment/simulate/success/{payment}', [PaymentController::class, 'simulateSuccess'])->name('payment.simulate.success');
    Route::get('payment/simulate/failure/{payment}', [PaymentController::class, 'simulateFailure'])->name('payment.simulate.failure');
}

// Check NRC and Student ID routes
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

// Student authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin routes (guest middleware)
Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
});

// Admin authenticated routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Application approval routes with digital payment integration
    // Finance Admin (FA) routes
    Route::middleware(['fa.admin'])->group(function () {
        Route::get('finance-applications', [ApplicationApprovalController::class, 'financeApplications'])->name('applications.finance');
        Route::post('verify-payment/{id}', [ApplicationApprovalController::class, 'verifyPayment'])->name('applications.verify-payment');
        
        Route::get('fa', [FaController::class, 'index'])->name('fa');
        Route::get('financial-reports', [FaController::class, 'financialReports'])->name('financial-reports');
        Route::get('fee-management', [FaController::class, 'feeManagement'])->name('fee.management');
        Route::post('mark-fee-paid/{id}', [FaController::class, 'markFeePaid'])->name('fee.paid');
        Route::get('payment/{id}', [FaController::class, 'viewPayment'])->name('payment.view');
        Route::get('payment-statistics', [FaController::class, 'paymentStatistics'])->name('payment.statistics');
        Route::get('pending-payments', [FaController::class, 'pendingPayments'])->name('pending.payments');
        Route::post('update-payment-status/{id}', [FaController::class, 'updatePaymentStatus'])->name('payment.status.update');
    });
    
    
        // Digital payment management
        Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payment-details/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('payment-refund/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('payments.refund');
    

// Admin Application Routes
Route::prefix('admin/applications')->name('admin.applications.')->group(function () {
    Route::get('/all', [ApplicationController::class, 'allApplications'])->name('all');
    Route::get('/academic', [ApplicationController::class, 'academicApplications'])->name('academic');
    Route::get('/finance', [ApplicationController::class, 'financeApplications'])->name('finance');
    Route::get('/hod', [ApplicationController::class, 'hodApplications'])->name('hod');
    Route::get('/view/{id}', [ApplicationController::class, 'viewApplication'])->name('view');
    
    // Action routes
    Route::post('/verify-payment/{id}', [ApplicationController::class, 'verifyPayment'])->name('verify-payment');
    Route::post('/academic-approve/{id}', [ApplicationController::class, 'academicApprove'])->name('academic-approve');
    Route::post('/academic-reject/{id}', [ApplicationController::class, 'academicReject'])->name('academic-reject');
    Route::post('/final-approve/{id}', [ApplicationController::class, 'finalApprove'])->name('final-approve');
});
    


    // HAA Admin routes
    // Academic Affairs Admin (HAA) routes
    Route::middleware(['haa.admin'])->group(function () {
        Route::get('academic-applications', [ApplicationApprovalController::class, 'academicApplications'])->name('applications.academic');
        Route::post('academic-approve/{id}', [ApplicationApprovalController::class, 'academicApprove'])->name('applications.academic-approve');
        Route::post('academic-reject/{id}', [ApplicationApprovalController::class, 'academicReject'])->name('applications.academic-reject');
        
        Route::get('haa', [HaaController::class, 'index'])->name('haa');
        Route::get('academic-affairs', [HaaController::class, 'academicAffairs'])->name('academic-affairs');
        Route::get('course-management', [HaaController::class, 'courseManagement'])->name('course.management');
        Route::post('approve-academic/{id}', [HaaController::class, 'approveAcademic'])->name('approve.academic');
    });

    // HOD Admin routes
    Route::middleware(['hod.admin'])->group(function () {
        Route::get('hod-applications', [ApplicationApprovalController::class, 'hodApplications'])->name('applications.hod');
        Route::post('final-approve/{id}', [ApplicationApprovalController::class, 'finalApprove'])->name('applications.final-approve');
        
        Route::get('hod', [HodController::class, 'index'])->name('hod.dashboard');
        Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
        Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');
        Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve.final');
    });

    // Global Admin routes
    Route::middleware(['global.admin'])->group(function () {
        Route::get('all-applications', [ApplicationApprovalController::class, 'allApplications'])->name('applications.all');
        Route::get('global', [GlobalAdminController::class, 'index'])->name('global');
        Route::get('users', [GlobalAdminController::class, 'users'])->name('users');
        Route::get('applications', [GlobalAdminController::class, 'applications'])->name('applications');
        Route::post('applications/{id}/reject', [ApplicationApprovalController::class, 'rejectApplication'])->name('applications.reject');
        
        // New Global Admin routes
        Route::get('global-payments', [GlobalAdminController::class, 'payments'])->name('global.payments');
        Route::get('global-reports', [GlobalAdminController::class, 'reports'])->name('global.reports');
        Route::get('global/users/{id}', [GlobalAdminController::class, 'viewUser'])->name('global.users.view');
        Route::get('global/applications/{id}', [GlobalAdminController::class, 'viewApplication'])->name('global.applications.view');
        Route::post('global/applications/{id}/verify-payment', [GlobalAdminController::class, 'verifyPayment'])->name('global.applications.verify-payment');
        Route::post('global/applications/{id}/academic-approve', [GlobalAdminController::class, 'academicApprove'])->name('global.applications.academic-approve');
        Route::post('global/applications/{id}/final-approve', [GlobalAdminController::class, 'finalApprove'])->name('global.applications.final-approve');
        Route::post('global/applications/{id}/reject', [GlobalAdminController::class, 'rejectApplication'])->name('global.applications.reject');
        Route::post('global/bulk-actions', [GlobalAdminController::class, 'bulkActions'])->name('global.bulk-actions');
    });


    // HSA Admin routes
    Route::middleware(['hsa.admin'])->group(function () {
        Route::get('hsa', [HsaController::class, 'index'])->name('hsa');
        Route::get('staff-management', [HsaController::class, 'staffManagement'])->name('staff-management');
        Route::get('teacher-management', [HsaController::class, 'teacherManagement'])->name('teacher.management');
        Route::post('assign-teacher/{id}', [HsaController::class, 'assignTeacher'])->name('assign.teacher');
    });

    // Teacher Admin routes
    Route::middleware(['teacher.admin'])->group(function () {
        Route::get('teacher', [TeacherController::class, 'index'])->name('teacher');
        Route::get('teacher-dashboard', [TeacherController::class, 'teacherDashboard'])->name('teacher.dashboard');
        Route::get('my-students', [TeacherController::class, 'myStudents'])->name('my.students');
        Route::get('student-progress/{id}', [TeacherController::class, 'studentProgress'])->name('student.progress');
    });

    // Common routes for all admins
    Route::get('applications/{id}', [ApplicationApprovalController::class, 'viewApplication'])->name('applications.view');

    // Enhanced application management routes
    Route::get('applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/{application}/show', [App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('applications.show');
    Route::post('applications/{application}/approve-academic', [App\Http\Controllers\Admin\ApplicationController::class, 'approveAcademic'])->name('applications.approve-academic');
    Route::post('applications/{application}/approve-final', [App\Http\Controllers\Admin\ApplicationController::class, 'approveFinal'])->name('applications.approve-final');
    Route::post('applications/{application}/reject', [App\Http\Controllers\Admin\ApplicationController::class, 'reject'])->name('applications.reject');
});



// Keep the choose-login route for backward compatibility
Route::get('choose-login', function () {
    return redirect('/');
})->name('choose.login');

// Authentication Routes (Laravel UI)
// Auth::routes(['register' => false]); // Disable default registration since we have custom one

// routes/web.php - Temporary debug route
Route::get('/test-application', function() {
    try {
        // Test creating a simple application
        $app = App\Models\Application::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '09123456789',
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother', 
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'nationality' => 'Myanmar',
            'nrc_number' => '12/TEST(N)123457', // Different NRC
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
        return "Error creating test application: " . $e->getMessage() . 
               "<br>File: " . $e->getFile() . 
               "<br>Line: " . $e->getLine() .
               "<br>Trace: " . $e->getTraceAsString();
    }
});

// Test payment route
Route::get('/test-payment/{applicationId}', function($applicationId) {
    try {
        $application = App\Models\Application::find($applicationId);
        if (!$application) {
            return "Application not found";
        }
        
        return "Application found: " . $application->id . 
               "<br>Route URL: " . route('payment.show', $application->id) .
               "<br><a href='" . route('payment.show', $application->id) . "'>Go to Payment Page</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});





////////////////////////////////////////////////////////////////////////////////


// Debug routes
Route::get('/debug-flow/{id?}', function($id = null) {
    try {
        if ($id) {
            $application = App\Models\Application::find($id);
            if (!$application) {
                return "Application not found with ID: " . $id;
            }
            
            return "
            <h3>Debug Application Flow</h3>
            <p><strong>Application ID (DB):</strong> {$application->id}</p>
            <p><strong>Application Display ID:</strong> {$application->application_id}</p>
            <p><strong>Status:</strong> {$application->status}</p>
            <p><strong>Payment Status:</strong> {$application->payment_status}</p>
            <hr>
            <h4>Test Links:</h4>
            <p><a href='" . route('application.success', $application->id) . "' class='btn btn-primary'>Test Success Page</a></p>
            <p><a href='" . route('payment.show', $application->id) . "' class='btn btn-success'>Test Payment Page</a></p>
            <hr>
            <h4>Route URLs:</h4>
            <p>Success: " . route('application.success', $application->id) . "</p>
            <p>Payment: " . route('payment.show', $application->id) . "</p>
            ";
        } else {
            return "
            <h3>Debug Application Flow</h3>
            <p>No application ID provided. Create a test application first.</p>
            <form action='/test-create-application' method='POST'>
                " . csrf_field() . "
                <button type='submit' class='btn btn-primary'>Create Test Application</button>
            </form>
            ";
        }
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::post('/test-create-application', function() {
    try {
        $application = App\Models\Application::create([
            'application_id' => 'TEST' . strtoupper(Str::random(8)) . date('Ymd'),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '09123456789',
            'nrc_number' => '12/TEST(N)' . time(),
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'nationality' => 'Myanmar',
            'address' => 'Test Address',
            'application_type' => 'new',
            'department' => 'Computer Engineering and Information Technology',
            'high_school_name' => 'Test School',
            'high_school_address' => 'Test School Address',
            'graduation_year' => 2023,
            'matriculation_score' => 450.00,
            'previous_qualification' => 'High School',
            'status' => 'payment_pending',
            'payment_status' => 'pending',
        ]);

        return redirect('/debug-flow/' . $application->id);
    } catch (\Exception $e) {
        return "Error creating test application: " . $e->getMessage();
    }
});


////////////////////////////////////////////////////////////////////////////////

// Temporary route to display payment config (for debugging only)
// Admin payment management routes
Route::middleware(['fa.admin'])->group(function () {
    Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payment-details/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('payment-refund/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('payments.refund');
    Route::get('payments/export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
});
////////////////////////////////////////////////////////////////////////////////


// In routes/web.php - Add this temporary route
Route::get('/check-payments', function() {
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
    
    $applications = App\Models\Application::all();
    echo "<h3>Applications:</h3>";
    foreach ($applications as $app) {
        echo "ID: {$app->id} | App ID: {$app->application_id} | Status: {$app->status} | Payment Status: {$app->payment_status}<br>";
    }
});

////////////////////////////////////////////////////////////////////////////////

// Add to routes/web.php
Route::get('/test-payment-process/{applicationId}', function($applicationId) {
    try {
        $application = App\Models\Application::findOrFail($applicationId);
        
        // Create a payment record manually
        $payment = App\Models\Payment::create([
            'application_id' => $application->id,
            'amount' => 50000,
            'payment_method' => 'kpay',
            'status' => App\Models\Payment::STATUS_PENDING,
            'transaction_id' => 'TEST_TXN_' . time()
        ]);
        
        // Update application status
        $application->update([
            'status' => 'payment_processing',
            'payment_status' => 'processing'
        ]);
        
        return "Payment created for application {$application->application_id}<br>
                Payment ID: {$payment->id}<br>
                Transaction ID: {$payment->transaction_id}<br>
                <a href='" . route('payment.show', $application->id) . "'>Go to Payment Page</a>";
                
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});


// Add to routes/web.php for testing
Route::get('/test-payment-controller/{applicationId}', function($applicationId) {
    try {
        $controller = new App\Http\Controllers\PaymentController(
            new App\Services\PaymentGatewayService()
        );
        
        // Test show method
        $response = $controller->show($applicationId);
        
        return "PaymentController show method works!<br>
                Application ID: {$applicationId}<br>
                <a href='" . route('payment.show', $applicationId) . "'>Test Payment Page</a>";
                
    } catch (\Exception $e) {
        return "Error in PaymentController: " . $e->getMessage();
    }
});



Route::get('/check-payment-table', function() {
    try {
        $columns = \DB::select('DESCRIBE payments');
        echo "<h3>Payments Table Structure:</h3>";
        foreach ($columns as $column) {
            echo "Field: {$column->Field} | Type: {$column->Type} | Null: {$column->Null} | Key: {$column->Key}<br>";
        }
        
        // Check if table has data
        $count = App\Models\Payment::count();
        echo "<br>Total payments in database: {$count}";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});


Route::get('/test-payment-success/{paymentId}', function($paymentId) {
    try {
        $payment = App\Models\Payment::findOrFail($paymentId);
        
        // Manually mark as completed
        $payment->markAsCompleted('TEST_REF_' . time(), ['test' => true]);
        
        return "Payment marked as completed!<br>
                Payment ID: {$payment->id}<br>
                Status: {$payment->status}<br>
                Application Status: {$payment->application->status}<br>
                <a href='" . route('payment.success', $payment->application->id) . "'>View Success Page</a>";
                
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});


Route::get('/check-payment-routes', function() {
    $routes = [
        'payment.show',
        'payment.process', 
        'payment.success',
        'payment.cancel',
        'payment.webhook.gateway',
        'payment.initiate',
        'payment.handle.webhook',
        'payment.check.status',
        'payment.callback.handler',
        'payment.retry'
    ];
    
    echo "<h3>Payment Routes Check:</h3>";
    foreach ($routes as $route) {
        try {
            $url = route($route, ['applicationId' => 1, 'gateway' => 'kpay']);
            echo "✓ {$route}: {$url}<br>";
        } catch (\Exception $e) {
            echo "✗ {$route}: ERROR - " . $e->getMessage() . "<br>";
        }
    }
});


Route::get('/payment-debug-live', function() {
    // Get recent payment activities
    $recentPayments = App\Models\Payment::with('application')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    $recentApplications = App\Models\Application::where('status', 'like', '%payment%')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    echo "<h3>Recent Payment Activities:</h3>";
    foreach ($recentPayments as $payment) {
        echo "Payment: {$payment->transaction_id} | Status: {$payment->status} | Method: {$payment->payment_method} | Created: {$payment->created_at->diffForHumans()}<br>";
    }
    
    echo "<h3>Recent Payment-related Applications:</h3>";
    foreach ($recentApplications as $app) {
        echo "App: {$app->application_id} | Status: {$app->status} | Payment Status: {$app->payment_status} | Created: {$app->created_at->diffForHumans()}<br>";
    }
    
    // Auto-refresh every 5 seconds
    echo '<script>setTimeout(function(){ location.reload(); }, 5000);</script>';
});


Route::get('/check-payment-config', function() {
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
    
    echo "<h3>Environment Variables:</h3>";
    $envVars = ['KPAY_MERCHANT_ID', 'KPAY_SECRET_KEY', 'KPAY_BASE_URL'];
    foreach ($envVars as $envVar) {
        echo "{$envVar}: " . (env($envVar) ? 'SET' : 'NOT SET') . "<br>";
    }
});








// In routes/web.php
Route::get('/debug-dashboard', function() {
    // Get recent logs (last 50 lines)
    $logFile = storage_path('logs/laravel.log');
    $logs = file_exists($logFile) 
        ? implode('', array_slice(file($logFile), -50))
        : 'No logs yet.';
    
    // Get payment stats
    $paymentStats = [
        'total' => App\Models\Payment::count(),
        'completed' => App\Models\Payment::where('status', 'completed')->count(),
        'pending' => App\Models\Payment::where('status', 'pending')->count(),
        'failed' => App\Models\Payment::where('status', 'failed')->count(),
    ];
    
    // Get recent payments
    $recentPayments = App\Models\Payment::with('application')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    return view('debug.dashboard', compact('logs', 'paymentStats', 'recentPayments'));
});
////////////////////////////////////////////////////////////////////////////////