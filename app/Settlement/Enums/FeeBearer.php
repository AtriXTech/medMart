<?php

declare(strict_types=1);

namespace App\Settlement\Enums;

enum FeeBearer: string
{
    case Pharmacy = 'pharmacy';
    case Platform = 'platform';
}
