<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Nomba;

final class NombaConfig
{
    public function accountId(): string
    {
        return $this->string('account_id');
    }

    public function clientId(): string
    {
        return $this->string('client_id');
    }

    public function clientSecret(): string
    {
        return $this->string('client_secret');
    }

    public function signatureKey(): string
    {
        return $this->string('webhook_signature_key');
    }

    public function senderName(): string
    {
        $name = $this->string('sender_name');

        return $name !== '' ? $name : (string) config('app.name', 'MedMart');
    }

    public function isSandbox(): bool
    {
        return strtolower($this->string('environment')) !== 'production';
    }

    public function baseUrl(): string
    {
        $override = $this->string('base_url');

        if ($override !== '') {
            return rtrim($override, '/');
        }

        return $this->isSandbox() ? 'https://sandbox.nomba.com' : 'https://api.nomba.com';
    }

    public function timeout(): int
    {
        return max(1, (int) config('services.nomba.timeout', 20));
    }

    public function connectTimeout(): int
    {
        return max(1, (int) config('services.nomba.connect_timeout', 5));
    }

    public function webhookTolerance(): int
    {
        return max(0, (int) config('services.nomba.webhook_tolerance', 0));
    }

    public function missing(): array
    {
        $required = [
            'NOMBA_ACCOUNT_ID' => $this->accountId(),
            'NOMBA_CLIENT_ID' => $this->clientId(),
            'NOMBA_CLIENT_SECRET' => $this->clientSecret(),
            'NOMBA_WEBHOOK_SIGNATURE_KEY' => $this->signatureKey(),
        ];

        return array_keys(array_filter($required, static fn (string $value): bool => $value === ''));
    }

    public function isConfigured(): bool
    {
        return $this->missing() === [];
    }

    private function string(string $key): string
    {
        $value = config('services.nomba.' . $key);

        return is_string($value) ? trim($value) : '';
    }
}
