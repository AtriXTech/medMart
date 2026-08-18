<?php

declare(strict_types=1);

namespace App\Enums;

enum PharmacyStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
}
