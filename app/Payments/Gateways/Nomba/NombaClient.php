<?php

declare(strict_types=1);

namespace App\Payments\Gateways\Nomba;

use App\Payments\Exceptions\GatewayException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class NombaClient
{
    private const SUCCESS_CODES = ['00', '200', '201'];

    public function __construct(
        private readonly NombaConfig $config,
        private readonly NombaTokenStore $tokens,
    ) {
    }

    public function get(string $uri, array $query = []): array
    {
        return $this->send('get', $uri, $query);
    }

    public function post(string $uri, array $payload = []): array
    {
        return $this->send('post', $uri, $payload);
    }

    private function send(string $method, string $uri, array $data): array
    {
        for ($attempt = 0; $attempt < 2; $attempt++) {
            try {
                $request = $this->http($this->tokens->accessToken());

                $response = $method === 'get'
                    ? $request->retry(2, 200, static fn (mixed $exception): bool => $exception instanceof ConnectionException, false)->get($uri, $data)
                    : $request->post($uri, $data);
            } catch (ConnectionException $exception) {
                throw GatewayException::ambiguous('Unable to reach Nomba.', null, $exception);
            }

            if ($response->status() === 401 && $attempt === 0) {
                $this->tokens->forget();

                continue;
            }

            return $this->interpret($response);
        }

        throw GatewayException::ambiguous('Nomba refused the request.', 401);
    }

    private function http(string $token): PendingRequest
    {
        return Http::baseUrl($this->config->baseUrl())
            ->withToken($token)
            ->withHeaders(['accountId' => $this->config->accountId()])
            ->acceptJson()
            ->asJson()
            ->timeout($this->config->timeout())
            ->connectTimeout($this->config->connectTimeout());
    }

    private function interpret(Response $response): array
    {
        $status = $response->status();
        $body = $response->json();

        if ($status >= 500 || in_array($status, [401, 403, 408, 429], true) || ! is_array($body)) {
            throw GatewayException::ambiguous('Nomba is unavailable or refused the request.', $status);
        }

        $code = (string) ($body['code'] ?? '');
        $description = strtoupper(trim((string) ($body['description'] ?? '')));
        $message = trim($description . ' ' . (string) ($body['message'] ?? ''));

        if ($status === 404 || $code === '404' || $code === '01') {
            throw GatewayException::notFound($message !== '' ? $message : 'Not found at Nomba.', $status);
        }

        if ($response->successful() && in_array($code, self::SUCCESS_CODES, true)) {
            return $body;
        }

        if ($description === 'INSUFFICIENT_BALANCE') {
            throw GatewayException::insufficientBalance($message, $status);
        }

        if (preg_match('/(duplicate|already exist)/i', $message)) {
            throw GatewayException::duplicateReference($message, $status);
        }

        throw GatewayException::rejected($message !== '' ? $message : 'Nomba request failed.', $status, $description !== '' ? $description : null);
    }
}
