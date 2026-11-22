<?php

declare(strict_types=1);

use App\Filament\Pages\PaymentGateways;
use App\Models\User;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\GatewaySettings;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

function superAdmin(): User
{
    return User::factory()->create(['is_super_admin' => true, 'pharmacy_id' => null, 'role' => null]);
}

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

it('is only accessible to super admins', function () {
    $this->actingAs(superAdmin());
    expect(PaymentGateways::canAccess())->toBeTrue();

    auth()->logout();
    $this->actingAs(User::factory()->create(['is_super_admin' => false]));
    expect(PaymentGateways::canAccess())->toBeFalse();
});

it('renders both gateways with their status', function () {
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->assertSuccessful()
        ->assertSee('Paystack')
        ->assertSee('Nomba')
        ->assertSee('Customer payments and subscriptions')
        ->assertSee('Pharmacy payouts and settlements')
        ->assertSee('/api/v1/webhooks/nomba');
});

it('lists the missing environment variables for an unconfigured gateway', function () {
    config(['services.nomba.client_secret' => '', 'services.nomba.webhook_signature_key' => '']);
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->assertSee('NOMBA_CLIENT_SECRET')
        ->assertSee('NOMBA_WEBHOOK_SIGNATURE_KEY');
});

it('switches a role to Nomba with a reason and writes an audit trail', function () {
    $admin = superAdmin();
    $this->actingAs($admin);

    Livewire::test(PaymentGateways::class)
        ->callAction('switchGateway', ['reason' => 'Paystack settlement delays'], ['role' => 'payouts', 'gateway' => 'nomba'])
        ->assertHasNoActionErrors()
        ->assertNotified();

    expect(app(GatewaySettings::class)->active(GatewayRole::Payouts))->toBe(Gateway::Nomba)
        ->and(app(GatewaySettings::class)->active(GatewayRole::Collections))->toBe(Gateway::Paystack);

    Livewire::test(PaymentGateways::class)->assertSee('Paystack settlement delays')->assertSee($admin->name);
});

it('requires a reason to switch', function () {
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->callAction('switchGateway', ['reason' => ''], ['role' => 'collections', 'gateway' => 'nomba'])
        ->assertHasActionErrors(['reason' => 'required']);

    expect(app(GatewaySettings::class)->active(GatewayRole::Collections))->toBe(Gateway::Paystack);
});

it('refuses to switch to a gateway that is not configured', function () {
    config(['services.nomba.client_id' => '']);
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->callAction('switchGateway', ['reason' => 'try'], ['role' => 'collections', 'gateway' => 'nomba']);

    expect(app(GatewaySettings::class)->active(GatewayRole::Collections))->toBe(Gateway::Paystack);
});

it('disables an inactive gateway but not one in use', function () {
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->callAction('toggleGateway', [], ['gateway' => 'paystack', 'enable' => false]);
    expect(app(GatewaySettings::class)->isEnabled(Gateway::Paystack))->toBeTrue();

    Livewire::test(PaymentGateways::class)
        ->callAction('toggleGateway', [], ['gateway' => 'nomba', 'enable' => false]);
    expect(app(GatewaySettings::class)->isEnabled(Gateway::Nomba))->toBeFalse();
});

it('tests a connection and shows the balance', function () {
    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '2500.00', 'currency' => 'NGN']]),
    ]);
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->call('testConnection', 'nomba')
        ->assertSee('Connected in')
        ->assertSee('2,500.00');
});

it('reports a failed connection test without throwing', function () {
    Http::fake(['*' => Http::response([], 500)]);
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->call('testConnection', 'nomba')
        ->assertSuccessful()
        ->assertSee('unavailable');
});

it('syncs bank codes from the gateway and reports unmatched banks', function () {
    \App\Models\Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);
    \App\Models\Bank::query()->create(['name' => 'Opay', 'code' => '999992', 'is_active' => true]);
    \App\Models\Bank::query()->create(['name' => 'Mystery Microfinance', 'code' => '777', 'is_active' => true]);

    Http::fake([
        '*/v1/auth/token/issue' => Http::response(['code' => '00', 'data' => ['access_token' => 't', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/transfers/banks' => Http::response(['code' => '00', 'data' => ['results' => [
            ['code' => '058', 'name' => 'GTBank'],
            ['code' => '058', 'name' => 'Guaranty Trust Bank'],
            ['code' => '305', 'name' => 'Opay'],
        ]]]),
    ]);
    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->callAction('syncBankCodes', [], ['gateway' => 'nomba'])
        ->assertNotified('Bank codes synced');

    $codes = \App\Models\BankGatewayCode::query()->where('gateway', 'nomba')->pluck('code', 'bank_id');

    expect($codes)->toHaveCount(2)
        ->and($codes[\App\Models\Bank::query()->where('name', 'Opay')->value('id')])->toBe('305');
});

it('is served inside the admin panel for super admins and blocked for everyone else', function () {
    $this->get('/admin/payment-gateways')->assertRedirect();

    $this->actingAs(User::factory()->create(['is_super_admin' => false]))
        ->get('/admin/payment-gateways')
        ->assertForbidden();

    auth()->logout();

    $this->actingAs(superAdmin())
        ->get('/admin/payment-gateways')
        ->assertOk()
        ->assertSee('Payment Gateways')
        ->assertSee('Switch to Nomba');
});

it('counts in-flight transfers per gateway and treats legacy rows without a gateway as Paystack', function () {
    $pharmacy = \App\Models\Pharmacy::factory()->create();
    $bank = \App\Models\Bank::query()->create(['name' => 'Guaranty Trust Bank', 'code' => '058', 'is_active' => true]);

    $account = new \App\Models\SettlementAccount();
    $account->forceFill([
        'pharmacy_id' => $pharmacy->id,
        'bank_id' => $bank->id,
        'bank_name' => 'Guaranty Trust Bank',
        'account_number' => '0123456789',
        'account_name' => 'GREEN CROSS PHARMACY',
        'status' => \App\Settlement\Enums\AccountStatus::Approved,
    ])->save();

    $row = fn (string $reference, string $status, ?string $gateway) => [
        'pharmacy_id' => $pharmacy->id,
        'settlement_account_id' => $account->id,
        'reference' => $reference,
        'amount' => 10,
        'status' => $status,
        'gross_kobo' => 1000,
        'fee_kobo' => 0,
        'adjustment_kobo' => 0,
        'net_kobo' => 1000,
        'attempts' => 1,
        'gateway' => $gateway,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    \Illuminate\Support\Facades\DB::table('settlements')->insert([
        $row('S1', 'processing', null),
        $row('S2', 'processing', 'paystack'),
        $row('S3', 'processing', 'nomba'),
        $row('S4', 'success', 'nomba'),
    ]);

    $this->actingAs(superAdmin());

    Livewire::test(PaymentGateways::class)
        ->assertViewHas('cards', function (array $cards): bool {
            $counts = collect($cards)->pluck('in_flight', 'key')->all();

            return $counts === ['paystack' => 2, 'nomba' => 1];
        });
});
