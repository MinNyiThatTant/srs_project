<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }
        
        $stats = $this->getDashboardStats();
        return view('admin.dashboard-finance', compact('stats'));
    }

    private function getDashboardStats()
    {
        $stats = [
            'total_payments' => Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'pending_verifications' => Application::where('payment_status', 'pending')
                ->whereHas('payments', function($query) {
                    $query->where('status', Payment::STATUS_COMPLETED);
                })->count(),
            'today_payments' => Payment::where('status', Payment::STATUS_COMPLETED)
                ->whereDate('paid_at', today())
                ->sum('amount'),
            'recent_payments' => Payment::with('application')
                ->where('status', Payment::STATUS_COMPLETED)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'pending_approvals' => Application::where('status', Application::STATUS_PAYMENT_VERIFIED)->count(),
        ];

        return $stats;
    }

    /**
     * Show applications with completed payments waiting for verification
     */
    public function pendingVerifications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $applications = Application::where('payment_status', 'pending')
            ->whereHas('payments', function($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->with(['payments' => function($query) {
                $query->where('status', Payment::STATUS_COMPLETED)->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.pending-verification', compact('applications'));
    }

    /**
     * Verify payment and move to payment_verified status
     */
    public function verifyPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        try {
            $application = Application::with('payments')->findOrFail($id);
            
            // Check if there's a completed payment
            $completedPayment = $application->payments()->where('status', Payment::STATUS_COMPLETED)->first();
            
            if (!$completedPayment) {
                return redirect()->back()->with('error', 'No completed payment found for this application.');
            }

            // Verify the payment
            $application->markPaymentAsVerified($admin->id);

            Log::info("Payment verified for application {$application->application_id} by finance admin");

            return redirect()->back()->with('success', 'Payment verified successfully. Application is now ready for approval.');

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Show payment verified applications ready for finance approval
     */
    public function paymentVerifiedApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $applications = Application::where('status', Application::STATUS_PAYMENT_VERIFIED)
            ->with(['payments' => function($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            }])
            ->orderBy('payment_verified_at', 'desc')
            ->paginate(20);

        return view('admin.applications.payment-verified', compact('applications'));
    }

    /**
     * Approve application by finance (move to academic_approved status)
     */
    public function approveApplication($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        try {
            $application = Application::findOrFail($id);
            
            // Verify payment is verified first
            if (!$application->isPaymentVerified()) {
                return redirect()->back()->with('error', 'Payment not verified for this application.');
            }

            // Move to academic approved status (ready for HAA approval)
            $application->markAsAcademicApproved($admin->id);

            Log::info("Application {$application->application_id} approved by finance admin, moved to academic approved");

            return redirect()->back()->with('success', 'Application approved successfully. Ready for academic review.');

        } catch (\Exception $e) {
            Log::error('Finance approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject application by finance
     */
    public function rejectApplication(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        try {
            $application = Application::findOrFail($id);
            
            $application->markAsRejected($request->notes, $admin->id);

            Log::info("Application {$application->application_id} rejected by finance admin");

            return redirect()->back()->with('success', 'Application rejected successfully.');

        } catch (\Exception $e) {
            Log::error('Finance rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }

    /**
     * Show application details for finance review
     */
    public function viewApplication($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $application = Application::with(['payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.applications.finance-view', compact('application'));
    }

    public function financialReports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payments = Payment::with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.financial', compact('payments'));
    }

    public function pendingPayments()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payments = Payment::where('status', Payment::STATUS_PENDING)
            ->with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.pending', compact('payments'));
    }

    // Keep other existing methods for backward compatibility
    public function financeApplications()
    {
        return $this->paymentVerifiedApplications();
    }

    public function updatePaymentStatus($id, Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        try {
            $payment = Payment::findOrFail($id);
            $payment->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Payment status updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment status update failed.');
        }
    }
}