<?php

declare(strict_types=1);

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Models\User;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\GatewaySettings;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Services\PaymentRefundService;
use App\Settlement\Services\SettlementAccountService;
use App\Settlement\Services\TransferService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

function paystackSettlement(string $status = 'pending'): Settlement
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
        'reference' => 'STL-PS-0001',
        'amount' => 3500.00,
        'status' => SettlementStatus::from($status),
        'gross_kobo' => 360000,
        'fee_kobo' => 10000,
        'adjustment_kobo' => 0,
        'net_kobo' => 350000,
        'attempts' => $status === 'processing' ? 1 : 0,
        'gateway' => $status === 'processing' ? 'paystack' : null,
    ])->save();

    return $settlement->fresh();
}

beforeEach(function () {
    Cache::flush();
});

it('pays out through Paystack by creating and persisting the recipient lazily', function () {
    Queue::fake();
    $settlement = paystackSettlement();

    Http::fake([
        'api.paystack.co/balance' => Http::response(['status' => true, 'data' => [['currency' => 'NGN', 'balance' => 99999999]]]),
        'api.paystack.co/transferrecipient' => Http::response(['status' => true, 'data' => ['recipient_code' => 'RCP_abc']]),
        'api.paystack.co/transfer' => Http::response(['status' => true, 'data' => ['status' => 'success', 'transfer_code' => 'TRF_1', 'amount' => 350000, 'currency' => 'NGN']]),
    ]);

    app(TransferService::class)->initiate($settlement->id);

    $fresh = $settlement->fresh();

    expect($fresh->status)->toBe(SettlementStatus::Success)
        ->and($fresh->gateway)->toBe('paystack')
        ->and($fresh->gateway_reference)->toBe('TRF_1')
        ->and(SettlementAccount::query()->withoutGlobalScopes()->first()->paystack_recipient_code)->toBe('RCP_abc');

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/transfer') && $r['recipient'] === 'RCP_abc' && $r['amount'] === 350000 && $r['reference'] === 'STL-PS-0001');
});

it('reuses a stored Paystack recipient instead of creating another one', function () {
    Queue::fake();
    $settlement = paystackSettlement();
    SettlementAccount::query()->withoutGlobalScopes()->update(['paystack_recipient_code' => 'RCP_existing']);

    Http::fake([
        'api.paystack.co/balance' => Http::response(['status' => true, 'data' => [['currency' => 'NGN', 'balance' => 99999999]]]),
        'api.paystack.co/transfer' => Http::response(['status' => true, 'data' => ['status' => 'pending', 'transfer_code' => 'TRF_2']]),
    ]);

    app(TransferService::class)->initiate($settlement->id);

    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), 'transferrecipient'));
    expect($settlement->fresh()->status)->toBe(SettlementStatus::Processing);
});

it('processes a signed Paystack transfer webhook through the existing route', function () {
    $settlement = paystackSettlement('processing');
    $body = json_encode(['event' => 'transfer.success', 'data' => [
        'reference' => 'STL-PS-0001',
        'transfer_code' => 'TRF_9',
        'amount' => 350000,
        'currency' => 'NGN',
        'status' => 'success',
    ]]);

    $secret = (string) config('settlement.paystack.secret_key');

    $this->call('POST', '/api/v1/webhooks/paystack/transfers', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_PAYSTACK_SIGNATURE' => hash_hmac('sha512', $body, $secret),
    ], $body)->assertOk();

    expect($settlement->fresh()->status)->toBe(SettlementStatus::Success);

    $this->call('POST', '/api/v1/webhooks/paystack/transfers', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_PAYSTACK_SIGNATURE' => 'bad',
    ], $body)->assertStatus(401);
});

