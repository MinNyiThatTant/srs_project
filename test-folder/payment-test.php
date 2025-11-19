<?php
// config/payment.php
return [
    // KPay Configuration
    'kpay_merchant_id' => env('KPAY_MERCHANT_ID', 'test_kpay_merchant'),
    'kpay_secret' => env('KPAY_SECRET_KEY', 'test_kpay_secret'),
    'kpay_url' => env('KPAY_BASE_URL', 'https://api.kpay.com.mm'),
    
    // WavePay Configuration
    'wavepay_merchant_id' => env('WAVEPAY_MERCHANT_ID', 'test_wave_merchant'),
    'wavepay_secret' => env('WAVEPAY_SECRET_KEY', 'test_wave_secret'),
    'wavepay_url' => env('WAVEPAY_BASE_URL', 'https://api.wavepay.com.mm'),
    
    // AYA Pay Configuration
    'ayapay_merchant_code' => env('AYAPAY_MERCHANT_CODE', 'test_aya_merchant'),
    'ayapay_secret' => env('AYAPAY_SECRET_KEY', 'test_aya_secret'),
    'ayapay_url' => env('AYAPAY_BASE_URL', 'https://api.ayapay.com'),
    
    // OK Pay Configuration
    'okpay_merchant_id' => env('OKPAY_MERCHANT_ID', 'test_ok_merchant'),
    'okpay_secret' => env('OKPAY_SECRET_KEY', 'test_ok_secret'),
    'okpay_url' => env('OKPAY_BASE_URL', 'https://api.okpay.com.mm'),
    
    // Card Payment Configuration
    'card_merchant_key' => env('CARD_MERCHANT_KEY', 'test_card_key'),
    
    // General Settings
    'admission_fee' => env('ADMISSION_FEE', 50000),
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', 'webhook_secret_123'),
    'timeout' => 30,
    'simulate_payments' => env('SIMULATE_PAYMENTS', false),
];