<?php

declare(strict_types=1);

namespace App\Payments\Enums;

enum Gateway: string
{
    case Paystack = 'paystack';
    case Nomba = 'nomba';

    public function label(): string
    {
        return match ($this) {
            self::Paystack => 'Paystack',
            self::Nomba => 'Nomba',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        return $value === null ? null : self::tryFrom(strtolower(trim($value)));
    }

    public static function values(): array
    {
        return array_map(static fn (self $gateway): string => $gateway->value, self::cases());
    }
}
