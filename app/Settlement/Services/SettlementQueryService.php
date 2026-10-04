<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Settlement;
use Illuminate\Pagination\LengthAwarePaginator;

final class SettlementQueryService
{
    public function paginate(array $filters, ?int $pharmacyId = null): LengthAwarePaginator
    {
        $pharmacyId ??= isset($filters['pharmacy_id']) ? (int) $filters['pharmacy_id'] : null;

        $query = Settlement::query()
            ->with(['settlementAccount:id,bank_name,account_number,account_name', 'pharmacy:id,name'])
            ->when($pharmacyId !== null, static fn ($q) => $q->where('pharmacy_id', $pharmacyId))
            ->when(! empty($filters['status']), static fn ($q) => $q->where('status', $filters['status']))
            ->when(! empty($filters['from']), static fn ($q) => $q->where('created_at', '>=', $filters['from']))
            ->when(! empty($filters['to']), static fn ($q) => $q->where('created_at', '<=', $filters['to'] . ' 23:59:59'))
            ->when(! empty($filters['search']), static fn ($q) => $q->where('reference', 'like', '%' . $filters['search'] . '%'))
            ->orderByDesc('id');

        $perPage = min(100, max(1, (int) ($filters['per_page'] ?? 20)));

        return $query->paginate($perPage);
    }

    public function find(int $id, ?int $pharmacyId = null): Settlement
    {
        $settlement = Settlement::query()
            ->with(['settlementAccount', 'pharmacy:id,name'])
            ->when($pharmacyId !== null, static fn ($q) => $q->where('pharmacy_id', $pharmacyId))
            ->findOrFail($id);

        $settlement->setRelation(
            'payments',
            $settlement->payments()->withoutGlobalScopes()->orderBy('payments.id')->get()
        );

        return $settlement;
    }
}
