<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\StudentCredentialsMail;
use App\Mail\ApplicationApprovedMail;

class GlobalAdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-global', compact('stats'));
    }

    private function getDashboardStats()
    {
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $totalUsers = User::count();
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('status', 'active')->count();

        $recentApplications = Application::with('payments')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $paymentStats = [
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return [
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'total_payments' => $totalPayments,
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'active_admins' => $activeAdmins,
            'recent_applications' => $recentApplications,
            'payment_stats' => $paymentStats,
        ];
    }

    public function allApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $applications = Application::with(['payments', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.all', compact('applications'));
    }

    public function users()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $users = User::with('applications')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function viewUser($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $user = User::with('applications.payments')->findOrFail($id);
        return view('admin.users.view', compact('user'));
    }

    public function payments()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $payments = Payment::with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
            'total_transactions' => Payment::count(),
            'success_rate' => Payment::where('status', 'completed')->count() / max(Payment::count(), 1) * 100
        ];

        return view('admin.payments.global', compact('payments', 'stats'));
    }

    public function reports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        // Generate reports data
        return view('admin.reports.global');
    }

    public function teachers()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $teachers = Admin::where('role', 'teacher_admin')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Application action methods
    public function verifyPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'payment_status' => 'verified',
            'status' => 'payment_verified'
        ]);

        return redirect()->back()->with('success', 'Payment verified successfully');
    }

    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'academic_approved',
            'academic_approved_at' => now(),
            'academic_approved_by' => $admin->name,
        ]);

        // Send academic approval email
        $this->sendAcademicApprovalEmail($application);

        return redirect()->back()->with('success', 'Application academically approved and student notified via email.');
    }

    public function finalApprove($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'final_approved_at' => now(),
            'final_approved_by' => $admin->name,
        ]);

        // Create student account and send credentials
        $this->createStudentAccount($application);

        // Send final approval email
        $this->sendFinalApprovalEmail($application);

        return redirect()->back()->with('success', 'Application finally approved and student credentials sent via email.');
    }

    /**
     * Create student account after global admin final approval
     */
    private function createStudentAccount(Application $application)
    {
        try {
            // Check if student already exists
            $existingStudent = Student::where('application_id', $application->id)->first();
            if ($existingStudent) {
                Log::info('Student account already exists for global admin approval', [
                    'application_id' => $application->id,
                    'student_id' => $existingStudent->student_id
                ]);
                return $existingStudent;
            }

            // Generate student ID
            $studentId = 'STU' . date('Y') . strtoupper(Str::random(6));
            $password = Str::random(12);

            Log::info('Creating student account for global admin approval', [
                'application_id' => $application->id,
                'student_id' => $studentId
            ]);

            // Create student record
            $studentData = [
                'student_id' => $studentId,
                'application_id' => $application->id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'password' => Hash::make($password),
                'department' => $application->department,
                'date_of_birth' => $application->date_of_birth,
                'gender' => $application->gender,
                'nrc_number' => $application->nrc_number,
                'address' => $application->address,
                'program' => $application->department,
                'status' => 'active',
                'registration_date' => now(),
                'password_changed_at' => null, // Force password change on first login
            ];

            $student = Student::create($studentData);

            // Update application with student ID
            $application->update(['student_id' => $studentId]);

            // Send student credentials email
            $this->sendStudentCredentials($student, $password);

            Log::info('Student account created successfully by global admin', [
                'student_id' => $studentId,
                'application_id' => $application->id
            ]);

            return $student;

        } catch (\Exception $e) {
            Log::error('Student account creation failed in global admin approval: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Send student credentials email
     */
    private function sendStudentCredentials(Student $student, string $password)
    {
        try {
            Log::info('Sending student credentials email from global admin approval', [
                'student_id' => $student->student_id,
                'email' => $student->email
            ]);

            Mail::to($student->email)
                ->send(new StudentCredentialsMail($student, $password, url('/student/login')));

            Log::info('Student credentials email sent successfully from global admin', [
                'student_id' => $student->student_id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send student credentials email from global admin: ' . $e->getMessage(), [
                'student_id' => $student->student_id ?? 'unknown',
                'error_details' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Send academic approval email
     */
    private function sendAcademicApprovalEmail(Application $application)
    {
        try {
            $approvalType = 'Academic Affairs Department';
            $approvedBy = Auth::guard('admin')->user()->name;
            $nextSteps = "Your application has been academically approved. It will now proceed to the final approval stage.";

            Mail::to($application->email)
                ->send(new ApplicationApprovedMail($application, $approvalType, $approvedBy, $nextSteps));

            Log::info('Academic approval email sent by global admin', [
                'application_id' => $application->id,
                'student_email' => $application->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send academic approval email from global admin: ' . $e->getMessage());
        }
    }

    /**
     * Send final approval email
     */
    private function sendFinalApprovalEmail(Application $application)
    {
        try {
            $approvalType = 'Administration';
            $approvedBy = Auth::guard('admin')->user()->name;
            $nextSteps = "Your application has been fully approved. Your student account has been created and login credentials have been sent to your email.";

            Mail::to($application->email)
                ->send(new ApplicationApprovedMail($application, $approvalType, $approvedBy, $nextSteps));

            Log::info('Final approval email sent by global admin', [
                'application_id' => $application->id,
                'student_email' => $application->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send final approval email from global admin: ' . $e->getMessage());
        }
    }

    public function rejectApplication($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'rejected'
        ]);

        return redirect()->back()->with('success', 'Application rejected');
    }

    public function bulkActions(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'global_admin') {
            abort(403, 'Access denied. Global admin only.');
        }

        // Handle bulk actions
        return redirect()->back()->with('success', 'Bulk action completed');
    }
}