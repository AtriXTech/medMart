<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Settlement;
use App\Settlement\Enums\SettlementStatus;

final class TransferEventProcessor
{
    public function __construct(
        private readonly SettlementLifecycleService $lifecycle,
        private readonly Notifier $notifier,
    ) {
    }

    public function handleWebhook(string $event, array $data): void
    {
        $status = match ($event) {
            'transfer.success' => 'success',
            'transfer.failed' => 'failed',
            'transfer.reversed' => 'reversed',
            default => null,
        };

        $reference = (string) ($data['reference'] ?? '');

        if ($status === null || $reference === '') {
            return;
        }

        $settlement = Settlement::query()->where('reference', $reference)->first();

        if ($settlement === null) {
            return;
        }

        $this->apply($settlement, $status, $data);
    }

    public function apply(Settlement $settlement, string $status, array $data): void
    {
        $status = strtolower($status);

        if (isset($data['amount']) && (int) $data['amount'] !== (int) $settlement->net_kobo) {
            $this->notifier->ops('Transfer amount mismatch', [
                'Reference: ' . $settlement->reference,
                'Expected (kobo): ' . (int) $settlement->net_kobo,
                'Received (kobo): ' . (int) $data['amount'],
            ], 'critical');

            return;
        }

        if (isset($data['currency']) && strtoupper((string) $data['currency']) !== strtoupper((string) config('settlement.paystack.currency'))) {
            $this->notifier->ops('Transfer currency mismatch', [
                'Reference: ' . $settlement->reference,
                'Currency: ' . (string) $data['currency'],
            ], 'critical');

            return;
        }

        $code = isset($data['transfer_code']) ? (string) $data['transfer_code'] : null;

        match ($status) {
            'success' => $this->lifecycle->markSucceeded($settlement->id, $code),
            'failed' => $this->lifecycle->markFailed($settlement->id, SettlementStatus::Failed, $this->reason($data, $status)),
            'reversed' => $this->lifecycle->markFailed($settlement->id, SettlementStatus::Reversed, $this->reason($data, $status)),
            'otp' => $this->lifecycle->hold($settlement->id, 'otp_required', null, [SettlementStatus::Pending, SettlementStatus::Processing]),
            default => null,
        };
    }

    private function reason(array $data, string $status): string
    {
        $reason = (string) ($data['complete_message'] ?? $data['gateway_response'] ?? '');

        return $reason !== '' ? mb_substr($reason, 0, 255) : 'Transfer ' . $status . ' at gateway.';
    }
}
