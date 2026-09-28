@php
    use App\Enums\SubscriptionStatus;
@endphp
<x-filament-panels::page>
@once
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
@endonce

        <div class="space-y-6">

            {{-- Back --}}
            <div>
                <a href="{{ \App\Filament\Pages\SubscriptionPlans::getUrl() }}"
                    class="inline-flex items-center gap-2 font-inter text-sm font-medium text-[#2775E4] transition hover:text-[#058A98]">
                    <i class="ph ph-arrow-left text-base"></i>
                    Back to Plans
                </a>
            </div>


            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div>
                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="font-manrope text-2xl font-extrabold tracking-tight text-[#171E26]">
                            {{ $plan->name }} Subscribers
                        </h1>

                        @if ($plan->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#E8FAF7] px-3 py-1 font-inter text-xs font-semibold text-[#058A98]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#08AEBC]"></span>
                                Active
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 font-inter text-xs font-semibold text-gray-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                Inactive
                            </span>
                        @endif

                    </div>

                    <p class="mt-1 font-inter text-sm text-[#171E26]/50">
                        Pharmacies currently subscribed to the {{ $plan->name }} plan.
                    </p>
                </div>

            </div>


            {{-- Summary --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Total Subscribers --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="font-inter text-xs font-medium text-[#171E26]/50">
                                Total Subscribers
                            </p>

                            <p class="mt-2 font-manrope text-2xl font-extrabold text-[#171E26]">
                                {{ number_format($plan->subscriptions()->count()) }}
                            </p>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]">
                            <i class="ph ph-buildings text-xl"></i>
                        </div>

                    </div>

                </div>


                {{-- Active Subscribers --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="font-inter text-xs font-medium text-[#171E26]/50">
                                Active Subscribers
                            </p>

                            <p class="mt-2 font-manrope text-2xl font-extrabold text-[#171E26]">
                                {{ number_format($plan->subscriptions()->where('status', SubscriptionStatus::Active->value)->count()) }}
                            </p>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8FAF7] text-[#08AEBC]">
                            <i class="ph ph-check-circle text-xl"></i>
                        </div>

                    </div>

                </div>


                {{-- Plan Price --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="font-inter text-xs font-medium text-[#171E26]/50">
                                Plan Price
                            </p>

                            <p class="mt-2 font-manrope text-2xl font-extrabold text-[#171E26]">
                                ₦{{ number_format((float) $plan->price, 2) }}
                            </p>

                            <p class="mt-0.5 font-inter text-xs text-[#171E26]/40">
                                / {{ $plan->billing_interval->value }}
                            </p>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE] text-[#2775E4]">
                            <i class="ph ph-credit-card text-xl"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Filters --}}
            <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">

                <div class="flex flex-col gap-4">

                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

                        {{-- Search --}}
                        <div class="relative flex-1">

                            <i
                                class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-base text-[#171E26]/35"></i>

                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search pharmacy..."
                                class="w-full rounded-xl border border-[#D8E5F5] bg-white py-2.5 pl-10 pr-4 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/35 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10">

                        </div>


                        {{-- Status --}}
                        <div class="lg:w-48">

                            <select wire:model.live="status"
                                class="w-full rounded-xl border border-[#D8E5F5] bg-white px-3 py-2.5 font-inter text-sm text-[#171E26] outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10">

                                <option value="">All Statuses</option>

                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption->value }}">
                                        {{ ucwords(str_replace('_', ' ', $statusOption->value)) }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- Date From --}}
                        <div class="lg:w-44">

                            <input type="date" wire:model.live="dateFrom"
                                class="w-full rounded-xl border border-[#D8E5F5] bg-white px-3 py-2.5 font-inter text-sm text-[#171E26] outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10">

                        </div>


                        {{-- Date To --}}
                        <div class="lg:w-44">

                            <input type="date" wire:model.live="dateTo"
                                class="w-full rounded-xl border border-[#D8E5F5] bg-white px-3 py-2.5 font-inter text-sm text-[#171E26] outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10">

                        </div>


                        {{-- Clear --}}
                        @if ($search || $status || $dateFrom || $dateTo)
                            <button type="button" wire:click="clearFilters"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#D8E5F5] px-4 py-2.5 font-inter text-sm font-semibold text-[#171E26]/60 transition hover:bg-[#E9F3FE] hover:text-[#2775E4]">
                                <i class="ph ph-x text-base"></i>
                                Clear
                            </button>
                        @endif

                    </div>

                </div>

            </div>


            {{-- Subscribers Table --}}
            <div class="overflow-hidden rounded-2xl border border-[#EAF1FB] bg-white shadow-sm">

                <div class="border-b border-[#EAF1FB] px-5 py-4 md:px-6">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <h2 class="font-manrope text-base font-bold text-[#171E26]">
                                Subscribers
                            </h2>

                            <p class="mt-0.5 font-inter text-xs text-[#171E26]/45">
                                {{ number_format($subscribers->total()) }} subscription
                                record{{ $subscribers->total() === 1 ? '' : 's' }}
                            </p>
                        </div>

                    </div>

                </div>


                @if ($subscribers->count())

                    {{-- Desktop Table --}}
                    <div class="hidden overflow-x-auto md:block">

                        <table class="w-full">

                            <thead>
                                <tr class="border-b border-[#EAF1FB] bg-[#F8FBFF]">

                                    <th
                                        class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                        Pharmacy
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                        Status
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                        Started
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                        Next Billing
                                    </th>

                                    <th
                                        class="px-6 py-3 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/45">
                                        Action
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#EAF1FB]">

                                @foreach ($subscribers as $subscription)
                                    <tr class="transition hover:bg-[#F8FBFF]">

                                        {{-- Pharmacy --}}
                                        <td class="px-6 py-4">

                                            <div class="flex items-center gap-3">

                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E9F3FE] font-manrope text-sm font-bold text-[#2775E4]">
                                                    {{ strtoupper(substr($subscription->pharmacy->name ?? 'P', 0, 1)) }}
                                                </div>

                                                <div class="min-w-0">

                                                    <p class="truncate font-inter text-sm font-semibold text-[#171E26]">
                                                        {{ $subscription->pharmacy->name ?? 'Unknown Pharmacy' }}
                                                    </p>

                                                    <p class="truncate font-inter text-xs text-[#171E26]/40">
                                                        {{ $subscription->pharmacy->email ?? '—' }}
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Status --}}
                                        <td class="px-6 py-4">

                                            @php
                                                $statusValue = $subscription->status->value;
                                            @endphp

                                            @if ($statusValue === 'active')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-[#E8FAF7] px-2.5 py-1 font-inter text-xs font-semibold text-[#058A98]">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-[#08AEBC]"></span>
                                                    Active
                                                </span>
                                            @elseif ($statusValue === 'past_due')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 font-inter text-xs font-semibold text-amber-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    Past Due
                                                </span>
                                            @elseif ($statusValue === 'cancelled')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 font-inter text-xs font-semibold text-red-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Cancelled
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 font-inter text-xs font-semibold text-gray-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                    Inactive
                                                </span>
                                            @endif

                                        </td>


                                        {{-- Started --}}
                                        <td class="px-6 py-4">

                                            <span class="font-inter text-sm text-[#171E26]">
                                                {{ $subscription->current_period_starts_at?->format('M d, Y') ?? '—' }}
                                            </span>

                                        </td>


                                        {{-- Next Billing --}}
                                        <td class="px-6 py-4">

                                            <span class="font-inter text-sm text-[#171E26]">
                                                {{ $subscription->current_period_ends_at?->format('M d, Y') ?? '—' }}
                                            </span>

                                        </td>


                                        {{-- Action --}}
                                        <td class="px-6 py-4 text-right">

                                            <a href="{{ \App\Filament\Pages\SubscriptionDetails::getUrl([
                                                'subscription' => $subscription->getKey(),
                                            ]) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-[#171E26]/40 transition hover:bg-[#E9F3FE] hover:text-[#2775E4]"
                                                title="View subscription">
                                                <i class="ph ph-dots-three-outline text-lg"></i>
                                            </a>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Mobile Cards --}}
                    <div class="divide-y divide-[#EAF1FB] md:hidden">

                        @foreach ($subscribers as $subscription)
                            <div class="p-5">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="flex min-w-0 items-center gap-3">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E9F3FE] font-manrope text-sm font-bold text-[#2775E4]">
                                            {{ strtoupper(substr($subscription->pharmacy->name ?? 'P', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate font-inter text-sm font-semibold text-[#171E26]">
                                                {{ $subscription->pharmacy->name ?? 'Unknown Pharmacy' }}
                                            </p>

                                            <p class="truncate font-inter text-xs text-[#171E26]/40">
                                                {{ $subscription->pharmacy->email ?? '—' }}
                                            </p>

                                        </div>

                                    </div>

                                    <button type="button"
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[#171E26]/40 transition hover:bg-[#E9F3FE] hover:text-[#2775E4]">
                                        <i class="ph ph-dots-three-outline text-lg"></i>
                                    </button>

                                </div>


                                <div class="mt-4 grid grid-cols-2 gap-3">

                                    <div class="rounded-xl bg-[#F8FBFF] p-3">

                                        <p class="font-inter text-[11px] text-[#171E26]/40">
                                            Status
                                        </p>

                                        @php
                                            $mobileStatus = $subscription->status->value;
                                        @endphp

                                        <p class="mt-1 font-inter text-xs font-semibold text-[#171E26]">
                                            {{ ucwords(str_replace('_', ' ', $mobileStatus)) }}
                                        </p>

                                    </div>


                                    <div class="rounded-xl bg-[#F8FBFF] p-3">

                                        <p class="font-inter text-[11px] text-[#171E26]/40">
                                            Started
                                        </p>

                                        <p class="mt-1 font-inter text-xs font-semibold text-[#171E26]">
                                            {{ $subscription->current_period_starts_at?->format('M d, Y') ?? '—' }}
                                        </p>

                                    </div>


                                    <div class="rounded-xl bg-[#F8FBFF] p-3">

                                        <p class="font-inter text-[11px] text-[#171E26]/40">
                                            Next Billing
                                        </p>

                                        <p class="mt-1 font-inter text-xs font-semibold text-[#171E26]">
                                            {{ $subscription->current_period_ends_at?->format('M d, Y') ?? '—' }}
                                        </p>

                                    </div>


                                    <div class="rounded-xl bg-[#F8FBFF] p-3">

                                        <p class="font-inter text-[11px] text-[#171E26]/40">
                                            Pharmacy Status
                                        </p>

                                        <p class="mt-1 font-inter text-xs font-semibold text-[#171E26]">
                                            {{ $subscription->pharmacy?->status?->value ? ucfirst($subscription->pharmacy->status->value) : '—' }}
                                        </p>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>


                    {{-- Pagination --}}
                    <div class="border-t border-[#EAF1FB] px-5 py-4 md:px-6">
                        {{ $subscribers->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="px-6 py-16 text-center">

                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9F3FE] text-[#2775E4]">
                            <i class="ph ph-buildings text-2xl"></i>
                        </div>

                        <h3 class="mt-4 font-manrope text-base font-bold text-[#171E26]">
                            No subscribers found
                        </h3>

                        <p class="mx-auto mt-1 max-w-sm font-inter text-sm leading-6 text-[#171E26]/45">
                            No pharmacies match the current filters for this subscription plan.
                        </p>

                        @if ($search || $status || $dateFrom || $dateTo)
                            <button type="button" wire:click="clearFilters"
                                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#2775E4] px-4 py-2.5 font-inter text-sm font-semibold text-white transition hover:bg-[#1F65C7]">
                                <i class="ph ph-arrow-counter-clockwise text-base"></i>
                                Clear Filters
                            </button>
                        @endif

                    </div>

                @endif

            </div>

        </div>


</x-filament-panels::page>
