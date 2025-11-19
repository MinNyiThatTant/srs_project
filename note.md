//if not already, routes/web.php

// Application routes
```bash
Route::get('new-student-apply', [ApplicationController::class, 'newStudentForm'])->name('new.student.apply');
Route::get('old-student-apply', [ApplicationController::class, 'oldStudentForm'])->name('old.student.apply');
Route::post('submit-application', [ApplicationController::class, 'submitApplication'])->name('submit.application');
Route::get('application/success/{id}', [ApplicationController::class, 'applicationSuccess'])->name('application.success');
Route::get('application/check-status', [ApplicationController::class, 'checkStatus'])->name('application.check.status');
Route::post('application/view-status', [ApplicationController::class, 'viewStatus'])->name('application.view.status');
```