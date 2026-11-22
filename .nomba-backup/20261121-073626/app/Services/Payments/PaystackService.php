<?php

declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class PaystackService implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.paystack.base_url', 'https://api.paystack.co');
        $this->secretKey = config('services.paystack.secret_key');
    }

    public function initializeTransaction(string $reference, int $amountInKobo, string $email, array $metadata = [], ?string $callbackUrl = null): array
    {
        $payload = [
            'reference' => $reference,
            'amount' => $amountInKobo,
            'email' => $email,
            'callback_url' => $callbackUrl ?? config('services.paystack.callback_url'),
        ];

        if (!empty($metadata)) {
            $payload['metadata'] = $metadata;
        }

        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl . '/transaction/initialize', $payload);

        $data = $response->throw()->json();

        if (!($data['status'] ?? false)) {
            throw new \Exception($data['message'] ?? 'Payment initialization failed');
        }

        return $data;
    }

    public function verifyTransaction(string $reference): array
    {
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . '/transaction/verify/' . $reference);

        $data = $response->throw()->json();

        if (!($data['status'] ?? false)) {
            throw new \Exception($data['message'] ?? 'Payment verification failed');
        }

        return $data;
    }
}