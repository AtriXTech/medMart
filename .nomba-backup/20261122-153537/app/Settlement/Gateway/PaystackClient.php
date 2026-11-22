<?php

declare(strict_types=1);

namespace App\Settlement\Gateway;

use App\Payments\Exceptions\GatewayException;
use App\Payments\GatewayManager;
use App\Payments\Gateways\Paystack\PaystackClient as PaystackApi;
use App\Settlement\Exceptions\PaystackException;

final class PaystackClient
{
    public function __construct(
        private readonly GatewayManager $gateways,
        private readonly PaystackApi $api,
    ) {
    }

    public function isTestKey(): bool
    {
        try {
            return $this->gateways->payouts()->isTestMode();
        } catch (GatewayException) {
            return $this->api->isTestKey();
        }
    }

    public function balance(): int
    {
        return $this->call(fn (): int => $this->gateways->payouts()->balanceKobo());
    }

    public function resolveAccount(string $accountNumber, string $bankCode): array
    {
        return $this->call(fn (): array => $this->api->resolveAccount($accountNumber, $bankCode));
    }

    public function createRecipient(string $name, string $accountNumber, string $bankCode): array
    {
        return $this->call(fn (): array => $this->api->createRecipient($name, $accountNumber, $bankCode));
    }

    public function initiateTransfer(string $reference, string $recipientCode, int $amountKobo, string $reason): array
    {
        return $this->call(fn (): array => $this->api->initiateTransfer($reference, $recipientCode, $amountKobo, $reason));
    }

    public function verifyTransfer(string $reference): ?array
    {
        return $this->call(fn (): ?array => $this->api->verifyTransfer($reference));
    }

    public function verifyTransaction(string $reference): array
    {
        return $this->call(fn (): array => $this->api->verifyTransaction($reference));
    }

    public function refund(string $transactionReference, string $note): array
    {
        return $this->call(fn (): array => $this->api->refund($transactionReference, $note));
    }

    private function call(callable $operation): mixed
    {
        try {
            return $operation();
        } catch (GatewayException $exception) {
            throw PaystackException::from($exception);
        }
    }
}
