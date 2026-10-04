<?php

declare(strict_types=1);

namespace App\Settlement\Support;

final class Money
{
    public static function toKobo(string|int|float|null $naira): int
    {
        return (int) round(((float) $naira) * 100);
    }

    public static function toNaira(int $kobo): string
    {
        $sign = $kobo < 0 ? '-' : '';
        $absolute = abs($kobo);

        return sprintf('%s%d.%02d', $sign, intdiv($absolute, 100), $absolute % 100);
    }

    public static function percentOf(int $kobo, float|int|string $percent): int
    {
        return (int) round($kobo * ((float) $percent) / 100);
    }

    public static function format(int $kobo): string
    {
        return '₦' . number_format($kobo / 100, 2);
    }
}
