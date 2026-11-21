<?php

use App\Env;

return [
    'app_name' => Env::get('APP_NAME', 'Shop Hades'),
    'app_env' => Env::get('APP_ENV', 'local'),
    'app_debug' => (bool) Env::get('APP_DEBUG', false),
    'base_url' => Env::get('APP_BASE_URL', 'http://127.0.0.1:8000'),
    'hotline' => Env::get('APP_HOTLINE', '1800 1234'),
    'gemini_api_key' => Env::get('GEMINI_API_KEY', ''),
    'gemini_model' => Env::get('GEMINI_MODEL', 'gemini-3.5-flash'),
    'ga4_measurement_id' => Env::get('GA4_MEASUREMENT_ID', ''),
    'meta_pixel_id' => Env::get('META_PIXEL_ID', ''),

    'ollama' => [
        'enabled' => (bool) Env::get('OLLAMA_ENABLED', false),
        'url' => Env::get('OLLAMA_URL', 'http://127.0.0.1:11434'),
        'model' => Env::get('OLLAMA_MODEL', 'qwen2.5:7b'),
        'timeout' => (int) Env::get('OLLAMA_TIMEOUT', 30),
        'max_tokens' => (int) Env::get('OLLAMA_MAX_TOKENS', 500),
        'temperature' => (float) Env::get('OLLAMA_TEMPERATURE', 0.6),
    ],

    'database' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', 3306),
        'name' => Env::get('DB_DATABASE', 'shop_hades'),
        'user' => Env::get('DB_USERNAME', 'root'),
        'password' => Env::get('DB_PASSWORD', ''),
        'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
        'socket' => Env::get('DB_SOCKET', ''),
        'connect_timeout' => (int) Env::get('DB_CONNECT_TIMEOUT', 3),
        'connect_retries' => (int) Env::get('DB_CONNECT_RETRIES', 2),
        'retry_delay_ms' => (int) Env::get('DB_RETRY_DELAY_MS', 150),
    ],

    'mail' => [
        'host' => Env::get('MAIL_HOST', 'smtp.gmail.com'),
        'port' => (int) Env::get('MAIL_PORT', 587),
        'username' => Env::get('MAIL_USERNAME', ''),
        'password' => Env::get('MAIL_PASSWORD', ''),
        'from_email' => Env::get('MAIL_FROM_ADDRESS', ''),
        'from_name' => Env::get('MAIL_FROM_NAME', 'Shop Hades'),
        'ssl_verify' => (bool) Env::get('MAIL_SSL_VERIFY', false),
    ],

    'otp' => [
        'length' => (int) Env::get('OTP_LENGTH', 6),
        'expire_minutes' => (int) Env::get('OTP_EXPIRE_MINUTES', 5),
        'resend_seconds' => (int) Env::get('OTP_RESEND_SECONDS', 60),
    ],

    'password_reset' => [
        'otp_expire_minutes' => (int) Env::get('PASSWORD_RESET_OTP_EXPIRE_MINUTES', 5),
        'token_expire_minutes' => (int) Env::get('PASSWORD_RESET_TOKEN_EXPIRE_MINUTES', 15),
        'max_send_per_hour' => (int) Env::get('PASSWORD_RESET_MAX_SEND_PER_HOUR', 5),
        'max_verify_attempts' => (int) Env::get('PASSWORD_RESET_MAX_VERIFY_ATTEMPTS', 5),
        'resend_seconds' => (int) Env::get('PASSWORD_RESET_RESEND_SECONDS', 60),
    ],

    'vnpay' => [
        'tmn_code' => Env::get('VNPAY_TMN_CODE', ''),
        'hash_secret' => Env::get('VNPAY_HASH_SECRET', ''),
        'url' => Env::get('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => Env::get('VNPAY_RETURN_URL', ''),
        'default_bank_code' => Env::get('VNPAY_DEFAULT_BANK', 'NCB'),
        'expire_minutes' => (int) Env::get('VNPAY_EXPIRE_MINUTES', 30),
        'banks' => [
            ['code' => 'VCB', 'name' => 'Vietcombank'],
            ['code' => 'MB', 'name' => 'MB Bank'],
            ['code' => 'NCB', 'name' => 'NCB'],
            ['code' => 'TCB', 'name' => 'Techcombank'],
            ['code' => 'BIDV', 'name' => 'BIDV'],
            ['code' => 'VBA', 'name' => 'Agribank'],
            ['code' => 'VPB', 'name' => 'VPBank'],
            ['code' => 'TPB', 'name' => 'TPBank'],
            ['code' => 'STB', 'name' => 'Sacombank'],
            ['code' => 'ACB', 'name' => 'ACB'],
        ],
        'test_card' => [
            'bank' => 'NCB',
            'card_number' => '9704198526191432',
            'cardholder' => 'NGUYEN VAN A',
            'issue_date' => '07/15',
            'otp' => '123456',
        ],
    ],

    'momo' => [
        'partner_code' => Env::get('MOMO_PARTNER_CODE', ''),
        'partner_name' => Env::get('MOMO_PARTNER_NAME', 'Shop Hades'),
        'store_id' => Env::get('MOMO_STORE_ID', 'ShopHades'),
        'access_key' => Env::get('MOMO_ACCESS_KEY', ''),
        'secret_key' => Env::get('MOMO_SECRET_KEY', ''),
        'endpoint' => Env::get('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'redirect_url' => Env::get('MOMO_REDIRECT_URL', ''),
        'ipn_url' => Env::get('MOMO_IPN_URL', ''),
        'max_amount' => (int) Env::get('MOMO_MAX_AMOUNT', 20000000),
    ],

    'bank_transfer' => [
        'bank_code' => Env::get('BANK_TRANSFER_BANK_CODE', 'MB'),
        'bank_name' => Env::get('BANK_TRANSFER_BANK_NAME', 'MB Bank'),
        'account_no' => Env::get('BANK_TRANSFER_ACCOUNT_NO', '0788415516'),
        'account_name' => Env::get('BANK_TRANSFER_ACCOUNT_NAME', 'TO QUANG HUY'),
        'account_name_display' => Env::get('BANK_TRANSFER_ACCOUNT_NAME_DISPLAY', 'Tô Quang Huy'),
        'template' => Env::get('BANK_TRANSFER_TEMPLATE', 'compact2'),
        // SePay webhook: Authorization Apikey {SEPAY_WEBHOOK_API_KEY}
        'sepay_api_key' => Env::get('SEPAY_WEBHOOK_API_KEY', ''),
        'auto_confirm' => filter_var(Env::get('BANK_TRANSFER_AUTO_CONFIRM', 'true'), FILTER_VALIDATE_BOOLEAN),
    ],

    'jwt' => [
        'secret' => Env::get('JWT_SECRET', ''),
        'expire_seconds' => (int) Env::get('JWT_EXPIRE_SECONDS', 86400),
    ],
    'google_client_id' => Env::get('GOOGLE_CLIENT_ID', ''),
    'google_client_secret' => Env::get('GOOGLE_CLIENT_SECRET', ''),
    'google_redirect_uri' => Env::get('GOOGLE_REDIRECT_URI', ''),
    'goong' => [
        'api_key' => Env::get('GOONG_API_KEY', ''),
        'geocode_url' => 'https://rsapi.goong.io/geocode',
    ],
];
