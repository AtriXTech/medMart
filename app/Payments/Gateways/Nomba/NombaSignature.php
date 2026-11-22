<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Nomba;

final class NombaSignature
{
    public static function compute(array $payload, string $signatureKey, string $timestamp): string
    {
        $fields = [
            (string) ($payload['event_type'] ?? ''),
            (string) ($payload['requestId'] ?? ''),
            self::value($payload, 'data.merchant.userId'),
            self::value($payload, 'data.merchant.walletId'),
            self::value($payload, 'data.transaction.transactionId'),
            self::value($payload, 'data.transaction.type'),
            self::value($payload, 'data.transaction.time'),
            self::responseCode($payload),
            $timestamp,
        ];

        return base64_encode(hash_hmac('sha256', implode(':', $fields), $signatureKey, true));
    }

    public static function matches(array $payload, string $signatureKey, string $timestamp, string $signature): bool
    {
        if ($signatureKey === '' || $signature === '' || $timestamp === '') {
            return false;
        }

        $expected = self::compute($payload, $signatureKey, $timestamp);

        return hash_equals(strtolower($expected), strtolower(trim($signature)));
    }

    public static function isFresh(string $timestamp, int $toleranceSeconds): bool
    {
        if ($toleranceSeconds <= 0) {
            return true;
        }

        $sent = strtotime($timestamp);

        return $sent !== false && abs(time() - $sent) <= $toleranceSeconds;
    }

    private static function responseCode(array $payload): string
    {
        $code = self::value($payload, 'data.transaction.responseCode');

        return $code === 'null' ? '' : $code;
    }

    private static function value(array $payload, string $path): string
    {
        $value = data_get($payload, $path);

        return is_scalar($value) ? (string) $value : '';
    }
}
