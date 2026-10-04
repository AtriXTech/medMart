<?php

declare(strict_types=1);

namespace App\Settlement\Console;

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Services\EligibilityService;
use App\Settlement\Services\PaymentSettlementRecorder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SeedTestDataCommand extends Command
{
    protected $signature = 'settlement:seed-test {pharmacy? : Pharmacy ID (defaults to the first pharmacy)} {--sets=1 : Number of scenario sets to create} {--scale=1 : Multiply every amount (use 0.1 for a small Paystack test balance)}';

    protected $description = 'Create paid orders and payments for settlement testing (Paystack test key only)';

    private const SCENARIOS = [
        ['label' => 'Completed 3 days ago', 'amount' => 12500, 'status' => 'completed', 'hours_ago' => 72],
        ['label' => 'Completed 2 days ago', 'amount' => 8300, 'status' => 'completed', 'hours_ago' => 48],
        ['label' => 'Completed 2 days ago', 'amount' => 45000, 'status' => 'completed', 'hours_ago' => 50],
        ['label' => 'Completed just now', 'amount' => 6200, 'status' => 'completed', 'hours_ago' => 0],
        ['label' => 'Still processing', 'amount' => 9900, 'status' => 'processing', 'hours_ago' => 1],
        ['label' => 'Cancelled after payment', 'amount' => 15000, 'status' => 'cancelled', 'hours_ago' => 1],
    ];

    public function handle(PaymentSettlementRecorder $recorder, EligibilityService $eligibility, PaystackClient $paystack): int
    {
        if (app()->environment('production')) {
            $this->error('This command is disabled in production.');

            return self::FAILURE;
        }

        if (! $paystack->isTestKey()) {
            $this->error('Refusing to seed: PAYSTACK_SECRET_KEY must be a test key (sk_test_...). Seeded payments are not real and must never be paid out with a live key.');

            return self::FAILURE;
        }

        $scale = (float) $this->option('scale');

        if ($scale <= 0) {
            $this->error('--scale must be greater than 0.');

            return self::FAILURE;
        }

        $pharmacy = $this->argument('pharmacy') !== null
            ? Pharmacy::query()->withoutGlobalScopes()->find((int) $this->argument('pharmacy'))
            : Pharmacy::query()->withoutGlobalScopes()->orderBy('id')->first();

        if ($pharmacy === null) {
            $this->error('Pharmacy not found. Register a pharmacy first.');

            return self::FAILURE;
        }

        $customer = Customer::query()->orderBy('id')->first();

        if ($customer === null) {
            $this->error('No customer found. Register a customer first (POST /api/v1/customer/register), then run this again.');

            return self::FAILURE;
        }

        $paidStatus = $this->resolvePaidStatus();

        if ($paidStatus === null) {
            $this->error('Could not find a paid case in App\Enums\PaymentStatus matching settlement.payment.paid_statuses.');

            return self::FAILURE;
        }

        if ((bool) $pharmacy->getAttribute('is_test_account')) {
            $this->warn('This pharmacy is flagged is_test_account, so payouts will be skipped. Set is_test_account to false to test payouts.');
        }

        config(['settlement.payment.verify_with_gateway' => false]);

        $rows = [];

        for ($set = 0; $set < max(1, (int) $this->option('sets')); $set++) {
            foreach (self::SCENARIOS as $scenario) {
                $amount = round($scenario['amount'] * $scale, 2);
                $payment = $this->createPaidOrder($pharmacy, $customer, $paidStatus, $scenario, $amount);
                $recorded = $recorder->record((int) $payment->id);

                $rows[] = [
                    $payment->id,
                    $payment->order_id,
                    $scenario['label'],
                    '₦' . number_format($amount, 2),
                    $recorded?->getAttribute('settlement_status') ?? 'not recorded',
                ];
            }
        }

        $promoted = $eligibility->promote();

        $this->table(['Payment', 'Order', 'Scenario', 'Amount', 'Settlement status'], $rows);
        $this->info('Promoted to eligible: ' . $promoted . '. Pharmacy: ' . $pharmacy->name . ' (ID ' . $pharmacy->id . ').');

        return self::SUCCESS;
    }

    private function resolvePaidStatus(): ?PaymentStatus
    {
        $paid = array_map('strtolower', (array) config('settlement.payment.paid_statuses'));

        foreach (PaymentStatus::cases() as $case) {
            if (in_array(strtolower((string) $case->value), $paid, true)) {
                return $case;
            }
        }

        return null;
    }

    private function createPaidOrder(Pharmacy $pharmacy, Customer $customer, PaymentStatus $paidStatus, array $scenario, float $amount): Payment
    {
        $status = OrderStatus::from((string) $scenario['status']);
        $hoursAgo = (int) $scenario['hours_ago'];

        return DB::transaction(function () use ($pharmacy, $customer, $paidStatus, $status, $hoursAgo, $amount): Payment {
            $order = new Order();
            $order->forceFill([
                'pharmacy_id' => $pharmacy->id,
                'customer_id' => $customer->id,
                'status' => $status,
                'subtotal' => $amount,
                'total' => $amount,
                'fulfillment_type' => FulfillmentType::cases()[0],
                'delivery_address' => 'Test address, Ibadan',
            ]);

            if ($status === OrderStatus::Cancelled) {
                $order->forceFill(['cancelled_at' => now(), 'cancellation_reason' => 'Seeded test cancellation']);
            }

            $order->saveQuietly();

            if ($status === OrderStatus::Completed) {
                DB::table('orders')->where('id', $order->id)->update(['completed_at' => now()->subHours($hoursAgo)]);
            }

            $reference = 'TEST-' . strtoupper((string) Str::ulid());

            $payment = new Payment();
            $payment->forceFill([
                'pharmacy_id' => $pharmacy->id,
                'order_id' => $order->id,
                'reference' => $reference,
                'paystack_reference' => $reference,
                'status' => $paidStatus,
                'amount' => $amount,
                'gateway_response' => 'Seeded test payment',
                'paid_at' => now()->subHours($hoursAgo),
            ])->saveQuietly();

            return $payment;
        });
    }
}
