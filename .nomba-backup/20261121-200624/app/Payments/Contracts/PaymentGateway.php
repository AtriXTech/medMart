<?php

declare(strict_types=1);

namespace App\Payments\Contracts;

use App\Payments\Data\CheckoutSession;
use App\Payments\Data\PayoutDestination;
use App\Payments\Data\ResolvedAccount;
use App\Payments\Data\TransferRequest;
use App\Payments\Data\TransferResult;
use App\Payments\Data\TransferWebhook;
use App\Payments\Data\VerifiedCharge;
use App\Payments\Enums\Gateway;

interface PaymentGateway
{
    public function gateway(): Gateway;

    public function isConfigured(): bool;

    public function missingConfiguration(): array;

    public function isTestMode(): bool;

    public function customerCallbackUrl(): ?string;

    public function staffCallbackUrl(): ?string;

    public function initializeCharge(string $reference, int $amountKobo, string $email, array $metadata = [], ?string $callbackUrl = null): CheckoutSession;

    public function verifyCharge(string $reference): VerifiedCharge;

    public function refundCharge(string $reference, ?string $gatewayReference, string $note): void;

    public function resolveAccount(string $accountNumber, string $bankCode): ResolvedAccount;

    public function prepareDestination(PayoutDestination $destination): PayoutDestination;

    public function balanceKobo(): int;

    public function initiateTransfer(TransferRequest $request): TransferResult;

    public function verifyTransfer(string $reference): ?TransferResult;

    public function listBanks(): array;

    public function parseTransferWebhook(string $event, array $payload): ?TransferWebhook;
}
