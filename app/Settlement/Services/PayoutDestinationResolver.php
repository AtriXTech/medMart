<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Bank;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Payments\Contracts\PaymentGateway;
use App\Payments\Data\PayoutDestination;
use App\Payments\Enums\Gateway;
use App\Payments\Support\BankCodeResolver;
use App\Settlement\Enums\AccountStatus;

final class PayoutDestinationResolver
{
    public function __construct(private readonly BankCodeResolver $codes)
    {
    }

    public function forSettlement(Settlement $settlement, PaymentGateway $gateway): ?PayoutDestination
    {
        $account = SettlementAccount::query()->withoutGlobalScopes()->find($settlement->settlement_account_id);

        if ($account === null || $account->status !== AccountStatus::Approved) {
            return null;
        }

        return $this->forAccount($account, $gateway);
    }

    public function forAccount(SettlementAccount $account, PaymentGateway $gateway): PayoutDestination
    {
        $bank = Bank::query()->findOrFail($account->bank_id);

        $destination = new PayoutDestination(
            (string) $account->account_number,
            (string) $account->account_name,
            $this->codes->codeFor($bank, $gateway->gateway()),
            $gateway->gateway() === Gateway::Paystack ? ($account->paystack_recipient_code ?: null) : null,
        );

        $prepared = $gateway->prepareDestination($destination);

        if ($gateway->gateway() === Gateway::Paystack && $prepared->recipientCode !== null && $prepared->recipientCode !== $account->paystack_recipient_code) {
            SettlementAccount::query()->withoutGlobalScopes()->whereKey($account->id)->update(['paystack_recipient_code' => $prepared->recipientCode]);
        }

        return $prepared;
    }
}
