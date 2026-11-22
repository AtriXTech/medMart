<?php

declare(strict_types=1);

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Pages\Controls;
use App\Filament\Widgets\PaymentsTable;
use App\Filament\Widgets\SettlementAccountsTable;
use App\Filament\Widgets\SettlementsTable;
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
use App\Settlement\Services\BalanceService;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;

function consoleAdmin(): User
{
    return User::factory()->create(['is_super_admin' => true, 'pharmacy_id' => null, 'role' => null]);
}

function consoleNombaAuth(): array
{
    return ['*/v1/auth/token/issue' => Http::response([
        'code' => '00',
        'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)],
    ])];
}

function consoleSettlement(string $status = 'settled', ?string $gateway = 'nomba', string $reference = 'STL-CON-1'): Settlement
{
    $pharmacy = Pharmacy::query()->first() ?? Pharmacy::factory()->create(['name' => 'Green Cross Pharmacy']);
    $bank = Bank::query()->first() ?? Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);
    $account = SettlementAccount::query()->withoutGlobalScopes()->first();

    if ($account === null) {
        $account = new SettlementAccount();
        $account->forceFill([
            'pharmacy_id' => $pharmacy->id,
            'bank_id' => $bank->id,
            'bank_name' => 'Guaranty Trust Bank',
            'account_number' => '0123456789',
            'account_name' => 'GREEN CROSS PHARMACY',
            'status' => AccountStatus::Approved,
        ])->save();
    }

    $settlement = new Settlement();
    $settlement->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'settlement_account_id' => $account->id,
        'reference' => $reference,
        'amount' => 3500,
        'status' => SettlementStatus::from($status),
        'gross_kobo' => 360000,
        'fee_kobo' => 10000,
        'adjustment_kobo' => 0,
        'net_kobo' => 350000,
        'attempts' => 1,
        'gateway' => $gateway,
        'gateway_reference' => 'API-TRANSFER-1',
    ])->save();

    return $settlement->fresh();
}

beforeEach(function () {
    Cache::flush();
    Filament::setCurrentPanel('admin');
});

it('computes the pharmacy balance with settled settlements instead of crashing', function () {
    $settlement = consoleSettlement('settled');
    consoleSettlement('processing', 'nomba', 'STL-CON-2');

    $balance = app(BalanceService::class)->forPharmacy((int) $settlement->pharmacy_id);

    expect($balance['paid_out_kobo'])->toBe(350000)
        ->and($balance['in_transit_kobo'])->toBe(350000);
});

it('migrates legacy success settlements to the settled status', function () {
    $settlement = consoleSettlement('settled');
    DB::table('settlements')->where('id', $settlement->id)->update(['status' => 'success']);

    $migration = require base_path('database/migrations/2026_11_22_000001_rename_settlement_success_status_to_settled.php');
    $migration->up();

    expect(DB::table('settlements')->where('id', $settlement->id)->value('status'))->toBe('settled');

    $migration->up();

    expect(DB::table('settlements')->where('status', 'success')->count())->toBe(0);
});

it('loads every settlement console page for a super admin', function (string $path) {
    Http::fake(['*' => Http::response(['status' => true, 'data' => [['currency' => 'NGN', 'balance' => 5000000]]])]);
    consoleSettlement('settled');
    $this->actingAs(consoleAdmin());

    $this->get($path)->assertOk();
})->with([
    '/admin/controls',
    '/admin/payments',
    '/admin/pharmacy-settlement',
    '/admin/settlements',
    '/admin/settlement-accounts',
    '/admin/payment-gateways',
]);

it('shows the active payout gateway, its mode and balance on the Controls page', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    Http::fake(consoleNombaAuth() + ['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '1500.00', 'currency' => 'NGN']])]);
    $this->actingAs(consoleAdmin());

    Livewire::test(Controls::class)
        ->assertSee('Nomba TEST mode')
        ->assertSee('Nomba payout balance')
        ->assertSee('1,500.00')
        ->assertDontSee('Paystack');
});

it('keeps showing Paystack on the Controls page while it is the payout gateway', function () {
    Http::fake(['api.paystack.co/balance' => Http::response(['status' => true, 'data' => [['currency' => 'NGN', 'balance' => 250000]]])]);
    $this->actingAs(consoleAdmin());

    Livewire::test(Controls::class)->assertSee('Paystack TEST mode')->assertSee('2,500.00');
});

it('shows a gateway failure on the Controls page instead of crashing and refreshes on demand', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    Http::fake(consoleNombaAuth() + ['*/v1/accounts/balance' => Http::sequence()
        ->push([], 500)
        ->push(['code' => '00', 'data' => ['amount' => '90.00', 'currency' => 'NGN']]),
    ]);
    $this->actingAs(consoleAdmin());

    Livewire::test(Controls::class)
        ->assertSuccessful()
        ->assertSee('Unavailable')
        ->callAction('refreshGateway')
        ->assertSee('90.00');
});

