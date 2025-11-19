<?php
// app/Services/PaymentGatewayService.php
namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    private $config;

    public function __construct()
    {
        $this->config = config('payment');
    }

    public function initiatePayment(Payment $payment, $callbackUrl)
    {
        $method = $payment->payment_method;
        
        $baseData = [
            'transaction_id' => $payment->transaction_id,
            'amount' => $payment->amount,
            'currency' => 'MMK',
            'callback_url' => $callbackUrl,
            'customer_name' => $payment->application->name,
            'customer_email' => $payment->application->email,
            'customer_phone' => $payment->application->phone,
            'description' => 'University Admission Fee - ' . $payment->application->application_id
        ];

        try {
            switch ($method) {
                case Payment::METHOD_KPAY:
                    return $this->initiateKPay($payment, $baseData);
                
                case Payment::METHOD_WAVEPAY:
                    return $this->initiateWavePay($payment, $baseData);
                
                case Payment::METHOD_AYAPAY:
                    return $this->initiateAyaPay($payment, $baseData);
                
                case Payment::METHOD_OKPAY:
                    return $this->initiateOKPay($payment, $baseData);
                
                case Payment::METHOD_CARD:
                    return $this->initiateCardPayment($payment, $baseData);
                
                default:
                    throw new \Exception("Unsupported payment method: {$method}");
            }
        } catch (\Exception $e) {
            Log::error("Payment initiation failed for {$method}: " . $e->getMessage());
            throw $e;
        }
    }

    private function initiateKPay(Payment $payment, $baseData)
    {
        $payload = array_merge($baseData, [
            'merchant_id' => $this->config['kpay_merchant_id'],
            'signature' => $this->generateKPaySignature($baseData)
        ]);

        $response = Http::timeout($this->config['timeout'])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->config['kpay_secret'],
                'Content-Type' => 'application/json'
            ])
            ->post($this->config['kpay_url'] . '/api/v1/payments', $payload);

        return $this->handleGatewayResponse($response, 'KPay');
    }

    private function initiateWavePay(Payment $payment, $baseData)
    {
        $payload = array_merge($baseData, [
            'merchant_id' => $this->config['wavepay_merchant_id'],
            'signature' => $this->generateWavePaySignature($baseData)
        ]);

        $response = Http::timeout($this->config['timeout'])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->config['wavepay_secret'],
                'Content-Type' => 'application/json'
            ])
            ->post($this->config['wavepay_url'] . '/payment/initiate', $payload);

        return $this->handleGatewayResponse($response, 'WavePay');
    }

    private function initiateAyaPay(Payment $payment, $baseData)
    {
        $payload = array_merge($baseData, [
            'merchant_code' => $this->config['ayapay_merchant_code'],
            'signature' => $this->generateAyaPaySignature($baseData)
        ]);

        $response = Http::timeout($this->config['timeout'])
            ->withHeaders([
                'X-API-KEY' => $this->config['ayapay_secret'],
                'Content-Type' => 'application/json'
            ])
            ->post($this->config['ayapay_url'] . '/v1/payments', $payload);

        return $this->handleGatewayResponse($response, 'AYA Pay');
    }

    private function initiateOKPay(Payment $payment, $baseData)
    {
        $payload = array_merge($baseData, [
            'merchant_id' => $this->config['okpay_merchant_id'],
            'signature' => $this->generateOKPaySignature($baseData)
        ]);

        $response = Http::timeout($this->config['timeout'])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->config['okpay_secret'],
                'Content-Type' => 'application/json'
            ])
            ->post($this->config['okpay_url'] . '/api/payment/create', $payload);

        return $this->handleGatewayResponse($response, 'OK Pay');
    }

    private function initiateCardPayment(Payment $payment, $baseData)
    {
        // For card payments, we'll use a simulated gateway or integrate with a real one
        $payload = array_merge($baseData, [
            'merchant_key' => $this->config['card_merchant_key'],
            'return_url' => route('payment.callback')
        ]);

        return [
            'success' => true,
            'payment_url' => route('payment.card.form', ['payment' => $payment->id]),
            'gateway_reference' => 'CARD_' . $payment->transaction_id,
            'payload' => $payload
        ];
    }

    private function handleGatewayResponse($response, $gatewayName)
    {
        if ($response->successful()) {
            $data = $response->json();
            
            return [
                'success' => true,
                'payment_url' => $data['payment_url'] ?? $data['redirect_url'] ?? $data['web_url'] ?? null,
                'gateway_reference' => $data['reference_id'] ?? $data['transaction_ref'] ?? $data['payment_id'] ?? null,
                'qr_code' => $data['qr_code'] ?? null,
                'deep_link' => $data['deep_link'] ?? null,
                'raw_response' => $data
            ];
        }

        throw new \Exception("{$gatewayName} API error: " . $response->body());
    }

    // Signature generation methods
    private function generateKPaySignature($data)
    {
        ksort($data);
        $signatureString = implode('', $data) . $this->config['kpay_secret'];
        return hash('sha256', $signatureString);
    }

    private function generateWavePaySignature($data)
    {
        $signatureString = $data['transaction_id'] . $data['amount'] . $this->config['wavepay_secret'];
        return hash('sha256', $signatureString);
    }

    private function generateAyaPaySignature($data)
    {
        ksort($data);
        $signatureString = http_build_query($data) . $this->config['ayapay_secret'];
        return hash('sha256', $signatureString);
    }

    private function generateOKPaySignature($data)
    {
        $signatureString = $data['amount'] . $data['transaction_id'] . $this->config['okpay_secret'];
        return hash('sha256', $signatureString);
    }

    // Webhook verification
    public function verifyWebhookSignature($payload, $signature, $method)
    {
        $secret = $this->config["{$method}_secret"];
        
        switch ($method) {
            case Payment::METHOD_KPAY:
                $expected = $this->generateKPaySignature($payload);
                break;
            case Payment::METHOD_WAVEPAY:
                $expected = $this->generateWavePaySignature($payload);
                break;
            case Payment::METHOD_AYAPAY:
                $expected = $this->generateAyaPaySignature($payload);
                break;
            case Payment::METHOD_OKPAY:
                $expected = $this->generateOKPaySignature($payload);
                break;
            default:
                return false;
        }

        return hash_equals($expected, $signature);
    }

    // Simulate payment for testing (remove in production)
    public function simulatePayment($payment, $status = 'success')
    {
        if (!app()->environment('local', 'testing')) {
            throw new \Exception('Simulation only allowed in local/testing environment');
        }

        if ($status === 'success') {
            return [
                'success' => true,
                'payment_url' => route('payment.simulate.success', ['payment' => $payment->id]),
                'gateway_reference' => 'SIM_' . $payment->transaction_id
            ];
        } else {
            return [
                'success' => true,
                'payment_url' => route('payment.simulate.failure', ['payment' => $payment->id]),
                'gateway_reference' => 'SIM_' . $payment->transaction_id
            ];
        }
    }
}