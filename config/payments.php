<?php

declare(strict_types=1);

return [
    'default' => env('PAYMENT_GATEWAY', 'paystack'),
    'collections' => env('PAYMENT_COLLECTIONS_GATEWAY'),
    'payouts' => env('PAYMENT_PAYOUTS_GATEWAY'),
    'allow_unmapped_bank_codes' => (bool) env('PAYMENT_ALLOW_UNMAPPED_BANK_CODES', true),
    'cache_ttl' => (int) env('PAYMENT_GATEWAY_CACHE_TTL', 300),
];
