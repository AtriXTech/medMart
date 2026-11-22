<?php

declare(strict_types=1);

namespace App\Payments\Exceptions;

use App\Payments\Enums\GatewayErrorKind;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Throwable;

final class GatewayException extends RuntimeException
{
    private function __construct(
        string $message,
        public readonly GatewayErrorKind $kind,
        public readonly ?int $httpStatus = null,
        public readonly ?string $reason = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function ambiguous(string $message, ?int $httpStatus = null, ?Throwable $previous = null, ?string $reason = null): self
    {
        return new self($message, GatewayErrorKind::Ambiguous, $httpStatus, $reason, $previous);
    }

    public static function rejected(string $message, ?int $httpStatus = null, ?string $reason = null): self
    {
        return new self($message, GatewayErrorKind::Rejected, $httpStatus, $reason);
    }

    public static function notFound(string $message = 'Record not found at the payment gateway.', ?int $httpStatus = 404): self
    {
        return new self($message, GatewayErrorKind::NotFound, $httpStatus);
    }

    public static function misconfigured(string $gatewayLabel): self
    {
        return new self($gatewayLabel . ' is not fully configured.', GatewayErrorKind::Misconfigured);
    }

    public static function insufficientBalance(string $message, ?int $httpStatus = null): self
    {
        return new self($message, GatewayErrorKind::InsufficientBalance, $httpStatus);
    }

    public static function duplicateReference(string $message, ?int $httpStatus = null): self
    {
        return new self($message, GatewayErrorKind::DuplicateReference, $httpStatus);
    }

    public static function alreadyRefunded(string $message, ?int $httpStatus = null): self
    {
        return new self($message, GatewayErrorKind::AlreadyRefunded, $httpStatus);
    }

    public function isAmbiguous(): bool
    {
        return in_array($this->kind, [GatewayErrorKind::Ambiguous, GatewayErrorKind::Misconfigured], true);
    }

    public function isNotFound(): bool
    {
        return $this->kind === GatewayErrorKind::NotFound;
    }

    public function isInsufficientBalance(): bool
    {
        return $this->kind === GatewayErrorKind::InsufficientBalance;
    }

    public function isDuplicateReference(): bool
    {
        return $this->kind === GatewayErrorKind::DuplicateReference;
    }

    public function isAlreadyRefunded(): bool
    {
        return $this->kind === GatewayErrorKind::AlreadyRefunded;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The payment gateway could not complete this request. Please try again shortly.',
            'code' => 'gateway_error',
        ], 502);
    }
}
