<?php

declare(strict_types=1);

use App\Payments\ChargeSettler;
use App\Payments\Gateways\Nomba\NombaSignature;
use App\Settlement\Jobs\ProcessTransferEventJob;
use Illuminate\Support\Facades\Queue;

function nombaWebhookBody(string $event, array $data = []): array
{
    return [
        'event_type' => $event,
        'requestId' => 'req-1',
        'data' => array_replace_recursive([
            'merchant' => ['userId' => 'user-1', 'walletId' => 'wallet-1'],
            'transaction' => [
                'transactionId' => 'API-TXN-1',
                'type' => 'online_checkout',
                'time' => '2026-11-18T10:00:00Z',
                'responseCode' => '',
            ],
        ], $data),
    ];
}

function postNomba(object $test, array $body, ?string $key = 'SampleSignatureKey123')
{
    $timestamp = gmdate('Y-m-d\TH:i:s\Z');
    $headers = ['nomba-timestamp' => $timestamp];

    if ($key !== null) {
        $headers['nomba-signature'] = NombaSignature::compute($body, $key, $timestamp);
    }

    return $test->postJson('/api/v1/webhooks/nomba', $body, $headers);
}

it('rejects webhooks without a valid signature', function () {
    $body = nombaWebhookBody('payment_success');

    $this->postJson('/api/v1/webhooks/nomba', $body)->assertStatus(401);
    postNomba($this, $body, 'wrong-key')->assertStatus(401);
});

it('fails closed when no signature key is configured', function () {
    config(['services.nomba.webhook_signature_key' => '']);

    postNomba($this, nombaWebhookBody('payment_success'), 'anything')->assertStatus(401);
});

it('rejects stale webhooks when a replay window is configured', function () {
    config(['services.nomba.webhook_tolerance' => 60]);
    $body = nombaWebhookBody('payment_success');
    $timestamp = '2020-01-01T00:00:00Z';

    $this->postJson('/api/v1/webhooks/nomba', $body, [
        'nomba-timestamp' => $timestamp,
        'nomba-signature' => NombaSignature::compute($body, 'SampleSignatureKey123', $timestamp),
    ])->assertStatus(401);
});

it('settles a charge using the merchant order reference', function () {
    $settler = Mockery::mock(ChargeSettler::class);
    $settler->shouldReceive('settle')->once()->with('MEDMART-1-abc')->andReturnTrue();
    $this->app->instance(ChargeSettler::class, $settler);

    postNomba($this, nombaWebhookBody('payment_success', ['order' => ['orderReference' => 'MEDMART-1-abc']]))->assertOk();
});

it('ignores payment events that are not checkout orders', function () {
    $settler = Mockery::mock(ChargeSettler::class);
    $settler->shouldNotReceive('settle');
    $this->app->instance(ChargeSettler::class, $settler);

    postNomba($this, nombaWebhookBody('payment_success'))->assertOk();
});

it('queues payout events for status confirmation', function () {
    Queue::fake();

    postNomba($this, nombaWebhookBody('payout_refund', ['transaction' => ['merchantTxRef' => 'STL-1']]))->assertOk();

    Queue::assertPushed(ProcessTransferEventJob::class, fn (ProcessTransferEventJob $job) => $job->gateway === 'nomba' && $job->event === 'payout_refund');
});

it('returns a retryable error when processing fails', function () {
    $settler = Mockery::mock(ChargeSettler::class);
    $settler->shouldReceive('settle')->andThrow(new RuntimeException('boom'));
    $this->app->instance(ChargeSettler::class, $settler);

    postNomba($this, nombaWebhookBody('payment_success', ['order' => ['orderReference' => 'MEDMART-1-abc']]))->assertStatus(500);
});
