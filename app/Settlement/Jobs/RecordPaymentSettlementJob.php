<?php

declare(strict_types=1);

namespace App\Settlement\Jobs;

use App\Settlement\Services\Notifier;
use App\Settlement\Services\PaymentSettlementRecorder;
use App\Settlement\Support\ConfiguresQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class RecordPaymentSettlementJob implements ShouldQueue
{
    use ConfiguresQueue, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 6;

    public int $timeout = 60;

    public function __construct(public readonly int $paymentId)
    {
        $this->configureQueue();
        $this->afterCommit();
    }

    public function backoff(): array
    {
        return [15, 60, 300, 900, 1800];
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('settlement-record-' . $this->paymentId))
                ->releaseAfter(30)
                ->expireAfter(180),
        ];
    }

    public function handle(PaymentSettlementRecorder $recorder): void
    {
        $recorder->record($this->paymentId);
    }

    public function failed(Throwable $exception): void
    {
        app(Notifier::class)->ops(
            'Payment could not be recorded for settlement',
            ['Payment ID: ' . $this->paymentId, 'Error: ' . $exception->getMessage()],
            'error'
        );
    }
}
