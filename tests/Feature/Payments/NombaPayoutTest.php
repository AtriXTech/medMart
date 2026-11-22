<?php

declare(strict_types=1);

use App\Models\Bank;
use App\Models\BankGatewayCode;
use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\GatewaySettings;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Services\TransferEventProcessor;
use App\Settlement\Services\TransferService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

function nombaPayoutAuth(): array
{
    return ['*/v1/auth/token/issue' => Http::response([
        'code' => '00',
        'data' => ['access_token' => 'tok', 'refresh_token' => 'ref', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)],
    ])];
}

function nombaPayoutFakes(array $overrides = []): array
{
    return nombaPayoutAuth() + $overrides + [
        '*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '100000.00', 'currency' => 'NGN']]),
        '*/v1/transfers/bank/lookup' => Http::response(['code' => '00', 'data' => ['accountNumber' => '0123456789', 'accountName' => 'GREEN CROSS PHARMACY']]),
    ];
}

function payoutSettlement(?string $gateway = null): Settlement
{
    $pharmacy = Pharmacy::factory()->create(['name' => 'Green Cross Pharmacy']);
    $bank = Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);

    $account = new SettlementAccount();
    $account->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'bank_id' => $bank->id,
        'bank_name' => 'Guaranty Trust Bank',
        'account_number' => '0123456789',
        'account_name' => 'GREEN CROSS PHARMACY',
        'status' => AccountStatus::Approved,
        'approved_at' => now(),
    ])->save();

    $settlement = new Settlement();
    $settlement->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'settlement_account_id' => $account->id,
        'reference' => 'STL-TEST-0001',
        'amount' => 3500.00,
        'status' => SettlementStatus::Pending,
        'gross_kobo' => 360000,
        'fee_kobo' => 10000,
        'adjustment_kobo' => 0,
        'net_kobo' => 350000,
        'attempts' => 0,
        'gateway' => $gateway,
    ])->save();

    return $settlement->fresh();
}

beforeEach(function () {
    Cache::flush();
    Queue::fake();
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
});

it('pays out through Nomba, uses the bank code mapped for Nomba and marks the settlement successful', function () {
    $settlement = payoutSettlement();
    BankGatewayCode::query()->create(['bank_id' => Bank::query()->value('id'), 'gateway' => 'nomba', 'code' => '000058']);

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response([
        'code' => '200',
        'description' => 'SUCCESS',
        'data' => ['id' => 'API-TRANSFER-9', 'status' => 'SUCCESS', 'amount' => '3500.0'],
    ])]));

    app(TransferService::class)->initiate($settlement->id);

    $fresh = $settlement->fresh();

    expect($fresh->status)->toBe(SettlementStatus::Settled)
        ->and($fresh->gateway)->toBe('nomba')
        ->and($fresh->gateway_reference)->toBe('API-TRANSFER-9');

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v2/transfers/bank')
        && $r['merchantTxRef'] === 'STL-TEST-0001'
        && $r['bankCode'] === '000058'
        && $r['amount'] === 3500.0);

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v1/transfers/bank/lookup') && $r['bankCode'] === '000058');
});

it('falls back to the default bank code when no Nomba mapping exists', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '200', 'description' => 'SUCCESS', 'data' => ['id' => 'T1', 'status' => 'SUCCESS', 'amount' => 3500]])]));

    app(TransferService::class)->initiate($settlement->id);

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v2/transfers/bank') && $r['bankCode'] === '058');
});

it('keeps a pending-billing transfer processing, then completes it from a confirmed payout_success webhook', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response([
        'code' => '201',
        'description' => 'PROCESSING',
        'status' => false,
        'data' => ['id' => 'API-TRANSFER-5', 'status' => 'PENDING_BILLING'],
    ], 201)]));

    app(TransferService::class)->initiate($settlement->id);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing)
        ->and($settlement->fresh()->gateway_reference)->toBe('API-TRANSFER-5');

    Http::fake(nombaPayoutAuth() + ['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'API-TRANSFER-5', 'status' => 'SUCCESS', 'amount' => 3500, 'merchantTxRef' => 'STL-TEST-0001'],
    ])]);

    app(TransferEventProcessor::class)->handleWebhook(Gateway::Nomba, 'payout_success', ['transaction' => ['merchantTxRef' => 'STL-TEST-0001']]);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Settled);
});

