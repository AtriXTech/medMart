<?php

declare(strict_types=1);

namespace App\Settlement\Exceptions;

use App\Payments\Exceptions\GatewayException;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class PaystackException extends RuntimeException
{
    private function __construct(string $message, private readonly GatewayException $source)
    {
        parent::__construct($message, 0, $source);
    }

    public static function from(GatewayException $exception): self
    {
        return new self($exception->getMessage(), $exception);
    }

    public function isAmbiguous(): bool
    {
        return $this->source->isAmbiguous();
    }

    public function isNotFound(): bool
    {
        return $this->source->isNotFound();
    }

    public function isInsufficientBalance(): bool
    {
        return $this->source->isInsufficientBalance();
    }

    public function isDuplicateReference(): bool
    {
        return $this->source->isDuplicateReference();
    }

    public function isAlreadyRefunded(): bool
    {
        return $this->source->isAlreadyRefunded();
    }

    public function render(): JsonResponse
    {
        return $this->source->render();
    }
}
