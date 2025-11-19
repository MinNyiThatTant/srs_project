<?php

return [
    // KPay Configuration
    'kpay_base_url' => env('KPAY_BASE_URL', 'https://api.kpay.com.mm'),
    'kpay_merchant_id' => env('KPAY_MERCHANT_ID'),
    'kpay_secret_key' => env('KPAY_SECRET_KEY'),
    'kpay_callback_url' => env('KPAY_CALLBACK_URL'),

    // WavePay Configuration
    'wavepay_base_url' => env('WAVEPAY_BASE_URL', 'https://api.wavepay.com.mm'),
    'wavepay_merchant_id' => env('WAVEPAY_MERCHANT_ID'),
    'wavepay_secret_key' => env('WAVEPAY_SECRET_KEY'),

    // AYA Pay Configuration
    'ayapay_base_url' => env('AYAPAY_BASE_URL', 'https://api.ayapay.com.mm'),
    'ayapay_merchant_id' => env('AYAPAY_MERCHANT_ID'),
    'ayapay_secret_key' => env('AYAPAY_SECRET_KEY'),

    // OK Pay Configuration
    'okpay_base_url' => env('OKPAY_BASE_URL', 'https://api.okpay.com.mm'),
    'okpay_merchant_id' => env('OKPAY_MERCHANT_ID'),
    'okpay_secret_key' => env('OKPAY_SECRET_KEY'),

    // General Settings
    'admission_fee' => 50000,
    'currency' => 'MMK',
    'timeout' => 30,
];