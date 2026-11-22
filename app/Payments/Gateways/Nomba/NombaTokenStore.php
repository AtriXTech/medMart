<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Nomba;

use App\Payments\Exceptions\GatewayException;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Throwable;

final class NombaTokenStore
{
    private const REFRESH_SKEW_SECONDS = 300;

    private const RETENTION_SECONDS = 86400;

    public function __construct(private readonly NombaConfig $config)
    {
    }

    public function accessToken(): string
    {
        $tokens = $this->read();

        if ($this->isUsable($tokens)) {
            return (string) $tokens['access_token'];
        }

        try {
            return Cache::lock($this->key() . ':lock', 20)->block(10, function (): string {
                $current = $this->read();

                if ($this->isUsable($current)) {
                    return (string) $current['access_token'];
                }

                return $this->renew($current);
            });
        } catch (LockTimeoutException) {
            return $this->renew($this->read());
        }
    }

    public function forget(): void
    {
        Cache::forget($this->key());
    }

    private function renew(?array $current): string
    {
        if ($current !== null && ! empty($current['refresh_token']) && ! empty($current['access_token'])) {
            try {
                return $this->persist($this->refresh((string) $current['access_token'], (string) $current['refresh_token']));
            } catch (GatewayException) {
                $this->forget();
            }
        }

        return $this->persist($this->issue());
    }

    private function issue(): array
    {
        return $this->request('/v1/auth/token/issue', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config->clientId(),
            'client_secret' => $this->config->clientSecret(),
        ]);
    }

    private function refresh(string $accessToken, string $refreshToken): array
    {
        return $this->request('/v1/auth/token/refresh', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ], $accessToken);
    }

    private function request(string $uri, array $payload, ?string $bearer = null): array
    {
        if (! $this->config->isConfigured() && ($this->config->accountId() === '' || $this->config->clientId() === '' || $this->config->clientSecret() === '')) {
            throw GatewayException::misconfigured('Nomba');
        }

        try {
            $response = $this->http($bearer)->post($uri, $payload);
        } catch (ConnectionException $exception) {
            throw GatewayException::ambiguous('Unable to reach Nomba.', null, $exception);
        }

        $body = $response->json();
        $data = is_array($body) ? ($body['data'] ?? null) : null;

        if ($response->status() >= 500 || $response->status() === 429) {
            throw GatewayException::ambiguous('Nomba authentication is temporarily unavailable.', $response->status());
        }

        if (! $response->successful() || ! is_array($body) || ($body['code'] ?? null) !== '00' || ! is_array($data) || empty($data['access_token'])) {
            throw GatewayException::ambiguous('Nomba rejected the API credentials.', $response->status(), null, 'authentication');
        }

        return $data;
    }

    private function http(?string $bearer): PendingRequest
    {
        $request = Http::baseUrl($this->config->baseUrl())
            ->withHeaders(['accountId' => $this->config->accountId()])
            ->acceptJson()
            ->asJson()
            ->timeout($this->config->timeout())
            ->connectTimeout($this->config->connectTimeout());

        return $bearer !== null ? $request->withToken($bearer) : $request;
    }

    private function persist(array $data): string
    {
        try {
            $expiresAt = Carbon::parse((string) ($data['expiresAt'] ?? ''))->getTimestamp();
        } catch (Throwable) {
            $expiresAt = time() + 1500;
        }

        $record = [
            'access_token' => (string) $data['access_token'],
            'refresh_token' => isset($data['refresh_token']) ? (string) $data['refresh_token'] : null,
            'expires_at' => $expiresAt,
        ];

        Cache::put($this->key(), Crypt::encryptString((string) json_encode($record)), self::RETENTION_SECONDS);

        return $record['access_token'];
    }

    private function read(): ?array
    {
        $payload = Cache::get($this->key());

        if (! is_string($payload) || $payload === '') {
            return null;
        }

        try {
            $record = json_decode(Crypt::decryptString($payload), true);
        } catch (Throwable) {
            return null;
        }

        return is_array($record) ? $record : null;
    }

    private function isUsable(?array $tokens): bool
    {
        return $tokens !== null
            && ! empty($tokens['access_token'])
            && (int) ($tokens['expires_at'] ?? 0) - self::REFRESH_SKEW_SECONDS > time();
    }

    private function key(): string
    {
        return 'payments:nomba:token:' . sha1($this->config->baseUrl() . '|' . $this->config->accountId() . '|' . $this->config->clientId());
    }
}
