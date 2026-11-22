<?php

declare(strict_types=1);

namespace App\Payments\Enums;

enum ChargeState: string
{
    case Success = 'success';
    case Pending = 'pending';
    case Failed = 'failed';
}
