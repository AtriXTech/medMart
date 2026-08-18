<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\SettlementAccountResource;
use App\Models\SettlementAccount;
use App\Notifications\SettlementAccountApproved;
use App\Notifications\SettlementAccountRejected;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class SettlementAccountController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $accounts = SettlementAccount::with('bank', 'pharmacy')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest()
            ->paginate(20);

        return SettlementAccountResource::collection($accounts);
    }

    public function pending(): AnonymousResourceCollection
    {
        $accounts = SettlementAccount::with('bank', 'pharmacy')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return SettlementAccountResource::collection($accounts);
    }

    public function approve(SettlementAccount $account): JsonResponse
    {
        $account->update([
            'status' => 'active',
            'reviewed_by_id' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $owner = $account->pharmacy->users()->where('role', 'owner')->first();
        if ($owner) {
            $owner->notify(new SettlementAccountApproved($account));
        }

        return response()->json(new SettlementAccountResource($account->load('bank')));
    }

    public function reject(Request $request, SettlementAccount $account): JsonResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $account->update([
            'status' => 'rejected',
            'reviewed_by_id' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->string('reason')->toString(),
        ]);

        $owner = $account->pharmacy->users()->where('role', 'owner')->first();
        if ($owner) {
            $owner->notify(new SettlementAccountRejected($account));
        }

        return response()->json(new SettlementAccountResource($account->load('bank')));
    }
}