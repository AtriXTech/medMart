<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Jobs\InitiateTransferJob;

final class SettlementAdminService
{
    public function __construct(
        private readonly SettlementLifecycleService $lifecycle,
        private readonly SettlementBatchService $batch,
        private readonly TransferService $transfers,
        private readonly SettingsService $settings,
        private readonly AuditLogger $audit,
        private readonly Notifier $notifier,
    ) {
    }

    public function retry(int $settlementId, ?int $actorId): string
    {
        $settlement = Settlement::query()->find($settlementId)
            ?? throw SettlementException::notFound('Settlement not found.');

        $outcome = match ($settlement->status) {
            SettlementStatus::Pending => $this->redispatch($settlement),
            SettlementStatus::OnHold => $this->releaseHold($settlement, $actorId),
            SettlementStatus::Processing => $this->transfers->verifyAndApply($settlement) === 'not_found' ? $this->resetAndRetry($settlement) : 'verified',
            SettlementStatus::Failed, SettlementStatus::Reversed, SettlementStatus::Cancelled => $this->rebatch($settlement),
            SettlementStatus::Success => throw SettlementException::invalidState('This settlement was already paid.'),
        };

        $this->audit->record('settlement.retry_requested', $settlement->pharmacy_id, $settlement, $actorId, ['outcome' => $outcome]);

        return $outcome;
    }

    public function hold(int $settlementId, string $reason, int $actorId): void
    {
        $this->lifecycle->hold($settlementId, $reason, $actorId);
    }

    public function release(int $settlementId, int $actorId): void
    {
        $this->lifecycle->release($settlementId, $actorId);
    }

    public function cancel(int $settlementId, string $reason, int $actorId): void
    {
        $this->lifecycle->cancel($settlementId, $reason, $actorId);
    }

    public function runBatch(?int $pharmacyId, ?int $actorId): array
    {
        if ($pharmacyId !== null) {
            $settlement = $this->batch->createForPharmacy($pharmacyId, false);

            $result = [
                'created' => $settlement !== null ? 1 : 0,
                'reference' => $settlement?->reference,
                'reason' => $settlement === null ? $this->batch->diagnose($pharmacyId) : null,
            ];
        } else {
            $result = $this->batch->runAll();
        }

        $this->audit->record('settlement.batch_run', $pharmacyId, null, $actorId, $result);

        return $result;
    }

    public function pausePayouts(string $reason, int $actorId): void
    {
        $this->settings->pause($reason, $actorId);
        $this->audit->record('settlement.payouts_paused', null, null, $actorId, ['reason' => $reason]);
        $this->notifier->ops('Payouts paused', ['Reason: ' . $reason], 'critical');
    }

    public function resumePayouts(int $actorId): int
    {
        $this->settings->resume($actorId);
        $released = $this->lifecycle->releasePaused($actorId);
        $this->audit->record('settlement.payouts_resumed', null, null, $actorId, ['released' => $released]);
        $this->notifier->ops('Payouts resumed', ['Settlements released: ' . $released], 'notice');

        return $released;
    }

    public function updatePharmacyPayout(int $pharmacyId, array $values, int $actorId): Pharmacy
    {
        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($pharmacyId)
            ?? throw SettlementException::notFound('Pharmacy not found.');

        $update = [];

        if (array_key_exists('payout_hold', $values)) {
            if ((bool) $values['payout_hold']) {
                $update['payout_hold'] = true;
                $update['payout_hold_reason'] = isset($values['payout_hold_reason']) ? mb_substr((string) $values['payout_hold_reason'], 0, 255) : 'admin_hold';
            } else {
                $update['payout_hold'] = false;
                $update['payout_hold_reason'] = null;
                $update['payout_hold_until'] = null;
                $update['consecutive_payout_failures'] = 0;
            }
        }

        if (array_key_exists('commission_rate', $values)) {
            $update['commission_rate'] = $values['commission_rate'] === null ? null : (float) $values['commission_rate'];
        }

        if (array_key_exists('payout_schedule', $values)) {
            $update['payout_schedule'] = (string) $values['payout_schedule'];
        }

        if ($update !== []) {
            Pharmacy::query()->withoutGlobalScopes()->whereKey($pharmacyId)->update($update);
            $this->audit->record('pharmacy.payout_settings_updated', $pharmacyId, $pharmacy, $actorId, $update);
        }

        return $pharmacy->refresh();
    }

    private function redispatch(Settlement $settlement): string
    {
        InitiateTransferJob::dispatch($settlement->id);

        return 'dispatched';
    }

    private function releaseHold(Settlement $settlement, ?int $actorId): string
    {
        $this->lifecycle->release($settlement->id, $actorId);

        return 'released';
    }

    private function resetAndRetry(Settlement $settlement): string
    {
        return $this->transfers->resetUnsubmitted($settlement->id) ? 'dispatched' : 'unchanged';
    }

    private function rebatch(Settlement $settlement): string
    {
        $created = $this->batch->createForPharmacy((int) $settlement->pharmacy_id, false);

        return $created !== null ? 'rebatched' : 'nothing_to_settle';
    }
}