<?php

declare(strict_types=1);

namespace App\Settlement\Enums;

enum LedgerEntryType: string
{
    case SaleCredit = 'sale_credit';
    case GatewayFee = 'gateway_fee';
    case PlatformFee = 'platform_fee';
    case RefundDebit = 'refund_debit';
    case FeeReversal = 'fee_reversal';
    case PayoutDebit = 'payout_debit';
    case PayoutReversal = 'payout_reversal';
    case Adjustment = 'adjustment';

    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
