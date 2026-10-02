<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Http\Resources\LedgerEntryResource;
use App\Settlement\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class LedgerAdminController extends Controller
{
    public function index(Request $request, int $pharmacy): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'type' => ['sometimes', 'string', Rule::in(LedgerEntryType::values())],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $entries = LedgerEntry::query()
            ->where('pharmacy_id', $pharmacy)
            ->when(isset($filters['type']), static fn ($q) => $q->where('type', $filters['type']))
            ->orderByDesc('id')
            ->paginate((int) ($filters['per_page'] ?? 25));

        return LedgerEntryResource::collection($entries);
    }
}
