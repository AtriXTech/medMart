<?php

declare(strict_types=1);

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Settlement\Services\PaymentSettlementRecorder;
use App\Settlement\Support\GatewayFeeEstimator;
use App\Payments\Enums\Gateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

function recordablePayment(string $gateway): Payment
{
    $pharmacy = Pharmacy::factory()->create();
    $customer = Customer::factory()->create();

    $order = new Order();
    $order->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'customer_id' => $customer->id,
        'status' => OrderStatus::Completed,
        'subtotal' => 2500,
        'total' => 2500,
        'fulfillment_type' => FulfillmentType::Pickup,
        'delivery_address' => 'x',
    ])->saveQuietly();

    $payment = new Payment();
    $payment->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'order_id' => $order->id,
        'reference' => 'MEDMART-REC-1',
        'gateway' => $gateway,
        'status' => PaymentStatus::Paid,
        'amount' => 2500,
        'paid_at' => now(),
    ])->saveQuietly();

    return $payment;
}

beforeEach(function () {
    Cache::flush();
    Queue::fake();
});

it('records the gateway reported Nomba fee when settling a payment', function () {
    $payment = recordablePayment('nomba');

    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/transactions/accounts/single*' => Http::response(['code' => '00', 'data' => [
            'id' => 'WEB-1',
            'status' => 'SUCCESS',
            'type' => 'online_checkout',
            'onlineCheckoutAmount' => '2500.00',
            'onlineCheckoutOrderReference' => 'MEDMART-REC-1',
            'fixedCharge' => '35.00',
            'currency' => 'NGN',
        ]]),
    ]);

    app(PaymentSettlementRecorder::class)->record($payment->id);

    $fresh = $payment->fresh();

    expect($fresh->gateway_fee_source)->toBe('gateway')
        ->and((int) $fresh->gateway_fee_kobo)->toBe(3500)
        ->and((int) $fresh->gross_kobo)->toBe(250000)
        ->and($fresh->hold_reason)->toBeNull();
});

it('holds a payment whose gateway amount does not match', function () {
    $payment = recordablePayment('nomba');

    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/transactions/accounts/single*' => Http::response(['code' => '00', 'data' => [
            'id' => 'WEB-1',
            'status' => 'SUCCESS',
            'type' => 'online_checkout',
            'onlineCheckoutAmount' => '10.00',
            'onlineCheckoutOrderReference' => 'MEDMART-REC-1',
            'currency' => 'NGN',
        ]]),
    ]);

    app(PaymentSettlementRecorder::class)->record($payment->id);

    expect($payment->fresh()->hold_reason)->toBe('amount_mismatch');
});

it('estimates Nomba fees with the configured percentage and cap', function () {
    $estimator = app(GatewayFeeEstimator::class);

    expect($estimator->estimate(25000000, Gateway::Nomba))->toBe(180000)
        ->and($estimator->estimate(100000, Gateway::Nomba))->toBe(1400);
});

it('settles an order through the existing Paystack charge.success webhook', function () {
    $pharmacy = Pharmacy::factory()->create();
    $customer = Customer::factory()->create();

    $order = new Order();
    $order->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'customer_id' => $customer->id,
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 2500,
        'total' => 2500,
        'fulfillment_type' => FulfillmentType::Pickup,
        'delivery_address' => 'x',
    ])->saveQuietly();

    $payment = new Payment();
    $payment->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'order_id' => $order->id,
        'reference' => 'MEDMART-PS-1',
        'gateway' => 'paystack',
        'status' => PaymentStatus::Unpaid,
        'amount' => 2500,
    ])->saveQuietly();

    Http::fake(['api.paystack.co/transaction/verify/*' => Http::response(['status' => true, 'data' => [
        'status' => 'success',
        'reference' => 'MEDMART-PS-1',
        'amount' => 250000,
        'currency' => 'NGN',
        'fees' => 3500,
    ]])]);

    $body = json_encode(['event' => 'charge.success', 'data' => ['reference' => 'MEDMART-PS-1', 'metadata' => ['payment_type' => 'order']]]);
    $secret = (string) config('services.paystack.secret_key');

    $this->call('POST', '/api/v1/webhooks/paystack', [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_PAYSTACK_SIGNATURE' => hash_hmac('sha512', $body, $secret),
    ], $body)->assertOk();

    expect($payment->fresh()->status)->toBe(PaymentStatus::Paid)
        ->and($payment->fresh()->gateway_reference)->toBe('MEDMART-PS-1');
});
