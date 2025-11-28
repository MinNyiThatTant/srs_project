<?php
namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    private $kpayConfig;
    private $wavepayConfig;
    private $ayapayConfig;
    private $okpayConfig;

    public function __construct()
    {
        $this->kpayConfig = [
            'base_url' => config('payment.kpay_base_url', 'https://api.kpay.com.mm'),
            'merchant_id' => config('payment.kpay_merchant_id'),
            'secret_key' => config('payment.kpay_secret_key'),
            'callback_url' => config('payment.kpay_callback_url')
        ];

        $this->wavepayConfig = [
            'base_url' => config('payment.wavepay_base_url', 'https://api.wavepay.com.mm'),
            'merchant_id' => config('payment.wavepay_merchant_id'),
            'secret_key' => config('payment.wavepay_secret_key')
        ];

        // Similar config for other gateways...
    }

    /**
     * Initiate payment with KPay
     */
    public function initiatePayment(Payment $payment, $callbackUrl)
    {
        try {
            Log::info('Initiating KPay payment', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id
            ]);

            $payload = [
                'merchantID' => $this->kpayConfig['merchant_id'],
                'invoiceNo' => $payment->transaction_id,
                'amount' => $payment->amount,
                'currencyCode' => 'MMK',
                'description' => 'Admission Fee Payment',
                'frontendReturnUrl' => route('payment.callback.handler'),
                'backendCallbackUrl' => $callbackUrl,
                'timestamp' => time()
            ];

            // Generate signature
            $payload['signature'] = $this->generateKPaySignature($payload);

            // Make API call to KPay
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->kpayConfig['secret_key']
                ])
                ->post($this->kpayConfig['base_url'] . '/api/v1/payment/initiate', $payload);

            $responseData = $response->json();

            Log::info('KPay initiation response', $responseData);

            if ($response->successful() && $responseData['respCode'] === '000') {
                return [
                    'success' => true,
                    'payment_url' => $responseData['webPaymentUrl'],
                    'gateway_reference' => $responseData['referenceNo'],
                    'message' => 'Payment initiated successfully'
                ];
            } else {
                throw new \Exception($responseData['respDesc'] ?? 'KPay initiation failed');
            }

        } catch (\Exception $e) {
            Log::error('KPay initiation error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate KPay signature
     */
    private function generateKPaySignature($payload)
    {
        $signatureString = $payload['merchantID'] . $payload['invoiceNo'] . $payload['amount'] . 
                          $payload['currencyCode'] . $payload['timestamp'] . $this->kpayConfig['secret_key'];
        
        return hash('sha256', $signatureString);
    }

    /**
     * Verify KPay webhook signature
     */
    public function verifyKPayWebhook($payload, $signature)
    {
        $verifyString = $payload['invoiceNo'] . $payload['referenceNo'] . $payload['amount'] . 
                       $payload['currencyCode'] . $payload['statusCode'] . $payload['timestamp'] . 
                       $this->kpayConfig['secret_key'];
        
        $computedSignature = hash('sha256', $verifyString);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Handle KPay callback
     */
    public function handleKPayCallback($payload)
    {
        try {
            Log::info('KPay callback received', $payload);

            // Verify signature
            if (!$this->verifyKPayWebhook($payload, $payload['signature'])) {
                throw new \Exception('Invalid KPay signature');
            }

            // Find payment
            $payment = Payment::where('transaction_id', $payload['invoiceNo'])->first();

            if (!$payment) {
                throw new \Exception('Payment not found: ' . $payload['invoiceNo']);
            }

            // Update payment status based on KPay response
            if ($payload['statusCode'] === '000') {
                $payment->markAsCompleted($payload['referenceNo'], $payload);
                return [
                    'success' => true,
                    'message' => 'Payment completed successfully'
                ];
            } else {
                $payment->markAsFailed($payload);
                return [
                    'success' => false,
                    'message' => 'Payment failed: ' . ($payload['statusDesc'] ?? 'Unknown error')
                ];
            }

        } catch (\Exception $e) {
            Log::error('KPay callback error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature for any gateway
     */
    public function verifyWebhookSignature($data, $signature, $gateway)
    {
        switch ($gateway) {
            case 'kpay':
                return $this->verifyKPayWebhook($data, $signature);
            case 'wavepay':
                return $this->verifyWavePayWebhook($data, $signature);
            // Add other gateways...
            default:
                return false;
        }
    }

    // Similar methods for other payment gateways...
    public function initiateWavePayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
    public function initiateAYAPayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
    public function initiateOKPayPayment(Payment $payment, $callbackUrl) { /* implementation */ }
}