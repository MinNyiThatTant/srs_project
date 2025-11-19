<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationApprovalController extends Controller
{
    /**
     * Display all new student applications for FA (Finance Admin)
     */
    public function financeApplications()
    {
        $applications = Application::newStudents()
            ->whereIn('status', [
                Application::STATUS_PAYMENT_PENDING,
                Application::STATUS_PAYMENT_VERIFIED
            ])
            ->orderBy('application_date', 'desc')
            ->get();

        return view('admin.applications.finance', compact('applications'));
    }

    /**
     * Verify payment by Finance Admin
     */
    public function verifyPayment(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if (!$application->requiresPayment()) {
            return redirect()->back()->with('error', 'This application does not require payment verification.');
        }

        $application->update([
            'status' => Application::STATUS_PAYMENT_VERIFIED,
            'payment_status' => 'verified',
            'payment_verified_by' => Auth::guard('admin')->user()->name,
            'payment_verified_at' => now(),
            'notes' => $request->notes ?: 'Payment verified by Finance Department'
        ]);

        return redirect()->back()->with('success', 'Payment verified successfully. Application moved to Academic Affairs for review.');
    }

    /**
     * Display applications for HAA (Head of Academic Affairs)
     */
    public function academicApplications()
    {
        $applications = Application::newStudents()
            ->where('status', Application::STATUS_PAYMENT_VERIFIED)
            ->orderBy('payment_verified_at', 'asc')
            ->get();

        return view('admin.applications.academic', compact('applications'));
    }

    /**
     * Approve application academically by HAA
     */
    public function academicApprove(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if (!$application->readyForAcademicApproval()) {
            return redirect()->back()->with('error', 'This application is not ready for academic approval.');
        }

        $application->update([
            'status' => Application::STATUS_ACADEMIC_APPROVED,
            'academic_approved_by' => Auth::guard('admin')->user()->name,
            'academic_approved_at' => now(),
            'notes' => $request->notes ?: 'Academically approved by Head of Academic Affairs'
        ]);

        return redirect()->back()->with('success', 'Application academically approved. Moved to HOD for final approval.');
    }

    /**
     * Reject application by HAA
     */
    public function academicReject(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $application->update([
            'status' => Application::STATUS_REJECTED,
            'academic_approved_by' => Auth::guard('admin')->user()->name,
            'academic_approved_at' => now(),
            'notes' => $request->notes ?: 'Rejected by Head of Academic Affairs: ' . $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    /**
     * Display applications for HOD (Head of Department)
     */
    public function hodApplications()
    {
        $department = Auth::guard('admin')->user()->department;
        
        $applications = Application::newStudents()
            ->where('status', Application::STATUS_ACADEMIC_APPROVED)
            ->where('department', $department)
            ->orderBy('academic_approved_at', 'asc')
            ->get();

        return view('admin.applications.hod', compact('applications'));
    }

    /**
     * Final approve application by HOD
     */
    public function finalApprove(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if (!$application->readyForFinalApproval()) {
            return redirect()->back()->with('error', 'This application is not ready for final approval.');
        }

        // Generate student ID
        $studentId = $this->generateStudentId($application);

        $application->update([
            'status' => Application::STATUS_FINAL_APPROVED,
            'student_id' => $studentId,
            'final_approved_by' => Auth::guard('admin')->user()->name,
            'final_approved_at' => now(),
            'approved_by' => Auth::guard('admin')->user()->name,
            'approved_at' => now(),
            'notes' => $request->notes ?: 'Final approval granted by Head of Department'
        ]);

        return redirect()->back()->with('success', 'Application finally approved. Student ID: ' . $studentId);
    }

    /**
     * Generate unique student ID
     */
    private function generateStudentId($application)
{
    $departmentCode = $this->getDepartmentCode($application->department);
    $year = date('y');
    $sequence = Application::where('department', $application->department)
        ->where('status', Application::STATUS_FINAL_APPROVED) // This is correct
        ->count() + 1;

    return $departmentCode . $year . str_pad($sequence, 3, '0', STR_PAD_LEFT);
}

    /**
     * Get department code
     */
    private function getDepartmentCode($department)
    {
        $codes = [
            'Civil Engineering' => 'CE',
            'Computer Engineering and Information Technology (CEIT)' => 'CEIT',
            'Electronics Engineering' => 'EE',
            'Electrical Power Engineering' => 'EPE',
            'Architecture' => 'ARCH',
            'Biotechnology' => 'BIO',
            'Textile Engineering' => 'TE',
            'Mechanical Engineering' => 'ME',
            'Chemical Engineering' => 'CHE'
        ];

        return $codes[$department] ?? 'GEN';
    }

    /**
     * Display all applications for Global Admin
     */
    public function allApplications()
    {
        $applications = Application::orderBy('application_date', 'desc')->get();
        
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', Application::STATUS_PENDING)->count(),
            'payment_pending' => Application::where('status', Application::STATUS_PAYMENT_PENDING)->count(),
            'payment_verified' => Application::where('status', Application::STATUS_PAYMENT_VERIFIED)->count(),
            'academic_approved' => Application::where('status', Application::STATUS_ACADEMIC_APPROVED)->count(),
            'approved' => Application::where('status', Application::STATUS_FINAL_APPROVED)->count(),
            'rejected' => Application::where('status', Application::STATUS_REJECTED)->count(),
        ];

        return view('admin.applications.all', compact('applications', 'stats'));
    }

    /**
     * View application details
     */
    public function viewApplication($id)
    {
        $application = Application::findOrFail($id);
        return view('admin.applications.view', compact('application'));
    }
}