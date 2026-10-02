<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Settlement\Services\ReconciliationService;
use Illuminate\Console\Command;

final class ReconcileLedgerCommand extends Command
{
    protected $signature = 'settlement:reconcile-ledger';

    protected $description = 'Compare pharmacy ledger balances against payments and settlements';

    public function handle(ReconciliationService $reconciliation): int
    {
        $mismatches = $reconciliation->reconcileLedger();

        if ($mismatches === []) {
            $this->info('Ledger is consistent.');

            return self::SUCCESS;
        }

        $this->table(
            ['Pharmacy', 'Expected (kobo)', 'Ledger (kobo)', 'Difference (kobo)'],
            array_map(static fn (array $row): array => [
                $row['pharmacy_id'],
                $row['expected_kobo'],
                $row['ledger_kobo'],
                $row['difference_kobo'],
            ], $mismatches)
        );

        return self::FAILURE;
    }
}
