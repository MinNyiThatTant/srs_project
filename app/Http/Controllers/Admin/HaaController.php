<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use App\Models\Admin;
use App\Models\StudentAcademicHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HaaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $stats = $this->getDashboardStats();

        // Get applications ready for academic review (payment verified by finance)
        $applications = Application::where('status', 'payment_verified')
            ->orderBy('payment_verified_at', 'desc')
            ->paginate(10);

        // Get old student applications that need academic verification
        $oldStudentApps = Application::where('application_type', 'old')
            ->where('status', 'payment_verified')
            ->where('needs_academic_approval', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.academic.dashboard-academic', compact('stats', 'applications', 'oldStudentApps', 'admin'));
    }

    private function getDashboardStats()
    {
        return [
            'pending_reviews' => Application::where('status', 'payment_verified')->count(),
            'pending_old_students' => Application::where('application_type', 'old')
                ->where('status', 'payment_verified')
                ->where('needs_academic_approval', true)
                ->count(),
            'assigned_pending' => Application::where('status', 'department_assigned')->count(),
            'academic_approved' => Application::where('status', 'academic_approved')->count(),
            'total_students' => Student::count(),
            'approved_today' => Application::where('status', 'academic_approved')
                ->whereDate('academic_approved_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'approved'])->count(),
        ];
    }

    public function academicApplications()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        // Get all applications that are payment verified (ready for academic review)
        $applications = Application::where('status', 'payment_verified')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.academic.applications', compact('applications', 'admin'));
    }

    /**
     * Old student applications that need academic verification
     */
    public function oldStudentApplications()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        // Get old student applications that need academic verification
        $applications = Application::where('application_type', 'old')
            ->where('status', 'payment_verified')
            ->where('needs_academic_approval', true)
            ->with(['studentRecord'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.academic.old-student-applications', compact('applications', 'admin'));
    }

    /**
     * View new student application details
     */
    public function viewNewApplication($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::with(['payments'])->findOrFail($id);
        
        if ($application->application_type !== 'new') {
            return redirect()->back()->with('error', 'This is not a new student application.');
        }
        
        $departments = $this->getAllDepartments();
        
        return view('admin.academic.new-application-view', compact('application', 'admin', 'departments'));
    }

    /**
     * View old student application details for academic verification
     */
    public function viewOldApplication($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::with(['payments', 'studentRecord'])->findOrFail($id);
        
        if ($application->application_type !== 'old') {
            return redirect()->back()->with('error', 'This is not an old student application.');
        }

        // Get student's academic history
        $academicHistory = $this->getStudentAcademicHistory($application->student_original_id);
        
        // Check if student has passed previous year
        $eligibilityCheck = $this->checkAcademicEligibility($application->student_original_id, $application->current_year);

        return view('admin.academic.old-application-view', compact('application', 'admin', 'academicHistory', 'eligibilityCheck'));
    }

    /**
     * Verify and approve old student for next academic year
     */
    public function verifyOldStudent(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $request->validate([
            'verification_status' => 'required|in:approved,rejected,pending',
            'remarks' => 'nullable|string|max:1000',
            'conditions' => 'nullable|array',
            'next_year_gpa_requirement' => 'nullable|numeric|min:2|max:4',
            'required_subjects' => 'nullable|array',
        ]);

        $application = Application::findOrFail($id);

        if ($application->application_type !== 'old') {
            return redirect()->back()->with('error', 'This is not an old student application.');
        }

        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not ready for academic verification.');
        }

        DB::beginTransaction();

        try {
            if ($request->verification_status === 'approved') {
                // Verify academic eligibility
                if (!$this->checkAcademicEligibility($application->student_original_id, $application->current_year)) {
                    return redirect()->back()->with('error', 'Student has not passed the previous academic year.');
                }

                // Update application with academic approval
                $application->update([
                    'status' => 'academic_approved',
                    'academic_approval_status' => 'approved',
                    'academic_verified_by' => $admin->id,
                    'academic_verified_at' => now(),
                    'verification_remarks' => $request->remarks,
                    'conditions' => $request->conditions ? json_encode($request->conditions) : null,
                    'next_year_gpa_requirement' => $request->next_year_gpa_requirement,
                    'required_subjects' => $request->required_subjects ? json_encode($request->required_subjects) : null,
                    'needs_academic_approval' => false,
                ]);

                // Update student record for next academic year
                $student = Student::find($application->student_original_id);
                if ($student) {
                    $student->update([
                        'current_year' => $this->getYearName($application->current_year),
                        'academic_year' => $this->getNextAcademicYear($student->academic_year),
                        'status' => 'active',
                    ]);

                    // Create academic history record for the previous year
                    StudentAcademicHistory::create([
                        'student_id' => $student->id,
                        'academic_year' => $student->academic_year,
                        'year' => $application->current_year - 1,
                        'status' => 'passed',
                        'cgpa' => $application->cgpa,
                        'approved_by' => $admin->id,
                        'approved_at' => now(),
                    ]);
                }

                // Send approval notification
                $this->sendOldStudentApprovalEmail($application, $student);

                DB::commit();

                return redirect()->route('admin.old-student.applications')->with('success', 
                    'Old student application academically approved! Student can now proceed to next academic year.');

            } elseif ($request->verification_status === 'rejected') {
                $application->update([
                    'status' => 'rejected',
                    'academic_approval_status' => 'rejected',
                    'rejection_reason' => $request->remarks,
                    'rejected_by' => $admin->id,
                    'rejected_at' => now(),
                    'needs_academic_approval' => false,
                ]);

                DB::commit();

                return redirect()->route('admin.old-student.applications')->with('success', 
                    'Old student application rejected.');

            } else {
                // Pending with conditions
                $application->update([
                    'academic_approval_status' => 'pending',
                    'verification_remarks' => $request->remarks,
                    'conditions' => $request->conditions ? json_encode($request->conditions) : null,
                ]);

                DB::commit();

                return redirect()->back()->with('info', 
                    'Application marked as pending. Additional conditions have been noted.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Old student verification failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    /**
 * Quick assign department AND approve new student application
 */
public function quickAssign(Request $request, $id)
{
    $admin = Auth::guard('admin')->user();

    if ($admin->role !== 'haa_admin') {
        abort(403, 'Access denied. Academic admin only.');
    }

    $request->validate([
        'priority_department' => 'required|string|max:255'
    ]);

    $application = Application::findOrFail($id);

    if ($application->application_type !== 'new') {
        return redirect()->back()->with('error', 'This method is for new students only.');
    }

    if ($application->status !== 'payment_verified') {
        return redirect()->back()->with('error', 'Application is not ready for department assignment. Payment must be verified first.');
    }

    DB::beginTransaction();

    try {
        $assignedDepartment = $request->priority_department;

        // Generate student ID and password
        $studentId = $this->generateStudentId();
        $password = Str::random(12);

        Log::info('Creating new student account during quick assign', [
            'application_id' => $application->id,
            'student_id' => $studentId,
            'assigned_department' => $assignedDepartment
        ]);

        // Update application with assigned department AND academic approval
        $application->update([
            'assigned_department' => $assignedDepartment,
            'status' => 'academic_approved',
            'department_assigned_by' => $admin->id,
            'department_assigned_at' => now(),
            'academic_approved_by' => $admin->id,
            'academic_approved_at' => now(),
            'student_id' => $studentId,
        ]);

        // Create student record with only existing columns
        $studentData = [
            'student_id' => $studentId,
            'application_id' => $application->id,
            'name' => $application->name,
            'email' => $application->email,
            'phone' => $application->phone,
            'password' => Hash::make($password),
            'department' => $assignedDepartment,
            'date_of_birth' => $application->date_of_birth,
            'gender' => $application->gender,
            'nrc_number' => $application->nrc_number,
            'address' => $application->address,
            'academic_year' => $this->getCurrentAcademicYear(),
            'status' => 'active',
            'registration_date' => now(),
            'needs_password_change' => true, // Force password change on first login
        ];

        // Add current_year only after we add the column to database
        // For now, we'll add it if the column exists
        try {
            $studentData['current_year'] = 'first_year';
        } catch (\Exception $e) {
            // Column doesn't exist, skip it
            Log::warning('current_year column not found in students table');
        }

        $student = Student::create($studentData);

        // Send student credentials email
        $emailSent = $this->sendStudentCredentialsEmail($student, $password, $assignedDepartment);

        DB::commit();

        if ($emailSent) {
            return redirect()->route('admin.applications.academic')->with('success', 
                "Application approved! Department {$assignedDepartment} assigned and student account created. " .
                "Student ID: {$studentId}. Credentials sent to student email."
            );
        } else {
            return redirect()->route('admin.applications.academic')->with('warning', 
                "Application approved but failed to send email. " .
                "Department: {$assignedDepartment}, Student ID: {$studentId}. Please contact the student directly."
            );
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Quick department assignment and approval failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Department assignment and approval failed: ' . $e->getMessage());
    }
}

    /**
     * Separate academic approval for already assigned departments (new students)
     */
    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);

        if ($application->application_type !== 'new') {
            return redirect()->back()->with('error', 'This method is for new students only.');
        }

        // Check if department is assigned
        if (!$application->assigned_department) {
            return redirect()->back()->with('error', 'Please assign a department before approving the application.');
        }

        // Check if application is in correct status
        if ($application->status !== 'department_assigned') {
            return redirect()->back()->with('error', 'Application must have department assigned first.');
        }

        DB::beginTransaction();

        try {
            $assignedDepartment = $application->assigned_department;

            // Generate student ID and password
            $studentId = $this->generateStudentId();
            $password = Str::random(12);

            Log::info('Creating new student account during academic approval', [
                'application_id' => $application->id,
                'student_id' => $studentId,
                'assigned_department' => $assignedDepartment
            ]);

            // Create student record
            $student = Student::create([
                'student_id' => $studentId,
                'application_id' => $application->id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'password' => Hash::make($password),
                'department' => $assignedDepartment,
                'date_of_birth' => $application->date_of_birth,
                'gender' => $application->gender,
                'nrc_number' => $application->nrc_number,
                'address' => $application->address,
                'current_year' => 'first_year',
                'academic_year' => $this->getCurrentAcademicYear(),
                'status' => 'active',
                'registration_date' => now(),
            ]);

            // Update application with academic approval and student ID
            $application->update([
                'status' => 'academic_approved',
                'academic_approved_by' => $admin->id,
                'academic_approved_at' => now(),
                'student_id' => $studentId,
            ]);

            // Send student credentials email
            $emailSent = $this->sendStudentCredentialsEmail($student, $password, $assignedDepartment);

            DB::commit();

            if ($emailSent) {
                return redirect()->route('admin.applications.academic')->with('success', 
                    "Application academically approved! Student account created and credentials sent to email. " .
                    "Assigned Department: {$assignedDepartment}, Student ID: {$studentId}"
                );
            } else {
                return redirect()->route('admin.applications.academic')->with('warning', 
                    "Application academically approved but failed to send email. " .
                    "Assigned Department: {$assignedDepartment}, Student ID: {$studentId}. Please contact the student directly."
                );
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Academic approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Academic approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Academic reject for both new and old students
     */
    public function academicReject(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $request->validate([
            'notes' => 'required|string|max:500'
        ]);

        $application = Application::findOrFail($id);

        if (!in_array($application->status, ['payment_verified', 'department_assigned'])) {
            return redirect()->back()->with('error', 'Application is not in correct status for rejection.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->notes,
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
        ]);

        return redirect()->route('admin.applications.academic')->with('success', 'Application rejected successfully.');
    }

    /**
     * Helper Methods
     */

    /**
     * Check if student has passed previous academic year
     */
    private function checkAcademicEligibility($studentId, $nextYear)
    {
        try {
            $currentYear = $nextYear - 1;
            
            if ($currentYear < 1) {
                return true; // First year students (no previous year)
            }

            // Check academic history
            $academicRecord = StudentAcademicHistory::where('student_id', $studentId)
                ->where('year', $currentYear)
                ->where('status', 'passed')
                ->first();

            if ($academicRecord) {
                return true;
            }

            // Check if student exists and has current year data
            $student = Student::find($studentId);
            if ($student) {
                // For first year to second year transition, check if student is active
                if ($currentYear == 1 && $student->status === 'active') {
                    return true;
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Academic eligibility check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student academic history
     */
    private function getStudentAcademicHistory($studentId)
    {
        return StudentAcademicHistory::where('student_id', $studentId)
            ->orderBy('academic_year', 'desc')
            ->orderBy('year', 'desc')
            ->get();
    }

    /**
     * Get current academic year
     */
    private function getCurrentAcademicYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    /**
     * Get next academic year
     */
    private function getNextAcademicYear($currentAcademicYear)
    {
        if (!$currentAcademicYear) {
            return $this->getCurrentAcademicYear();
        }

        $parts = explode('-', $currentAcademicYear);
        if (count($parts) === 2) {
            $nextStart = intval($parts[1]);
            $nextEnd = $nextStart + 1;
            return $nextStart . '-' . $nextEnd;
        }

        return $this->getCurrentAcademicYear();
    }

    /**
     * Get year name from number
     */
    private function getYearName($yearNumber)
    {
        $yearNames = [
            1 => 'first_year',
            2 => 'second_year',
            3 => 'third_year',
            4 => 'fourth_year',
            5 => 'fifth_year',
        ];

        return $yearNames[$yearNumber] ?? 'first_year';
    }

    /**
     * Generate student ID in WYTU202500001 format
     */
    private function generateStudentId()
    {
        $year = date('Y');

        // Get the last student ID for this year
        $lastStudent = Student::where('student_id', 'like', "WYTU{$year}%")
            ->orderBy('student_id', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent->student_id, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "WYTU{$year}" . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get all available departments
     */
    private function getAllDepartments()
    {
        return [
            'Civil Engineering',
            'Computer Engineering and Information Technology',
            'Electronic Engineering',
            'Electrical Power Engineering',
            'Architecture',
            'Biotechnology',
            'Textile Engineering',
            'Mechanical Engineering',
            'Chemical Engineering',
            'Automobile Engineering',
            'Mechatronic Engineering',
            'Metallurgy Engineering'
        ];
    }

    /**
     * Send student credentials email (for new students)
     */
    private function sendStudentCredentialsEmail($student, $password, $department)
    {
        try {
            Mail::send('emails.student-credentials', [
                'student' => $student,
                'password' => $password,
                'department' => $department,
                'loginUrl' => route('student.login'),
            ], function ($message) use ($student, $department) {
                $message->to($student->email)
                    ->subject("Your Student Account Credentials - WYTU University - {$department}");
            });

            Log::info('Student credentials email sent successfully', [
                'student_id' => $student->student_id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send student credentials email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send old student approval email
     */
    private function sendOldStudentApprovalEmail($application, $student)
    {
        try {
            Mail::send('emails.old-student-approval', [
                'application' => $application,
                'student' => $student,
                'nextYear' => $application->current_year,
                'academicYear' => $this->getNextAcademicYear($student->academic_year),
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject("Academic Year Progression Approved - WYTU University");
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send old student approval email: ' . $e->getMessage());
            return false;
        }
    }

    public function academicAffairs()
    {
        return view('admin.academic.affairs');
    }

    public function courseManagement()
    {
        return view('admin.academic.course-management');
    }

    /**
     * View assigned applications ready for approval (new students)
     */
    public function assignedApplications()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        // Get new student applications that have departments assigned but not approved
        $applications = Application::where('application_type', 'new')
            ->where('status', 'department_assigned')
            ->whereNotNull('assigned_department')
            ->orderBy('department_assigned_at', 'desc')
            ->paginate(20);

        return view('admin.academic.assigned-applications', compact('applications', 'admin'));
    }
}