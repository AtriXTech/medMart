<?php

declare(strict_types=1);

namespace App\Settlement\Exceptions;

use RuntimeException;

final class InsufficientBalanceException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Paystack balance is insufficient for this transfer.');
    }
}
