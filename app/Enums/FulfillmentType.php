<?php

declare(strict_types=1);

namespace App\Enums;

enum FulfillmentType: string
{
    case Pickup = 'pickup';
    case Delivery = 'delivery';
}
