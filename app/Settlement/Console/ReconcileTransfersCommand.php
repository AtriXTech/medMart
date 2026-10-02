<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Settlement\Services\ReconciliationService;
use Illuminate\Console\Command;

final class ReconcileTransfersCommand extends Command
{
    protected $signature = 'settlement:reconcile-transfers';

    protected $description = 'Verify stuck transfers with Paystack and recover unsubmitted settlements';

    public function handle(ReconciliationService $reconciliation): int
    {
        $result = $reconciliation->reconcileTransfers();

        $this->info(sprintf(
            'Verified: %d, reset: %d, redispatched: %d, errors: %d',
            $result['verified'],
            $result['reset'],
            $result['redispatched'],
            $result['errors']
        ));

        return self::SUCCESS;
    }
}
