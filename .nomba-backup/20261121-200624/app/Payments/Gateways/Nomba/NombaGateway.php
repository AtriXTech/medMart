<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Nomba;

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
use App\Settlement\Support\Money;
use App\Settlement\Support\NameMatcher;

final class NombaGateway implements PaymentGateway
{
    private const DEFINITIVE_REJECTIONS = [
        'BAD_REQUEST',
        'ACCOUNT_NOT_FOUND',
        'INVALID_TRANSACTION',
        'WALLET_NOT_FOUND',
        'BLACKLISTED',
    ];

    private const TRANSFER_EVENTS = ['payout_success', 'payout_failed', 'payout_refund'];

    public function __construct(
        private readonly NombaClient $client,
        private readonly NombaConfig $config,
    ) {
    }

    public function gateway(): Gateway
    {
        return Gateway::Nomba;
    }

    public function isConfigured(): bool
    {
        return $this->config->isConfigured();
    }

    public function missingConfiguration(): array
    {
        return $this->config->missing();
    }

    public function isTestMode(): bool
    {
        return $this->config->isSandbox();
    }

    public function customerCallbackUrl(): ?string
    {
        return $this->nullable(config('services.nomba.customer_callback_url'));
    }

    public function staffCallbackUrl(): ?string
    {
        return $this->nullable(config('services.nomba.callback_url'));
    }

    public function initializeCharge(string $reference, int $amountKobo, string $email, array $metadata = [], ?string $callbackUrl = null): CheckoutSession
    {
        if (strlen($reference) > 50) {
            throw GatewayException::rejected('The payment reference is too long for Nomba.');
        }

        $order = [
            'orderReference' => $reference,
            'amount' => Money::toNaira($amountKobo),
            'currency' => $this->currency(),
            'customerEmail' => $email,
        ];

        $callback = $callbackUrl ?? $this->staffCallbackUrl();

        if ($callback !== null) {
            $order['callbackUrl'] = $callback;
        }

        if ($metadata !== []) {
            $order['orderMetaData'] = $this->stringMap($metadata);
        }

        $body = $this->client->post('/v1/checkout/order', ['order' => $order, 'tokenizeCard' => false]);
        $data = is_array($body['data'] ?? null) ? $body['data'] : [];
        $link = (string) ($data['checkoutLink'] ?? '');

        if ($link === '') {
            throw GatewayException::rejected('Nomba did not return a checkout link.');
        }

        return new CheckoutSession($link, $reference, isset($data['orderReference']) ? (string) $data['orderReference'] : null);
    }

    public function verifyCharge(string $reference): VerifiedCharge
    {
        try {
            $body = $this->client->get('/v1/transactions/accounts/single', ['orderReference' => $reference]);
        } catch (GatewayException $exception) {
            if ($exception->isNotFound()) {
                return new VerifiedCharge(ChargeState::Pending, $reference, null, 0, $this->currency(), null, 'NOT_FOUND', []);
            }

            throw $exception;
        }

        $data = is_array($body['data'] ?? null) ? $body['data'] : [];
        $status = strtoupper(trim((string) ($data['status'] ?? '')));
        $type = strtolower((string) ($data['type'] ?? 'online_checkout'));
        $returnedReference = (string) ($data['onlineCheckoutOrderReference'] ?? $reference);

        $state = match (true) {
            $status === 'SUCCESS' && $type === 'online_checkout' && $returnedReference === $reference => ChargeState::Success,
            $status === '', str_starts_with($status, 'PENDING'), str_starts_with($status, 'PROCESSING'), $status === 'NEW' => ChargeState::Pending,
            default => ChargeState::Failed,
        };

        $amount = $data['onlineCheckoutAmount'] ?? $data['amount'] ?? 0;
        $currency = $data['onlineCheckoutCurrency'] ?? $data['currency'] ?? $data['walletCurrency'] ?? $this->currency();
        $fee = $data['fixedCharge'] ?? null;

        return new VerifiedCharge(
            $state,
            $returnedReference,
            isset($data['id']) ? (string) $data['id'] : null,
            Money::toKobo(is_numeric($amount) ? $amount : 0),
            strtoupper((string) $currency),
            is_numeric($fee) ? Money::toKobo($fee) : null,
            $status === '' ? null : $status,
            $data,
        );
    }

    public function refundCharge(string $reference, ?string $gatewayReference, string $note): void
    {
        $transactionId = $gatewayReference;

        if ($transactionId === null || $transactionId === '') {
            $transactionId = $this->verifyCharge($reference)->gatewayReference;
        }

        if ($transactionId === null || $transactionId === '') {
            throw GatewayException::rejected('The Nomba transaction for this payment could not be found.');
        }

        $body = $this->client->post('/v1/checkout/refund', ['transactionId' => $transactionId]);
        $data = is_array($body['data'] ?? null) ? $body['data'] : [];

        if (($data['success'] ?? true) === false) {
            throw GatewayException::rejected((string) ($data['message'] ?? 'Nomba could not process the refund.'));
        }
    }

