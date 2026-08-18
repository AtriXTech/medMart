<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PharmacyAccessSuspendedException extends Exception
{
    protected $message = 'This pharmacy is currently suspended. Please contact support.';

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 403);
    }
}
