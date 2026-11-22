<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Paystack;

use App\Payments\Exceptions\GatewayException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class PaystackClient
{
    public function secretKey(): string
    {
        return (string) (config('services.paystack.secret_key') ?: config('settlement.paystack.secret_key'));
    }

    public function isConfigured(): bool
    {
        return $this->secretKey() !== '';
    }

    public function isTestKey(): bool
    {
        return str_starts_with($this->secretKey(), 'sk_test_');
    }

    public function initialize(array $payload): array
    {
        return $this->send('post', '/transaction/initialize', $payload);
    }

    public function resolveAccount(string $accountNumber, string $bankCode): array
    {
        return $this->send('get', '/bank/resolve', [
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
        ]);
    }

    public function createRecipient(string $name, string $accountNumber, string $bankCode): array
    {
        return $this->send('post', '/transferrecipient', [
            'type' => 'nuban',
            'name' => $name,
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'currency' => $this->currency(),
        ]);
    }

    public function balance(): int
    {
        $rows = $this->send('get', '/balance');

        foreach ($rows as $row) {
            if (is_array($row) && strtoupper((string) ($row['currency'] ?? '')) === strtoupper($this->currency())) {
                return (int) ($row['balance'] ?? 0);
            }
        }

        return 0;
    }

    public function initiateTransfer(string $reference, string $recipientCode, int $amountKobo, string $reason): array
    {
        return $this->send('post', '/transfer', [
            'source' => 'balance',
            'amount' => $amountKobo,
            'recipient' => $recipientCode,
            'reference' => $reference,
            'reason' => $reason,
            'currency' => $this->currency(),
        ]);
    }

    public function verifyTransfer(string $reference): ?array
    {
        try {
            return $this->send('get', '/transfer/verify/' . rawurlencode($reference));
        } catch (GatewayException $exception) {
            if ($exception->isNotFound()) {
                return null;
            }

            throw $exception;
        }
    }

    public function verifyTransaction(string $reference): array
    {
        return $this->send('get', '/transaction/verify/' . rawurlencode($reference));
    }

    public function refund(string $transactionReference, string $note): array
    {
        return $this->send('post', '/refund', [
            'transaction' => $transactionReference,
            'merchant_note' => $note,
        ]);
    }

    public function listBanks(): array
    {
        return $this->send('get', '/bank', ['country' => 'nigeria', 'perPage' => 200, 'use_cursor' => 'false']);
    }

    private function currency(): string
    {
        return (string) config('settlement.currency', 'NGN');
    }

    private function http(): PendingRequest
    {
        $secret = $this->secretKey();

        if ($secret === '') {
            throw GatewayException::misconfigured('Paystack');
        }

        return Http::baseUrl((string) (config('services.paystack.base_url') ?: config('settlement.paystack.base_url')))
            ->withToken($secret)
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('settlement.paystack.timeout', 20))
            ->connectTimeout((int) config('settlement.paystack.connect_timeout', 5));
    }

    private function send(string $method, string $uri, array $payload = []): array
    {
        try {
            $response = $method === 'get'
                ? $this->http()->get($uri, $payload)
                : $this->http()->post($uri, $payload);
        } catch (ConnectionException $exception) {
            throw GatewayException::ambiguous('Unable to reach the payment gateway.', null, $exception);
        }

        return $this->interpret($response);
    }

    private function interpret(Response $response): array
    {
        $status = $response->status();
        $body = $response->json();

        if ($status >= 500 || in_array($status, [401, 403, 408, 429], true) || ! is_array($body)) {
            throw GatewayException::ambiguous('Payment gateway is unavailable or refused the request.', $status);
        }

        if ($response->successful() && ($body['status'] ?? false) === true) {
            $data = $body['data'] ?? [];

            return is_array($data) ? $data : [];
        }

        $message = (string) ($body['message'] ?? 'Payment gateway request failed.');

        if ($status === 404) {
            throw GatewayException::notFound($message, $status);
        }

        if (preg_match('/balance/i', $message) && preg_match('/(not enough|insufficient|low)/i', $message)) {
            throw GatewayException::insufficientBalance($message, $status);
        }

        if (preg_match('/reference/i', $message) && preg_match('/(exist|duplicate|already)/i', $message)) {
            throw GatewayException::duplicateReference($message, $status);
        }

        if (preg_match('/(already|fully)/i', $message)) {
            throw GatewayException::alreadyRefunded($message, $status);
        }

        throw GatewayException::rejected($message, $status);
    }
}
