<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Concerns;

use App\Settlement\Exceptions\SettlementException;
use Illuminate\Http\Request;

trait ResolvesPharmacy
{
    protected function pharmacyId(Request $request): int
    {
        $id = $request->user()?->pharmacy_id;

        if ($id === null) {
            throw SettlementException::forbidden('Your account is not linked to a pharmacy.');
        }

        return (int) $id;
    }
}
