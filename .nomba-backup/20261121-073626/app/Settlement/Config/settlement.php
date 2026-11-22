<?php

declare(strict_types=1);

return [
    'paystack' => [
        'secret_key' => env('PAYSTACK_SECRET_KEY', env('PAYSTACK_SECRET')),
        'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
        'timeout' => (int) env('SETTLEMENT_HTTP_TIMEOUT', 20),
        'connect_timeout' => (int) env('SETTLEMENT_HTTP_CONNECT_TIMEOUT', 5),
        'currency' => 'NGN',
        'webhook_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('PAYSTACK_WEBHOOK_IPS', ''))))),
    ],

    'bank' => [
        'code_attribute' => env('SETTLEMENT_BANK_CODE_ATTRIBUTE', 'code'),
        'name_attribute' => env('SETTLEMENT_BANK_NAME_ATTRIBUTE', 'name'),
    ],

    'queue' => [
        'connection' => env('SETTLEMENT_QUEUE_CONNECTION'),
        'name' => env('SETTLEMENT_QUEUE', 'default'),
    ],

    'payment' => [
        'paid_statuses' => ['paid', 'success', 'successful'],
        'verify_with_gateway' => (bool) env('SETTLEMENT_VERIFY_WITH_GATEWAY', true),
        'backfill_window_days' => (int) env('SETTLEMENT_BACKFILL_WINDOW_DAYS', 7),
    ],

    'eligibility' => [
        'hold_hours' => (int) env('SETTLEMENT_HOLD_HOURS', 24),
    ],

    'commission' => [
        'enabled' => (bool) env('SETTLEMENT_COMMISSION_ENABLED', false),
        'rate' => (float) env('SETTLEMENT_COMMISSION_RATE', 0),
        'flat_kobo' => (int) env('SETTLEMENT_COMMISSION_FLAT_KOBO', 0),
        'cap_kobo' => (int) env('SETTLEMENT_COMMISSION_CAP_KOBO', 0),
    ],

    'fees' => [
        'gateway_fee_bearer' => env('SETTLEMENT_GATEWAY_FEE_BEARER', 'pharmacy'),
        'percent' => 1.5,
        'flat_kobo' => 10000,
        'flat_threshold_kobo' => 250000,
        'cap_kobo' => 200000,
    ],

    'payout' => [
        'minimum_kobo' => (int) env('SETTLEMENT_MIN_PAYOUT_KOBO', 100000),
        'max_single_kobo' => (int) env('SETTLEMENT_MAX_SINGLE_PAYOUT_KOBO', 0),
        'weekly_day' => (int) env('SETTLEMENT_WEEKLY_DAY', 1),
        'account_change_cooldown_hours' => (int) env('SETTLEMENT_ACCOUNT_COOLDOWN_HOURS', 48),
        'max_consecutive_failures' => (int) env('SETTLEMENT_MAX_CONSECUTIVE_FAILURES', 3),
        'narration' => env('SETTLEMENT_NARRATION', 'Pharmacy settlement'),
        'name_match_threshold' => (int) env('SETTLEMENT_NAME_MATCH_THRESHOLD', 50),
    ],

    'transfer' => [
        'check_balance' => (bool) env('SETTLEMENT_CHECK_BALANCE', true),
        'max_attempts' => (int) env('SETTLEMENT_MAX_TRANSFER_ATTEMPTS', 5),
    ],

    'reconciliation' => [
        'stuck_after_minutes' => (int) env('SETTLEMENT_STUCK_AFTER_MINUTES', 30),
        'pending_stale_after_minutes' => (int) env('SETTLEMENT_PENDING_STALE_MINUTES', 60),
        'processing_alert_after_hours' => (int) env('SETTLEMENT_PROCESSING_ALERT_HOURS', 6),
    ],

    'notifications' => [
        'pharmacy' => (bool) env('SETTLEMENT_NOTIFY_PHARMACY', true),
        'ops_email' => env('SETTLEMENT_OPS_EMAIL'),
    ],

    'permissions' => [
        'manage' => env('SETTLEMENT_PERMISSION', 'manage_settlement'),
    ],

    'ui' => [
        'admin_console' => (bool) env('SETTLEMENT_ADMIN_CONSOLE', env('APP_ENV') !== 'production'),
        'admin_console_path' => env('SETTLEMENT_ADMIN_CONSOLE_PATH', 'super-admin/settlement'),
    ],

    'schedule' => [
        'enabled' => (bool) env('SETTLEMENT_SCHEDULE_ENABLED', true),
        'timezone' => env('SETTLEMENT_TIMEZONE', 'Africa/Lagos'),
        'batch_at' => env('SETTLEMENT_BATCH_AT', '02:00'),
        'ledger_audit_at' => env('SETTLEMENT_LEDGER_AUDIT_AT', '03:30'),
    ],
];
