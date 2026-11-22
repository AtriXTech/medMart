<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Payment;
use App\Models\Pharmacy;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Gateway\PaystackClient;
use Illuminate\Support\Facades\DB;

final class PaymentRefundService
{
    private const REFUNDABLE = ['pending', 'eligible', 'held', 'settled'];

    public function __construct(
        private readonly PaystackClient $paystack,
        private readonly LedgerService $ledger,
        private readonly AuditLogger $audit,
    ) {
    }

    public function refund(int $paymentId, string $reason, ?int $actorId, bool $viaGateway = true): Payment
    {
        $payment = Payment::query()->withoutGlobalScopes()->find($paymentId)
            ?? throw SettlementException::notFound('Payment not found.');

        $this->assertRefundable($payment);

        if ($viaGateway) {
            $this->refundAtGateway($payment, $reason);
        }

        return DB::transaction(function () use ($payment, $reason, $actorId, $viaGateway): Payment {
            Pharmacy::query()->withoutGlobalScopes()->whereKey($payment->pharmacy_id)->lockForUpdate()->first();

            $locked = Payment::query()->withoutGlobalScopes()->whereKey($payment->id)->lockForUpdate()->first()
                ?? throw SettlementException::notFound('Payment not found.');

            $this->assertRefundable($locked);

            $gross = (int) $locked->gross_kobo;
            $net = (int) $locked->net_kobo;
            $refs = ['payment_id' => $locked->id, 'order_id' => $locked->order_id];

            $this->ledger->post($locked->pharmacy_id, LedgerEntryType::RefundDebit, -$gross, 'payment:' . $locked->id . ':refund_debit', $refs, ['reason' => $reason]);

            if ($gross - $net > 0) {
                $this->ledger->post($locked->pharmacy_id, LedgerEntryType::FeeReversal, $gross - $net, 'payment:' . $locked->id . ':fee_reversal', $refs);
            }

            $previous = (string) $locked->getAttribute('settlement_status');

            $locked->forceFill([
                'settlement_status' => PaymentSettlementStatus::Refunded->value,
                'refunded_at' => now(),
                'hold_reason' => mb_substr($reason, 0, 255),
                'eligible_at' => null,
            ])->saveQuietly();

            $this->audit->record('payment.refunded', $locked->pharmacy_id, $locked, $actorId, [
                'reason' => $reason,
                'previous_status' => $previous,
                'via_gateway' => $viaGateway,
                'net_kobo' => $net,
            ]);

            return $locked;
        });
    }

    private function assertRefundable(Payment $payment): void
    {
        $status = $payment->getAttribute('settlement_status');

        if ($status === null) {
            throw SettlementException::invalidState('This payment has not been recorded for settlement yet.');
        }

        if ($status === PaymentSettlementStatus::Refunded->value) {
            throw SettlementException::invalidState('This payment has already been refunded.');
        }

        if (! in_array($status, self::REFUNDABLE, true)) {
            throw SettlementException::invalidState('This payment cannot be refunded in its current state.');
        }
    }

    private function refundAtGateway(Payment $payment, string $reason): void
    {
        try {
            $this->paystack->refund((string) ($payment->paystack_reference ?: $payment->reference), mb_substr($reason, 0, 200));
        } catch (PaystackException $exception) {
            if (! $exception->isAmbiguous() && $exception->isAlreadyRefunded()) {
                return;
            }

            throw $exception;
        }
    }
}
