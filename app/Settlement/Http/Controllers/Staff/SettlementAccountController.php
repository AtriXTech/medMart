<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SettlementAccount;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Http\Controllers\Concerns\ResolvesPharmacy;
use App\Settlement\Http\Requests\StoreSettlementAccountRequest;
use App\Settlement\Http\Resources\SettlementAccountResource;
use App\Settlement\Services\SettlementAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SettlementAccountController extends Controller
{
    use ResolvesPharmacy;

    public function __construct(private readonly SettlementAccountService $accounts)
    {
    }

    public function show(Request $request): JsonResponse
    {
        $accounts = SettlementAccount::query()
            ->withoutGlobalScopes()
            ->where('pharmacy_id', $this->pharmacyId($request))
            ->orderByDesc('id')
            ->limit(25)
            ->get();

        $approved = $accounts->first(static fn (SettlementAccount $account): bool => $account->status === AccountStatus::Approved);
        $pending = $accounts->first(static fn (SettlementAccount $account): bool => $account->status === AccountStatus::Pending);
        $latest = $accounts->first();
        $rejected = $latest !== null && $latest->status === AccountStatus::Rejected ? $latest : null;

        return response()->json([
            'data' => [
                'approved' => $approved ? (new SettlementAccountResource($approved))->resolve($request) : null,
                'pending' => $pending ? (new SettlementAccountResource($pending))->resolve($request) : null,
                'rejected' => $rejected ? (new SettlementAccountResource($rejected))->resolve($request) : null,
            ],
        ]);
    }

    public function store(StoreSettlementAccountRequest $request): JsonResponse
    {
        $account = $this->accounts->submit(
            $request->user(),
            (int) $request->validated('bank_id'),
            (string) $request->validated('account_number')
        );

        return response()->json([
            'message' => 'Settlement account submitted for review.',
            'data' => (new SettlementAccountResource($account))->resolve($request),
        ], 201);
    }
}
