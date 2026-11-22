@php
    $status = $settlement->status instanceof \App\Settlement\Enums\SettlementStatus
        ? $settlement->status->value
        : (string) $settlement->status;

    $statusLabel = match ($status) {
        'settled' => 'Settled',
        'pending' => 'Pending',
        'processing' => 'Processing',
        'failed' => 'Failed',
        'reversed' => 'Reversed',
        'on_hold' => 'On Hold',
        'cancelled' => 'Cancelled',
        default => ucfirst(str_replace('_', ' ', $status)),
    };

    $statusColor = match ($status) {
        'settled' => 'success',
        'pending', 'on_hold' => 'warning',
        'processing' => 'info',
        'failed', 'reversed' => 'danger',
        'cancelled' => 'gray',
        default => 'gray',
    };

    $account = $settlement->settlementAccount;
    $payments = $settlement->payments;

    $kobo = static fn ($value): string => '₦' . number_format(((int) $value) / 100, 2);

    $lagos = static fn ($date): string => $date
        ? $date->copy()->timezone('Africa/Lagos')->format('M j, Y · H:i:s') . ' WAT'
        : '—';
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                Settlement Details
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                {{ $settlement->reference }}
            </p>
        </div>

        <x-filament::badge :color="$statusColor">
            {{ $statusLabel }}
        </x-filament::badge>
    </div>

    {{-- Two columns, same as the console --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">

        {{-- Left column --}}
        <div class="divide-y divide-gray-100 rounded-xl border border-gray-200">

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Status</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $statusLabel }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Pharmacy</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $settlement->pharmacy?->name ?? $settlement->pharmacy_id }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Gross</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $kobo($settlement->gross_kobo) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Fees</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $kobo($settlement->fee_kobo) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Adjustment</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $kobo($settlement->adjustment_kobo) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Net</span>
                <span class="text-right text-sm font-bold text-gray-900">{{ $kobo($settlement->net_kobo) }}</span>
            </div>

        </div>

        {{-- Right column --}}
        <div class="divide-y divide-gray-100 rounded-xl border border-gray-200">

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Account</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    @if ($account)
                        {{ $account->bank_name }} · {{ $account->account_number }}
                    @else
                        —
                    @endif
                </span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Account name</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $account?->account_name ?? '—' }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Paystack transfer</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $settlement->gateway_reference ?: '—' }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Attempts</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ (int) $settlement->attempts }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Created</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $lagos($settlement->created_at) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Processed</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $lagos($settlement->processed_at) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Hold / failure</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $settlement->hold_reason ?: ($settlement->failure_reason ?: '—') }}
                </span>
            </div>

        </div>

    </div>

    {{-- Payments --}}
    <div>
        <h4 class="text-sm font-semibold text-gray-900">
            Payments ({{ $payments->count() }})
        </h4>

        @if ($payments->isNotEmpty())

            <div class="mt-3 overflow-hidden rounded-xl border border-gray-200">

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">ID</th>
                                <th class="px-4 py-3 font-medium">Order</th>
                                <th class="px-4 py-3 text-right font-medium">Gross</th>
                                <th class="px-4 py-3 text-right font-medium">Commission</th>
                                <th class="px-4 py-3 text-right font-medium">Gateway</th>
                                <th class="px-4 py-3 text-right font-medium">Net</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                        #{{ $payment->id }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                        Order #{{ $payment->order_id }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-right text-gray-900">
                                        {{ $kobo($payment->gross_kobo) }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-right text-gray-600">
                                        {{ $kobo($payment->platform_fee_kobo) }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-right text-gray-600">
                                        {{ $kobo($payment->gateway_fee_kobo) }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-gray-900">
                                        {{ $kobo($payment->net_kobo) }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>
        @else
            <div class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm text-gray-500">
                    No payments linked.
                </p>
            </div>
        @endif
    </div>

    {{-- Pending --}}
    @if ($status === 'pending')
        <div class="rounded-xl bg-amber-50 p-4">
            <p class="text-sm text-amber-800">
                This settlement has not yet been credited.
            </p>
        </div>
    @endif

</div>