it('ignores a payout_failed webhook unless the requery confirms a refund', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '201', 'description' => 'PROCESSING', 'data' => ['id' => 'T5', 'status' => 'PENDING_BILLING']], 201)]));
    app(TransferService::class)->initiate($settlement->id);

    Http::fake(nombaPayoutAuth() + ['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'T5', 'status' => 'PAYMENT_FAILED', 'amount' => 3500, 'merchantTxRef' => 'STL-TEST-0001'],
    ])]);

    app(TransferEventProcessor::class)->handleWebhook(Gateway::Nomba, 'payout_failed', ['transaction' => ['merchantTxRef' => 'STL-TEST-0001']]);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing);
});

it('releases funds only when Nomba confirms the payout was refunded', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '201', 'description' => 'PROCESSING', 'data' => ['id' => 'T6', 'status' => 'PENDING_BILLING']], 201)]));
    app(TransferService::class)->initiate($settlement->id);

    Http::fake(nombaPayoutAuth() + ['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'T6', 'status' => 'REFUND', 'amount' => 3500, 'merchantTxRef' => 'STL-TEST-0001'],
    ])]);

    app(TransferEventProcessor::class)->handleWebhook(Gateway::Nomba, 'payout_refund', ['transaction' => ['merchantTxRef' => 'STL-TEST-0001']]);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Reversed);
});

it('does not unwind a transfer when Nomba answers with an unknown server error', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '500', 'description' => 'ERROR'], 500)]));

    app(TransferService::class)->initiate($settlement->id);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing);
});

it('fails the settlement on a definitive pre-debit rejection', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '400', 'description' => 'ACCOUNT_NOT_FOUND'], 400)]));

    app(TransferService::class)->initiate($settlement->id);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Failed);
});

it('holds the settlement when the account name no longer matches the bank record', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutAuth() + [
        '*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '100000.00', 'currency' => 'NGN']]),
        '*/v1/transfers/bank/lookup' => Http::response(['code' => '00', 'data' => ['accountNumber' => '0123456789', 'accountName' => 'SOMEONE ELSE ENTIRELY']]),
    ]);

    app(TransferService::class)->initiate($settlement->id);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::OnHold);
    Http::assertNotSent(fn (Request $r) => str_ends_with($r->url(), '/v2/transfers/bank'));
});

it('does not submit a transfer when the Nomba balance is too low', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutAuth() + ['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '10.00', 'currency' => 'NGN']])]);

    expect(fn () => app(TransferService::class)->initiate($settlement->id))->toThrow(\App\Settlement\Exceptions\InsufficientBalanceException::class);
    expect($settlement->fresh()->status)->toBe(SettlementStatus::Pending);
});

it('keeps a submitted settlement on its original gateway after the payout gateway changes', function () {
    $settlement = payoutSettlement('paystack');
    $settlement->forceFill(['status' => SettlementStatus::Processing, 'attempts' => 1, 'initiated_at' => now()])->save();

    Http::fake(['api.paystack.co/transfer/verify/*' => Http::response(['status' => true, 'data' => [
        'status' => 'success',
        'transfer_code' => 'TRF_abc',
        'amount' => 350000,
        'currency' => 'NGN',
    ]])]);

    $outcome = app(TransferService::class)->verifyAndApply($settlement->fresh());

    expect($outcome)->toBe('applied')
        ->and($settlement->fresh()->status)->toBe(SettlementStatus::Settled);

    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), 'nomba'));
});

it('ignores a webhook delivered by a different gateway than the one that sent the transfer', function () {
    $settlement = payoutSettlement('paystack');
    $settlement->forceFill(['status' => SettlementStatus::Processing, 'attempts' => 1])->save();

    app(TransferEventProcessor::class)->handleWebhook(Gateway::Nomba, 'payout_success', ['transaction' => ['merchantTxRef' => 'STL-TEST-0001']]);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing);
    Http::assertNothingSent();
});

it('reports a mismatch instead of completing a transfer whose amount differs', function () {
    $settlement = payoutSettlement();

    Http::fake(nombaPayoutFakes(['*/v2/transfers/bank' => Http::response(['code' => '201', 'description' => 'PROCESSING', 'data' => ['id' => 'T8', 'status' => 'PENDING_BILLING']], 201)]));
    app(TransferService::class)->initiate($settlement->id);

    Http::fake(nombaPayoutAuth() + ['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'T8', 'status' => 'SUCCESS', 'amount' => 1, 'merchantTxRef' => 'STL-TEST-0001'],
    ])]);

    app(TransferEventProcessor::class)->handleWebhook(Gateway::Nomba, 'payout_success', ['transaction' => ['merchantTxRef' => 'STL-TEST-0001']]);

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing);
});
