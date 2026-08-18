<?php

declare(strict_types=1);

namespace App\Services\Prescriptions;

use App\Enums\PrescriptionStatus;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Models\User;
use App\Notifications\PrescriptionReviewed;
use Illuminate\Http\UploadedFile;

class PrescriptionService
{
    public function upload(Customer $customer, Pharmacy $pharmacy, UploadedFile $file): Prescription
    {
        $path = $file->store('prescriptions', 'local');

        return Prescription::create([
            'pharmacy_id' => $pharmacy->id,
            'customer_id' => $customer->id,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'status' => PrescriptionStatus::Pending,
        ]);
    }

    public function approve(Prescription $prescription, User $staff): Prescription
    {
        $prescription->update([
            'status' => PrescriptionStatus::Approved,
            'reviewed_by_id' => $staff->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $prescription->customer->notify(new PrescriptionReviewed($prescription));

        return $prescription;
    }

    public function reject(Prescription $prescription, User $staff, string $reason): Prescription
    {
        $prescription->update([
            'status' => PrescriptionStatus::Rejected,
            'reviewed_by_id' => $staff->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        $prescription->customer->notify(new PrescriptionReviewed($prescription));

        return $prescription;
    }

    public function customerHasApprovedPrescription(Customer $customer, Pharmacy $pharmacy): bool
    {
        return Prescription::where('customer_id', $customer->id)
            ->where('pharmacy_id', $pharmacy->id)
            ->where('status', PrescriptionStatus::Approved)
            ->exists();
    }
}
