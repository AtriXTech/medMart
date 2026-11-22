<?php

declare(strict_types=1);

namespace App\Payments\Enums;

enum GatewayErrorKind: string
{
    case Ambiguous = 'ambiguous';
    case Misconfigured = 'misconfigured';
    case Rejected = 'rejected';
    case NotFound = 'not_found';
    case InsufficientBalance = 'insufficient_balance';
    case DuplicateReference = 'duplicate_reference';
    case AlreadyRefunded = 'already_refunded';
}
