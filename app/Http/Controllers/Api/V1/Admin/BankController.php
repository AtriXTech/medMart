<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\BankResource;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BankController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BankResource::collection(Bank::orderBy('name')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:banks,code'],
        ]);

        $bank = Bank::create([
            'name' => $request->string('name')->toString(),
            'code' => $request->string('code')->toString(),
            'is_active' => true,
        ]);

        return response()->json(new BankResource($bank), 201);
    }

    public function update(Request $request, Bank $bank): JsonResponse
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:10', 'unique:banks,code,' . $bank->id],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $bank->update($request->only(['name', 'code', 'is_active']));

        return response()->json(new BankResource($bank));
    }

    public function destroy(Bank $bank): JsonResponse
    {
        $bank->delete();

        return response()->json(['message' => 'Bank deleted.']);
    }
}