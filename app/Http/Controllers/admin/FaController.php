<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_verifications' => Application::where('payment_status', 'pending')
                ->whereHas('payments', function($query) {
                    $query->where('status', 'completed');
                })->count(),
            'today_payments' => Payment::where('status', 'completed')
                ->whereDate('paid_at', today())
                ->sum('amount'),
            'recent_payments' => Payment::with('application')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];

        return $stats;
    }

    public function financeApplications()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $applications = Application::where('payment_status', 'pending')
            ->whereHas('payments', function($query) {
                $query->where('status', 'completed');
            })
            ->with('payments')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applications.finance', compact('applications'));
    }

    public function verifyPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $application = Application::findOrFail($id);
        $application->update([
            'payment_status' => 'verified',
            'status' => 'payment_verified'
        ]);

        return redirect()->back()->with('success', 'Payment verified successfully');
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

    public function feeManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        return view('admin.fee.management');
    }

    public function markFeePaid($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        // Mark fee as paid logic
        return redirect()->back()->with('success', 'Fee marked as paid');
    }

    public function viewPayment($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payment = Payment::with('application')->findOrFail($id);
        return view('admin.payments.view', compact('payment'));
    }

    public function paymentStatistics()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_transactions' => Payment::count(),
            'success_rate' => Payment::where('status', 'completed')->count() / max(Payment::count(), 1) * 100
        ];

        return view('admin.payments.statistics', compact('stats'));
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

        return view('admin.payments.pending', compact('payments'));
    }

    public function updatePaymentStatus($id, Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->role !== 'fa_admin') {
            abort(403, 'Access denied. Finance admin only.');
        }

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Payment status updated');
    }
}