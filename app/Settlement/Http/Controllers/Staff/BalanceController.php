<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Settlement\Http\Controllers\Concerns\ResolvesPharmacy;
use App\Settlement\Services\BalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BalanceController extends Controller
{
    use ResolvesPharmacy;

    public function __construct(private readonly BalanceService $balances)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->balances->forPharmacy($this->pharmacyId($request))]);
    }
}
