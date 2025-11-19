<?php
// app/Http\Controllers\Admin\GlobalAdminController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class GlobalAdminController extends Controller
{
    /**
     * Global Admin Dashboard
     */
    public function index()
    {
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::pending()->count(),
            'payment_pending' => Application::paymentPending()->count(),
            'payment_verified' => Application::paymentVerified()->count(),
            'academic_approved' => Application::academicApproved()->count(),
            'approved_applications' => Application::approved()->count(),
            'rejected_applications' => Application::rejected()->count(),
            'new_students' => Application::newStudents()->count(),
            'old_students' => Application::oldStudents()->count(),
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
        ];

        $recentApplications = Application::with(['payments'])->latest()->take(5)->get();
        $recentPayments = Payment::with(['application'])->latest()->take(5)->get();

        return view('admin.global.dashboard', compact('stats', 'recentApplications', 'recentPayments'));
    }

    /**
     * Manage Users (Students)
     */
    public function users()
    {
        $users = User::where('role', 'student')
                    ->withCount(['applications'])
                    ->latest()
                    ->paginate(10);
                    
        return view('admin.global.users', compact('users'));
    }

    /**
     * View User Details
     */
    public function viewUser($id)
    {
        $user = User::with(['applications', 'applications.payments'])
                    ->findOrFail($id);
        return view('admin.global.user-details', compact('user'));
    }

    /**
     * View All Applications
     */
    public function applications(Request $request)
    {
        $query = Application::with(['payments', 'latestPayment']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by application type
        if ($request->has('application_type') && $request->application_type) {
            $query->where('application_type', $request->application_type);
        }
        
        $applications = $query->latest()->paginate(10);
        
        return view('admin.global.applications', compact('applications'));
    }

    /**
     * View Application Details
     */
    public function viewApplication($id)
    {
        $application = Application::with(['payments', 'latestPayment'])->findOrFail($id);
        return view('admin.global.application-details', compact('application'));
    }

    /**
     * Verify Payment (Mark as Completed)
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'verified_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $application = Application::findOrFail($id);
        
        // Update payment status
        $application->update([
            'payment_status' => Application::PAYMENT_COMPLETED,
            'payment_verified_by' => $request->verified_by,
            'payment_verified_at' => now(),
            'status' => Application::STATUS_PAYMENT_VERIFIED,
            'notes' => $request->notes
        ]);

        // Update related payment records
        if ($application->latestPayment) {
            $application->latestPayment->update([
                'status' => 'completed',
                'paid_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Payment verified successfully! Application moved to academic review.');
    }

    /**
     * Academic Approval (For HAA Admin)
     */
    public function academicApprove(Request $request, $id)
    {
        $request->validate([
            'approved_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $application = Application::findOrFail($id);
        
        if (!$application->readyForAcademicApproval()) {
            return redirect()->back()->with('error', 'Application is not ready for academic approval!');
        }

        $application->update([
            'status' => Application::STATUS_ACADEMIC_APPROVED,
            'academic_approved_by' => $request->approved_by,
            'academic_approved_at' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Application academically approved!');
    }

    /**
     * Final Approval (For HOD Admin)
     */
    public function finalApprove(Request $request, $id)
    {
        $request->validate([
            'approved_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $application = Application::findOrFail($id);
        
        if (!$application->readyForFinalApproval()) {
            return redirect()->back()->with('error', 'Application is not ready for final approval!');
        }

        // Generate student ID if new student
        if ($application->application_type === 'new') {
            $application->generateStudentId();
        }

        $application->update([
            'status' => Application::STATUS_FINAL_APPROVED,
            'final_approved_by' => $request->approved_by,
            'final_approved_at' => now(),
            'approved_at' => now(),
            'approved_by' => $request->approved_by,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Application finally approved! Student ID: ' . $application->student_id);
    }

    /**
     * Reject Application
     */
    public function rejectApplication(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'rejected_by' => 'required|string|max:255'
        ]);

        $application = Application::findOrFail($id);
        
        $application->update([
            'status' => Application::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => now(),
            'approved_by' => $request->rejected_by,
            'notes' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully!');
    }

    /**
     * Payment Management
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['application']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $payments = $query->latest()->paginate(10);
        
        return view('admin.global.payments', compact('payments'));
    }

    /**
     * Update Payment Status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,processing,completed,failed,refunded'
        ]);

        $payment = Payment::findOrFail($id);
        $oldStatus = $payment->status;
        
        $payment->update([
            'status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'completed' ? now() : null
        ]);

        // Update application payment status if needed
        if ($request->payment_status === 'completed') {
            $payment->application->update([
                'payment_status' => Application::PAYMENT_COMPLETED,
                'payment_verified_at' => now(),
                'status' => Application::STATUS_PAYMENT_VERIFIED
            ]);
        }

        return redirect()->back()->with('success', "Payment status updated from {$oldStatus} to {$request->payment_status}!");
    }

    /**
     * View Payment Details
     */
    public function viewPayment($id)
    {
        $payment = Payment::with(['application'])->findOrFail($id);
        return view('admin.global.payment-details', compact('payment'));
    }

    /**
     * Reports and Analytics
     */
    public function reports()
    {
        // Application statistics by status
        $applicationsByStatus = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Payment statistics
        $paymentsByStatus = Payment::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Monthly applications
        $monthlyApplications = Application::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // Department-wise applications
        $departmentApplications = Application::selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->get();

        return view('admin.global.reports', compact(
            'applicationsByStatus',
            'paymentsByStatus',
            'monthlyApplications',
            'departmentApplications'
        ));
    }

    /**
     * Bulk Actions
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify_payment,academic_approve,final_approve,reject',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id'
        ]);

        $count = 0;
        
        foreach ($request->application_ids as $applicationId) {
            $application = Application::find($applicationId);
            
            switch ($request->action) {
                case 'verify_payment':
                    if ($application->isPaymentCompleted()) {
                        $application->markPaymentAsVerified(auth()->user()->name);
                        $count++;
                    }
                    break;
                    
                case 'academic_approve':
                    if ($application->readyForAcademicApproval()) {
                        $application->update([
                            'status' => Application::STATUS_ACADEMIC_APPROVED,
                            'academic_approved_by' => auth()->user()->name,
                            'academic_approved_at' => now()
                        ]);
                        $count++;
                    }
                    break;
                    
                case 'final_approve':
                    if ($application->readyForFinalApproval()) {
                        $application->generateStudentId();
                        $application->update([
                            'status' => Application::STATUS_FINAL_APPROVED,
                            'final_approved_by' => auth()->user()->name,
                            'final_approved_at' => now()
                        ]);
                        $count++;
                    }
                    break;
                    
                case 'reject':
                    $application->update([
                        'status' => Application::STATUS_REJECTED,
                        'rejection_reason' => 'Bulk rejection by admin',
                        'approved_at' => now(),
                        'approved_by' => auth()->user()->name
                    ]);
                    $count++;
                    break;
            }
        }

        return redirect()->back()->with('success', "Bulk action completed! {$count} applications processed.");
    }
}