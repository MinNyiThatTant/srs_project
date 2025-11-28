<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show($applicationId)
    {
        try {
            Log::info('=== PAYMENT SHOW METHOD START ===', ['application_id' => $applicationId]);
            
            // Enhanced debugging
            \Log::debug('RAW applicationId parameter: ' . $applicationId);
            \Log::debug('Request URL: ' . request()->fullUrl());
            
            // Try multiple ways to find the application
            $application = Application::where('id', $applicationId)->first();
            
            if (!$application) {
                \Log::warning('Application not found by ID, trying application_id', ['id' => $applicationId]);
                $application = Application::where('application_id', $applicationId)->first();
            }

            if (!$application) {
                Log::error('APPLICATION NOT FOUND IN DATABASE', [
                    'searched_id' => $applicationId,
                    'all_applications_count' => Application::count(),
                    'sample_applications' => Application::select('id', 'application_id', 'name')->limit(5)->get()->toArray()
                ]);
                return redirect('/')->with('error', 'Application not found. Please check your application ID.');
            }

            Log::info('APPLICATION FOUND SUCCESSFULLY', [
                'db_id' => $application->id,
                'display_id' => $application->application_id,
                'name' => $application->name,
                'status' => $application->status,
                'payment_status' => $application->payment_status
            ]);

            // Check if application is ready for payment
            if ($application->status !== 'payment_pending') {
                Log::warning('INVALID APPLICATION STATUS', [
                    'current_status' => $application->status,
                    'required_status' => 'payment_pending'
                ]);
                return redirect()->back()->with('error', 'Application is not ready for payment. Current status: ' . $application->status);
            }

            if ($application->payment_status !== 'pending') {
                Log::warning('INVALID PAYMENT STATUS', [
                    'current_payment_status' => $application->payment_status,
                    'required_payment_status' => 'pending'
                ]);
                return redirect()->back()->with('error', 'Payment has already been processed. Current payment status: ' . $application->payment_status);
            }

            // Log view data being passed
            Log::info('PASSING DATA TO PAYMENT VIEW', [
                'application_id' => $application->id,
                'application_name' => $application->name,
                'application_email' => $application->email
            ]);

            return view('payment.show', compact('application'));
            
        } catch (\Exception $e) {
            Log::error('PAYMENT SHOW ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'An error occurred while loading the payment page: ' . $e->getMessage());
        }
    }

    /**
     * Process payment
     */
    public function process(Request $request, $applicationId)
    {
        Log::info('=== PAYMENT PROCESS START ===', [
            'application_id' => $applicationId,
            'payment_method' => $request->payment_method,
            'all_data' => $request->all()
        ]);

        // Allow test payments in local environment
        if (app()->environment('local') && $request->has('test_mode')) {
            Log::info('PROCESSING TEST PAYMENT');
            return $this->processTestPayment($applicationId, $request->payment_method);
        }

        $request->validate([
            'payment_method' => 'required|in:kpay,wavepay,ayapay,okpay,card'
        ]);

        DB::beginTransaction();

        try {
            $application = Application::find($applicationId);
            
            if (!$application) {
                throw new \Exception('Application not found with ID: ' . $applicationId);
            }

            // Create payment record
            $payment = Payment::create([
                'application_id' => $application->id,
                'amount' => 50000,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)) . time(),
                'paid_at' => now(),
            ]);

            // Update application status
            $application->update([
                'payment_status' => 'completed',
                'status' => 'payment_verified' // Changed to indicate payment is done
            ]);

            DB::commit();

            Log::info('=== PAYMENT COMPLETED SUCCESSFULLY ===', [
                'application_id' => $application->id,
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id
            ]);

            return redirect()->route('payment.success', $application->id)
                           ->with('success', 'Payment completed successfully! Your application is now under review.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== PAYMENT PROCESSING FAILED ===: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Process test payment
     */
    private function processTestPayment($applicationId, $paymentMethod)
    {
        DB::beginTransaction();
        
        try {
            $application = Application::find($applicationId);
            
            if (!$application) {
                throw new \Exception('Application not found with ID: ' . $applicationId);
            }

            $payment = Payment::create([
                'application_id' => $application->id,
                'amount' => 50000,
                'payment_method' => 'test',
                'status' => 'completed',
                'transaction_id' => 'TEST_' . strtoupper(Str::random(8)),
                'paid_at' => now(),
            ]);

            $application->update([
                'payment_status' => 'completed',
                'status' => 'payment_verified',
            ]);

            DB::commit();

            Log::info('=== TEST PAYMENT COMPLETED ===', [
                'application_id' => $application->id,
                'payment_id' => $payment->id
            ]);

            return redirect()->route('payment.success', $application->id)
                           ->with('success', 'Test payment completed successfully! Your application is now under review.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== TEST PAYMENT FAILED ===: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Test payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Payment success page
     */
    public function success($applicationId)
    {
        try {
            Log::info('=== PAYMENT SUCCESS PAGE ===', ['application_id' => $applicationId]);

            $application = Application::find($applicationId);
            
            if (!$application) {
                Log::error('Application not found in success page: ' . $applicationId);
                return redirect('/')->with('error', 'Application not found.');
            }

            $payment = Payment::where('application_id', $application->id)
                             ->where('status', 'completed')
                             ->latest()
                             ->first();

            if (!$payment) {
                Log::warning('No payment found for application', ['application_id' => $application->id]);
                return redirect()->route('payment.show', $application->id)
                               ->with('error', 'No payment record found. Please complete your payment.');
            }

            Log::info('PAYMENT SUCCESS PAGE LOADED', [
                'application_id' => $application->id,
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id
            ]);

            return view('payment.success', compact('application', 'payment'));
            
        } catch (\Exception $e) {
            Log::error('PAYMENT SUCCESS PAGE ERROR: ' . $e->getMessage());
            return redirect('/')->with('error', 'Error loading payment success page.');
        }
    }

    /**
     * Payment cancel page
     */
    public function cancel($applicationId)
    {
        try {
            $application = Application::find($applicationId);
            
            if (!$application) {
                return redirect('/')->with('error', 'Application not found.');
            }

            Log::info('Payment cancelled', [
                'application_id' => $application->id,
                'name' => $application->name
            ]);

            return view('payment.cancel', compact('application'));
            
        } catch (\Exception $e) {
            Log::error('PAYMENT CANCEL PAGE ERROR: ' . $e->getMessage());
            return redirect('/')->with('error', 'Error loading payment cancellation page.');
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($applicationId)
    {
        try {
            $application = Application::find($applicationId);
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found'
                ], 404);
            }

            $payment = Payment::where('application_id', $application->id)
                             ->latest()
                             ->first();

            return response()->json([
                'success' => true,
                'application' => [
                    'id' => $application->id,
                    'application_id' => $application->application_id,
                    'status' => $application->status,
                    'payment_status' => $application->payment_status,
                    'name' => $application->name
                ],
                'payment' => $payment ? [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'transaction_id' => $payment->transaction_id,
                    'payment_method' => $payment->payment_method,
                    'paid_at' => $payment->paid_at
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status'
            ], 500);
        }
    }

    /**
     * Verify payment (for external callbacks if needed)
     */
    public function verifyPayment($transactionId)
    {
        try {
            $payment = Payment::where('transaction_id', $transactionId)
                             ->where('status', 'completed')
                             ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found or not completed'
                ], 404);
            }

            $application = Application::find($payment->application_id);

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'paid_at' => $payment->paid_at
                ],
                'application' => $application ? [
                    'id' => $application->id,
                    'application_id' => $application->application_id,
                    'name' => $application->name,
                    'status' => $application->status,
                    'payment_status' => $application->payment_status
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment'
            ], 500);
        }
    }
}