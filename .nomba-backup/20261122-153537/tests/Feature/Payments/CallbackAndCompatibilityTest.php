<?php

declare(strict_types=1);

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\GatewaySettings;
use App\Payments\Gateways\Nomba\NombaGateway;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Support\PaystackFeeEstimator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

function nombaTokenFake(): array
{
    return ['*/v1/auth/token/issue' => Http::response([
        'code' => '00',
        'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)],
    ])];
}

beforeEach(fn () => Cache::flush());

it('sends the configured https callback URL to Nomba', function () {
    Http::fake(nombaTokenFake() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/x']])]);

    app(NombaGateway::class)->initializeCharge('REF-1', 100000, 'a@b.co', [], 'https://app.example.com/customer/payment-callback');

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v1/checkout/order')
        && $r['order']['callbackUrl'] === 'https://app.example.com/customer/payment-callback');
});

it('omits a non-https callback URL instead of breaking checkout, and logs why', function () {
    Log::spy();
    Http::fake(nombaTokenFake() + ['*/v1/checkout/order' => Http::response(['code' => '00', 'data' => ['checkoutLink' => 'https://pay.nomba/x']])]);

    app(NombaGateway::class)->initializeCharge('REF-2', 100000, 'a@b.co', [], 'http://127.0.0.1:8000/payment-callback');

    Http::assertSent(fn (Request $r) => str_ends_with($r->url(), '/v1/checkout/order') && ! isset($r['order']['callbackUrl']));
    Log::shouldHaveReceived('warning')->once();
});

it('derives default callback URLs from APP_URL when nothing is configured', function () {
    $services = require base_path('config/services.php');

    expect($services['nomba']['callback_url'])->toEndWith('/payment-callback')
        ->and($services['nomba']['customer_callback_url'])->toEndWith('/customer/payment-callback');
});

it('warns on the admin screen when Nomba callbacks are not https', function () {
    config(['services.nomba.callback_url' => 'http://127.0.0.1:8000/payment-callback', 'services.nomba.customer_callback_url' => null]);

    $warnings = app(NombaGateway::class)->configurationWarnings();

    expect($warnings)->toHaveCount(2)
        ->and($warnings[0])->toContain('nomba.com');

    config(['services.nomba.callback_url' => 'https://a.test/payment-callback', 'services.nomba.customer_callback_url' => 'https://a.test/customer/payment-callback']);

    expect(app(NombaGateway::class)->configurationWarnings())->toBe([]);
});

it('keeps the legacy PaystackClient working for code that still uses it and follows the payout gateway', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    Http::fake(nombaTokenFake() + ['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '250.00', 'currency' => 'NGN']])]);

    $client = app(PaystackClient::class);

    expect($client->balance())->toBe(25000)
        ->and($client->isTestKey())->toBeTrue();
});

it('maps gateway failures to the legacy PaystackException', function () {
    Http::fake(['api.paystack.co/balance' => Http::response([], 500)]);

    try {
        app(PaystackClient::class)->balance();
        $this->fail('Expected an exception');
    } catch (PaystackException $exception) {
        expect($exception->isAmbiguous())->toBeTrue();
    }
});

it('keeps the legacy fee estimator available', function () {
    expect(app(PaystackFeeEstimator::class)->estimate(100000))->toBeInt()->toBeGreaterThan(0);
});

function callbackOrder(): array
{
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
        'reference' => 'MEDMART-CB-1',
        'gateway' => 'nomba',
        'status' => PaymentStatus::Unpaid,
        'amount' => 2500,
    ])->saveQuietly();

    return [$customer, $order, $payment];
}

function nombaPaidResponse(): array
{
    return nombaTokenFake() + ['*/v1/transactions/accounts/single*' => Http::response(['code' => '00', 'data' => [
        'id' => 'WEB-1',
        'status' => 'SUCCESS',
        'type' => 'online_checkout',
        'onlineCheckoutAmount' => '2500.00',
        'onlineCheckoutOrderReference' => 'MEDMART-CB-1',
        'currency' => 'NGN',
    ]])];
}

it('verifies a customer payment from the Nomba redirect parameters without needing the order id', function () {
    [$customer, , $payment] = callbackOrder();
    Sanctum::actingAs($customer, ['*']);
    Http::fake(nombaPaidResponse());

    $this->postJson('/api/v1/customer/payments/verify', ['orderReference' => 'MEDMART-CB-1'])
        ->assertOk()
        ->assertJsonPath('status', 'success');

    expect($payment->fresh()->status)->toBe(PaymentStatus::Paid);
});

it('reports pending to the callback page while Nomba has no record of the payment yet', function () {
    [$customer] = callbackOrder();
    Sanctum::actingAs($customer, ['*']);
    Http::fake(nombaTokenFake() + ['*/v1/transactions/accounts/single*' => Http::response(['code' => '01', 'description' => 'Transaction not found'])]);

    $this->postJson('/api/v1/customer/payments/verify', ['reference' => 'MEDMART-CB-1'])
        ->assertOk()
        ->assertJsonPath('status', 'pending');
});

it('never lets a customer verify somebody else\'s payment', function () {
    callbackOrder();
    Sanctum::actingAs(Customer::factory()->create(), ['*']);
    Http::fake(nombaPaidResponse());

    $this->postJson('/api/v1/customer/payments/verify', ['reference' => 'MEDMART-CB-1'])->assertNotFound();
});

it('serves the callback pages with Nomba parameter support', function () {
    $this->get('/payment-callback')->assertOk()->assertSee('orderReference', false)->assertSee('MAX_ATTEMPTS', false);
    $this->get('/customer/payment-callback')->assertOk()->assertSee('orderReference', false)->assertSee('MAX_ATTEMPTS', false);
});
