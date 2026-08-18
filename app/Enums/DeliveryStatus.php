<?php

declare(strict_types=1);

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
}
