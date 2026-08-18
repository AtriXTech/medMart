<?php

declare(strict_types=1);

namespace App\Services\Pharmacy;

use App\Models\Customer;
use App\Models\CustomerPharmacyLink;
use App\Models\Pharmacy;
use App\Models\PharmacyCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PharmacyLinkService
{
    public function __construct(private readonly PharmacyCodeService $pharmacyCodeService)
    {
    }

    public function linkCustomerToPharmacy(Customer $customer, PharmacyCode $pharmacyCode): CustomerPharmacyLink
    {
        return DB::transaction(function () use ($customer, $pharmacyCode) {
            $existing = CustomerPharmacyLink::where('customer_id', $customer->id)
                ->where('pharmacy_id', $pharmacyCode->pharmacy_id)
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'pharmacy_code' => ['You are already linked to this pharmacy.'],
                ]);
            }

            CustomerPharmacyLink::where('customer_id', $customer->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $link = CustomerPharmacyLink::create([
                'customer_id' => $customer->id,
                'pharmacy_id' => $pharmacyCode->pharmacy_id,
                'is_active' => true,
            ]);

            $this->pharmacyCodeService->redeem($pharmacyCode);

            return $link->load('pharmacy');
        });
    }

    public function switchActivePharmacy(Customer $customer, Pharmacy $pharmacy): CustomerPharmacyLink
    {
        return DB::transaction(function () use ($customer, $pharmacy) {
            $link = CustomerPharmacyLink::where('customer_id', $customer->id)
                ->where('pharmacy_id', $pharmacy->id)
                ->first();

            if (! $link) {
                throw ValidationException::withMessages([
                    'pharmacy_id' => ['You are not linked to this pharmacy.'],
                ]);
            }

            CustomerPharmacyLink::where('customer_id', $customer->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $link->update(['is_active' => true]);

            return $link->load('pharmacy');
        });
    }
}
