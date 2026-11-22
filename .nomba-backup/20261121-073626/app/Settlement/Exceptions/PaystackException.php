<?php

declare(strict_types=1);

namespace App\Settlement\Exceptions;

use Illuminate\Http\JsonResponse;
use RuntimeException;
use Throwable;

final class PaystackException extends RuntimeException
{
    private function __construct(
        string $message,
        private readonly ?int $httpStatus,
        private readonly bool $ambiguous,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function ambiguous(string $message, ?int $httpStatus = null, ?Throwable $previous = null): self
    {
        return new self($message, $httpStatus, true, $previous);
    }

    public static function rejected(string $message, int $httpStatus): self
    {
        return new self($message, $httpStatus, false);
    }

    public static function misconfigured(): self
    {
        return new self('Paystack secret key is not configured.', null, true);
    }

    public function isAmbiguous(): bool
    {
        return $this->ambiguous;
    }

    public function isNotFound(): bool
    {
        return $this->httpStatus === 404;
    }

    public function isInsufficientBalance(): bool
    {
        return (bool) preg_match('/balance/i', $this->getMessage())
            && (bool) preg_match('/(not enough|insufficient|low)/i', $this->getMessage());
    }

    public function isDuplicateReference(): bool
    {
        return (bool) preg_match('/reference/i', $this->getMessage())
            && (bool) preg_match('/(exist|duplicate|already)/i', $this->getMessage());
    }

    public function isAlreadyRefunded(): bool
    {
        return (bool) preg_match('/(already|fully)/i', $this->getMessage());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The payment gateway could not complete this request. Please try again shortly.',
            'code' => 'gateway_error',
        ], 502);
    }
}
