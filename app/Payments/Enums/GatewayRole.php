<?php

declare(strict_types=1);

namespace App\Payments\Enums;

enum GatewayRole: string
{
    case Collections = 'collections';
    case Payouts = 'payouts';

    public function label(): string
    {
        return match ($this) {
            self::Collections => 'Customer payments and subscriptions',
            self::Payouts => 'Pharmacy payouts and settlements',
        };
    }
}
