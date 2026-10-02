<?php

declare(strict_types=1);

namespace App\Settlement\Enums;

enum SettlementStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Success = 'success';
    case Failed = 'failed';
    case Reversed = 'reversed';
    case OnHold = 'on_hold';
    case Cancelled = 'cancelled';

    public static function countedValues(): array
    {
        return [
            self::Pending->value,
            self::Processing->value,
            self::Success->value,
            self::OnHold->value,
        ];
    }

    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
