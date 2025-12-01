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
            'approved_today' => Application::where('status', 'academic_approved') // Changed key to match blade file
                ->whereDate('academic_approved_at', today())
                ->count(),
            'total_reviewed' => Application::whereIn('status', ['academic_approved', 'approved']) // Changed key to match blade file
                ->count(),
            'total_students' => Student::count(), // Changed key to match blade file
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

    public function academicApprove($id)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'haa_admin') {
            abort(403, 'Access denied. Academic admin only.');
        }

        $application = Application::findOrFail($id);

        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not ready for academic approval. Payment must be verified first.');
        }

        try {
            // Generate student ID and password
            $studentId = $this->generateStudentId();
            $password = Str::random(12);

            Log::info('Creating student account during academic approval', [
                'application_id' => $application->id,
                'student_id' => $studentId
            ]);

            // Create student record
            $student = Student::create([
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
                'status' => 'pending', // Set to pending until HOD approval
                'registration_date' => now(),
                'academic_year' => date('Y'),
            ]);

            // Update application with academic approval and student ID
            $application->update([
                'status' => 'academic_approved', // Ready for HOD approval
                'academic_approved_by' => $admin->id,
                'academic_approved_at' => now(),
                'student_id' => $studentId,
            ]);

            // Send student credentials email
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
            Log::error('Academic approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Academic approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Quick approve - academic approval with student creation and email
     */
    public function quickApprove($id)
    {
        return $this->academicApprove($id);
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
     * Send student credentials email
     */
    private function sendStudentCredentialsEmail($student, $password)
    {
        try {
            Log::info('Attempting to send student credentials email', [
                'student_id' => $student->student_id,
                'email' => $student->email
            ]);

            Mail::send('emails.student-credentials', [
                'student' => $student,
                'password' => $password,
                'loginUrl' => route('student.login'),
                'status' => 'pending' // Inform student that HOD approval is pending
            ], function ($message) use ($student) {
                $message->to($student->email)
                    ->subject('Your Student Account Credentials - WYTU University - Pending Final Approval');

                Log::info('Email prepared for sending', [
                    'to' => $student->email,
                    'subject' => 'Your Student Account Credentials - WYTU University - Pending Final Approval'
                ]);
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

        if ($application->status !== 'payment_verified') {
            return redirect()->back()->with('error', 'Application is not in correct status for rejection.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->notes,
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    public function academicAffairs()
    {
        return view('admin.academic.affairs');
    }

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
        return view('admin.academic.application-view', compact('application', 'admin'));
    }
}
