<?php

declare(strict_types=1);

use App\Payments\Gateways\Nomba\NombaSignature;

function nombaSamplePayload(): array
{
    return [
        'event_type' => 'payment_success',
        'requestId' => '9d3b6f1e-2c4a-4e8b-a7d5-1f0c3e9b8a26',
        'data' => [
            'merchant' => [
                'walletId' => '66f2a91c4be7d3a5c1e08b47',
                'userId' => '3c8e1f72-5a9d-4b16-8e2f-7d4c0a9b6e53',
            ],
            'transaction' => [
                'type' => 'vact_transfer',
                'transactionId' => 'API-VACT_TRA-3C8E1-a4f7c2d9-6b1e-4f38-9c5a-2e8d7b0f1c64',
                'responseCode' => '',
                'time' => '2025-09-29T10:51:44Z',
            ],
        ],
    ];
}

it('reproduces the signature published in the Nomba documentation', function () {
    $signature = NombaSignature::compute(nombaSamplePayload(), 'SampleSignatureKey123', '2025-09-29T10:51:44Z');

    expect($signature)->toBe('q30yFZDyykoymBf9+mt1wz5UAuLk57raPGPHlGzIv00=');
});

it('accepts a valid signature and rejects a wrong key', function () {
    $signature = 'q30yFZDyykoymBf9+mt1wz5UAuLk57raPGPHlGzIv00=';

    expect(NombaSignature::matches(nombaSamplePayload(), 'SampleSignatureKey123', '2025-09-29T10:51:44Z', $signature))->toBeTrue()
        ->and(NombaSignature::matches(nombaSamplePayload(), 'other-key', '2025-09-29T10:51:44Z', $signature))->toBeFalse()
        ->and(NombaSignature::matches(nombaSamplePayload(), 'SampleSignatureKey123', '2025-09-29T10:51:45Z', $signature))->toBeFalse()
        ->and(NombaSignature::matches(nombaSamplePayload(), 'SampleSignatureKey123', '2025-09-29T10:51:44Z', ''))->toBeFalse();
});

it('detects a tampered payload', function () {
    $payload = nombaSamplePayload();
    $payload['data']['transaction']['transactionId'] = 'API-VACT_TRA-FORGED';

    expect(NombaSignature::matches($payload, 'SampleSignatureKey123', '2025-09-29T10:51:44Z', 'q30yFZDyykoymBf9+mt1wz5UAuLk57raPGPHlGzIv00='))->toBeFalse();
});

it('enforces the replay window only when configured', function () {
    expect(NombaSignature::isFresh('2001-01-01T00:00:00Z', 0))->toBeTrue()
        ->and(NombaSignature::isFresh('2001-01-01T00:00:00Z', 300))->toBeFalse()
        ->and(NombaSignature::isFresh(gmdate('Y-m-d\TH:i:s\Z'), 300))->toBeTrue();
});
