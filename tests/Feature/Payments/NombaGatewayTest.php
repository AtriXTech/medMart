<?php

declare(strict_types=1);

use App\Payments\Data\PayoutDestination;
use App\Payments\Data\TransferRequest;
use App\Payments\Enums\ChargeState;
use App\Payments\Enums\TransferState;
use App\Payments\Exceptions\GatewayException;
use App\Payments\Gateways\Nomba\NombaGateway;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

function nombaFake(array $routes = []): void
{
    Http::fake(array_merge([
        '*/v1/auth/token/issue' => Http::response([
            'code' => '00',
            'description' => 'Success',
            'data' => [
                'access_token' => 'token-1',
                'refresh_token' => 'refresh-1',
                'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800),
            ],
        ]),
    ], $routes));
}

function nombaGateway(): NombaGateway
{
    return app(NombaGateway::class);
}

function transferRequest(): TransferRequest
{
    return new TransferRequest('STL-REF-1', 350000, 'Payout', new PayoutDestination('0123456789', 'JOHN DOE', '058'));
}

beforeEach(fn () => Cache::flush());

it('is configured from the environment and reports sandbox mode', function () {
    expect(nombaGateway()->isConfigured())->toBeTrue()
        ->and(nombaGateway()->isTestMode())->toBeTrue()
        ->and(nombaGateway()->missingConfiguration())->toBe([]);
});

it('reports missing configuration', function () {
    config(['services.nomba.client_secret' => '']);

    expect(nombaGateway()->isConfigured())->toBeFalse()
        ->and(nombaGateway()->missingConfiguration())->toBe(['NOMBA_CLIENT_SECRET']);
});

it('creates a checkout order with the amount in naira and the account header', function () {
    nombaFake(['*/v1/checkout/order' => Http::response([
        'code' => '00',
        'data' => ['checkoutLink' => 'https://checkout.nomba.com/pay/abc', 'orderReference' => 'nomba-order-id'],
    ])]);

    $session = nombaGateway()->initializeCharge('MEDMART-1-abc', 150050, 'buyer@example.com', ['order_id' => 7, 'tags' => ['a']], 'https://app.test/cb');

    expect($session->authorizationUrl)->toBe('https://checkout.nomba.com/pay/abc')
        ->and($session->reference)->toBe('MEDMART-1-abc');

    Http::assertSent(function (Request $request) {
        if (! str_ends_with($request->url(), '/v1/checkout/order')) {
            return false;
        }

        $order = $request['order'];

        return $request->hasHeader('accountId', 'acc-test')
            && $request->hasHeader('Authorization', 'Bearer token-1')
            && $order['amount'] === '1500.50'
            && $order['orderReference'] === 'MEDMART-1-abc'
            && $order['currency'] === 'NGN'
            && $order['callbackUrl'] === 'https://app.test/cb'
            && $order['orderMetaData'] === ['order_id' => '7', 'tags' => '["a"]'];
    });
});

it('reuses the cached access token across requests', function () {
    nombaFake(['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '10.00', 'currency' => 'NGN']])]);

    nombaGateway()->balanceKobo();
    nombaGateway()->balanceKobo();

    Http::assertSentCount(3);
});

it('refreshes the token once when Nomba answers 401', function () {
    Http::fake([
        '*/v1/auth/token/issue' => Http::sequence()
            ->push(['code' => '00', 'data' => ['access_token' => 'old', 'refresh_token' => 'r', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]])
            ->push(['code' => '00', 'data' => ['access_token' => 'new', 'refresh_token' => 'r2', 'expiresAt' => gmdate('Y-m-d\TH:i:s\Z', time() + 1800)]]),
        '*/v1/auth/token/refresh' => Http::response([], 401),
        '*/v1/accounts/balance' => Http::sequence()
            ->push(['code' => '401'], 401)
            ->push(['code' => '00', 'data' => ['amount' => '5.00', 'currency' => 'NGN']]),
    ]);

    expect(nombaGateway()->balanceKobo())->toBe(500);
});

it('verifies a successful checkout and converts amount and fee to kobo', function () {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => [
            'id' => 'WEB-ONLINE_C-123',
            'status' => 'SUCCESS',
            'type' => 'online_checkout',
            'amount' => '1500.50',
            'onlineCheckoutAmount' => '1500.50',
            'onlineCheckoutOrderReference' => 'MEDMART-1-abc',
            'fixedCharge' => '21.01',
            'currency' => 'NGN',
        ],
    ])]);

    $charge = nombaGateway()->verifyCharge('MEDMART-1-abc');

    expect($charge->state)->toBe(ChargeState::Success)
        ->and($charge->amountKobo)->toBe(150050)
        ->and($charge->feeKobo)->toBe(2101)
        ->and($charge->gatewayReference)->toBe('WEB-ONLINE_C-123')
        ->and($charge->currency)->toBe('NGN');

    Http::assertSent(fn (Request $request) => str_contains($request->url(), 'orderReference=MEDMART-1-abc'));
});

