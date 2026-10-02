<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Http\Controllers\Concerns\ResolvesPharmacy;
use App\Settlement\Http\Resources\LedgerEntryResource;
use App\Settlement\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class LedgerController extends Controller
{
    use ResolvesPharmacy;

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'type' => ['sometimes', 'string', Rule::in(LedgerEntryType::values())],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $entries = LedgerEntry::query()
            ->where('pharmacy_id', $this->pharmacyId($request))
            ->when(isset($filters['type']), static fn ($q) => $q->where('type', $filters['type']))
            ->when(isset($filters['from']), static fn ($q) => $q->where('created_at', '>=', $filters['from']))
            ->when(isset($filters['to']), static fn ($q) => $q->where('created_at', '<=', $filters['to'] . ' 23:59:59'))
            ->orderByDesc('id')
            ->paginate((int) ($filters['per_page'] ?? 25));

        return LedgerEntryResource::collection($entries);
    }
}
