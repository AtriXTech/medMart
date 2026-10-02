<?php

declare(strict_types=1);

namespace App\Settlement\Jobs;

use App\Settlement\Exceptions\InsufficientBalanceException;
use App\Settlement\Services\Notifier;
use App\Settlement\Services\TransferService;
use App\Settlement\Support\ConfiguresQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class InitiateTransferJob implements ShouldQueue
{
    use ConfiguresQueue, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 8;

    public int $timeout = 90;

    public function __construct(public readonly int $settlementId)
    {
        $this->configureQueue();
        $this->afterCommit();
    }

    public function backoff(): array
    {
        return [30, 120, 300, 900, 1800];
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('settlement-transfer-' . $this->settlementId))
                ->releaseAfter(30)
                ->expireAfter(300),
        ];
    }

    public function handle(TransferService $transfers): void
    {
        try {
            $transfers->initiate($this->settlementId);
        } catch (InsufficientBalanceException) {
            $this->release(300);
        }
    }

    public function failed(Throwable $exception): void
    {
        app(Notifier::class)->ops(
            'Transfer job failed permanently',
            ['Settlement ID: ' . $this->settlementId, 'Error: ' . $exception->getMessage()],
            'error'
        );
    }
}
