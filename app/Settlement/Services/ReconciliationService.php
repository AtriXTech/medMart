<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Settlement;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Jobs\InitiateTransferJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ReconciliationService
{
    public function __construct(
        private readonly TransferService $transfers,
        private readonly Notifier $notifier,
    ) {
    }

    public function reconcileTransfers(): array
    {
        $result = ['verified' => 0, 'reset' => 0, 'errors' => 0, 'redispatched' => 0];

        $cutoff = now()->subMinutes((int) config('settlement.reconciliation.stuck_after_minutes'));
        $alertCutoff = now()->subHours((int) config('settlement.reconciliation.processing_alert_after_hours'));

        Settlement::query()
            ->where('status', SettlementStatus::Processing->value)
            ->where(static function (Builder $query) use ($cutoff): void {
                $query->whereNull('last_attempt_at')->orWhere('last_attempt_at', '<=', $cutoff);
            })
            ->orderBy('id')
            ->chunkById(50, function ($settlements) use (&$result, $alertCutoff): void {
                foreach ($settlements as $settlement) {
                    try {
                        $outcome = $this->transfers->verifyAndApply($settlement);

                        if ($outcome === 'not_found') {
                            if ($this->transfers->resetUnsubmitted($settlement->id)) {
                                $result['reset']++;
                            }

                            continue;
                        }

                        $result['verified']++;

                        if ($outcome === 'pending' && $settlement->last_attempt_at !== null && $settlement->last_attempt_at->lte($alertCutoff)) {
                            $this->alertLongProcessing($settlement);
                        }
                    } catch (PaystackException $exception) {
                        $result['errors']++;
                        Log::warning('[settlement] Reconciliation lookup failed', [
                            'settlement_id' => $settlement->id,
                            'message' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        $staleCutoff = now()->subMinutes((int) config('settlement.reconciliation.pending_stale_after_minutes'));

        Settlement::query()
            ->where('status', SettlementStatus::Pending->value)
            ->where('updated_at', '<=', $staleCutoff)
            ->orderBy('id')
            ->chunkById(100, function ($settlements) use (&$result): void {
                foreach ($settlements as $settlement) {
                    if (Cache::add('settlement:redispatch:' . $settlement->id, 1, 3600)) {
                        InitiateTransferJob::dispatch($settlement->id);
                        $result['redispatched']++;
                    }
                }
            });

        return $result;
    }

    public function reconcileLedger(): array
    {
        $ledger = $this->totals(DB::table('pharmacy_ledger_entries'), 'amount_kobo');

        $adjustments = $this->totals(
            DB::table('pharmacy_ledger_entries')->where('type', 'adjustment'),
            'amount_kobo'
        );

        $payments = $this->totals(
            DB::table('payments')->whereNotNull('settlement_status')->where('settlement_status', '!=', 'refunded'),
            'net_kobo'
        );

        $settlements = $this->totals(
            DB::table('settlements')->whereIn('status', SettlementStatus::countedValues()),
            'net_kobo'
        );

        $pharmacyIds = collect(array_keys($ledger))
            ->merge(array_keys($payments))
            ->merge(array_keys($settlements))
            ->unique()
            ->values();

        $mismatches = [];

        foreach ($pharmacyIds as $pharmacyId) {
            $expected = ($payments[$pharmacyId] ?? 0) - ($settlements[$pharmacyId] ?? 0) + ($adjustments[$pharmacyId] ?? 0);
            $actual = $ledger[$pharmacyId] ?? 0;

            if ($expected !== $actual) {
                $mismatches[] = [
                    'pharmacy_id' => (int) $pharmacyId,
                    'expected_kobo' => $expected,
                    'ledger_kobo' => $actual,
                    'difference_kobo' => $actual - $expected,
                ];
            }
        }

        if ($mismatches !== []) {
            $lines = array_map(
                static fn (array $row): string => 'Pharmacy ' . $row['pharmacy_id'] . ': expected ' . $row['expected_kobo'] . ', ledger ' . $row['ledger_kobo'] . ' (kobo)',
                array_slice($mismatches, 0, 25)
            );

            $this->notifier->ops('Ledger reconciliation mismatch', $lines, 'critical');
        }

        return $mismatches;
    }

    private function totals($query, string $column): array
    {
        $rows = $query
            ->selectRaw('pharmacy_id, COALESCE(SUM(' . $column . '), 0) as total')
            ->groupBy('pharmacy_id')
            ->pluck('total', 'pharmacy_id');

        $totals = [];

        foreach ($rows as $pharmacyId => $total) {
            $totals[(int) $pharmacyId] = (int) $total;
        }

        return $totals;
    }

    private function alertLongProcessing(Settlement $settlement): void
    {
        if (! Cache::add('settlement:processing-alert:' . $settlement->id, 1, 86400)) {
            return;
        }

        $this->notifier->ops('Transfer has been processing for a long time', [
            'Reference: ' . $settlement->reference,
            'Pharmacy ID: ' . $settlement->pharmacy_id,
        ], 'warning');
    }
}
