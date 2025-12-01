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

Route::get('new-student-apply', [ApplicationController::class, 'newStudentForm'])->name('new.student.apply');
Route::get('old-student-apply', [ApplicationController::class, 'oldStudentForm'])->name('old.student.apply');
Route::post('submit-application', [ApplicationController::class, 'submitApplication'])->name('submit.application');
Route::get('application/success/{id}', [ApplicationController::class, 'applicationSuccess'])->name('application.success');

// Application status routes
Route::get('applications/{applicationId}', [ApplicationController::class, 'show'])->name('applications.show');
Route::get('applications/{applicationId}/pay', [ApplicationController::class, 'paymentPage'])->name('applications.payment');
Route::get('applications/{applicationId}/status', [ApplicationController::class, 'checkStatus'])->name('applications.status');

// ========== PAYMENT ROUTES ==========

// Payment Routes
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
Route::post('check-student-id', [ApplicationController::class, 'checkStudentId'])->name('check.student.id');
<<<<<<< HEAD
Route::post('/check-email', [ApplicationController::class, 'checkEmail'])->name('check.email');
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

// ========== DEPARTMENT & COURSE ROUTES ==========

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
    });

    // Protected Student Routes
    Route::middleware(['auth:student'])->group(function () {
        Route::post('logout', [StudentAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::post('profile/update', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('change-password', [StudentAuthController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('change-password', [StudentAuthController::class, 'changePassword'])->name('password.change.submit');
        Route::get('payments', [StudentDashboardController::class, 'paymentHistory'])->name('payments');
    });
});

// ========== AUTHENTICATED USER ROUTES ==========

Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ========== ADMIN ROUTES ==========

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

<<<<<<< HEAD
    // Academic Admin Routes
    Route::get('academic-dashboard', [HaaController::class, 'dashboard'])->name('academic.dashboard');
    Route::get('applications/academic', [HaaController::class, 'academicApplications'])->name('applications.academic');
    Route::get('applications/assigned', [HaaController::class, 'assignedApplications'])->name('applications.assigned');
    Route::get('application/view/{id}', [HaaController::class, 'viewApplication'])->name('applications.view');

    // Department assignment and approval routes
    Route::post('academic/assign-department/{id}', [HaaController::class, 'assignDepartment'])->name('academic.assign-department');
    Route::post('academic/quick-assign/{id}', [HaaController::class, 'quickAssign'])->name('academic.quick-assign');
    Route::post('academic-approve/{id}', [HaaController::class, 'academicApprove'])->name('applications.academic-approve');
    Route::post('academic-reject/{id}', [HaaController::class, 'academicReject'])->name('applications.academic-reject');

=======
   // ========== ACADEMIC ADMIN ROUTES ==========
Route::get('academic-dashboard', [HaaController::class, 'dashboard'])->name('academic.dashboard');
Route::get('applications/academic', [HaaController::class, 'academicApplications'])->name('applications.academic');
Route::post('academic-approve/{id}', [HaaController::class, 'academicApprove'])->name('applications.academic-approve');
Route::post('academic-reject/{id}', [HaaController::class, 'academicReject'])->name('applications.academic-reject');
Route::get('haa', [HaaController::class, 'dashboard'])->name('haa');
Route::get('academic-affairs', [HaaController::class, 'academicAffairs'])->name('academic-affairs');
Route::get('academic/affairs', [HaaController::class, 'academicAffairs'])->name('academic.affairs');
Route::get('course-management', [HaaController::class, 'courseManagement'])->name('course.management');
Route::post('approve-academic/{id}', [HaaController::class, 'approveAcademic'])->name('approve.academic');
Route::get('application/view/{id}', [HaaController::class, 'viewApplication'])->name('applications.view');
Route::post('final-approve/{id}', [HaaController::class, 'finalApprove'])->name('applications.final-approve');
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

    // ========== HOD ROUTES ==========
    Route::get('hod-dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('hod', [HodController::class, 'dashboard'])->name('hod');
    Route::get('applications/hod', [HodController::class, 'hodApplications'])->name('applications.hod');
    Route::post('final-approve/{id}', [HodController::class, 'finalApprove'])->name('applications.final-approve');
    Route::post('approve-final/{id}', [HodController::class, 'approveFinal'])->name('approve.final');
<<<<<<< HEAD
    Route::post('applications/hod-reject/{id}', [HodController::class, 'hodReject'])->name('applications.hod-reject');
    Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
    Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');
    Route::get('department-students', [HodController::class, 'departmentStudents'])->name('department.students');
=======
    Route::get('my-department', [HodController::class, 'myDepartment'])->name('my-department');
    Route::get('department-applications', [HodController::class, 'departmentApplications'])->name('department.applications');
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

    // Staff Management Routes
    Route::get('hod/staff', [HodController::class, 'staffIndex'])->name('hod.staff.index');
    Route::post('hod/staff', [HodController::class, 'staffStore'])->name('hod.staff.store');
    Route::put('hod/staff/{id}', [HodController::class, 'staffUpdate'])->name('hod.staff.update');
    Route::delete('hod/staff/{id}', [HodController::class, 'staffDestroy'])->name('hod.staff.destroy');

<<<<<<< HEAD

=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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

// ========== DEBUG & TESTING ROUTES  ==========

if (app()->environment('local')) {
    // Simulation routes
    Route::get('payment/simulate/success/{payment}', [PaymentController::class, 'simulateSuccess'])->name('payment.simulate.success');
    Route::get('payment/simulate/failure/{payment}', [PaymentController::class, 'simulateFailure'])->name('payment.simulate.failure');

    // Test application route
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

<<<<<<< HEAD
    // Debug route for academic admin
    Route::get('/debug-academic-routes', function () {
        $routes = [
            'admin.academic.dashboard' => route('admin.academic.dashboard'),
            'admin.applications.academic' => route('admin.applications.academic'),
            'admin.applications.view' => route('admin.applications.view', 1),
            'admin.academic.quick-assign' => route('admin.academic.quick-assign', 1),
            'admin.academic.assign-department' => route('admin.academic.assign-department', 1),
            'admin.applications.academic-approve' => route('admin.applications.academic-approve', 1),
            'admin.applications.academic-reject' => route('admin.applications.academic-reject', 1),
        ];

        return response()->json($routes);
    });


    // Debug route to check academic routes
    Route::get('/debug-academic-routes-list', function () {
        $routes = [
            'admin.academic.dashboard' => 'Exists: ' . (Route::has('admin.academic.dashboard') ? 'YES' : 'NO'),
            'admin.applications.academic' => 'Exists: ' . (Route::has('admin.applications.academic') ? 'YES' : 'NO'),
            'admin.applications.view' => 'Exists: ' . (Route::has('admin.applications.view') ? 'YES' : 'NO'),
            'admin.academic.quick-assign' => 'Exists: ' . (Route::has('admin.academic.quick-assign') ? 'YES' : 'NO'),
            'admin.academic.assign-department' => 'Exists: ' . (Route::has('admin.academic.assign-department') ? 'YES' : 'NO'),
            'admin.applications.academic-approve' => 'Exists: ' . (Route::has('admin.applications.academic-approve') ? 'YES' : 'NO'),
            'admin.applications.academic-reject' => 'Exists: ' . (Route::has('admin.applications.academic-reject') ? 'YES' : 'NO'),
        ];

        echo "<h3>Academic Route Status:</h3>";
        foreach ($routes as $name => $status) {
            echo "<strong>{$name}</strong>: {$status}<br>";
        }

        echo "<h3>All routes with 'academic' or 'applications':</h3>";
        $allRoutes = Route::getRoutes();
        foreach ($allRoutes as $route) {
            if (method_exists($route, 'getName') && $route->getName()) {
                $name = $route->getName();
                if (str_contains($name, 'academic') || str_contains($name, 'applications')) {
                    echo "<strong>{$name}</strong>: {$route->uri}<br>";
                }
            }
        }
    });


=======
    // Debug route for academic routes
    Route::get('/debug-academic-routes', function () {
        $academicRoutes = collect(\Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return str_contains($route->uri, 'academic') ||
                    str_contains($route->uri, 'admin') ||
                    (method_exists($route, 'getName') && $route->getName() && str_contains($route->getName(), 'academic'));
            })
            ->map(function ($route) {
                return [
                    'name' => $route->getName(),
                    'uri' => $route->uri,
                    'action' => $route->getActionName(),
                ];
            });

        return response()->json($academicRoutes->values());
    });

>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    // Test route to check if admin.academic.dashboard exists
    Route::get('/test-admin-academic-route', function () {
        try {
            $url = route('admin.academic.dashboard');
            return "SUCCESS: Route exists! URL: " . $url;
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    });

    // Test email route
    Route::get('/test-email', function () {
        try {
            \Mail::raw('Test email from WYTU System', function ($message) {
                $message->to('test@example.com')
                    ->subject('Test Email from Laravel');
            });
            return 'Email sent successfully!';
        } catch (\Exception $e) {
            return 'Email error: ' . $e->getMessage();
        }
    });

    // Test payment simulation route
    Route::get('/test-payment-success/{applicationId}', function ($applicationId) {
        try {
            $application = \App\Models\Application::findOrFail($applicationId);

            \Illuminate\Support\Facades\DB::beginTransaction();

            // Create fake payment record
            $payment = \App\Models\Payment::create([
                'application_id' => $application->id,
                'amount' => 50000,
                'payment_method' => 'test',
                'status' => 'completed',
                'transaction_id' => 'TEST_TXN_' . \Illuminate\Support\Str::random(10),
                'paid_at' => now(),
            ]);

            // Update application status
            $application->update([
                'payment_status' => 'verified',
                'status' => 'payment_verified'
            ]);

            // Create student account for new students
            if ($application->application_type === 'new') {
                $studentId = 'STU' . date('y') . 'TEST' . \Illuminate\Support\Str::random(3);
                $password = \Illuminate\Support\Str::random(12);

                $student = \App\Models\Student::create([
                    'student_id' => $studentId,
                    'application_id' => $application->id,
                    'name' => $application->name,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'department' => $application->department,
                    'date_of_birth' => $application->date_of_birth,
                    'gender' => $application->gender,
                    'nrc_number' => $application->nrc_number,
                    'address' => $application->address,
                    'status' => 'active',
                    'registration_date' => now(),
                ]);

                $application->update(['student_id' => $studentId]);

                // Send email
                \Illuminate\Support\Facades\Mail::send('emails.student-credentials', [
                    'student' => $student,
                    'password' => $password,
                    'loginUrl' => url('/login')
                ], function ($message) use ($student) {
                    $message->to($student->email)
                        ->subject('Your Student Account Credentials - WYTU');
                });
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('payment.success', $application->id)
                ->with('success', 'Test payment completed successfully! Student credentials sent to email.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Test payment failed: ' . $e->getMessage());
        }
    });

    // Test payment page - shows all pending applications for testing
    Route::get('/test-payments', function () {
        $applications = \App\Models\Application::where('status', 'payment_pending')
            ->where('payment_status', 'pending')
            ->get();

        return view('test-payments', compact('applications'));
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




// Add this to your web.php temporarily
<<<<<<< HEAD
Route::get('/debug-application/{id}', function ($id) {
    try {
        $application = \App\Models\Application::find($id);

=======
Route::get('/debug-application/{id}', function($id) {
    try {
        $application = \App\Models\Application::find($id);
        
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        if (!$application) {
            return response()->json([
                'error' => 'Application not found in database',
                'searched_id' => $id
            ], 404);
        }

        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'application_id' => $application->application_id,
                'name' => $application->name,
                'status' => $application->status,
                'payment_status' => $application->payment_status
            ],
            'payment_route' => route('payment.show', $application->id),
            'payment_url' => url('/payment/' . $application->id)
        ]);
<<<<<<< HEAD
=======
        
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
<<<<<<< HEAD
});
=======
});
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
