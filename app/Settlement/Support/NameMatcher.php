<?php

declare(strict_types=1);

namespace App\Settlement\Support;

final class NameMatcher
{
    private const STOP_WORDS = [
        'ltd', 'limited', 'plc', 'nig', 'nigeria', 'pharmacy', 'pharmacies', 'pharmaceutical',
        'pharmaceuticals', 'chemist', 'chemists', 'stores', 'store', 'enterprises', 'enterprise',
        'and', 'the', 'of', 'co', 'company', 'services', 'int', 'global', 'ventures', 'venture',
    ];

    public static function score(string $expected, string $actual): int
    {
        $a = self::tokens($expected);
        $b = self::tokens($actual);

        if ($a !== [] && $b !== []) {
            $shared = count(array_intersect($a, $b));

            return (int) round(100 * $shared / min(count($a), count($b)));
        }

        similar_text(self::normalize($expected), self::normalize($actual), $percent);

        return (int) round($percent);
    }

    private static function normalize(string $value): string
    {
        $value = strtolower($value);
        $value = (string) preg_replace('/[^a-z0-9\s]/', ' ', $value);

        return trim((string) preg_replace('/\s+/', ' ', $value));
    }

    private static function tokens(string $value): array
    {
        $parts = explode(' ', self::normalize($value));

        return array_values(array_unique(array_filter(
            $parts,
            static fn (string $part): bool => $part !== '' && ! in_array($part, self::STOP_WORDS, true)
        )));
    }
}
