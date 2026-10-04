<?php

declare(strict_types=1);

namespace App\Settlement\Gateway;

use App\Settlement\Exceptions\PaystackException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class PaystackClient
{
    public function isTestKey(): bool
    {
        return str_starts_with((string) config('settlement.paystack.secret_key'), 'sk_test_');
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
        } catch (PaystackException $exception) {
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

    private function currency(): string
    {
        return (string) config('settlement.paystack.currency', 'NGN');
    }

    private function http(): PendingRequest
    {
        $secret = config('settlement.paystack.secret_key');

        if (! is_string($secret) || $secret === '') {
            throw PaystackException::misconfigured();
        }

        return Http::baseUrl((string) config('settlement.paystack.base_url'))
            ->withToken($secret)
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('settlement.paystack.timeout'))
            ->connectTimeout((int) config('settlement.paystack.connect_timeout'));
    }

    private function send(string $method, string $uri, array $payload = []): array
    {
        try {
            $response = $method === 'get'
                ? $this->http()->get($uri, $payload)
                : $this->http()->post($uri, $payload);
        } catch (ConnectionException $exception) {
            throw PaystackException::ambiguous('Unable to reach the payment gateway.', null, $exception);
        }

        return $this->interpret($response);
    }

    private function interpret(Response $response): array
    {
        $status = $response->status();
        $body = $response->json();

        if ($status >= 500 || in_array($status, [401, 403, 408, 429], true) || ! is_array($body)) {
            throw PaystackException::ambiguous('Payment gateway is unavailable or refused the request.', $status);
        }

        if ($response->successful() && ($body['status'] ?? false) === true) {
            $data = $body['data'] ?? [];

            return is_array($data) ? $data : [];
        }

        throw PaystackException::rejected((string) ($body['message'] ?? 'Payment gateway request failed.'), $status);
    }
}
