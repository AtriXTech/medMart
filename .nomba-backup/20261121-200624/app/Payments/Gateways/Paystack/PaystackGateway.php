<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Paystack;

use App\Payments\Contracts\PaymentGateway;
use App\Payments\Data\CheckoutSession;
use App\Payments\Data\PayoutDestination;
use App\Payments\Data\ResolvedAccount;
use App\Payments\Data\TransferRequest;
use App\Payments\Data\TransferResult;
use App\Payments\Data\TransferWebhook;
use App\Payments\Data\VerifiedCharge;
use App\Payments\Enums\ChargeState;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\TransferState;
use App\Payments\Exceptions\GatewayException;

final class PaystackGateway implements PaymentGateway
{
    public function __construct(private readonly PaystackClient $client)
    {
    }

    public function gateway(): Gateway
    {
        return Gateway::Paystack;
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }

    public function missingConfiguration(): array
    {
        return $this->client->isConfigured() ? [] : ['PAYSTACK_SECRET_KEY'];
    }

    public function isTestMode(): bool
    {
        return $this->client->isTestKey();
    }

    public function customerCallbackUrl(): ?string
    {
        return $this->nullable(config('services.paystack.customer_callback_url'));
    }

    public function staffCallbackUrl(): ?string
    {
        return $this->nullable(config('services.paystack.callback_url'));
    }

    public function initializeCharge(string $reference, int $amountKobo, string $email, array $metadata = [], ?string $callbackUrl = null): CheckoutSession
    {
        $payload = [
            'reference' => $reference,
            'amount' => $amountKobo,
            'email' => $email,
            'callback_url' => $callbackUrl ?? $this->staffCallbackUrl(),
        ];

        if ($metadata !== []) {
            $payload['metadata'] = $metadata;
        }

        $data = $this->client->initialize($payload);
        $url = (string) ($data['authorization_url'] ?? '');

        if ($url === '') {
            throw GatewayException::rejected('Paystack did not return a payment link.');
        }

        return new CheckoutSession($url, $reference, isset($data['access_code']) ? (string) $data['access_code'] : null);
    }

    public function verifyCharge(string $reference): VerifiedCharge
    {
        $data = $this->client->verifyTransaction($reference);
        $status = strtolower((string) ($data['status'] ?? ''));
        $fees = $data['fees'] ?? null;

        $state = match ($status) {
            'success' => ChargeState::Success,
            'pending', 'ongoing', 'processing', 'queued' => ChargeState::Pending,
            default => ChargeState::Failed,
        };

        return new VerifiedCharge(
            $state,
            (string) ($data['reference'] ?? $reference),
            (string) ($data['reference'] ?? $reference),
            (int) ($data['amount'] ?? 0),
            strtoupper((string) ($data['currency'] ?? (string) config('settlement.currency', 'NGN'))),
            is_numeric($fees) ? (int) $fees : null,
            $status === '' ? null : $status,
            $data,
        );
    }

    public function refundCharge(string $reference, ?string $gatewayReference, string $note): void
    {
        $this->client->refund($gatewayReference ?: $reference, $note);
    }

    public function resolveAccount(string $accountNumber, string $bankCode): ResolvedAccount
    {
        $data = $this->client->resolveAccount($accountNumber, $bankCode);
        $name = trim((string) ($data['account_name'] ?? ''));

        if ($name === '') {
            throw GatewayException::rejected('The account could not be resolved.');
        }

        return new ResolvedAccount($name, (string) ($data['account_number'] ?? $accountNumber));
    }

    public function prepareDestination(PayoutDestination $destination): PayoutDestination
    {
        if ($destination->recipientCode !== null && $destination->recipientCode !== '') {
            return $destination;
        }

        $recipient = $this->client->createRecipient($destination->accountName, $destination->accountNumber, $destination->bankCode);
        $code = (string) ($recipient['recipient_code'] ?? '');

        if ($code === '') {
            throw GatewayException::rejected('Paystack did not return a recipient code.');
        }

        return $destination->withRecipient($code);
    }

    public function balanceKobo(): int
    {
        return $this->client->balance();
    }

    public function initiateTransfer(TransferRequest $request): TransferResult
    {
        $code = $request->destination->recipientCode;

        if ($code === null || $code === '') {
            throw GatewayException::misconfigured('Paystack recipient');
        }

        $data = $this->client->initiateTransfer($request->reference, $code, $request->amountKobo, $request->narration);

        return $this->transferResult($data);
    }

    public function verifyTransfer(string $reference): ?TransferResult
    {
        $data = $this->client->verifyTransfer($reference);

        return $data === null ? null : $this->transferResult($data);
    }

    public function listBanks(): array
    {
        $rows = [];

        foreach ($this->client->listBanks() as $bank) {
            if (is_array($bank) && isset($bank['code'], $bank['name'])) {
                $rows[] = ['code' => (string) $bank['code'], 'name' => (string) $bank['name']];
            }
        }

        return $rows;
    }

    public function parseTransferWebhook(string $event, array $payload): ?TransferWebhook
    {
        $state = match ($event) {
            'transfer.success' => TransferState::Success,
            'transfer.failed' => TransferState::Failed,
            'transfer.reversed' => TransferState::Reversed,
            default => null,
        };

        $reference = (string) ($payload['reference'] ?? '');

        if ($state === null || $reference === '') {
            return null;
        }

        return new TransferWebhook($reference, $this->transferResult($payload, $state));
    }

    private function transferResult(array $data, ?TransferState $forced = null): TransferResult
    {
        $state = $forced ?? match (strtolower((string) ($data['status'] ?? ''))) {
            'success' => TransferState::Success,
            'failed' => TransferState::Failed,
            'reversed' => TransferState::Reversed,
            'otp' => TransferState::OtpRequired,
            default => TransferState::Pending,
        };

        $message = (string) ($data['complete_message'] ?? $data['gateway_response'] ?? '');

        return new TransferResult(
            $state,
            isset($data['transfer_code']) ? (string) $data['transfer_code'] : null,
            isset($data['amount']) ? (int) $data['amount'] : null,
            isset($data['currency']) ? strtoupper((string) $data['currency']) : null,
            $message !== '' ? $message : null,
            $data,
        );
    }

    private function nullable(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
