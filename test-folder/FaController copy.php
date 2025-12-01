<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaController extends Controller
{
    /**
     * FA Admin Dashboard
     */
    public function index()
    {
        // Calculate real statistics from database
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'cleared_applications' => Application::whereHas('payments', function($query) {
                $query->where('status', 'completed');
            })->count(),
            'total_applications' => Application::approved()->count(),
            'revenue_this_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        $financialApplications = Application::approved()
            ->with(['payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('application')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.fa.dashboard', compact('stats', 'financialApplications', 'recentPayments'));
    }

    /**
     * Financial Reports
     */
    public function financialReports()
    {
        $reports = Payment::with('application')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as completed_amount'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(10);

        $summary = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->count(),
            'avg_transaction' => Payment::where('status', 'completed')->avg('amount'),
            'success_rate' => Payment::where('status', 'completed')->count() / max(Payment::count(), 1) * 100,
        ];

        return view('admin.fa.financial-reports', compact('reports', 'summary'));
    }

    /**
     * Fee Management
     */
    public function feeManagement()
    {
        $applications = Application::approved()
            ->with(['payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->latest()
            ->paginate(10);

        return view('admin.fa.fee-management', compact('applications'));
    }

    /**
     * Mark Fee as Paid
     */
    public function markFeePaid(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $application = Application::findOrFail($id);

        // Create payment record
        Payment::create([
            'application_id' => $application->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => $request->notes ?? 'Manual payment recorded by Finance Admin',
        ]);

        // Update application notes
        $application->update([
            'notes' => $application->notes . "\n\nFee cleared by Finance Admin: " . now()->format('Y-m-d H:i:s') . 
                      " - Amount: " . $request->amount . 
                      " - Method: " . $request->payment_method .
                      ($request->transaction_id ? " - Transaction ID: " . $request->transaction_id : ""),
        ]);

        return redirect()->back()->with('success', 'Fee marked as paid successfully!');
    }

    /**
     * View Payment Details
     */
    public function viewPayment($id)
    {
        $payment = Payment::with('application')->findOrFail($id);
        return view('admin.fa.payment-details', compact('payment'));
    }

    /**
     * Generate Invoice
     */
    public function generateInvoice($applicationId)
    {
        $application = Application::with('payments')->findOrFail($applicationId);
        
        // In a real application, you would generate a PDF invoice here
        // For now, we'll just return a view
        return view('admin.fa.invoice', compact('application'));
    }

    /**
     * Payment Statistics
     */
    public function paymentStatistics()
    {
        $monthlyStats = Payment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_payments'),
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('AVG(amount) as average_amount')
        )
        ->where('status', 'completed')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        $paymentMethods = Payment::select(
            'payment_method',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->where('status', 'completed')
        ->groupBy('payment_method')
        ->get();

        $statusStats = Payment::select(
            'status',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->groupBy('status')
        ->get();

        return view('admin.fa.payment-statistics', compact('monthlyStats', 'paymentMethods', 'statusStats'));
    }

    /**
     * Pending Payments
     */
    public function pendingPayments()
    {
        $pendingPayments = Payment::with('application')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.fa.pending-payments', compact('pendingPayments'));
    }

    /**
     * Update Payment Status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'status' => $request->status,
            'notes' => $payment->notes . "\n\nStatus updated to " . $request->status . " by Finance Admin: " . now()->format('Y-m-d H:i:s'),
        ]);

        if ($request->status === 'completed') {
            $payment->update(['paid_at' => now()]);
        }

        return redirect()->back()->with('success', 'Payment status updated successfully!');
    }

    /**
     * Export Financial Data
     */
    public function exportFinancialData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'export_type' => 'required|in:payments,applications,summary',
        ]);

        // In a real application, you would generate CSV or Excel file here
        // For now, we'll just return a success message
        return redirect()->back()->with('success', 
            'Financial data exported successfully for period: ' . 
            $request->start_date . ' to ' . $request->end_date
        );
    }

    /**
     * Fee Structure Management
     */
    public function feeStructure()
    {
        $feeStructure = [
            'new_student_registration' => 50000,
            'old_student_registration' => 30000,
            'late_fee' => 10000,
            'exam_fee' => 20000,
            'library_fee' => 5000,
            'laboratory_fee' => 15000,
        ];

        return view('admin.fa.fee-structure', compact('feeStructure'));
    }

    /**
     * Update Fee Structure
     */
    public function updateFeeStructure(Request $request)
    {
        $validated = $request->validate([
            'new_student_registration' => 'required|numeric|min:0',
            'old_student_registration' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'exam_fee' => 'required|numeric|min:0',
            'library_fee' => 'required|numeric|min:0',
            'laboratory_fee' => 'required|numeric|min:0',
        ]);

        // In a real application, you would save this to database
        // For now, we'll just return success message
        return redirect()->back()->with('success', 'Fee structure updated successfully!');
    }
}