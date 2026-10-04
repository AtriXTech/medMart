<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Bank;
use App\Models\Pharmacy;
use App\Models\SettlementAccount;
use App\Models\User;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Support\NameMatcher;
use Illuminate\Support\Facades\DB;

final class SettlementAccountService
{
    public function __construct(
        private readonly PaystackClient $paystack,
        private readonly AuditLogger $audit,
        private readonly Notifier $notifier,
    ) {
    }

    public function submit(User $user, int $bankId, string $accountNumber): SettlementAccount
    {
        $pharmacyId = $user->pharmacy_id;

        if ($pharmacyId === null) {
            throw SettlementException::forbidden('Your account is not linked to a pharmacy.');
        }

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->findOrFail($pharmacyId);
        $bank = Bank::query()->find($bankId) ?? throw SettlementException::notFound('Bank not found.');

        $bankCode = $this->bankCode($bank);
        $bankName = (string) $bank->getAttribute((string) config('settlement.bank.name_attribute'));

        $alreadyActive = SettlementAccount::query()
            ->withoutGlobalScopes()
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', AccountStatus::Approved->value)
            ->where('bank_id', $bank->getKey())
            ->where('account_number', $accountNumber)
            ->exists();

        if ($alreadyActive) {
            throw SettlementException::invalidState('This account is already your active settlement account.');
        }

        try {
            $resolved = $this->paystack->resolveAccount($accountNumber, $bankCode);
        } catch (PaystackException $exception) {
            if ($exception->isAmbiguous()) {
                throw $exception;
            }

            throw SettlementException::accountNotResolved();
        }

        $accountName = trim((string) ($resolved['account_name'] ?? ''));

        if ($accountName === '') {
            throw SettlementException::accountNotResolved();
        }

        $score = NameMatcher::score((string) $pharmacy->name, $accountName);

        $hadApproved = SettlementAccount::query()
            ->withoutGlobalScopes()
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', AccountStatus::Approved->value)
            ->exists();

        $account = DB::transaction(function () use ($pharmacyId, $bank, $bankName, $accountNumber, $accountName, $score, $user): SettlementAccount {
            SettlementAccount::query()
                ->withoutGlobalScopes()
                ->where('pharmacy_id', $pharmacyId)
                ->where('status', AccountStatus::Pending->value)
                ->update(['status' => AccountStatus::Superseded->value]);

            $account = SettlementAccount::query()->create([
                'pharmacy_id' => $pharmacyId,
                'bank_id' => $bank->getKey(),
                'bank_name' => $bankName,
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'status' => AccountStatus::Pending,
                'name_match_score' => $score,
            ]);

            $this->audit->record('settlement_account.submitted', $pharmacyId, $account, $user->getKey(), [
                'bank_id' => $bank->getKey(),
                'name_match_score' => $score,
            ]);

            return $account;
        });

        $this->notifier->ops('Settlement account awaiting review', [
            'Pharmacy: ' . $pharmacy->name . ' (ID ' . $pharmacy->id . ')',
            'Account name: ' . $accountName,
            'Name match score: ' . $score,
        ], 'notice');

        if ($hadApproved) {
            $this->notifier->pharmacy($pharmacy, 'A change to your settlement account was requested', [
                'A request was made to change your settlement account.',
                'If this was not you, contact support immediately. Payouts to the new account only begin after it is reviewed and approved.',
            ]);
        }

        return $account;
    }

    public function approve(int $accountId, User $admin): SettlementAccount
    {
        $account = SettlementAccount::query()->withoutGlobalScopes()->find($accountId)
            ?? throw SettlementException::notFound('Settlement account not found.');

        if ($account->status !== AccountStatus::Pending) {
            throw SettlementException::invalidState('Only pending accounts can be approved.');
        }

        $bank = Bank::query()->find($account->bank_id) ?? throw SettlementException::notFound('Bank not found.');

        try {
            $recipient = $this->paystack->createRecipient(
                (string) $account->account_name,
                (string) $account->account_number,
                $this->bankCode($bank)
            );
        } catch (PaystackException $exception) {
            if ($exception->isAmbiguous()) {
                throw $exception;
            }

            throw SettlementException::invalidState('Paystack rejected this account: ' . $exception->getMessage());
        }

        $recipientCode = (string) ($recipient['recipient_code'] ?? '');

        if ($recipientCode === '') {
            throw SettlementException::invalidState('Paystack did not return a recipient code.');
        }

        DB::transaction(function () use ($account, $recipientCode, $admin): void {
            $locked = SettlementAccount::query()->withoutGlobalScopes()->whereKey($account->id)->lockForUpdate()->first();

            if ($locked === null || $locked->status !== AccountStatus::Pending) {
                throw SettlementException::invalidState('Only pending accounts can be approved.');
            }

            $previous = SettlementAccount::query()
                ->withoutGlobalScopes()
                ->where('pharmacy_id', $locked->pharmacy_id)
                ->where('status', AccountStatus::Approved->value)
                ->where('id', '!=', $locked->id);

            $replacing = $previous->exists();

            $previous->update(['status' => AccountStatus::Superseded->value]);

            $locked->forceFill([
                'status' => AccountStatus::Approved,
                'paystack_recipient_code' => $recipientCode,
                'reviewed_by_id' => $admin->getKey(),
                'reviewed_at' => now(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ])->save();

            if ($replacing) {
                Pharmacy::query()->withoutGlobalScopes()->whereKey($locked->pharmacy_id)->update([
                    'payout_hold_until' => now()->addHours((int) config('settlement.payout.account_change_cooldown_hours')),
                ]);
            }

            $this->audit->record('settlement_account.approved', $locked->pharmacy_id, $locked, $admin->getKey(), [
                'replaced_previous' => $replacing,
            ]);
        });

        $account->refresh();

        $this->notifier->pharmacy($account->pharmacy_id, 'Your settlement account was approved', [
            'Your settlement account ending in ' . substr((string) $account->account_number, -4) . ' has been approved.',
            'Payouts will be sent to this account.',
        ]);

        return $account;
    }

    public function reject(int $accountId, string $reason, User $admin): SettlementAccount
    {
        $account = SettlementAccount::query()->withoutGlobalScopes()->find($accountId)
            ?? throw SettlementException::notFound('Settlement account not found.');

        DB::transaction(function () use ($account, $reason, $admin): void {
            $locked = SettlementAccount::query()->withoutGlobalScopes()->whereKey($account->id)->lockForUpdate()->first();

            if ($locked === null || $locked->status !== AccountStatus::Pending) {
                throw SettlementException::invalidState('Only pending accounts can be rejected.');
            }

            $locked->forceFill([
                'status' => AccountStatus::Rejected,
                'rejection_reason' => mb_substr($reason, 0, 255),
                'reviewed_by_id' => $admin->getKey(),
                'reviewed_at' => now(),
            ])->save();

            $this->audit->record('settlement_account.rejected', $locked->pharmacy_id, $locked, $admin->getKey(), ['reason' => $reason]);
        });

        $account->refresh();

        $this->notifier->pharmacy($account->pharmacy_id, 'Your settlement account was not approved', [
            'Reason: ' . $reason,
            'Please submit a corrected settlement account.',
        ]);

        return $account;
    }

    private function bankCode(Bank $bank): string
    {
        $code = (string) $bank->getAttribute((string) config('settlement.bank.code_attribute'));

        if ($code === '') {
            throw SettlementException::invalidState('The selected bank has no gateway code configured.');
        }

        return $code;
    }
}
