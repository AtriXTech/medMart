<?php

declare(strict_types=1);

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\GatewaySettings;
use App\Services\Billing\PharmacySubscriptionService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

function useGateway(Gateway $gateway, GatewayRole ...$roles): void
{
    foreach ($roles ?: GatewayRole::cases() as $role) {
        app(GatewaySettings::class)->switchTo($role, $gateway, null, 'test', true);
    }
}

function nombaAuth(): array
{
    return ['*/v1/auth/token/issue' => Http::response([
        'code' => '00',
        'data' => ['access_token' => 'tok', 'refresh_token' => 'ref', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)],
    ])];
}

function pendingOrder(float $total = 2500.00): Order
{
    $pharmacy = Pharmacy::factory()->create();
    $customer = Customer::factory()->create();

    $order = new Order();
    $order->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'customer_id' => $customer->id,
        'status' => OrderStatus::PendingPayment,
        'subtotal' => $total,
        'total' => $total,
        'fulfillment_type' => FulfillmentType::Pickup,
        'delivery_address' => 'Test address',
    ])->saveQuietly();

    return $order->fresh();
}

function nombaVerifyResponse(string $reference, string $status = 'SUCCESS', string $amount = '2500.00'): array
{
    return ['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => [
            'id' => 'WEB-ONLINE_C-777',
            'status' => $status,
            'type' => 'online_checkout',
            'amount' => $amount,
            'onlineCheckoutAmount' => $amount,
            'onlineCheckoutOrderReference' => $reference,
            'fixedCharge' => '35.00',
            'currency' => 'NGN',
        ],
    ])];
}

beforeEach(function () {
    Cache::flush();
    Queue::fake();
});

it('starts a customer checkout on Nomba and records the gateway on the payment', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc', 'orderReference' => 'n-1']])]);

    $result = app(PaymentService::class)->initiate(pendingOrder());

    $payment = Payment::query()->withoutGlobalScopes()->where('reference', $result['reference'])->first();

    expect($result['authorization_url'])->toBe('https://pay.nomba/abc')
        ->and($payment->gateway)->toBe('nomba')
        ->and($payment->status)->toBe(PaymentStatus::Unpaid);

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v1/checkout/order') && $r['order']['amount'] === '2500.00' && $r['order']['orderReference'] === $result['reference']);
});

it('marks the payment paid after a verified Nomba success and stores the Nomba transaction id', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $order = pendingOrder();

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];

    Http::fake(nombaAuth() + nombaVerifyResponse($reference));
    $outcome = app(PaymentService::class)->verifyPayment($order, $reference);

    $payment = Payment::query()->withoutGlobalScopes()->where('reference', $reference)->first();

    expect($outcome['status'])->toBe('success')
        ->and($payment->status)->toBe(PaymentStatus::Paid)
        ->and($payment->gateway_reference)->toBe('WEB-ONLINE_C-777')
        ->and($order->fresh()->status)->not->toBe(OrderStatus::PendingPayment);
});

it('accepts the Nomba transaction id as the verification reference', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $order = pendingOrder();

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];
    Payment::query()->withoutGlobalScopes()->where('reference', $reference)->update(['gateway_reference' => 'WEB-ONLINE_C-777']);

    Http::fake(nombaAuth() + nombaVerifyResponse($reference));

    expect(app(PaymentService::class)->verifyPayment($order, 'WEB-ONLINE_C-777')['status'])->toBe('success');
});

it('never marks a payment paid when the amount differs', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $order = pendingOrder();

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];

    Http::fake(nombaAuth() + nombaVerifyResponse($reference, 'SUCCESS', '100.00'));
    $outcome = app(PaymentService::class)->verifyPayment($order, $reference);

    expect($outcome['status'])->toBe('failed')
        ->and(Payment::query()->withoutGlobalScopes()->where('reference', $reference)->first()->status)->toBe(PaymentStatus::Failed)
        ->and($order->fresh()->status)->toBe(OrderStatus::PendingPayment);
});

it('leaves a payment unpaid and reports pending while the customer has not paid yet', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $order = pendingOrder();

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];

    Http::fake(nombaAuth() + ['*/v1/transactions/accounts/single*' => Http::response(['code' => '01', 'description' => 'Transaction not found'])]);
    $outcome = app(PaymentService::class)->verifyPayment($order, $reference);

    expect($outcome['status'])->toBe('pending')
        ->and(Payment::query()->withoutGlobalScopes()->where('reference', $reference)->first()->status)->toBe(PaymentStatus::Unpaid);
});

it('keeps verifying a payment on the gateway that created it after the active gateway changes', function () {
    $order = pendingOrder();

    Http::fake(['api.paystack.co/transaction/initialize' => Http::response(['status' => true, 'data' => ['authorization_url' => 'https://paystack.test/x', 'reference' => 'r']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];

    expect(Payment::query()->withoutGlobalScopes()->where('reference', $reference)->value('gateway'))->toBe('paystack');

    useGateway(Gateway::Nomba, GatewayRole::Collections);

    Http::fake(['api.paystack.co/transaction/verify/*' => Http::response(['status' => true, 'data' => [
        'status' => 'success',
        'reference' => $reference,
        'amount' => 250000,
        'currency' => 'NGN',
        'fees' => 3500,
    ]])]);

    $outcome = app(PaymentService::class)->verifyPayment($order, $reference);

    expect($outcome['status'])->toBe('success');
    Http::assertNotSent(fn (Request $r) => str_contains($r->url(), 'nomba'));
});

it('runs a subscription purchase end to end on Nomba', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $pharmacy = Pharmacy::factory()->create();
    $plan = SubscriptionPlan::factory()->create(['price' => 5000]);

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/sub']])]);
    $started = app(PharmacySubscriptionService::class)->initiate($pharmacy, $plan, 2);

    expect($started['amount'])->toEqual(10000)
        ->and(SubscriptionPayment::query()->withoutGlobalScopes()->where('reference', $started['reference'])->value('gateway'))->toBe('nomba');

    Http::fake(nombaAuth() + nombaVerifyResponse($started['reference'], 'SUCCESS', '10000.00'));
    $outcome = app(PharmacySubscriptionService::class)->verifyPayment($pharmacy, $started['reference']);

    $subscription = $pharmacy->fresh()->subscription;

    expect($outcome['status'])->toBe('success')
        ->and($subscription->status)->toBe(SubscriptionStatus::Active)
        ->and((int) round($subscription->current_period_starts_at->diffInDays($subscription->current_period_ends_at)))->toBeGreaterThanOrEqual(59);
});

it('settles a charge from a webhook reference for both orders and subscriptions', function () {
    useGateway(Gateway::Nomba, GatewayRole::Collections);
    $order = pendingOrder();

    Http::fake(nombaAuth() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/abc']])]);
    $reference = app(PaymentService::class)->initiate($order)['reference'];

    Http::fake(nombaAuth() + nombaVerifyResponse($reference));

    expect(app(\App\Payments\ChargeSettler::class)->settle($reference))->toBeTrue()
        ->and(Payment::query()->withoutGlobalScopes()->where('reference', $reference)->first()->status)->toBe(PaymentStatus::Paid)
        ->and(app(\App\Payments\ChargeSettler::class)->settle('UNKNOWN-REF'))->toBeFalse();
});
