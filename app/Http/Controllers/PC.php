<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show payment page (FIXED - using applicationId parameter)
     */
    public function show($applicationId)
    {
        try {
            Log::info('Payment show method called', ['application_id' => $applicationId]);
            
            $application = Application::findOrFail($applicationId);
            
            // Check if application is in correct status for payment
            if ($application->status !== 'payment_pending' && $application->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Invalid application status for payment.');
            }

            Log::info('Rendering payment page', ['application_id' => $application->id]);
            return view('payment.show', compact('application'));
            
        } catch (\Exception $e) {
            Log::error('Payment show error: ' . $e->getMessage(), ['application_id' => $applicationId]);
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Process payment (FIXED - using applicationId parameter)
     */
    public function process(Request $request, $applicationId)
    {
        Log::info('Payment process method called', [
            'application_id' => $applicationId,
            'method' => $request->payment_method
        ]);

        $request->validate([
            'payment_method' => 'required|in:kpay,wavepay,ayapay,okpay,card'
        ]);

        try {
            $application = Application::findOrFail($applicationId);
            
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'application_id' => $application->id,
                'amount' => 50000, // 50,000 MMK
                'payment_method' => $request->payment_method,
                'status' => Payment::STATUS_PENDING,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)) . time()
            ]);

            // Update application status
            $application->update([
                'status' => 'payment_processing',
                'payment_status' => 'processing'
            ]);

            DB::commit();

            Log::info('Payment processing started', [
                'application_id' => $application->id,
                'payment_id' => $payment->id,
                'method' => $request->payment_method
            ]);

            // For demo purposes, simulate payment success
            if (app()->environment('local', 'testing')) {
                return $this->simulatePaymentSuccess($payment);
            }

            // Redirect to appropriate payment gateway
            return $this->redirectToGateway($request->payment_method, $payment);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'application_id' => $applicationId,
                'method' => $request->payment_method
            ]);

            return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Payment success (FIXED - using applicationId parameter)
     */
    public function success($applicationId)
    {
        try {
            Log::info('Payment success method called', ['application_id' => $applicationId]);
            
            $application = Application::findOrFail($applicationId);
            
            // Verify payment success
            $payment = Payment::where('application_id', $application->id)
                             ->where('status', Payment::STATUS_COMPLETED)
                             ->first();

            if (!$payment) {
                Log::warning('Payment verification failed', ['application_id' => $application->id]);
                return redirect()->route('payment.show', $application->id)
                               ->with('error', 'Payment verification failed. Please check your payment status.');
            }

            Log::info('Payment success page rendered', ['application_id' => $application->id]);
            return view('payment.success', compact('application', 'payment'));
            
        } catch (\Exception $e) {
            Log::error('Payment success error: ' . $e->getMessage(), ['application_id' => $applicationId]);
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Payment cancellation (FIXED - using applicationId parameter)
     */
    public function cancel($applicationId)
    {
        try {
            Log::info('Payment cancel method called', ['application_id' => $applicationId]);
            
            $application = Application::findOrFail($applicationId);
            
            // Update payment status to cancelled
            $payment = Payment::where('application_id', $application->id)
                             ->where('status', Payment::STATUS_PENDING)
                             ->first();

            if ($payment) {
                $payment->update(['status' => Payment::STATUS_FAILED]);
            }

            // Reset application status
            $application->update([
                'status' => 'payment_pending',
                'payment_status' => 'pending'
            ]);

            Log::info('Payment cancelled', ['application_id' => $application->id]);
            return redirect()->route('payment.show', $application->id)
                           ->with('error', 'Payment was cancelled.');
            
        } catch (\Exception $e) {
            Log::error('Payment cancel error: ' . $e->getMessage(), ['application_id' => $applicationId]);
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Handle payment webhooks
     */
    public function webhook(Request $request, $gateway)
    {
        Log::info("Payment webhook received for gateway: {$gateway}", $request->all());

        // Handle payment gateway webhooks
        $payload = $request->all();
        
        switch ($gateway) {
            case 'kpay':
                return $this->handleKPayWebhook($payload);
            case 'wavepay':
                return $this->handleWavePayWebhook($payload);
            case 'ayapay':
                return $this->handleAYAPayWebhook($payload);
            case 'okpay':
                return $this->handleOKPayWebhook($payload);
            default:
                return response()->json(['error' => 'Unknown gateway'], 400);
        }
    }

    // ========== EXISTING METHODS ==========
    
    /**
     * Initiate payment (existing method)
     */
    public function initiate(Request $request, $applicationId)
    {
        Log::info('Payment initiate method called', [
            'application_id' => $applicationId,
            'method' => $request->payment_method
        ]);

        $request->validate([
            'payment_method' => 'required|in:kpay,wavepay,ayapay,okpay,card'
        ]);

        try {
            $application = Application::where('application_id', $applicationId)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            // Check if payment is required
            if (!$this->requiresPayment($application)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment is not required for this application.'
                ], 400);
            }

            // Create payment record
            $payment = Payment::create([
                'application_id' => $application->id,
                'amount' => 50000, // 50,000 MMK
                'payment_method' => $request->payment_method,
                'status' => Payment::STATUS_PENDING,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)) . time()
            ]);

            // If payment service is available, use it
            if ($this->paymentService && method_exists($this->paymentService, 'initiatePayment')) {
                $callbackUrl = route('payment.handle.webhook');
                $result = $this->paymentService->initiatePayment($payment, $callbackUrl);

                if ($result['success']) {
                    // Update payment with gateway reference
                    $payment->update(['gateway_reference' => $result['gateway_reference']]);

                    Log::info('Payment initiated successfully via service', [
                        'transaction_id' => $payment->transaction_id,
                        'application_id' => $application->application_id,
                        'method' => $request->payment_method
                    ]);

                    return response()->json([
                        'success' => true,
                        'payment_url' => $result['payment_url'],
                        'transaction_id' => $payment->transaction_id,
                        'gateway_reference' => $result['gateway_reference'],
                        'message' => 'Payment initiated successfully'
                    ]);
                }

                throw new \Exception('Payment initiation failed: ' . ($result['message'] ?? 'Unknown error'));
            } else {
                // Fallback: simulate payment initiation
                $payment->update(['gateway_reference' => 'GATEWAY_' . $payment->transaction_id]);

                Log::info('Payment initiated successfully (simulated)', [
                    'transaction_id' => $payment->transaction_id,
                    'application_id' => $application->application_id
                ]);

                return response()->json([
                    'success' => true,
                    'payment_url' => route('payment.simulate.success', $payment->id),
                    'transaction_id' => $payment->transaction_id,
                    'gateway_reference' => $payment->gateway_reference,
                    'message' => 'Payment initiated successfully (simulated)'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment initiation error: ' . $e->getMessage(), [
                'application_id' => $applicationId,
                'method' => $request->payment_method
            ]);

            // Mark payment as failed
            if (isset($payment)) {
                $payment->update([
                    'status' => Payment::STATUS_FAILED, 
                    'notes' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment. Please try again or contact support.'
            ], 500);
        }
    }

    /**
     * Handle webhook (existing method)
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Payment webhook received:', $request->all());

        try {
            // Validate webhook request
            $validated = $request->validate([
                'transaction_id' => 'required|string',
                'status' => 'required|in:success,failed,completed,pending',
                'gateway_reference' => 'required|string',
                'signature' => 'sometimes|string',
                'payment_method' => 'required|string'
            ]);

            // Verify signature if provided
            if (isset($validated['signature']) && $this->paymentService) {
                $isValid = $this->paymentService->verifyWebhookSignature(
                    $request->except('signature'),
                    $validated['signature'],
                    $validated['payment_method']
                );

                if (!$isValid) {
                    Log::warning('Invalid webhook signature', $request->all());
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            }

            // Find payment
            $payment = Payment::where('transaction_id', $validated['transaction_id'])
                             ->orWhere('gateway_reference', $validated['gateway_reference'])
                             ->first();

            if (!$payment) {
                Log::error('Payment not found for transaction: ' . $validated['transaction_id']);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Update payment status
            if (in_array($validated['status'], ['success', 'completed'])) {
                $payment->markAsCompleted($validated['gateway_reference'], $request->all());
                
                Log::info('Payment completed successfully via webhook', [
                    'transaction_id' => $payment->transaction_id,
                    'application_id' => $payment->application->application_id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully'
                ]);
            } else if ($validated['status'] === 'failed') {
                $payment->markAsFailed($request->all());
                
                Log::warning('Payment failed via webhook', [
                    'transaction_id' => $payment->transaction_id,
                    'reason' => $request->get('failure_reason', 'Unknown')
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed'
                ]);
            } else {
                // Payment still pending
                Log::info('Payment still pending via webhook', [
                    'transaction_id' => $payment->transaction_id,
                    'status' => $validated['status']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated to pending'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Webhook handling error: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Check payment status (existing method)
     */
    public function checkStatus($transactionId)
    {
        try {
            $payment = Payment::with('application')
                ->where('transaction_id', $transactionId)
                ->firstOrFail();

            Log::info('Payment status checked', ['transaction_id' => $transactionId]);

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'payment_status' => $payment->application->payment_status,
                'application_status' => $payment->application->status,
                'student_id' => $payment->application->student_id,
                'paid_at' => $payment->paid_at,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method_name
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage(), ['transaction_id' => $transactionId]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }
    }

    /**
     * Payment callback (existing method)
     */
    public function callback(Request $request)
    {
        Log::info('Payment callback received:', $request->all());

        $transactionId = $request->get('transaction_id') ?? $request->get('txn_id') ?? $request->get('reference');
        
        if (!$transactionId) {
            Log::warning('Payment callback without transaction ID', $request->all());
            return redirect('/')->with('error', 'Invalid callback - missing transaction ID');
        }

        try {
            $payment = Payment::with('application')
                ->where('transaction_id', $transactionId)
                ->orWhere('gateway_reference', $transactionId)
                ->first();

            if (!$payment) {
                Log::error('Payment not found for callback', ['transaction_id' => $transactionId]);
                return redirect('/')->with('error', 'Payment not found');
            }

            if ($payment->isSuccessful()) {
                Log::info('Payment callback - successful', ['transaction_id' => $transactionId]);
                return redirect()->route('payment.success', $payment->application->id)
                    ->with('success', 'Payment completed successfully!');
            }

            if ($payment->isPending()) {
                Log::info('Payment callback - still pending', ['transaction_id' => $transactionId]);
                return redirect()->route('applications.show', $payment->application->application_id)
                    ->with('warning', 'Payment is still processing. Please check back later.');
            }

            Log::warning('Payment callback - failed', ['transaction_id' => $transactionId]);
            return redirect()->route('applications.show', $payment->application->application_id)
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), ['transaction_id' => $transactionId]);
            return redirect('/')->with('error', 'Error processing payment callback.');
        }
    }

    /**
     * Retry payment (existing method)
     */
    public function retryPayment($applicationId)
    {
        try {
            $application = Application::where('application_id', $applicationId)
                ->where('payment_status', 'failed')
                ->firstOrFail();

            $lastPayment = $application->latestPayment;

            if (!$lastPayment || !$lastPayment->canRetry()) {
                return redirect()->route('applications.show', $application->application_id)
                    ->with('error', 'Cannot retry this payment. Please initiate a new payment.');
            }

            Log::info('Payment retry initiated', ['application_id' => $applicationId]);

            // Create a new payment record for retry
            $newPayment = Payment::create([
                'application_id' => $application->id,
                'amount' => 50000,
                'payment_method' => $lastPayment->payment_method,
                'status' => Payment::STATUS_PENDING,
                'transaction_id' => 'TXN' . strtoupper(Str::random(10)) . time(),
                'notes' => 'Retry of payment ' . $lastPayment->transaction_id
            ]);

            // Update application status
            $application->update([
                'status' => 'payment_processing',
                'payment_status' => 'processing'
            ]);

            return redirect()->route('payment.show', $application->id)
                ->with('success', 'Payment retry initiated. Please complete the payment.');

        } catch (\Exception $e) {
            Log::error('Payment retry error: ' . $e->getMessage(), ['application_id' => $applicationId]);
            return redirect()->route('applications.show', $applicationId)
                ->with('error', 'Unable to retry payment. Please contact support.');
        }
    }

    /**
     * Simulation routes for testing (remove in production)
     */
    public function simulateSuccess($paymentId)
    {
        if (!app()->environment('local', 'testing')) {
            abort(404);
        }

        try {
            $payment = Payment::findOrFail($paymentId);
            $payment->markAsCompleted('SIM_' . $payment->transaction_id, ['simulated' => true]);

            Log::info('Payment simulation completed', ['payment_id' => $paymentId]);

            return redirect()->route('payment.success', $payment->application->id)
                ->with('success', 'Payment simulation completed successfully!');

        } catch (\Exception $e) {
            Log::error('Payment simulation error: ' . $e->getMessage(), ['payment_id' => $paymentId]);
            return redirect('/')->with('error', 'Payment simulation failed.');
        }
    }

    public function simulateFailure($paymentId)
    {
        if (!app()->environment('local', 'testing')) {
            abort(404);
        }

        try {
            $payment = Payment::findOrFail($paymentId);
            $payment->markAsFailed(['simulated' => true, 'failure_reason' => 'Simulated failure']);

            Log::info('Payment simulation failed', ['payment_id' => $paymentId]);

            return redirect()->route('applications.show', $payment->application->application_id)
                ->with('error', 'Payment simulation failed.');

        } catch (\Exception $e) {
            Log::error('Payment simulation failure error: ' . $e->getMessage(), ['payment_id' => $paymentId]);
            return redirect('/')->with('error', 'Payment simulation failure error.');
        }
    }

    // ========== HELPER METHODS ==========

    private function redirectToGateway($method, Payment $payment)
    {
        switch ($method) {
            case 'kpay':
                return $this->redirectToKPay($payment);
            case 'wavepay':
                return $this->redirectToWavePay($payment);
            case 'ayapay':
                return $this->redirectToAYAPay($payment);
            case 'okpay':
                return $this->redirectToOKPay($payment);
            case 'card':
                return $this->redirectToCreditCard($payment);
            default:
                throw new \Exception('Unknown payment method');
        }
    }

    private function redirectToKPay(Payment $payment)
    {
        // Simulate payment success for demo
        $this->simulatePaymentSuccess($payment);
        return redirect()->route('payment.success', $payment->application_id);
    }

    private function redirectToWavePay(Payment $payment)
    {
        // Simulate payment success for demo
        $this->simulatePaymentSuccess($payment);
        return redirect()->route('payment.success', $payment->application_id);
    }

    private function redirectToAYAPay(Payment $payment)
    {
        // Simulate payment success for demo
        $this->simulatePaymentSuccess($payment);
        return redirect()->route('payment.success', $payment->application_id);
    }

    private function redirectToOKPay(Payment $payment)
    {
        // Simulate payment success for demo
        $this->simulatePaymentSuccess($payment);
        return redirect()->route('payment.success', $payment->application_id);
    }

    private function redirectToCreditCard(Payment $payment)
    {
        // Simulate payment success for demo
        $this->simulatePaymentSuccess($payment);
        return redirect()->route('payment.success', $payment->application_id);
    }

    private function simulatePaymentSuccess(Payment $payment)
    {
        // Simulate successful payment
        $payment->update([
            'status' => Payment::STATUS_COMPLETED,
            'paid_at' => now(),
            'gateway_reference' => 'DEMO_' . $payment->transaction_id,
            'gateway_response' => ['simulated' => true, 'demo' => true]
        ]);

        // Update application
        $payment->application->update([
            'status' => 'academic_review',
            'payment_status' => 'completed'
        ]);

        Log::info('Payment simulation completed', [
            'payment_id' => $payment->id,
            'application_id' => $payment->application_id
        ]);
    }

    private function requiresPayment(Application $application)
    {
        // Check if payment is required for this application
        return in_array($application->status, ['payment_pending', 'payment_processing']) &&
               in_array($application->payment_status, ['pending', 'processing', 'failed']);
    }

    private function handleKPayWebhook($payload)
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $status = $payload['status'] ?? null;

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment && $status === 'success') {
            $payment->markAsCompleted(
                $payload['gateway_reference'] ?? null, 
                $payload
            );
        } else if ($payment) {
            $payment->markAsFailed($payload);
        }

        return response()->json(['status' => 'success']);
    }

    private function handleWavePayWebhook($payload) 
    {
        // Similar implementation as KPay
        return $this->handleKPayWebhook($payload);
    }

    private function handleAYAPayWebhook($payload) 
    {
        // Similar implementation as KPay
        return $this->handleKPayWebhook($payload);
    }

    private function handleOKPayWebhook($payload) 
    {
        // Similar implementation as KPay
        return $this->handleKPayWebhook($payload);
    }
}