it('accepts the settled status filter and rejects the retired success value on the console API', function () {
    $settlement = consoleSettlement('settled');
    Sanctum::actingAs(consoleAdmin(), ['*']);

    $this->getJson('/api/v1/admin/settlement/settlements?status=settled')
        ->assertOk()
        ->assertJsonPath('data.0.status', 'settled')
        ->assertJsonPath('data.0.gateway', 'nomba')
        ->assertJsonPath('data.0.id', $settlement->id);

    $this->getJson('/api/v1/admin/settlement/settlements?status=success')->assertStatus(422);
});

it('reports the payout gateway on the console gateway endpoint', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    Http::fake(consoleNombaAuth() + ['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '10.00', 'currency' => 'NGN']])]);
    Sanctum::actingAs(consoleAdmin(), ['*']);

    $this->getJson('/api/v1/admin/settlement/gateway')
        ->assertOk()
        ->assertJsonPath('data.gateway', 'nomba')
        ->assertJsonPath('data.gateway_label', 'Nomba')
        ->assertJsonPath('data.mode', 'test')
        ->assertJsonPath('data.balance_kobo', 1000);
});

it('ships the console with the settled status wired in and no retired success status', function () {
    $console = file_get_contents(base_path('app/Settlement/Resources/views/admin/console.blade.php'));

    expect($console)->toContain('<option value="settled">Settled</option>')
        ->toContain("case 'settled':")
        ->not->toContain('<option value="success">')
        ->not->toContain("case 'success':")
        ->toContain('gatewayLabel');
});

it('lists the gateway on settlements and filters by it', function () {
    consoleSettlement('settled', 'nomba', 'STL-NOM-1');
    consoleSettlement('settled', 'paystack', 'STL-PAY-1');
    consoleSettlement('settled', null, 'STL-LEG-1');
    $this->actingAs(consoleAdmin());

    $component = Livewire::test(SettlementsTable::class)->assertSee('STL-NOM-1')->assertSee('Nomba')->assertSee('Paystack');

    $component->filterTable('gateway', 'nomba')->assertSee('STL-NOM-1')->assertDontSee('STL-PAY-1')->assertDontSee('STL-LEG-1');
    $component->filterTable('gateway', 'paystack')->assertSee('STL-PAY-1')->assertSee('STL-LEG-1')->assertDontSee('STL-NOM-1');
});

function consolePaidPayment(string $gateway, ?string $gatewayReference): Payment
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
        'reference' => 'MEDMART-CON-' . $gateway,
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

it('shows the gateway on payments and refunds through the payment gateway', function () {
    $payment = consolePaidPayment('nomba', 'WEB-ONLINE_C-9');
    Http::fake(consoleNombaAuth() + ['*/v1/checkout/refund' => Http::response(['code' => '00', 'data' => ['success' => true]])]);
    $this->actingAs(consoleAdmin());

    Livewire::test(PaymentsTable::class)
        ->assertSee('Nomba')
        ->callTableAction('refund', $payment, ['reason' => 'Customer request', 'via_gateway' => true])
        ->assertNotified('Payment refunded.');

    expect($payment->fresh()->settlement_status)->toBe('refunded');
    Http::assertSent(fn ($request) => str_ends_with($request->url(), '/v1/checkout/refund') && $request['transactionId'] === 'WEB-ONLINE_C-9');
});

it('reports a rejected gateway refund as a notification instead of a server error', function () {
    $payment = consolePaidPayment('nomba', 'WEB-ONLINE_C-9');
    Http::fake(consoleNombaAuth() + ['*/v1/checkout/refund' => Http::response(['code' => '400', 'description' => 'Refund not supported in sandbox'], 400)]);
    $this->actingAs(consoleAdmin());

    Livewire::test(PaymentsTable::class)
        ->callTableAction('refund', $payment, ['reason' => 'Customer request', 'via_gateway' => true])
        ->assertNotified('Unable to refund payment');

    expect($payment->fresh()->settlement_status)->toBe('pending');
});

it('reports a gateway failure while approving a settlement account as a notification', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'test', true);
    $pharmacy = Pharmacy::factory()->create();
    $bank = Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);

    $account = new SettlementAccount();
    $account->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'bank_id' => $bank->id,
        'bank_name' => 'Guaranty Trust Bank',
        'account_number' => '0123456789',
        'account_name' => 'GREEN CROSS PHARMACY',
        'status' => AccountStatus::Pending,
    ])->save();

    Http::fake(['*' => Http::response([], 500)]);
    $this->actingAs(consoleAdmin());

    Livewire::test(SettlementAccountsTable::class)
        ->callTableAction('approve', $account)
        ->assertNotified('Unable to approve account');

    expect($account->fresh()->status)->toBe(AccountStatus::Pending);
});
