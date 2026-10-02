<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Settlement\Services\SettlementBatchService;
use Illuminate\Console\Command;

final class CreateSettlementsCommand extends Command
{
    protected $signature = 'settlement:create-settlements {--pharmacy= : Settle a single pharmacy by ID} {--force : Ignore the pharmacy payout schedule}';

    protected $description = 'Batch eligible payments into settlements and dispatch transfers';

    public function handle(SettlementBatchService $batch): int
    {
        $pharmacy = $this->option('pharmacy');

        if ($pharmacy !== null && $pharmacy !== '') {
            $settlement = $batch->createForPharmacy((int) $pharmacy, ! (bool) $this->option('force'));

            $this->info($settlement !== null ? 'Created ' . $settlement->reference : 'Nothing to settle.');

            return self::SUCCESS;
        }

        $summary = $batch->runAll();

        $this->info(sprintf(
            'Created: %d, skipped: %d, failed: %d%s',
            $summary['created'],
            $summary['skipped'],
            $summary['failed'],
            $summary['paused'] ? ' (payouts paused)' : ''
        ));

        return $summary['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
