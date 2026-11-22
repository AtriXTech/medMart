<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'callback_url' => env('PAYSTACK_CALLBACK_URL'),
        'customer_callback_url' => env('PAYSTACK_CUSTOMER_CALLBACK_URL', env('PAYSTACK_CALLBACK_URL')),
        'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
    ],

    'nomba' => [
        'environment' => env('NOMBA_ENVIRONMENT', env('APP_ENV') === 'production' ? 'production' : 'sandbox'),
        'account_id' => env('NOMBA_ACCOUNT_ID'),
        'client_id' => env('NOMBA_CLIENT_ID'),
        'client_secret' => env('NOMBA_CLIENT_SECRET'),
        'webhook_signature_key' => env('NOMBA_WEBHOOK_SIGNATURE_KEY'),
        'base_url' => env('NOMBA_BASE_URL'),
        'callback_url' => env('NOMBA_CALLBACK_URL', env('PAYSTACK_CALLBACK_URL', rtrim((string) env('APP_URL'), '/') . '/payment-callback')),
        'customer_callback_url' => env('NOMBA_CUSTOMER_CALLBACK_URL', env('PAYSTACK_CUSTOMER_CALLBACK_URL', rtrim((string) env('APP_URL'), '/') . '/customer/payment-callback')),
        'sender_name' => env('NOMBA_SENDER_NAME', env('APP_NAME', 'MedMart')),
        'timeout' => (int) env('NOMBA_HTTP_TIMEOUT', 20),
        'connect_timeout' => (int) env('NOMBA_HTTP_CONNECT_TIMEOUT', 5),
        'webhook_tolerance' => (int) env('NOMBA_WEBHOOK_TOLERANCE', 0),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
