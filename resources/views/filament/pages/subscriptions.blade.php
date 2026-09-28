<x-filament-panels::page>

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#171E26] dark:text-white">
                Subscriptions
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Monitor pharmacy subscriptions across the MedMart platform.
            </p>
        </div>

        <button
            type="button"
            wire:click="$refresh"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2775E4] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1f63c4] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:ring-offset-2"
        >
            <i class="ph ph-arrows-clockwise text-lg"></i>
            Refresh
        </button>
    </div>


    {{-- KPI Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total Subscriptions
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#171E26] dark:text-white">
                        {{ number_format($this->getTotalSubscriptions()) }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#E9F3FE] text-[#2775E4]">
                    <i class="ph ph-credit-card text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Active --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Active Subscriptions
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#171E26] dark:text-white">
                        {{ number_format($this->getActiveSubscriptions()) }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-950/30 dark:text-green-400">
                    <i class="ph ph-check-circle text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Expiring --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Expiring Soon
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#171E26] dark:text-white">
                        {{ number_format($this->getExpiringSubscriptions()) }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400">
                    <i class="ph ph-warning text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Cancelled --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Cancelled
                    </p>

                    <p class="mt-2 text-2xl font-bold text-[#171E26] dark:text-white">
                        {{ number_format($this->getCancelledSubscriptions()) }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400">
                    <i class="ph ph-x-circle text-xl"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- All Subscriptions --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        {{-- Section Header --}}
        <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-[#171E26] dark:text-white">
                        All Subscriptions
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and manage pharmacy subscription records.
                    </p>
                </div>

            </div>


            {{-- Filters --}}
            <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">

                {{-- Search --}}
                <div class="relative">
                    <i class="ph ph-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Search pharmacy or email..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >
                </div>


                {{-- Plan --}}
                <select
                    wire:model.live="planFilter"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">All Plans</option>

                    @foreach ($this->getPlans() as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>


                {{-- Status --}}
                <select
                    wire:model.live="statusFilter"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="past_due">Past Due</option>
                </select>


                {{-- Billing --}}
                <select
                    wire:model.live="billingFilter"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">All Billing</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>

            </div>
        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Pharmacy
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Plan
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Status
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Amount
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Next Billing
                        </th>

                        <th class="px-5 py-3.5 text-right font-semibold text-gray-600 dark:text-gray-300">
                            Action
                        </th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse ($this->subscriptions as $subscription)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                            {{-- Pharmacy --}}
                            <td class="px-5 py-4">
                                <div>
                                    <p class="font-medium text-[#171E26] dark:text-white">
                                        {{ $subscription->pharmacy?->name ?? 'N/A' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $subscription->pharmacy?->email ?? 'No email' }}
                                    </p>
                                </div>
                            </td>


                            {{-- Plan --}}
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $subscription->plan?->name ?? 'N/A' }}
                                </span>
                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @php
                                    $status = $subscription->status?->value ?? $subscription->status;
                                @endphp

                                @if ($status === 'active')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 dark:bg-green-950/30 dark:text-green-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Active
                                    </span>
                                @elseif ($status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-950/30 dark:text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Cancelled
                                    </span>
                                @elseif ($status === 'past_due')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Past Due
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>


                            {{-- Amount --}}
                            <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-300">
                                ₦{{ number_format((float) ($subscription->plan?->price ?? 0), 2) }}
                            </td>


                            {{-- Next Billing --}}
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $subscription->current_period_ends_at?->format('M d, Y') ?? '—' }}
                            </td>


                            {{-- Action --}}
                            <td class="px-5 py-4 text-right">
                                <a
                                    href="{{ \App\Filament\Pages\SubscriptionOverview::getUrl(['subscription' => $subscription]) }}"
                                    class="inline-flex items-center gap-1.5 font-medium text-[#2775E4] transition hover:text-[#058A98]"
                                >
                                    View
                                    <i class="ph ph-arrow-up-right"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-[#E9F3FE] text-[#2775E4]">
                                        <i class="ph ph-credit-card text-2xl"></i>
                                    </div>

                                    <p class="font-medium text-gray-700 dark:text-gray-300">
                                        No subscriptions found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Try adjusting your filters or search.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($this->subscriptions->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800">
                {{ $this->subscriptions->links() }}
            </div>
        @endif

    </div>


    {{-- Expiring Subscriptions --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        {{-- Header --}}
        <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">

            <div>
                <h2 class="text-lg font-semibold text-[#171E26] dark:text-white">
                    Expiring Subscriptions
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Active subscriptions expiring within the next 7 days.
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                    <tr>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Pharmacy
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Plan
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Price
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Next Billing
                        </th>

                        <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                            Expires In
                        </th>

                        <th class="px-5 py-3.5 text-right font-semibold text-gray-600 dark:text-gray-300">
                            Action
                        </th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse ($this->getExpiringSubscriptionList() as $subscription)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                            {{-- Pharmacy --}}
                            <td class="px-5 py-4">

                                <div>
                                    <p class="font-medium text-[#171E26] dark:text-white">
                                        {{ $subscription->pharmacy?->name ?? 'N/A' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $subscription->pharmacy?->email ?? 'No email' }}
                                    </p>
                                </div>

                            </td>


                            {{-- Plan --}}
                            <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-300">
                                {{ $subscription->plan?->name ?? 'N/A' }}
                            </td>


                            {{-- Price --}}
                            <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-300">
                                ₦{{ number_format((float) ($subscription->plan?->price ?? 0), 2) }}
                            </td>


                            {{-- Next Billing --}}
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $subscription->current_period_ends_at?->format('M d, Y') ?? '—' }}
                            </td>


                            {{-- Expires In --}}
                            <td class="px-5 py-4">

                                @php
                                    $expiryDate = $subscription->current_period_ends_at;
                                    $daysRemaining = now()->diffInDays($expiryDate, false);
                                @endphp

                                @if ($daysRemaining <= 1)
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-950/30 dark:text-red-400">
                                        {{ $daysRemaining === 0 ? 'Today' : 'Tomorrow' }}
                                    </span>
                                @elseif ($daysRemaining <= 3)
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                        {{ $daysRemaining }} {{ \Illuminate\Support\Str::plural('day', $daysRemaining) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-[#E9F3FE] px-2.5 py-1 text-xs font-semibold text-[#2775E4]">
                                        {{ $daysRemaining }} {{ \Illuminate\Support\Str::plural('day', $daysRemaining) }}
                                    </span>
                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ \App\Filament\Pages\SubscriptionOverview::getUrl(['subscription' => $subscription]) }}"
                                    class="inline-flex items-center gap-1.5 font-medium text-[#2775E4] transition hover:text-[#058A98]"
                                >
                                    View
                                    <i class="ph ph-arrow-up-right"></i>
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-5 py-12 text-center">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-950/30 dark:text-green-400">
                                        <i class="ph ph-check-circle text-2xl"></i>
                                    </div>

                                    <p class="font-medium text-gray-700 dark:text-gray-300">
                                        No subscriptions expiring soon
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        There are no active subscriptions expiring within 7 days.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Cancelled Subscriptions --}}
<div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

    {{-- Header --}}
    <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800">

        <div>
            <h2 class="text-lg font-semibold text-[#171E26] dark:text-white">
                Cancelled Subscriptions
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                View pharmacies with cancelled subscription plans.
            </p>
        </div>

    </div>


    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[850px] text-left text-sm">

            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                <tr>

                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                        Pharmacy
                    </th>

                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                        Plan
                    </th>

                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                        Amount
                    </th>

                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                        Cancelled At
                    </th>

                    <th class="px-5 py-3.5 font-semibold text-gray-600 dark:text-gray-300">
                        Status
                    </th>

                    <th class="px-5 py-3.5 text-right font-semibold text-gray-600 dark:text-gray-300">
                        Action
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                @forelse ($this->getCancelledSubscriptionList() as $subscription)

                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">

                        {{-- Pharmacy --}}
                        <td class="px-5 py-4">

                            <div>
                                <p class="font-medium text-[#171E26] dark:text-white">
                                    {{ $subscription->pharmacy?->name ?? 'N/A' }}
                                </p>

                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $subscription->pharmacy?->email ?? 'No email' }}
                                </p>
                            </div>

                        </td>


                        {{-- Plan --}}
                        <td class="px-5 py-4">
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                {{ $subscription->plan?->name ?? 'N/A' }}
                            </span>
                        </td>


                        {{-- Amount --}}
                        <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-300">
                            ₦{{ number_format((float) ($subscription->plan?->price ?? 0), 2) }}
                        </td>


                        {{-- Cancelled At --}}
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                            {{ $subscription->cancelled_at?->format('M d, Y') ?? '—' }}
                        </td>


                        {{-- Status --}}
                        <td class="px-5 py-4">

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-950/30 dark:text-red-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                Cancelled
                            </span>

                        </td>


                        {{-- Action --}}
                        <td class="px-5 py-4 text-right">

                            <a
                                href="{{ \App\Filament\Pages\SubscriptionOverview::getUrl(['subscription' => $subscription]) }}"
                                class="inline-flex items-center gap-1.5 font-medium text-[#2775E4] transition hover:text-[#058A98]"
                            >
                                View
                                <i class="ph ph-arrow-up-right"></i>
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-5 py-12 text-center">

                            <div class="flex flex-col items-center justify-center">

                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                    <i class="ph ph-x-circle text-2xl"></i>
                                </div>

                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                    No cancelled subscriptions
                                </p>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    There are no cancelled subscriptions to display.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



</x-filament-panels::page>