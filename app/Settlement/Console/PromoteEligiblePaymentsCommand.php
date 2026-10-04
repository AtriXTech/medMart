<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Settlement\Services\EligibilityService;
use Illuminate\Console\Command;

final class PromoteEligiblePaymentsCommand extends Command
{
    protected $signature = 'settlement:promote-eligible';

    protected $description = 'Mark payments whose hold window has elapsed as eligible for settlement';

    public function handle(EligibilityService $eligibility): int
    {
        $this->info('Promoted: ' . $eligibility->promote());

        return self::SUCCESS;
    }
}
