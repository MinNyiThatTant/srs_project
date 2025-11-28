<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FaController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }
        
        $stats = $this->getDashboardStats();
        
        // Get applications with completed payments for verification
        $applications = Application::where('payment_status', 'completed')
            ->where('status', 'payment_pending')
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.finance.dashboard-finance', compact('stats', 'applications', 'admin'));
    }

    private function getDashboardStats()
    {
        return [
            'total_payments' => Payment::where('status', 'completed')->sum('amount') ?? 0,
            'pending_verifications' => Application::where('payment_status', 'completed')
                ->where('status', 'payment_pending')
                ->count(),
            'verified_today' => Application::where('payment_status', 'verified')
                ->whereDate('payment_verified_at', today())
                ->count(),
            'total_verified' => Application::where('payment_status', 'verified')->count(),
            'today_payments' => Payment::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount') ?? 0,
            'recent_payments' => Payment::with(['application'])
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    public function verifyPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $application = Application::findOrFail($id);
        
        // Check if payment is actually completed
        $hasCompletedPayment = $application->payments()
            ->where('status', 'completed')
            ->exists();
            
        if (!$hasCompletedPayment) {
            return redirect()->back()->with('error', 'No completed payment found for this application.');
        }

        // Check if already verified
        if ($application->payment_status === 'verified') {
            return redirect()->back()->with('error', 'Payment already verified for this application.');
        }

        // Update application payment status - move to academic review
        $application->update([
            'payment_status' => 'verified',
            'status' => 'payment_verified', 
            'payment_verified_by' => $admin->id,
            'payment_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment verified successfully! Application moved to academic review.');
    }

    public function financeApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        // Get applications with completed payments for verification
        $applications = Application::where('payment_status', 'completed')
            ->where('status', 'payment_pending')
            ->with(['payments' => function($query) {
                $query->where('status', 'completed')->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.applications', compact('applications', 'admin'));
    }


    public function financialReports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $stats = $this->getFinancialReportStats();
        $payments = Payment::with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.reports', compact('payments', 'stats', 'admin'));
    }

    public function feeManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $feeSettings = [
            'admission_fee' => 50000,
            'processing_fee' => 5000,
            'late_fee' => 10000,
        ];

        return view('admin.finance.fee-management', compact('feeSettings', 'admin'));
    }

    public function markFeePaid($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $application = Application::findOrFail($id);
        
        // Create a payment record
        $payment = Payment::create([
            'application_id' => $application->id,
            'amount' => 50000, // Admission fee
            'payment_method' => 'manual',
            'status' => 'completed',
            'transaction_id' => 'MANUAL_' . time() . '_' . $application->id,
            'paid_at' => now(),
        ]);

        // Update application payment status
        $application->update([
            'payment_status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Fee marked as paid manually. Application ready for payment verification.');
    }

    public function viewPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payment = Payment::with('application')->findOrFail($id);
        return view('admin.finance.payment-view', compact('payment', 'admin'));
    }

    public function paymentStatistics()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $stats = $this->getPaymentStatistics();
        return view('admin.finance.statistics', compact('stats', 'admin'));
    }

    public function pendingPayments()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payments = Payment::where('status', 'pending')
            ->with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.pending-payments', compact('payments', 'admin'));
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $payment = Payment::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        if ($request->status === 'completed') {
            $updateData['paid_at'] = now();
        }

        $payment->update($updateData);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    public function paymentReports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $payments = Payment::with('application')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $summary = $this->getPaymentSummary($dateFrom, $dateTo);

        return view('admin.finance.payment-reports', compact('payments', 'summary', 'dateFrom', 'dateTo', 'admin'));
    }

    private function getFinancialReportStats()
    {
        $today = now()->format('Y-m-d');
        $monthStart = now()->startOfMonth()->format('Y-m-d');
        $yearStart = now()->startOfYear()->format('Y-m-d');

        return [
            'today_revenue' => Payment::where('status', 'completed')
                ->whereDate('created_at', $today)
                ->sum('amount'),
            'month_revenue' => Payment::where('status', 'completed')
                ->whereDate('created_at', '>=', $monthStart)
                ->sum('amount'),
            'year_revenue' => Payment::where('status', 'completed')
                ->whereDate('created_at', '>=', $yearStart)
                ->sum('amount'),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_verifications' => Application::readyForVerification()->count(),
        ];
    }

    private function getPaymentStatistics()
    {
        return [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_transactions' => Payment::count(),
            'completed_transactions' => Payment::where('status', 'completed')->count(),
            'pending_transactions' => Payment::where('status', 'pending')->count(),
            'failed_transactions' => Payment::where('status', 'failed')->count(),
            'success_rate' => Payment::count() > 0 ? 
                (Payment::where('status', 'completed')->count() / Payment::count() * 100) : 0,
            'verified_applications' => Application::where('payment_status', 'verified')->count(),
            'pending_verification' => Application::readyForVerification()->count(),
            'payment_methods' => Payment::select('payment_method', DB::raw('count(*) as count'))
                ->groupBy('payment_method')
                ->get()
                ->pluck('count', 'payment_method')
        ];
    }

    private function getPaymentSummary($dateFrom, $dateTo)
    {
        return [
            'total_amount' => Payment::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->where('status', 'completed')
                ->sum('amount'),
            'total_transactions' => Payment::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->count(),
            'completed_transactions' => Payment::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->where('status', 'completed')
                ->count(),
            'pending_transactions' => Payment::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->where('status', 'pending')
                ->count(),
        ];
    }

    public function paymentsIndex()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payments = Payment::with('application')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.payments-index', compact('payments', 'admin'));
    }
}
