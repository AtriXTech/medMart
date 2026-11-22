<?php

declare(strict_types=1);

namespace App\Payments\Support;

use App\Models\Bank;
use App\Models\BankGatewayCode;
use App\Payments\Enums\Gateway;
use App\Settlement\Exceptions\SettlementException;

final class BankCodeResolver
{
    public function codeFor(Bank $bank, Gateway $gateway): string
    {
        $mapped = BankGatewayCode::query()
            ->where('bank_id', $bank->getKey())
            ->where('gateway', $gateway->value)
            ->value('code');

        if (is_string($mapped) && $mapped !== '') {
            return $mapped;
        }

        if ($gateway === Gateway::Paystack || (bool) config('payments.allow_unmapped_bank_codes', true)) {
            $code = (string) $bank->getAttribute((string) config('settlement.bank.code_attribute'));

            if ($code !== '') {
                return $code;
            }
        }

        throw SettlementException::invalidState('The selected bank has no ' . $gateway->label() . ' code configured. Sync bank codes from the Payment Gateways page.');
    }
}
