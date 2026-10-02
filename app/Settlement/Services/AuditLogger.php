<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Settlement\Models\SettlementAuditLog;
use Illuminate\Database\Eloquent\Model;

final class AuditLogger
{
    public function record(
        string $action,
        int|string|null $pharmacyId = null,
        ?Model $subject = null,
        ?int $actorId = null,
        array $meta = [],
    ): void {
        SettlementAuditLog::query()->create([
            'action' => $action,
            'pharmacy_id' => $pharmacyId === null ? null : (int) $pharmacyId,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'actor_id' => $actorId,
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
            'meta' => $meta === [] ? null : $meta,
        ]);
    }
}
