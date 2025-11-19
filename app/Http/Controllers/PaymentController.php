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
     * Show payment page
     */
    public function show($applicationId)
    {
        try {
            Log::info('Payment show method called', ['application_id' => $applicationId]);
            
            $application = Application::findOrFail($applicationId);
            
            if ($application->status !== 'payment_pending' && $application->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Invalid application status for payment.');
            }

            return view('payment.show', compact('application'));
            
        } catch (\Exception $e) {
            Log::error('Payment show error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    /**
     * Process payment with selected gateway
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
                'amount' => 50000,
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

            // Redirect to appropriate payment gateway
            return $this->redirectToGateway($request->payment_method, $payment);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Redirect to payment gateway
     */
    private function redirectToGateway($method, Payment $payment)
    {
        $callbackUrl = route('payment.webhook.gateway', ['gateway' => $method]);

        switch ($method) {
            case 'kpay':
                return $this->initiateKPayPayment($payment, $callbackUrl);
            case 'wavepay':
                return $this->initiateWavePayPayment($payment, $callbackUrl);
            case 'ayapay':
                return $this->initiateAYAPayPayment($payment, $callbackUrl);
            case 'okpay':
                return $this->initiateOKPayPayment($payment, $callbackUrl);
            case 'card':
                return $this->initiateCardPayment($payment, $callbackUrl);
            default:
                return redirect()->back()->with('error', 'Invalid payment method.');
        }
    }

    /**
     * Initiate KPay payment
     */
    private function initiateKPayPayment(Payment $payment, $callbackUrl)
    {
        try {
            $result = $this->paymentService->initiatePayment($payment, $callbackUrl);

            if ($result['success']) {
                // Update payment with gateway reference
                $payment->update(['gateway_reference' => $result['gateway_reference']]);

                // Redirect to KPay payment page
                return redirect()->away($result['payment_url']);
            } else {
                throw new \Exception($result['message']);
            }

        } catch (\Exception $e) {
            Log::error('KPay initiation error: ' . $e->getMessage());
            $payment->markAsFailed(['error' => $e->getMessage()]);
            return redirect()->route('payment.show', $payment->application_id)
                           ->with('error', 'KPay payment initiation failed: ' . $e->getMessage());
        }
    }


    public function handleCallback(Request $request)
{
    \Log::info('KBZ Callback Received', $request->all());
    
    $transactionId = $request->input('transaction_id');
    $status = $request->input('status');
    
    // Find and update payment
    $payment = Payment::where('transaction_id', $transactionId)->first();
    
    if ($payment) {
        $payment->status = $status === 'success' ? 'completed' : 'failed';
        $payment->save();
        
        \Log::info("Payment updated: {$transactionId} to {$payment->status}");
        
        return response()->json(['success' => true]);
    }
    
    \Log::error("Payment not found: {$transactionId}");
    return response()->json(['success' => false], 404);
}


    /**
     * Handle payment webhooks from gateways
     */
    public function webhook(Request $request, $gateway)
    {
        Log::info("Payment webhook received for gateway: {$gateway}", $request->all());

        try {
            switch ($gateway) {
                case 'kpay':
                    $result = $this->paymentService->handleKPayCallback($request->all());
                    break;
                case 'wavepay':
                    $result = $this->handleWavePayWebhook($request->all());
                    break;
                case 'ayapay':
                    $result = $this->handleAYAPayWebhook($request->all());
                    break;
                case 'okpay':
                    $result = $this->handleOKPayWebhook($request->all());
                    break;
                default:
                    return response()->json(['error' => 'Unknown gateway'], 400);
            }

            if ($result['success']) {
                return response()->json(['status' => 'success', 'message' => $result['message']]);
            } else {
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Payment success page
     */
    public function success($applicationId)
    {
        try {
            $application = Application::findOrFail($applicationId);
            $payment = Payment::where('application_id', $application->id)
                             ->where('status', Payment::STATUS_COMPLETED)
                             ->first();

            if (!$payment) {
                return redirect()->route('payment.show', $application->id)
                               ->with('error', 'Payment verification failed.');
            }

            return view('payment.success', compact('application', 'payment'));
            
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Application not found.');
        }
    }

    // Other methods (cancel, checkStatus, callback, etc.) remain the same...
    public function cancel($applicationId) { /* existing code */ }
    public function checkStatus($transactionId) { /* existing code */ }
    public function callback(Request $request) { /* existing code */ }
    public function retryPayment($applicationId) { /* existing code */ }

    // Simulation methods for testing
    public function simulateSuccess($paymentId)
    {
        if (!app()->environment('local', 'testing')) {
            abort(404);
        }

        $payment = Payment::findOrFail($paymentId);
        $payment->markAsCompleted('SIM_' . $payment->transaction_id, ['simulated' => true]);

        return redirect()->route('payment.success', $payment->application->id)
                       ->with('success', 'Payment simulation completed successfully!');
    }

    // Helper methods for other gateways
    private function initiateWavePayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
    private function initiateAYAPayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
    private function initiateOKPayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
    private function initiateCardPayment(Payment $payment, $callbackUrl) { /* implementation */ }
}