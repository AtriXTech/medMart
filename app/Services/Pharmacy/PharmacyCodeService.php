<?php

declare(strict_types=1);

namespace App\Services\Pharmacy;

use App\Models\Pharmacy;
use App\Models\PharmacyCode;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PharmacyCodeService
{
    public function generate(Pharmacy $pharmacy, array $data, ?User $createdBy = null): PharmacyCode
    {
        return PharmacyCode::create([
            'pharmacy_id' => $pharmacy->id,
            'code' => $data['code'] ?? $this->generateUniqueCode(),
            'expires_at' => $data['expires_at'] ?? null,
            'max_uses' => $data['max_uses'] ?? null,
            'uses_count' => 0,
            'is_active' => true,
            'created_by_id' => $createdBy?->id,
        ]);
    }

    public function validateCode(string $code): PharmacyCode
    {
        $pharmacyCode = PharmacyCode::withoutGlobalScope('pharmacy')
            ->where('code', strtoupper($code))
            ->first();

        if (! $pharmacyCode || ! $pharmacyCode->isValid()) {
            throw ValidationException::withMessages([
                'pharmacy_code' => ['This pharmacy code is invalid or has expired.'],
            ]);
        }

        return $pharmacyCode;
    }

    public function redeem(PharmacyCode $pharmacyCode): void
    {
        $pharmacyCode->increment('uses_count');

        if ($pharmacyCode->max_uses !== null && $pharmacyCode->uses_count >= $pharmacyCode->max_uses) {
            $pharmacyCode->update(['is_active' => false]);
        }
    }

    private function generateUniqueCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = '';

            for ($i = 0; $i < 8; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (PharmacyCode::withoutGlobalScope('pharmacy')->where('code', $code)->exists());

        return $code;
    }
}
