<?php

declare(strict_types=1);

namespace App\Settlement\Support;

trait ConfiguresQueue
{
    protected function configureQueue(): void
    {
        $this->onQueue((string) config('settlement.queue.name', 'default'));

        $connection = config('settlement.queue.connection');

        if (is_string($connection) && $connection !== '') {
            $this->onConnection($connection);
        }
    }
}
