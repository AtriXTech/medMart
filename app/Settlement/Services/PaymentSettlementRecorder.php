<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Settlement\Enums\FeeBearer;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Payments\Enums\Gateway;
use App\Payments\Exceptions\GatewayException;
use App\Payments\GatewayManager;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Support\Money;
use App\Settlement\Support\PaymentState;
use App\Settlement\Support\GatewayFeeEstimator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class PaymentSettlementRecorder
{
    public function __construct(
        private readonly GatewayManager $gateways,
        private readonly GatewayFeeEstimator $estimator,
        private readonly CommissionCalculator $commission,
        private readonly SettingsService $settings,
        private readonly LedgerService $ledger,
        private readonly AuditLogger $audit,
        private readonly Notifier $notifier,
    ) {
    }

    public function record(int $paymentId): ?Payment
    {
        $payment = Payment::query()->withoutGlobalScopes()->find($paymentId);

        if ($payment === null || ! PaymentState::isPaid($payment) || $payment->getAttribute('settlement_status') !== null) {
            return $payment;
        }

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($payment->pharmacy_id);

        if ($pharmacy === null) {
            throw SettlementException::invalidState('Pharmacy missing for payment ' . $payment->id . '.');
        }

        $order = Order::query()->withoutGlobalScopes()->find($payment->order_id);
        $gross = Money::toKobo($payment->amount);

        [$gatewayFee, $feeSource, $problem] = $this->inspectGateway($payment, $gross);

        $gatewayCharge = $this->settings->gatewayFeeBearer() === FeeBearer::Pharmacy ? min($gatewayFee, $gross) : 0;
        $platformFee = min($this->commission->platformFeeKobo($pharmacy, $gross), max(0, $gross - $gatewayCharge));
        $net = $gross - $gatewayCharge - $platformFee;

        [$status, $eligibleAt, $holdReason] = $this->position($order, $problem);

        $recorded = DB::transaction(function () use ($payment, $gross, $gatewayFee, $feeSource, $gatewayCharge, $platformFee, $net, $status, $eligibleAt, $holdReason): ?Payment {
            $locked = Payment::query()->withoutGlobalScopes()->whereKey($payment->id)->lockForUpdate()->first();

            if ($locked === null || $locked->getAttribute('settlement_status') !== null) {
                return $locked;
            }

            $locked->forceFill([
                'gross_kobo' => $gross,
                'gateway_fee_kobo' => $gatewayFee,
                'platform_fee_kobo' => $platformFee,
                'net_kobo' => $net,
                'gateway_fee_source' => $feeSource,
                'settlement_status' => $status->value,
                'eligible_at' => $eligibleAt,
                'hold_reason' => $holdReason,
            ])->saveQuietly();

            $refs = ['payment_id' => $locked->id, 'order_id' => $locked->order_id];

            $this->ledger->post($locked->pharmacy_id, LedgerEntryType::SaleCredit, $gross, 'payment:' . $locked->id . ':sale_credit', $refs);

            if ($gatewayCharge > 0) {
                $this->ledger->post($locked->pharmacy_id, LedgerEntryType::GatewayFee, -$gatewayCharge, 'payment:' . $locked->id . ':gateway_fee', $refs, ['source' => $feeSource]);
            }

            if ($platformFee > 0) {
                $this->ledger->post($locked->pharmacy_id, LedgerEntryType::PlatformFee, -$platformFee, 'payment:' . $locked->id . ':platform_fee', $refs);
            }

            $this->audit->record('payment.recorded', $locked->pharmacy_id, $locked, null, [
                'gross_kobo' => $gross,
                'gateway_fee_kobo' => $gatewayFee,
                'platform_fee_kobo' => $platformFee,
                'net_kobo' => $net,
                'status' => $status->value,
            ]);

            return $locked;
        });

        if ($recorded !== null && $status === PaymentSettlementStatus::Held && $holdReason !== null && $holdReason !== 'order_cancelled') {
            $this->notifier->ops('Payment held on recording', [
                'Payment ID: ' . $payment->id,
                'Reason: ' . $holdReason,
            ], 'warning');
        }

        return $recorded;
    }

    private function inspectGateway(Payment $payment, int $gross): array
    {
        if ($gross <= 0) {
            return [0, 'estimated', 'invalid_amount'];
        }

        if (! (bool) config('settlement.payment.verify_with_gateway')) {
            return [$this->estimator->estimate($gross, $this->gatewayOf($payment)), 'estimated', null];
        }

        $gatewayName = $this->gatewayOf($payment);

        try {
            $charge = $this->gateways->for($gatewayName)->verifyCharge((string) $payment->reference);
        } catch (GatewayException $exception) {
            if ($exception->isAmbiguous()) {
                throw $exception;
            }

            return [$this->estimator->estimate($gross, $gatewayName), 'estimated', null];
        }

        $currency = strtoupper((string) config('settlement.currency'));
        $problem = null;

        if (! $charge->isSuccessful()) {
            $problem = 'gateway_status_' . (string) ($charge->rawStatus ?? $charge->state->value);
        } elseif ($charge->amountKobo !== $gross) {
            $problem = 'amount_mismatch';
        } elseif ($charge->currency !== $currency) {
            $problem = 'currency_mismatch';
        }

        if ($charge->feeKobo !== null) {
            return [$charge->feeKobo, 'gateway', $problem];
        }

        return [$this->estimator->estimate($gross, $gatewayName), 'estimated', $problem];
    }

    private function gatewayOf(Payment $payment): Gateway
    {
        return Gateway::fromValue((string) $payment->gateway) ?? Gateway::Paystack;
    }

    private function position(?Order $order, ?string $problem): array
    {
        if ($problem !== null) {
            return [PaymentSettlementStatus::Held, null, $problem];
        }

        $status = $order?->status;

        if ($status === OrderStatus::Cancelled) {
            return [PaymentSettlementStatus::Held, null, 'order_cancelled'];
        }

        if ($status === OrderStatus::Completed) {
            $completed = $order->getAttribute('completed_at');
            $base = $completed !== null ? Carbon::parse($completed) : now();

            return [
                PaymentSettlementStatus::Pending,
                $base->copy()->addHours((int) config('settlement.eligibility.hold_hours')),
                null,
            ];
        }

        return [PaymentSettlementStatus::Pending, null, null];
    }
}
