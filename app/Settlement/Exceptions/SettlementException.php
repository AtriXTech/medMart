<?php

declare(strict_types=1);

namespace App\Settlement\Exceptions;

use Illuminate\Http\JsonResponse;
use RuntimeException;

class SettlementException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $httpStatus = 422,
        private readonly ?string $errorCode = null,
    ) {
        parent::__construct($message);
    }

    public static function invalidState(string $message): self
    {
        return new self($message, 422, 'invalid_state');
    }

    public static function notFound(string $message): self
    {
        return new self($message, 404, 'not_found');
    }

    public static function accountNotResolved(): self
    {
        return new self('We could not verify this bank account. Check the account number and bank, then try again.', 422, 'account_not_resolved');
    }

    public static function forbidden(string $message): self
    {
        return new self($message, 403, 'forbidden');
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'code' => $this->errorCode,
        ], $this->httpStatus);
    }
}