    public function resolveAccount(string $accountNumber, string $bankCode): ResolvedAccount
    {
        $body = $this->client->post('/v1/transfers/bank/lookup', [
            'accountNumber' => $accountNumber,
            'bankCode' => $bankCode,
        ]);

        $data = is_array($body['data'] ?? null) ? $body['data'] : [];
        $name = trim((string) ($data['accountName'] ?? ''));

        if ($name === '') {
            throw GatewayException::rejected('The account could not be resolved.');
        }

        return new ResolvedAccount($name, (string) ($data['accountNumber'] ?? $accountNumber));
    }

    public function prepareDestination(PayoutDestination $destination): PayoutDestination
    {
        $resolved = $this->resolveAccount($destination->accountNumber, $destination->bankCode);

        $threshold = (int) config('settlement.payout.name_match_threshold', 50);

        if (NameMatcher::score($destination->accountName, $resolved->accountName) < $threshold) {
            throw GatewayException::rejected('The bank account name no longer matches the approved settlement account.');
        }

        return $destination->withAccountName($resolved->accountName);
    }

    public function balanceKobo(): int
    {
        $body = $this->client->get('/v1/accounts/balance');
        $data = is_array($body['data'] ?? null) ? $body['data'] : [];
        $currency = strtoupper((string) ($data['currency'] ?? $this->currency()));

        if ($currency !== $this->currency() || ! is_numeric($data['amount'] ?? null)) {
            return 0;
        }

        return Money::toKobo($data['amount']);
    }

    public function initiateTransfer(TransferRequest $request): TransferResult
    {
        $payload = [
            'amount' => (float) Money::toNaira($request->amountKobo),
            'accountNumber' => $request->destination->accountNumber,
            'accountName' => $request->destination->accountName,
            'bankCode' => $request->destination->bankCode,
            'merchantTxRef' => $request->reference,
            'senderName' => $this->config->senderName(),
            'narration' => mb_substr($request->narration, 0, 100),
        ];

        try {
            $body = $this->client->post('/v2/transfers/bank', $payload);
        } catch (GatewayException $exception) {
            if ($exception->kind->value === 'rejected' && ! in_array((string) $exception->reason, self::DEFINITIVE_REJECTIONS, true)) {
                throw GatewayException::ambiguous($exception->getMessage(), $exception->httpStatus, $exception, $exception->reason);
            }

            throw $exception;
        }

        $description = strtoupper((string) ($body['description'] ?? ''));

        if ($description === 'INSUFFICIENT_BALANCE') {
            throw GatewayException::insufficientBalance('Nomba balance is insufficient for this transfer.');
        }

        if (in_array($description, self::DEFINITIVE_REJECTIONS, true)) {
            throw GatewayException::rejected('Nomba rejected the transfer: ' . $description, null, $description);
        }

        $data = is_array($body['data'] ?? null) ? $body['data'] : [];

        return $this->transferResult($data, $request->reference);
    }

    public function verifyTransfer(string $reference): ?TransferResult
    {
        try {
            $body = $this->client->get('/v1/transactions/accounts/single', ['merchantTxRef' => $reference]);
        } catch (GatewayException $exception) {
            if ($exception->isNotFound()) {
                return null;
            }

            throw $exception;
        }

        $data = is_array($body['data'] ?? null) ? $body['data'] : [];

        if ($data === []) {
            return null;
        }

        if (isset($data['merchantTxRef']) && (string) $data['merchantTxRef'] !== $reference) {
            throw GatewayException::ambiguous('Nomba returned a different transaction for this reference.');
        }

        return $this->transferResult($data, $reference);
    }

    public function listBanks(): array
    {
        $body = $this->client->get('/v1/transfers/banks');
        $data = $body['data'] ?? [];
        $rows = is_array($data) && isset($data['results']) && is_array($data['results']) ? $data['results'] : (is_array($data) ? $data : []);
        $banks = [];

        foreach ($rows as $row) {
            if (is_array($row) && ! empty($row['code']) && ! empty($row['name'])) {
                $banks[] = ['code' => (string) $row['code'], 'name' => (string) $row['name']];
            }
        }

        return $banks;
    }

    public function parseTransferWebhook(string $event, array $payload): ?TransferWebhook
    {
        if (! in_array($event, self::TRANSFER_EVENTS, true)) {
            return null;
        }

        $reference = (string) data_get($payload, 'transaction.merchantTxRef', '');

        return $reference === '' ? null : new TransferWebhook($reference, null);
    }

    private function transferResult(array $data, string $reference): TransferResult
    {
        $status = strtoupper(trim((string) ($data['status'] ?? '')));
        $amount = $data['amount'] ?? null;

        $state = match ($status) {
            'SUCCESS' => TransferState::Success,
            'REFUND' => TransferState::Reversed,
            'CANCELLED' => TransferState::ActionRequired,
            default => TransferState::Pending,
        };

        return new TransferResult(
            $state,
            isset($data['id']) ? (string) $data['id'] : null,
            is_numeric($amount) ? Money::toKobo($amount) : null,
            isset($data['currency']) ? strtoupper((string) $data['currency']) : null,
            $status !== '' ? 'Nomba status: ' . $status : null,
            $data,
        );
    }

    private function stringMap(array $values): array
    {
        $map = [];

        foreach ($values as $key => $value) {
            $map[(string) $key] = is_scalar($value) ? (string) $value : (string) json_encode($value);
        }

        return $map;
    }

    private function currency(): string
    {
        return strtoupper((string) config('settlement.currency', 'NGN'));
    }

    private function nullable(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
