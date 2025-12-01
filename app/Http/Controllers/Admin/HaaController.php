<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c

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

        return view('admin.academic.dashboard-academic', compact('stats', 'applications', 'admin'));
    }

    private function getDashboardStats()
    {
        return [
            'pending_reviews' => Application::where('status', 'payment_verified')->count(),
<<<<<<< HEAD
            'assigned_pending' => Application::where('status', 'department_assigned')->count(),
            'academic_approved' => Application::where('status', 'academic_approved')->count(),
            'total_students' => Student::count(),
            'approved_today' => Application::where('status', 'academic_approved')
                ->whereDate('academic_approved_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'approved'])->count(),
=======
            'approved_today' => Application::where('status', 'academic_approved') // Changed key to match blade file
                ->whereDate('academic_approved_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'approved']) // Changed key to match blade file
                ->count(),
            'total_students' => Student::count(), // Changed key to match blade file
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
        ];
    }

    public function academicApplications()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        // Get applications that are payment verified (ready for academic review)
        $applications = Application::where('status', 'payment_verified')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.academic.applications', compact('applications', 'admin'));
    }

<<<<<<< HEAD
    /**
     * View application details
     */
    public function viewApplication($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::with(['payments'])->findOrFail($id);
        $departments = $this->getAllDepartments();
        
        return view('admin.academic.application-view', compact('application', 'admin', 'departments'));
    }

    /**
     * Quick assign department AND approve application (create student account)
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

        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not ready for department assignment. Payment must be verified first.');
        }

        DB::beginTransaction();

        try {
            $assignedDepartment = $request->priority_department;

            // Generate student ID and password
            $studentId = $this->generateStudentId();
            $password = Str::random(12);

            Log::info('Creating student account during quick assign', [
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
                'status' => 'active',
                'registration_date' => now(),
                'academic_year' => date('Y'),
            ]);

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
     * Assign custom department AND approve application (create student account)
     */
    public function assignDepartment(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $request->validate([
            'assigned_department' => 'required|string|max:255'
        ]);

        $application = Application::findOrFail($id);

        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not ready for department assignment. Payment must be verified first.');
        }

        DB::beginTransaction();

        try {
            $assignedDepartment = $request->assigned_department;

            // Generate student ID and password
            $studentId = $this->generateStudentId();
            $password = Str::random(12);

            Log::info('Creating student account during custom department assignment', [
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
                'status' => 'active',
                'registration_date' => now(),
                'academic_year' => date('Y'),
            ]);

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
            Log::error('Custom department assignment and approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Department assignment and approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Separate academic approval for already assigned departments
     */
=======
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);

<<<<<<< HEAD
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

=======
        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not ready for academic approval. Payment must be verified first.');
        }

        try {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            // Generate student ID and password
            $studentId = $this->generateStudentId();
            $password = Str::random(12);

            Log::info('Creating student account during academic approval', [
                'application_id' => $application->id,
<<<<<<< HEAD
                'student_id' => $studentId,
                'assigned_department' => $assignedDepartment
=======
                'student_id' => $studentId
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            ]);

            // Create student record
            $student = Student::create([
                'student_id' => $studentId,
                'application_id' => $application->id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'password' => Hash::make($password),
<<<<<<< HEAD
                'department' => $assignedDepartment,
=======
                'department' => $application->department,
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'date_of_birth' => $application->date_of_birth,
                'gender' => $application->gender,
                'nrc_number' => $application->nrc_number,
                'address' => $application->address,
<<<<<<< HEAD
                'status' => 'active',
=======
                'status' => 'pending', // Set to pending until HOD approval
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'registration_date' => now(),
                'academic_year' => date('Y'),
            ]);

            // Update application with academic approval and student ID
            $application->update([
<<<<<<< HEAD
                'status' => 'academic_approved',
=======
                'status' => 'academic_approved', // Ready for HOD approval
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                'academic_approved_by' => $admin->id,
                'academic_approved_at' => now(),
                'student_id' => $studentId,
            ]);

            // Send student credentials email
<<<<<<< HEAD
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
=======
            $emailSent = $this->sendStudentCredentialsEmail($student, $password);

            if ($emailSent) {
                Log::info('Student credentials email sent successfully', [
                    'student_id' => $studentId,
                    'email' => $student->email
                ]);
                return redirect()->back()->with('success', 'Application academically approved! Student account created and credentials sent to email. Student ID: ' . $studentId . ' - Now waiting for HOD final approval.');
            } else {
                Log::error('Failed to send student credentials email', [
                    'student_id' => $studentId,
                    'email' => $student->email
                ]);
                return redirect()->back()->with('warning', 'Application academically approved but failed to send email. Student ID: ' . $studentId . '. Please contact the student directly.');
            }
        } catch (\Exception $e) {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            Log::error('Academic approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Academic approval failed: ' . $e->getMessage());
        }
    }

    /**
<<<<<<< HEAD
=======
     * Quick approve - academic approval with student creation and email
     */
    public function quickApprove($id)
    {
        return $this->academicApprove($id);
    }

    /**
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
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
<<<<<<< HEAD
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
     * Send student credentials email
     */
    private function sendStudentCredentialsEmail($student, $password, $department)