it('treats an unknown order reference as pending rather than failed', function () {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response(['code' => '01', 'description' => 'Transaction not found'], 200)]);

    expect(nombaGateway()->verifyCharge('MEDMART-9-zzz')->state)->toBe(ChargeState::Pending);
});

it('does not trust a success status that belongs to a different order reference', function () {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'x', 'status' => 'SUCCESS', 'type' => 'online_checkout', 'amount' => '10', 'onlineCheckoutOrderReference' => 'OTHER'],
    ])]);

    expect(nombaGateway()->verifyCharge('MEDMART-1-abc')->state)->toBe(ChargeState::Failed);
});

it('reports a successful transfer', function () {
    nombaFake(['*/v2/transfers/bank' => Http::response([
        'code' => '200',
        'description' => 'SUCCESS',
        'data' => ['id' => 'API-TRANSFER-1', 'status' => 'SUCCESS', 'amount' => '3500.0'],
    ])]);

    $result = nombaGateway()->initiateTransfer(transferRequest());

    expect($result->state)->toBe(TransferState::Success)
        ->and($result->gatewayReference)->toBe('API-TRANSFER-1')
        ->and($result->amountKobo)->toBe(350000);

    Http::assertSent(function (Request $request) {
        return str_ends_with($request->url(), '/v2/transfers/bank')
            && $request['merchantTxRef'] === 'STL-REF-1'
            && $request['amount'] === 3500.0
            && $request['bankCode'] === '058'
            && $request['accountName'] === 'JOHN DOE';
    });
});

it('keeps a pending-billing transfer in flight', function () {
    nombaFake(['*/v2/transfers/bank' => Http::response([
        'code' => '201',
        'description' => 'PROCESSING',
        'status' => false,
        'data' => ['id' => 'API-TRANSFER-2', 'status' => 'PENDING_BILLING'],
    ], 201)]);

    $result = nombaGateway()->initiateTransfer(transferRequest());

    expect($result->state)->toBe(TransferState::Pending)
        ->and($result->gatewayReference)->toBe('API-TRANSFER-2');
});

it('never treats a synchronous FAILED description as a final failure', function () {
    nombaFake(['*/v2/transfers/bank' => Http::response(['code' => '200', 'description' => 'FAILED', 'status' => false], 200)]);

    expect(nombaGateway()->initiateTransfer(transferRequest())->state)->toBe(TransferState::Pending);
});

it('classifies insufficient balance and definitive rejections', function () {
    nombaFake(['*/v2/transfers/bank' => Http::response(['code' => '400', 'description' => 'INSUFFICIENT_BALANCE'], 400)]);

    expect(fn () => nombaGateway()->initiateTransfer(transferRequest()))
        ->toThrow(fn (GatewayException $e) => $e->isInsufficientBalance());

    nombaFake(['*/v2/transfers/bank' => Http::response(['code' => '400', 'description' => 'ACCOUNT_NOT_FOUND'], 400)]);

    try {
        nombaGateway()->initiateTransfer(transferRequest());
        $this->fail('Expected an exception');
    } catch (GatewayException $exception) {
        expect($exception->isAmbiguous())->toBeFalse();
    }
});

