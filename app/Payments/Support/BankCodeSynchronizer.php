<?php

declare(strict_types=1);

namespace App\Payments\Support;

use App\Models\Bank;
use App\Models\BankGatewayCode;
use App\Payments\Contracts\PaymentGateway;

final class BankCodeSynchronizer
{
    private const NOISE = ['bank', 'plc', 'ltd', 'limited', 'of', 'nigeria', 'nig', 'the', 'mfb', 'microfinance'];

    public function sync(PaymentGateway $gateway): array
    {
        $remote = $gateway->listBanks();

        if ($remote === []) {
            return ['matched' => 0, 'unmatched' => [], 'total' => 0];
        }

        $byName = [];
        $byCode = [];

        foreach ($remote as $row) {
            $byName[$this->normalize($row['name'])] ??= $row;
            $byCode[$row['code']] ??= $row;
        }

        $matched = 0;
        $unmatched = [];
        $codeAttribute = (string) config('settlement.bank.code_attribute');

        foreach (Bank::query()->get() as $bank) {
            $name = (string) $bank->getAttribute((string) config('settlement.bank.name_attribute'));
            $code = (string) $bank->getAttribute($codeAttribute);
            $row = $byName[$this->normalize($name)] ?? null;

            if ($row === null && isset($byCode[$code]) && $this->similarity($name, $byCode[$code]['name']) >= 60.0) {
                $row = $byCode[$code];
            }

            if ($row === null) {
                $unmatched[] = $name;

                continue;
            }

            BankGatewayCode::query()->updateOrCreate(
                ['bank_id' => $bank->getKey(), 'gateway' => $gateway->gateway()->value],
                ['code' => $row['code']]
            );

            $matched++;
        }

        return ['matched' => $matched, 'unmatched' => $unmatched, 'total' => $matched + count($unmatched)];
    }

    private function similarity(string $a, string $b): float
    {
        similar_text($this->normalize($a), $this->normalize($b), $percent);

        return $percent;
    }

    private function normalize(string $value): string
    {
        $value = strtolower((string) preg_replace('/[^a-z0-9\s]/i', ' ', $value));
        $words = array_filter(explode(' ', $value), static fn (string $word): bool => $word !== '' && ! in_array($word, self::NOISE, true));

        return implode('', $words);
    }
}
