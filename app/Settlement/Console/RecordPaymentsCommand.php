<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Models\Payment;
use App\Settlement\Jobs\RecordPaymentSettlementJob;
use App\Settlement\Services\PaymentSettlementRecorder;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

final class RecordPaymentsCommand extends Command
{
    protected $signature = 'settlement:record-payments {--since= : Only payments paid on or after this date} {--sync : Record inline instead of queueing}';

    protected $description = 'Record paid order payments that have not yet entered the settlement ledger';

    public function handle(PaymentSettlementRecorder $recorder): int
    {
        $since = $this->option('since')
            ? Carbon::parse((string) $this->option('since'))
            : now()->subDays((int) config('settlement.payment.backfill_window_days'));

        $count = 0;
        $failed = 0;
        $sync = (bool) $this->option('sync');

        Payment::query()
            ->withoutGlobalScopes()
            ->whereIn('status', (array) config('settlement.payment.paid_statuses'))
            ->whereNull('settlement_status')
            ->where(static function (Builder $query) use ($since): void {
                $query->where('paid_at', '>=', $since)
                    ->orWhere(static function (Builder $inner) use ($since): void {
                        $inner->whereNull('paid_at')->where('updated_at', '>=', $since);
                    });
            })
            ->orderBy('id')
            ->chunkById(200, function ($payments) use ($sync, $recorder, &$count, &$failed): void {
                foreach ($payments as $payment) {
                    if ($sync) {
                        try {
                            $recorder->record((int) $payment->id);
                            $count++;
                        } catch (\Throwable $exception) {
                            $failed++;
                            report($exception);
                        }

                        continue;
                    }

                    RecordPaymentSettlementJob::dispatch((int) $payment->id);
                    $count++;
                }
            });

        $this->info('Processed: ' . $count . ($failed > 0 ? ', failed: ' . $failed : ''));

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
