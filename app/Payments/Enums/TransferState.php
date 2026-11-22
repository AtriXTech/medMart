<?php

declare(strict_types=1);

namespace App\Payments\Enums;

enum TransferState: string
{
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';
    case Reversed = 'reversed';
    case OtpRequired = 'otp';
    case ActionRequired = 'action_required';

    public function isFinal(): bool
    {
        return $this !== self::Pending;
    }
}