it('treats unknown client errors and server errors on transfers as ambiguous', function () {
    foreach ([[400, 'SOMETHING_NEW'], [500, 'ERROR']] as [$status, $description]) {
        nombaFake(['*/v2/transfers/bank' => Http::response(['code' => (string) $status, 'description' => $description], $status)]);

        try {
            nombaGateway()->initiateTransfer(transferRequest());
            $this->fail('Expected an exception');
        } catch (GatewayException $exception) {
            expect($exception->isAmbiguous())->toBeTrue();
        }
    }
});

it('maps requery statuses for transfers', function (string $status, ?TransferState $expected) {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response([
        'code' => '00',
        'data' => ['id' => 'API-TRANSFER-3', 'status' => $status, 'amount' => 3500, 'merchantTxRef' => 'STL-REF-1'],
    ])]);

    expect(nombaGateway()->verifyTransfer('STL-REF-1')?->state)->toBe($expected);
})->with([
    ['SUCCESS', TransferState::Success],
    ['REFUND', TransferState::Reversed],
    ['CANCELLED', TransferState::ActionRequired],
    ['PAYMENT_FAILED', TransferState::Pending],
    ['PENDING_BILLING', TransferState::Pending],
    ['REVERSED_BY_VENDOR', TransferState::Pending],
]);

it('returns null when the transfer reference is unknown', function () {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response(['code' => '01', 'description' => 'Transaction not found'], 200)]);

    expect(nombaGateway()->verifyTransfer('UNKNOWN'))->toBeNull();
});

it('rejects a requery response for a different merchant reference', function () {
    nombaFake(['*/v1/transactions/accounts/single*' => Http::response(['code' => '00', 'data' => ['status' => 'SUCCESS', 'merchantTxRef' => 'OTHER']])]);

    expect(fn () => nombaGateway()->verifyTransfer('STL-REF-1'))->toThrow(GatewayException::class);
});

it('reads the parent account balance in kobo', function () {
    nombaFake(['*/v1/accounts/balance' => Http::response(['code' => '00', 'data' => ['amount' => '1234.56', 'currency' => 'NGN']])]);

    expect(nombaGateway()->balanceKobo())->toBe(123456);
});

it('lists banks from either response shape', function (array $data) {
    nombaFake(['*/v1/transfers/banks' => Http::response(['code' => '00', 'data' => $data])]);

    expect(nombaGateway()->listBanks())->toBe([['code' => '058', 'name' => 'GTBank']]);
})->with([
    'results wrapper' => [['results' => [['code' => '058', 'name' => 'GTBank']]]],
    'bare list' => [[['code' => '058', 'name' => 'GTBank']]],
]);

it('adopts the bank-registered account name when it still matches', function () {
    nombaFake(['*/v1/transfers/bank/lookup' => Http::response(['code' => '00', 'data' => ['accountNumber' => '0123456789', 'accountName' => 'DOE JOHN A']])]);

    $prepared = nombaGateway()->prepareDestination(new PayoutDestination('0123456789', 'JOHN A DOE', '058'));

    expect($prepared->accountName)->toBe('DOE JOHN A');
});

it('refuses a destination whose registered name no longer matches', function () {
    nombaFake(['*/v1/transfers/bank/lookup' => Http::response(['code' => '00', 'data' => ['accountNumber' => '0123456789', 'accountName' => 'COMPLETELY DIFFERENT PERSON']])]);

    expect(fn () => nombaGateway()->prepareDestination(new PayoutDestination('0123456789', 'JOHN A DOE', '058')))
        ->toThrow(GatewayException::class);
});

it('refunds a checkout using the stored Nomba transaction id', function () {
    nombaFake(['*/v1/checkout/refund' => Http::response(['code' => '00', 'data' => ['success' => true]])]);

    nombaGateway()->refundCharge('MEDMART-1-abc', 'WEB-ONLINE_C-123', 'note');

    Http::assertSent(fn (Request $request) => str_ends_with($request->url(), '/v1/checkout/refund') && $request['transactionId'] === 'WEB-ONLINE_C-123');
});
