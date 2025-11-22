<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HaaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();

        // Allow both haa_admin and academic_admin roles
        if (!in_array($admin->role, ['haa_admin', 'academic_admin'])) {
            abort(403, 'Access denied. Academic admin only.');
        }

        $stats = $this->getDashboardStats();
        return view('admin.dashboard-academic', compact('stats'));
    }

    private function getDashboardStats()
    {
        $stats = [
            'pending_reviews' => Application::where('status', Application::STATUS_ACADEMIC_APPROVED)->count(),
            'approved_today' => Application::where('status', Application::STATUS_FINAL_APPROVED)
                ->whereDate('final_approved_at', today())
                ->count(),
            'total_reviewed' => Application::where('status', Application::STATUS_FINAL_APPROVED)->count(),
            'recent_applications' => Application::where('status', Application::STATUS_ACADEMIC_APPROVED)
                ->orderBy('academic_approved_at', 'desc')
                ->limit(10)
                ->get(),
            'total_students' => Student::count(),
        ];

        return $stats;
    }

    /**
     * Show applications ready for HAA approval (academically approved by finance)
     */
    public function academicApplications()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $applications = Application::where('status', Application::STATUS_ACADEMIC_APPROVED)
            ->with('payments')
            ->orderBy('academic_approved_at', 'desc')
            ->paginate(20);

        return view('admin.applications.academic', compact('applications'));
    }

    /**
     * Final approve application by HAA and create student account
     */
    public function approveApplication($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        try {
            $application = Application::findOrFail($id);

            // Check if already finally approved
            if ($application->status === Application::STATUS_FINAL_APPROVED) {
                return redirect()->back()->with('error', 'Application already finally approved.');
            }

            // Generate student credentials and mark as finally approved
            $credentials = $application->markAsFinalApproved($admin->id);

            // Create student record
            $student = $this->createStudentRecord($application, $credentials['password']);

            // Send email with credentials
            $this->sendStudentCredentials($student, $credentials['password']);

            Log::info("Application {$application->application_id} finally approved by HAA admin, student created: {$credentials['student_id']}");

            return redirect()->back()->with('success', 'Application approved and student credentials sent via email.');
        } catch (\Exception $e) {
            Log::error('HAA approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject application by HAA
     */
    public function rejectApplication(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        try {
            $application = Application::findOrFail($id);

            $application->markAsRejected($request->notes, $admin->id);

            Log::info("Application {$application->application_id} rejected by HAA admin");

            return redirect()->back()->with('success', 'Application rejected successfully.');
        } catch (\Exception $e) {
            Log::error('HAA rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }

    /**
     * Create student record
     */
    private function createStudentRecord($application, $password)
    {
        // Check if student already exists
        $existingStudent = Student::where('application_id', $application->application_id)->first();

        if ($existingStudent) {
            return $existingStudent;
        }

        $student = Student::create([
            'application_id' => $application->application_id,
            'student_id' => $application->student_id,
            'name' => $application->name,
            'email' => $application->email,
            'password' => Hash::make($password),
            'phone' => $application->phone,
            'nrc_number' => $application->nrc_number,
            'date_of_birth' => $application->date_of_birth,
            'gender' => $application->gender,
            'nationality' => $application->nationality,
            'address' => $application->address,
            'department' => $application->department,
            'father_name' => $application->father_name,
            'mother_name' => $application->mother_name,
            'high_school_name' => $application->high_school_name,
            'high_school_address' => $application->high_school_address,
            'graduation_year' => $application->graduation_year,
            'matriculation_score' => $application->matriculation_score,
            'previous_qualification' => $application->previous_qualification,
            'status' => 'active',
        ]);

        return $student;
    }

    /**
     * Send student credentials via email
     */
    private function sendStudentCredentials($student, $password)
    {
        $data = [
            'student' => $student,
            'password' => $password,
            'loginUrl' => route('student.login')
        ];

        try {
            Mail::send('emails.student-credentials', $data, function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Your WYTU Student Account Credentials')
                    ->from(env('MAIL_FROM_ADDRESS'), 'West Yangon Technological University');
            });

            Log::info("Student credentials email sent to: " . $student->email);
        } catch (\Exception $e) {
            Log::error("Failed to send email to: " . $student->email . " Error: " . $e->getMessage());
        }
    }

    /**
     * Show application details for academic review
     */
    public function viewApplication($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::with(['payments'])->findOrFail($id);
        return view('admin.applications.academic-view', compact('application'));
    }

    /**
     * Student management
     */
    public function studentManagement()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $students = Student::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.students.management', compact('students'));
    }

    // Keep existing methods for backward compatibility
    public function academicAffairs()
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->role !== 'haa_admin') abort(403);
        return view('admin.academic.affairs');
    }

    public function courseManagement()
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->role !== 'haa_admin') abort(403);
        return view('admin.courses.management');
    }
}
