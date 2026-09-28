<x-filament-panels::page>
@once
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
@endonce
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-manrope text-2xl font-bold text-[#171E26]">
                        Subscription Plans
                    </h1>

                    <p class="mt-1 font-inter text-sm text-[#171E26]/60">
                        Manage the subscription plans available to MedMart pharmacies.
                    </p>
                </div>

                <a href="{{ \App\Filament\Pages\CreateSubscriptionPlan::getUrl() }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#2775E4] px-4 py-2.5 font-inter text-sm font-semibold text-white transition hover:bg-[#1f68cf]">
                    <i class="ph ph-plus text-base"></i>
                    Create Plan
                </a>

            </div>

            {{-- Content will come here --}}
            {{-- KPI Overview --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Total Plans --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-inter text-sm font-medium text-[#171E26]/60">
                                Total Plans
                            </p>

                            <h3 class="mt-2 font-manrope text-2xl font-bold text-[#171E26]">
                                {{ number_format($totalPlans) }}
                            </h3>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE]">
                            <i class="ph ph-stack text-xl text-[#2775E4]"></i>
                        </div>
                    </div>
                </div>

                {{-- Active Plans --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-inter text-sm font-medium text-[#171E26]/60">
                                Active Plans
                            </p>

                            <h3 class="mt-2 font-manrope text-2xl font-bold text-[#171E26]">
                                {{ number_format($activePlans) }}
                            </h3>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8FAF8]">
                            <i class="ph ph-check-circle text-xl text-[#08AEBC]"></i>
                        </div>
                    </div>
                </div>

                {{-- Pharmacies Subscribed --}}
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-inter text-sm font-medium text-[#171E26]/60">
                                Pharmacies Subscribed
                            </p>

                            <h3 class="mt-2 font-manrope text-2xl font-bold text-[#171E26]">
                                {{ number_format($pharmaciesSubscribed) }}
                            </h3>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E9F3FE]">
                            <i class="ph ph-buildings text-xl text-[#2775E4]"></i>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Plan Cards --}}
            <div>
                <div class="mb-4">
                    <h2 class="font-manrope text-lg font-bold text-[#171E26]">
                        Available Plans
                    </h2>

                    <p class="mt-1 font-inter text-sm text-[#171E26]/55">
                        Manage the subscription plans available to MedMart pharmacies.
                    </p>
                </div>

                @if ($plans->isEmpty())

                    {{-- Empty State --}}
                    <div class="rounded-2xl border border-[#EAF1FB] bg-white px-6 py-12 text-center shadow-sm">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9F3FE]">
                            <i class="ph ph-package text-2xl text-[#2775E4]"></i>
                        </div>

                        <h3 class="mt-4 font-manrope text-base font-bold text-[#171E26]">
                            No subscription plans
                        </h3>

                        <p class="mx-auto mt-1 max-w-md font-inter text-sm text-[#171E26]/55">
                            Create your first subscription plan to start managing pharmacy subscriptions.
                        </p>

                        <a href="#"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#2775E4] px-4 py-2.5 font-inter text-sm font-semibold text-white transition hover:bg-[#1f68cf]">
                            <i class="ph ph-plus"></i>
                            Create Plan
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

                        @foreach ($plans as $plan)
                            <div
                                class="flex flex-col rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">

                                {{-- Plan Header --}}
                                <div class="flex items-start justify-between gap-3">

                                    <div>
                                        <h3 class="font-manrope text-lg font-bold uppercase text-[#171E26]">
                                            {{ $plan->name }}
                                        </h3>

                                        <div class="mt-1 flex items-center gap-1.5">

                                            <span
                                                class="h-2 w-2 rounded-full
                                    {{ $plan->is_active ? 'bg-[#08AEBC]' : 'bg-[#94A3B8]' }}"></span>

                                            <span
                                                class="font-inter text-xs font-medium
                                    {{ $plan->is_active ? 'text-[#058A98]' : 'text-[#64748B]' }}">
                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                            </span>

                                        </div>
                                    </div>

                                    {{-- More --}}
                                    <div class="relative" x-data="{ open: false }">

                                        <button type="button" @click="open = !open"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#D8E5F5] text-[#171E26]/50 transition hover:bg-[#E9F3FE] hover:text-[#2775E4]">
                                            <i class="ph ph-dots-three-outline text-lg"></i>
                                        </button>

                                        <div x-show="open" @click.outside="open = false" x-transition
                                            class="absolute right-0 top-12 z-30 w-48 overflow-hidden rounded-xl border border-[#EAF1FB] bg-white py-1 shadow-lg">

                                            {{-- Edit --}}
                                            <a href="{{ \App\Filament\Pages\EditSubscriptionPlan::getUrl([
                                                'plan' => $plan->getKey(),
                                            ]) }}"
                                                class="flex items-center gap-3 px-4 py-2.5 font-inter text-xs font-medium text-[#171E26] transition hover:bg-[#E9F3FE]">
                                                <i class="ph ph-pencil-simple text-base text-[#2775E4]"></i>
                                                Edit
                                            </a>

                                            {{-- View Subscribers --}}
                                            <button type="button"
                                                class="flex w-full items-center gap-3 px-4 py-2.5 text-left font-inter text-xs font-medium text-[#171E26] transition hover:bg-[#E9F3FE]">

                                                <a href="{{ \App\Filament\Pages\ViewPlanSubscribers::getUrl([
                                                    'plan' => $plan->getKey(),
                                                ]) }}"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 font-inter text-xs font-medium text-[#171E26] transition hover:bg-[#E9F3FE]">
                                                    <i class="ph ph-users text-base text-[#2775E4]"></i>
                                                    View Subscribers
                                                </a>

                                            </button>

                                            <div class="my-1 border-t border-[#EAF1FB]"></div>

                                            {{-- Activate / Deactivate --}}
                                            <button type="button" wire:click="toggleStatus({{ $plan->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="toggleStatus({{ $plan->id }})"
                                                class="flex w-full items-center gap-3 px-4 py-2.5 text-left font-inter text-xs font-medium transition hover:bg-[#E9F3FE] {{ $plan->is_active ? 'text-amber-600' : 'text-[#08AEBC]' }}">
                                                @if ($plan->is_active)
                                                    <i class="ph ph-pause-circle text-base"></i>
                                                    Deactivate Plan
                                                @else
                                                    <i class="ph ph-play-circle text-base"></i>
                                                    Activate Plan
                                                @endif
                                            </button>

                                            {{-- Delete --}}
                                            <button type="button" wire:click="deletePlan({{ $plan->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="deletePlan({{ $plan->id }})"
                                                wire:confirm="Are you sure you want to delete {{ $plan->name }}?"
                                                class="flex w-full items-center gap-3 px-4 py-2.5 text-left font-inter text-xs font-medium text-red-600 transition hover:bg-red-50">
                                                <i class="ph ph-trash text-base"></i>
                                                Delete Plan
                                            </button>

                                        </div>
                                    </div>

                                </div>


                                {{-- Price --}}
                                <div class="mt-5 flex items-baseline gap-1">

                                    <span class="font-manrope text-2xl font-extrabold text-[#171E26]">
                                        ₦{{ number_format((float) $plan->price, 0) }}
                                    </span>

                                    <span class="font-inter text-xs text-[#171E26]/50">
                                        /
                                        {{ $plan->billing_interval?->value ?? 'period' }}
                                    </span>

                                </div>


                                {{-- Description --}}
                                <p class="mt-3 min-h-[42px] font-inter text-sm leading-6 text-[#171E26]/60">
                                    {{ $plan->description ?? 'Complete digital pharmacy management package.' }}
                                </p>


                                {{-- Features --}}
                                <div class="mt-5">

                                    <p
                                        class="font-inter text-[11px] font-bold uppercase tracking-wider text-[#171E26]/40">
                                        Plan Limits
                                    </p>

                                    <div class="mt-3 space-y-2.5">

                                        <div class="flex items-center gap-2 font-inter text-sm text-[#171E26]/75">
                                            <i class="ph-fill ph-check-circle text-[#08AEBC]"></i>
                                            <span>
                                                {{ $plan->max_branches ?? 'Unlimited' }} branches
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 font-inter text-sm text-[#171E26]/75">
                                            <i class="ph-fill ph-check-circle text-[#08AEBC]"></i>
                                            <span>
                                                {{ $plan->max_staff ?? 'Unlimited' }} staff members
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 font-inter text-sm text-[#171E26]/75">
                                            <i class="ph-fill ph-check-circle text-[#08AEBC]"></i>
                                            <span>
                                                {{ $plan->max_products ?? 'Unlimited' }} products
                                            </span>
                                        </div>

                                    </div>

                                </div>


                                {{-- Subscribers --}}
                                <div class="mt-6 flex items-center justify-between border-t border-[#EAF1FB] pt-4">

                                    <div class="flex items-center gap-2">

                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#E9F3FE]">
                                            <i class="ph ph-buildings text-base text-[#2775E4]"></i>
                                        </div>

                                        <span class="font-inter text-sm text-[#171E26]/60">
                                            Pharmacies subscribed
                                        </span>

                                    </div>

                                    <span class="font-manrope text-sm font-bold text-[#171E26]">
                                        {{ number_format($plan->subscribers_count) }}
                                    </span>

                                </div>


                                {{-- Actions --}}
                                <div class="mt-4 flex gap-2">

                                    <a href="{{ \App\Filament\Pages\EditSubscriptionPlan::getUrl([
                                        'plan' => $plan->getKey(),
                                    ]) }}"
                                        class="flex-1 rounded-xl border border-[#D8E5F5] px-3 py-2.5 text-center font-inter text-xs font-semibold text-[#2775E4] transition hover:bg-[#E9F3FE]">
                                        Edit
                                    </a>

                                    <a
    href="{{ \App\Filament\Pages\ViewPlanSubscribers::getUrl([
        'plan' => $plan->getKey(),
    ]) }}"
    class="flex-1 rounded-xl border border-[#D8E5F5] px-3 py-2.5 text-center font-inter text-xs font-semibold text-[#2775E4] transition hover:bg-[#E9F3FE]"
>
    View Subscribers
</a>

                                </div>

                            </div>
                        @endforeach

                    </div>

                @endif
            </div>

        </div>
    
</x-filament-panels::page>
