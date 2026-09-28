<x-filament-panels::page>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css"
    >

    <style>
        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body>

<div class="space-y-6">

    {{-- Back --}}
    <div>
        <a
            href="{{ \App\Filament\Pages\ViewPlanSubscribers::getUrl([
                'plan' => $plan->getKey(),
            ]) }}"
            class="inline-flex items-center gap-2 font-inter text-sm font-medium text-[#2775E4] transition hover:text-[#058A98]"
        >
            <i class="ph ph-arrow-left text-base"></i>
            Back to Subscribers
        </a>
    </div>


    {{-- Header --}}
    <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm md:p-6">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

            <div>

                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="font-manrope text-2xl font-extrabold tracking-tight text-[#171E26]">
                        {{ $subscription->pharmacy->name }}
                    </h1>

                    @if ($subscription->status->value === 'active')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E8FAF7] px-3 py-1 font-inter text-xs font-semibold text-[#058A98]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#08AEBC]"></span>
                            Active Subscription
                        </span>

                    @elseif ($subscription->status->value === 'past_due')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 font-inter text-xs font-semibold text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Past Due
                        </span>

                    @elseif ($subscription->status->value === 'cancelled')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 font-inter text-xs font-semibold text-red-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Cancelled
                        </span>

                    @else

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 font-inter text-xs font-semibold text-gray-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                            Inactive
                        </span>

                    @endif

                </div>


                <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 font-inter text-sm text-[#171E26]/50">

                    <span>
                        {{ $plan->name }}
                    </span>

                    <span>·</span>

                    <span>
                        ₦{{ number_format((float) $plan->price, 2) }}
                        / {{ $plan->billing_interval->value }}
                    </span>

                    @if ($subscription->paystack_subscription_code)

                        <span>·</span>

                        <span>
                            Ref: {{ $subscription->paystack_subscription_code }}
                        </span>

                    @endif

                </div>

            </div>


            {{-- Actions --}}
            <div class="flex flex-wrap gap-2">

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2.5 font-inter text-xs font-semibold text-red-600 transition hover:bg-red-50"
                >
                    <i class="ph ph-x-circle text-base"></i>
                    Cancel Subscription
                </button>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#2775E4] px-4 py-2.5 font-inter text-xs font-semibold text-white transition hover:bg-[#1F65C7]"
                >
                    <i class="ph ph-arrows-clockwise text-base"></i>
                    Change Plan Tier
                </button>

            </div>

        </div>

    </div>


    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Started --}}
        <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="font-inter text-xs font-medium text-[#171E26]/50">
                        Subscription Started
                    </p>

                    <p class="mt-2 font-manrope text-xl font-extrabold text-[#171E26]">
                        {{ $subscription->current_period_starts_at?->format('M d, Y') ?? '—' }}
                    </p>

                    @if ($subscription->current_period_starts_at)

                        @php
                            $cycles = max(
                                1,
                                $subscription->current_period_starts_at->diffInMonths(
                                    now()
                                ) + 1
                            );
                        @endphp

                        <p class="mt-1 font-inter text-xs text-[#171E26]/40">
                            {{ $cycles }} billing {{ $cycles === 1 ? 'cycle' : 'cycles' }}
                        </p>

                    @endif

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]">
                    <i class="ph ph-calendar-blank text-xl"></i>
                </div>

            </div>

        </div>


        {{-- Next Billing --}}
        <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="font-inter text-xs font-medium text-[#171E26]/50">
                        Next Billing Date
                    </p>

                    <p class="mt-2 font-manrope text-xl font-extrabold text-[#171E26]">
                        {{ $subscription->current_period_ends_at?->format('M d, Y') ?? '—' }}
                    </p>

                    @if ($subscription->status->value === 'active')

                        <p class="mt-1 inline-flex items-center gap-1 font-inter text-xs font-medium text-[#058A98]">
                            <i class="ph ph-check-circle"></i>
                            Auto-renewal enabled
                        </p>

                    @elseif ($subscription->cancelled_at)

                        <p class="mt-1 font-inter text-xs font-medium text-red-500">
                            Cancelled {{ $subscription->cancelled_at->format('M d, Y') }}
                        </p>

                    @endif

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8FAF7] text-[#08AEBC]">
                    <i class="ph ph-calendar-check text-xl"></i>
                </div>

            </div>

        </div>


        {{-- Lifetime Paid --}}
        <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="font-inter text-xs font-medium text-[#171E26]/50">
                        Total Lifetime Paid
                    </p>

                    <p class="mt-2 font-manrope text-xl font-extrabold text-[#171E26]">
                        ₦{{ number_format($totalLifetimePaid, 2) }}
                    </p>

                    <p class="mt-1 font-inter text-xs text-[#171E26]/40">
                        {{ $successfulPaymentCount }}
                        {{ $successfulPaymentCount === 1 ? 'payment' : 'payments' }}
                        processed
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]">
                    <i class="ph ph-wallet text-xl"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Subscription Details --}}
    <div class="rounded-2xl border border-[#EAF1FB] bg-white shadow-sm">

        <div class="border-b border-[#EAF1FB] px-5 py-4 md:px-6">

            <h2 class="font-manrope text-base font-bold text-[#171E26]">
                Subscription Details
            </h2>

            <p class="mt-0.5 font-inter text-xs text-[#171E26]/45">
                Current subscription information for this pharmacy.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 md:grid-cols-2 md:p-6">

            {{-- Pharmacy --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Subscribed Pharmacy
                </p>

                <div class="mt-2 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE] font-manrope text-sm font-bold text-[#2775E4]">
                        {{ strtoupper(substr($subscription->pharmacy->name, 0, 1)) }}
                    </div>

                    <div>

                        <p class="font-inter text-sm font-semibold text-[#171E26]">
                            {{ $subscription->pharmacy->name }}
                        </p>

                        <p class="mt-0.5 font-inter text-xs text-[#171E26]/45">
                            {{ $subscription->pharmacy->email }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Plan --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Plan Name
                </p>

                <p class="mt-2 font-inter text-sm font-semibold text-[#171E26]">
                    {{ $plan->name }}
                </p>

            </div>


            {{-- Billing --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Billing Interval
                </p>

                <p class="mt-2 font-inter text-sm font-semibold text-[#171E26]">
                    {{ ucfirst($plan->billing_interval->value) }}
                    (₦{{ number_format((float) $plan->price, 2) }} /
                    {{ $plan->billing_interval->value }})
                </p>

            </div>


            {{-- Status --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Status
                </p>

                <p class="mt-2 font-inter text-sm font-semibold text-[#171E26]">
                    {{ ucwords(str_replace('_', ' ', $subscription->status->value)) }}
                </p>

            </div>


            {{-- Start Date --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Start Date
                </p>

                <p class="mt-2 font-inter text-sm font-semibold text-[#171E26]">
                    {{ $subscription->current_period_starts_at?->format('F d, Y') ?? '—' }}
                </p>

            </div>


            {{-- Next Billing --}}
            <div>

                <p class="font-inter text-xs font-medium text-[#171E26]/45">
                    Next Billing Date
                </p>

                <p class="mt-2 font-inter text-sm font-semibold text-[#171E26]">
                    {{ $subscription->current_period_ends_at?->format('F d, Y') ?? '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- Payment History --}}
    <div class="overflow-hidden rounded-2xl border border-[#EAF1FB] bg-white shadow-sm">

        <div class="border-b border-[#EAF1FB] px-5 py-4 md:px-6">

            <h2 class="font-manrope text-base font-bold text-[#171E26]">
                Payment History
            </h2>

            <p class="mt-0.5 font-inter text-xs text-[#171E26]/45">
                Platform Subscription Receipts
            </p>

        </div>


        @if ($payments->count())

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-[#EAF1FB] bg-[#F8FBFF]">

                            <th class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                Reference
                            </th>

                            <th class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                Amount
                            </th>

                            <th class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#EAF1FB]">

                        @foreach ($payments as $payment)

                            <tr class="transition hover:bg-[#F8FBFF]">

                                <td class="px-6 py-4">

                                    <span class="font-inter text-sm font-semibold text-[#171E26]">
                                        {{ $payment->reference }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="font-inter text-sm font-semibold text-[#171E26]">
                                        ₦{{ number_format((float) $payment->amount, 2) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                   @if ($payment->status === \App\Enums\PaymentStatus::Paid)
    <span class="inline-flex items-center rounded-full bg-[#E8F8F5] px-2.5 py-1 text-[11px] font-semibold text-[#058A98]">
        Paid
    </span>
@elseif ($payment->status === \App\Enums\PaymentStatus::Failed)
    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
        Failed
    </span>
@elseif ($payment->status === \App\Enums\PaymentStatus::Refunded)
    <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-[11px] font-semibold text-orange-600">
        Refunded
    </span>
@elseif($payment->status === \App\Enums\PaymentStatus::Unpaid)
    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-600">
        Unpaid
    </span>
@else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 font-inter text-xs font-semibold text-gray-600">
                                            {{ ucwords(str_replace('_', ' ', $payment->status->value)) }}
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <span class="font-inter text-sm text-[#171E26]">
                                        {{ $payment->paid_at?->format('M d, Y') ?? $payment->created_at?->format('M d, Y') ?? '—' }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9F3FE] text-[#2775E4]">
                    <i class="ph ph-receipt text-2xl"></i>
                </div>

                <h3 class="mt-4 font-manrope text-base font-bold text-[#171E26]">
                    No payment history
                </h3>

                <p class="mx-auto mt-1 max-w-sm font-inter text-sm leading-6 text-[#171E26]/45">
                    No subscription payment records are available for this pharmacy and plan.
                </p>

            </div>

        @endif

    </div>

</div>

</body>
</html>

</x-filament-panels::page>