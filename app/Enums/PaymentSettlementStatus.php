<?php

declare(strict_types=1);

namespace App\Settlement\Enums;

enum PaymentSettlementStatus: string
{
    case Pending = 'pending';
    case Eligible = 'eligible';
    case Settled = 'settled';
    case Held = 'held';
    case Refunded = 'refunded';
}
