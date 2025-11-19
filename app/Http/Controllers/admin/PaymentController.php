<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index(Request $request)
    {
        try {
            $query = Payment::with('application')
                ->latest();

            // Filter by status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter by payment method
            if ($request->has('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->paginate(20);

            $stats = [
                'total' => Payment::count(),
                'completed' => Payment::completed()->count(),
                'pending' => Payment::pending()->count(),
                'failed' => Payment::failed()->count(),
                'total_amount' => Payment::completed()->sum('amount')
            ];

            return view('admin.payments.index', compact('payments', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Admin payment index error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Error loading payments.');
        }
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        try {
            $payment = Payment::with(['application'])->findOrFail($id);
            return view('admin.payments.show', compact('payment'));
        } catch (\Exception $e) {
            Log::error('Admin payment show error: ' . $e->getMessage());
            return redirect()->route('admin.payments.index')->with('error', 'Payment not found.');
        }
    }

    /**
     * Process refund
     */
    public function refund(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            if ($payment->status !== Payment::STATUS_COMPLETED) {
                return redirect()->back()->with('error', 'Only completed payments can be refunded.');
            }

            DB::beginTransaction();

            // Update payment status
            $payment->update([
                'status' => Payment::STATUS_REFUNDED,
                'notes' => $request->notes ?: 'Refund processed by admin'
            ]);

            // Update application status
            $payment->application->update([
                'payment_status' => 'refunded',
                'status' => 'payment_pending'
            ]);

            DB::commit();

            Log::info('Payment refund processed', [
                'payment_id' => $payment->id,
                'admin_id' => auth('admin')->id()
            ]);

            return redirect()->route('admin.payments.show', $payment->id)
                           ->with('success', 'Refund processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment refund error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Refund processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Export payments data
     */
    public function export(Request $request)
    {
        try {
            $payments = Payment::with('application')
                ->when($request->date_from, function($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->date_to, function($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->date_to);
                })
                ->when($request->status && $request->status !== 'all', function($q) use ($request) {
                    $q->where('status', $request->status);
                })
                ->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="payments_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'Transaction ID',
                    'Application ID',
                    'Student Name',
                    'Amount',
                    'Payment Method',
                    'Status',
                    'Paid At',
                    'Gateway Reference'
                ]);

                // Add data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->transaction_id,
                        $payment->application->application_id,
                        $payment->application->name,
                        $payment->amount,
                        $payment->payment_method_name,
                        $payment->status_text,
                        $payment->paid_at?->format('Y-m-d H:i:s'),
                        $payment->gateway_reference
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Payments export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}