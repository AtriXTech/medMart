<x-filament-panels::page>
 
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>
                <a
                    href="{{ $this->getBackUrl() }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400"
                >
                    <x-filament::icon
                        icon="heroicon-o-arrow-left"
                        class="h-4 w-4"
                    />

                    Back to Subscriptions
                </a>

                <div class="mt-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                            {{ $subscription->pharmacy?->name ?? 'Unknown Pharmacy' }}
                        </h1>

                        @php
                            $status = $subscription->status?->value ?? 'inactive';

                            $statusClasses = match ($status) {
                                'active' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400',
                                'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-400',
                                'past_due' => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-400/10 dark:text-orange-400',
                                default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',
                            };
                        @endphp

                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClasses }}">
                            {{ str($status)->replace('_', ' ')->title() }}
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $subscription->pharmacy?->email ?? 'No email available' }}
                    </p>
                </div>
            </div>

        </div>


        {{-- Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            {{-- Plan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Plan
                </p>

                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    {{ $subscription->plan?->name ?? '—' }}
                </p>
            </div>


            {{-- Price --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Price
                </p>

                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    ₦{{ number_format((float) ($subscription->plan?->price ?? 0), 2) }}
                </p>

                @if ($subscription->plan?->billing_interval)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ str($subscription->plan->billing_interval->value)->replace('_', ' ')->title() }}
                    </p>
                @endif
            </div>


            {{-- Started --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Started
                </p>

                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    {{ $subscription->current_period_starts_at?->format('d M Y') ?? '—' }}
                </p>
            </div>


            {{-- Next Billing --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Next Billing
                </p>

                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    {{ $subscription->current_period_ends_at?->format('d M Y') ?? '—' }}
                </p>
            </div>


            {{-- Total Paid --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Total Paid
                </p>

                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    ₦{{ number_format($this->getTotalPaid(), 2) }}
                </p>
            </div>

        </div>


        {{-- Subscription Information --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

            <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                    Subscription Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Details about the pharmacy's current subscription.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 px-5 py-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Pharmacy
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ $subscription->pharmacy?->name ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Plan
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ $subscription->plan?->name ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Price
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        ₦{{ number_format((float) ($subscription->plan?->price ?? 0), 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Billing Interval
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ $subscription->plan?->billing_interval
                            ? str($subscription->plan->billing_interval->value)->replace('_', ' ')->title()
                            : '—'
                        }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Status
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ str($status)->replace('_', ' ')->title() }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Started
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ $subscription->current_period_starts_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Next Billing
                    </p>

                    <p class="mt-1 font-medium text-gray-950 dark:text-white">
                        {{ $subscription->current_period_ends_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>

                @if ($subscription->cancelled_at)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Cancelled
                        </p>

                        <p class="mt-1 font-medium text-red-600 dark:text-red-400">
                            {{ $subscription->cancelled_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @endif

            </div>
        </div>


        {{-- Payment History --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

            <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                    Payment History
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Subscription payments associated with this pharmacy and plan.
                </p>
            </div>


            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left">

                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950/50">
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Reference
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Amount
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Date
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">

                        @forelse ($this->getPaymentHistory() as $payment)

                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                                <td class="px-5 py-4">
                                    <span class="font-mono text-sm text-gray-950 dark:text-white">
                                        {{ $payment->reference }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-medium text-gray-950 dark:text-white">
                                        ₦{{ number_format((float) $payment->amount, 2) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">

                                    @php
                                        $paymentStatus = $payment->status?->value ?? 'unpaid';

                                        $paymentStatusClasses = match ($paymentStatus) {
                                            'paid' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400',
                                            'failed' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-400',
                                            default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $paymentStatusClasses }}">
                                        {{ str($paymentStatus)->replace('_', ' ')->title() }}
                                    </span>

                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-sm text-gray-950 dark:text-white">
                                        {{ ($payment->paid_at ?? $payment->created_at)?->format('d M Y') ?? '—' }}
                                    </div>

                                    <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        {{ ($payment->paid_at ?? $payment->created_at)?->format('h:i A') ?? '' }}
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center">

                                    <div class="mx-auto flex max-w-md flex-col items-center">

                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                            <x-filament::icon
                                                icon="heroicon-o-banknotes"
                                                class="h-6 w-6 text-gray-400"
                                            />
                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-gray-950 dark:text-white">
                                            No payment history
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            No subscription payments have been recorded for this pharmacy and plan.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</x-filament-panels::page>