<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Payment;
use App\Settlement\Enums\PaymentSettlementStatus;

final class EligibilityService
{
    public function promote(): int
    {
        return Payment::query()
            ->withoutGlobalScopes()
            ->where('settlement_status', PaymentSettlementStatus::Pending->value)
            ->whereNotNull('eligible_at')
            ->where('eligible_at', '<=', now())
            ->update(['settlement_status' => PaymentSettlementStatus::Eligible->value]);
    }
}
