<?php

declare(strict_types=1);

namespace App\Settlement\Support;

use App\Models\Payment;
use BackedEnum;

final class PaymentState
{
    public static function isPaid(Payment $payment): bool
    {
        $status = $payment->status;
        $value = $status instanceof BackedEnum ? (string) $status->value : (string) $status;
        $paid = array_map('strtolower', (array) config('settlement.payment.paid_statuses', ['paid']));

        return in_array(strtolower($value), $paid, true);
    }
}
