<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettlementAccount;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Http\Requests\ReasonRequest;
use App\Settlement\Http\Resources\SettlementAccountResource;
use App\Settlement\Services\SettlementAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class AccountReviewController extends Controller
{
    public function __construct(private readonly SettlementAccountService $accounts)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(AccountStatus::values())],
            'pharmacy_id' => ['sometimes', 'integer'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $accounts = SettlementAccount::query()
            ->withoutGlobalScopes()
            ->with('pharmacy:id,name')
            ->when(isset($filters['status']), static fn ($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['pharmacy_id']), static fn ($q) => $q->where('pharmacy_id', (int) $filters['pharmacy_id']))
            ->orderByDesc('id')
            ->paginate((int) ($filters['per_page'] ?? 20));

        return SettlementAccountResource::collection($accounts);
    }

    public function approve(Request $request, int $account): JsonResponse
    {
        $approved = $this->accounts->approve($account, $request->user());

        return response()->json([
            'message' => 'Settlement account approved.',
            'data' => (new SettlementAccountResource($approved))->resolve($request),
        ]);
    }

    public function reject(ReasonRequest $request, int $account): JsonResponse
    {
        $rejected = $this->accounts->reject($account, (string) $request->validated('reason'), $request->user());

        return response()->json([
            'message' => 'Settlement account rejected.',
            'data' => (new SettlementAccountResource($rejected))->resolve($request),
        ]);
    }
}
