<?php

declare(strict_types=1);

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
    public function initializeTransaction(string $reference, int $amountInKobo, string $email, array $metadata = [], ?string $callbackUrl = null): array;
    
    public function verifyTransaction(string $reference): array;
}