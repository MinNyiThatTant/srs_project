<?

// Admin authenticated routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Global Admin routes
    Route::middleware(['global.admin'])->group(function () {
        Route::get('all-applications', [ApplicationApprovalController::class, 'allApplications'])->name('applications.all');
        Route::get('global', [GlobalAdminController::class, 'index'])->name('global');
        Route::get('users', [GlobalAdminController::class, 'users'])->name('users');
        Route::get('applications', [GlobalAdminController::class, 'applications'])->name('applications');
        
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
});