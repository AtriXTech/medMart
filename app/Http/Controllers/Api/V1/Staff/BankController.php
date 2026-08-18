<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\BankResource;
use App\Models\Bank;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BankController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BankResource::collection(
            Bank::where('is_active', true)->orderBy('name')->get()
        );
    }
}