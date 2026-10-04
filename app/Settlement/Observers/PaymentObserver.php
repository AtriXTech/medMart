<?php

declare(strict_types=1);

namespace App\Settlement\Observers;

use App\Models\Payment;
use App\Settlement\Jobs\RecordPaymentSettlementJob;
use App\Settlement\Support\PaymentState;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Cache;

final class PaymentObserver implements ShouldHandleEventsAfterCommit
{
    public function saved(Payment $payment): void
    {
        if (! PaymentState::isPaid($payment) || $payment->getAttribute('settlement_status') !== null) {
            return;
        }

        if (! Cache::add('settlement:record:' . $payment->getKey(), 1, 60)) {
            return;
        }

        RecordPaymentSettlementJob::dispatch((int) $payment->getKey());
    }
}
