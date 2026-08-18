<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreSettlementAccountRequest;
use App\Http\Resources\Staff\SettlementAccountResource;
use App\Models\Bank;
use App\Models\SettlementAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettlementAccountController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $account = SettlementAccount::where('pharmacy_id', $request->user()->pharmacy_id)
            ->with('bank')
            ->latest()
            ->first();

        return response()->json($account ? new SettlementAccountResource($account) : null);
    }

    public function store(StoreSettlementAccountRequest $request): JsonResponse
    {
        $bank = Bank::findOrFail($request->integer('bank_id'));

        $account = SettlementAccount::create([
            'pharmacy_id' => $request->user()->pharmacy_id,
            'bank_id' => $bank->id,
            'bank_name' => $bank->name,
            'account_number' => $request->string('account_number')->toString(),
            'account_name' => $request->string('account_name')->toString(),
            'status' => 'pending',
        ]);

        return response()->json(new SettlementAccountResource($account->load('bank')), 201);
    }
}