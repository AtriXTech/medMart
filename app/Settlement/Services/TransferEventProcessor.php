<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Settlement;
use App\Payments\Data\TransferResult;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\TransferState;
use App\Payments\GatewayManager;
use App\Settlement\Enums\SettlementStatus;

final class TransferEventProcessor
{
    public function __construct(
        private readonly SettlementLifecycleService $lifecycle,
        private readonly Notifier $notifier,
        private readonly GatewayManager $gateways,
    ) {
    }

    public function handleWebhook(Gateway $gateway, string $event, array $data): void
    {
        $driver = $this->gateways->for($gateway);
        $parsed = $driver->parseTransferWebhook($event, $data);

        if ($parsed === null) {
            return;
        }

        $settlement = Settlement::query()->where('reference', $parsed->reference)->first();

        if ($settlement === null || $settlement->gateway !== null && $settlement->gateway !== $gateway->value) {
            return;
        }

        $result = $parsed->result ?? $driver->verifyTransfer($parsed->reference);

        if ($result === null) {
            return;
        }

        $this->apply($settlement, $result);
    }

    public function apply(Settlement $settlement, TransferResult $result): void
    {
        if ($result->amountKobo !== null && $result->amountKobo !== (int) $settlement->net_kobo) {
            $this->notifier->ops('Transfer amount mismatch', [
                'Reference: ' . $settlement->reference,
                'Expected (kobo): ' . (int) $settlement->net_kobo,
                'Received (kobo): ' . $result->amountKobo,
            ], 'critical');

            return;
        }

        if ($result->currency !== null && $result->currency !== strtoupper((string) config('settlement.currency'))) {
            $this->notifier->ops('Transfer currency mismatch', [
                'Reference: ' . $settlement->reference,
                'Currency: ' . $result->currency,
            ], 'critical');

            return;
        }

        $code = $result->gatewayReference;

        match ($result->state) {
            TransferState::Success => $this->lifecycle->markSucceeded($settlement->id, $code),
            TransferState::Failed => $this->lifecycle->markFailed($settlement->id, SettlementStatus::Failed, $this->reason($result, 'failed')),
            TransferState::Reversed => $this->lifecycle->markFailed($settlement->id, SettlementStatus::Reversed, $this->reason($result, 'reversed')),
            TransferState::OtpRequired => $this->lifecycle->hold($settlement->id, 'otp_required', null, [SettlementStatus::Pending, SettlementStatus::Processing]),
            TransferState::ActionRequired => $this->lifecycle->hold($settlement->id, 'gateway_action_required', null, [SettlementStatus::Pending, SettlementStatus::Processing]),
            TransferState::Pending => null,
        };
    }

    private function reason(TransferResult $result, string $status): string
    {
        $reason = (string) $result->message;

        return $reason !== '' ? mb_substr($reason, 0, 255) : 'Transfer ' . $status . ' at gateway.';
    }
}