function paidPayment(string $gateway, ?string $gatewayReference): Payment
{
    $pharmacy = Pharmacy::factory()->create();
    $customer = Customer::factory()->create();

    $order = new Order();
    $order->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'customer_id' => $customer->id,
        'status' => OrderStatus::Completed,
        'subtotal' => 1000,
        'total' => 1000,
        'fulfillment_type' => FulfillmentType::Pickup,
        'delivery_address' => 'x',
    ])->saveQuietly();

    $payment = new Payment();
    $payment->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'order_id' => $order->id,
        'reference' => 'MEDMART-REF-1',
        'gateway' => $gateway,
        'gateway_reference' => $gatewayReference,
        'status' => PaymentStatus::Paid,
        'amount' => 1000,
        'paid_at' => now(),
        'settlement_status' => 'pending',
        'gross_kobo' => 100000,
        'gateway_fee_kobo' => 1000,
        'net_kobo' => 99000,
    ])->saveQuietly();

    return $payment;
}

it('refunds a Paystack payment with the merchant reference', function () {
    $payment = paidPayment('paystack', 'MEDMART-REF-1');
    Http::fake(['api.paystack.co/refund' => Http::response(['status' => true, 'data' => []])]);

    app(PaymentRefundService::class)->refund($payment->id, 'Customer request', null);

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/refund') && $r['transaction'] === 'MEDMART-REF-1');
    expect($payment->fresh()->settlement_status)->toBe('refunded');
});

it('refunds a Nomba payment with the stored Nomba transaction id even after switching away from Nomba', function () {
    $payment = paidPayment('nomba', 'WEB-ONLINE_C-55');
    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/checkout/refund' => Http::response(['code' => '00', 'data' => ['success' => true]]),
    ]);

    app(PaymentRefundService::class)->refund($payment->id, 'Customer request', null);

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v1/checkout/refund') && $r['transactionId'] === 'WEB-ONLINE_C-55');
});

it('does not mark a payment refunded when the gateway refund is rejected', function () {
    $payment = paidPayment('nomba', 'WEB-ONLINE_C-55');
    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/checkout/refund' => Http::response(['code' => '400', 'description' => 'Refund not supported in sandbox'], 400),
    ]);

    expect(fn () => app(PaymentRefundService::class)->refund($payment->id, 'x', null))->toThrow(\App\Payments\Exceptions\GatewayException::class);
    expect($payment->fresh()->settlement_status)->toBe('pending');
});

function pendingAccountFor(): array
{
    $pharmacy = Pharmacy::factory()->create();
    $bank = Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);
    $admin = User::factory()->create(['is_super_admin' => true, 'pharmacy_id' => null, 'role' => null]);

    $account = new SettlementAccount();
    $account->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'bank_id' => $bank->id,
        'bank_name' => 'Guaranty Trust Bank',
        'account_number' => '0123456789',
        'account_name' => 'GREEN CROSS PHARMACY',
        'status' => AccountStatus::Pending,
    ])->save();

    return [$account, $admin];
}

it('approves a settlement account on Paystack and stores the recipient code', function () {
    [$account, $admin] = pendingAccountFor();
    Http::fake(['api.paystack.co/transferrecipient' => Http::response(['status' => true, 'data' => ['recipient_code' => 'RCP_new']])]);

    $approved = app(SettlementAccountService::class)->approve($account->id, $admin);

    expect($approved->status)->toBe(AccountStatus::Approved)
        ->and($approved->paystack_recipient_code)->toBe('RCP_new');
});

it('approves a settlement account on Nomba after validating it with a lookup', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    [$account, $admin] = pendingAccountFor();

    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/transfers/bank/lookup' => Http::response(['code' => '00', 'data' => ['accountNumber' => '0123456789', 'accountName' => 'GREEN CROSS PHARMACY']]),
    ]);

    $approved = app(SettlementAccountService::class)->approve($account->id, $admin);

    expect($approved->status)->toBe(AccountStatus::Approved)
        ->and($approved->paystack_recipient_code)->toBeNull();
});

it('rejects approval when the payout gateway cannot validate the account', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    [$account, $admin] = pendingAccountFor();

    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/transfers/bank/lookup' => Http::response(['code' => '400', 'description' => 'ACCOUNT_NOT_FOUND'], 400),
    ]);

    expect(fn () => app(SettlementAccountService::class)->approve($account->id, $admin))->toThrow(\App\Settlement\Exceptions\SettlementException::class);
    expect($account->fresh()->status)->toBe(AccountStatus::Pending);
});