=======
     * Send student credentials email
     */
    private function sendStudentCredentialsEmail($student, $password)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    {
        try {
            Log::info('Attempting to send student credentials email', [
                'student_id' => $student->student_id,
<<<<<<< HEAD
                'email' => $student->email,
                'department' => $department
=======
                'email' => $student->email
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            ]);

            Mail::send('emails.student-credentials', [
                'student' => $student,
                'password' => $password,
<<<<<<< HEAD
                'department' => $department,
                'loginUrl' => route('student.login'),
            ], function ($message) use ($student, $department) {
                $message->to($student->email)
                    ->subject("Your Student Account Credentials - WYTU University - {$department}");
=======
                'loginUrl' => route('student.login'),
                'status' => 'pending' // Inform student that HOD approval is pending
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Your Student Account Credentials - WYTU University - Pending Final Approval');

                Log::info('Email prepared for sending', [
                    'to' => $student->email,
                    'subject' => 'Your Student Account Credentials - WYTU University - Pending Final Approval'
                ]);
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            });

            Log::info('Student credentials email sent successfully', [
                'student_id' => $student->student_id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send student credentials email: ' . $e->getMessage(), [
                'student_id' => $student->student_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

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

<<<<<<< HEAD
        if (!in_array($application->status, ['payment_verified', 'department_assigned'])) {
=======
        if ($application->status !== 'payment_verified') {
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
            return redirect()->back()->with('error', 'Application is not in correct status for rejection.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->notes,
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
        ]);

<<<<<<< HEAD
        return redirect()->route('admin.applications.academic')->with('success', 'Application rejected successfully.');
=======
        return redirect()->back()->with('success', 'Application rejected successfully.');
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    }

    public function academicAffairs()
    {
        return view('admin.academic.affairs');
    }

<<<<<<< HEAD
    public function courseManagement()
    {
        return view('admin.academic.course-management');
    }

    /**
     * View assigned applications ready for approval
     */
    public function assignedApplications()
=======
    /**
     * View application details
     */
    public function viewApplication($id)
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

<<<<<<< HEAD
        // Get applications that have departments assigned but not approved
        $applications = Application::where('status', 'department_assigned')
            ->whereNotNull('assigned_department')
            ->orderBy('department_assigned_at', 'desc')
            ->paginate(20);

        return view('admin.academic.assigned-applications', compact('applications', 'admin'));
    }
}
=======
        $application = Application::with(['payments'])->findOrFail($id);
        return view('admin.academic.application-view', compact('application', 'admin'));
    }
}
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
