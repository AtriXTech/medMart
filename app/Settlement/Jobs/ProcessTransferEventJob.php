<?php

declare(strict_types=1);

namespace App\Settlement\Jobs;

use App\Settlement\Services\TransferEventProcessor;
use App\Settlement\Support\ConfiguresQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ProcessTransferEventJob implements ShouldQueue
{
    use ConfiguresQueue, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 6;

    public int $timeout = 60;

    public function __construct(
        public readonly string $event,
        public readonly array $data,
    ) {
        $this->configureQueue();
    }

    public function backoff(): array
    {
        return [10, 60, 300, 900, 1800];
    }

    public function handle(TransferEventProcessor $processor): void
    {
        $processor->handleWebhook($this->event, $this->data);
    }
}
