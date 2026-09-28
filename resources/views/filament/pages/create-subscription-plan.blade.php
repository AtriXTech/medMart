<x-filament-panels::page>
@once
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
@endonce

    <div class="space-y-6">

        {{-- Back to Plans --}}
        <div>
            <a
                href="{{ \App\Filament\Pages\SubscriptionPlans::getUrl() }}"
                class="inline-flex items-center gap-2 font-inter text-sm font-medium text-[#2775E4] transition hover:text-[#058A98]"
            >
                <i class="ph ph-arrow-left"></i>
                Back to Plans
            </a>
        </div>


        {{-- Page Header --}}
        <div>
            <h1 class="font-manrope text-2xl font-bold text-[#171E26]">
                Create Subscription Plan
            </h1>

            <p class="mt-1 font-inter text-sm text-[#171E26]/60">
                Create and configure a subscription plan for MedMart pharmacies.
            </p>
        </div>


        {{-- Form --}}
        <div class="max-w-4xl">

            <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm md:p-6">

                {{-- Plan Information --}}
                <div>
                    <h2 class="font-manrope text-base font-bold text-[#171E26]">
                        Plan Information
                    </h2>

                    <p class="mt-1 font-inter text-xs text-[#171E26]/50">
                        Configure the pricing and billing details for this plan.
                    </p>
                </div>


                {{-- Name + Price --}}
                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Plan Name --}}
                    <div>
                        <label
                            for="name"
                            class="font-inter text-sm font-semibold text-[#171E26]"
                        >
                            Plan Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Standard"
                            class="mt-2 w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                        >

                        @error('name')
                            <p class="mt-1.5 font-inter text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Price --}}
                    <div>
                        <label
                            for="price"
                            class="font-inter text-sm font-semibold text-[#171E26]"
                        >
                            Price
                        </label>

                        <div class="relative mt-2">

                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-inter text-sm font-medium text-[#171E26]/50">
                                ₦
                            </span>

                            <input
                                id="price"
                                type="number"
                                wire:model="price"
                                min="0"
                                step="0.01"
                                placeholder="9500"
                                class="w-full rounded-xl border border-[#D8E5F5] bg-white py-3 pl-9 pr-4 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                            >

                        </div>

                        @error('price')
                            <p class="mt-1.5 font-inter text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- Billing Interval --}}
                <div class="mt-5">

                    <label
                        for="billingInterval"
                        class="font-inter text-sm font-semibold text-[#171E26]"
                    >
                        Billing Interval
                    </label>

                    <select
                        id="billingInterval"
                        wire:model="billingInterval"
                        class="mt-2 w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                    >
                        <option value="monthly">
                            Monthly
                        </option>

                        <option value="yearly">
                            Yearly
                        </option>
                    </select>

                    @error('billingInterval')
                        <p class="mt-1.5 font-inter text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Plan Limits --}}
                <div class="mt-8 border-t border-[#EAF1FB] pt-6">

                    <div>
                        <h2 class="font-manrope text-base font-bold text-[#171E26]">
                            Plan Limits
                        </h2>

                        <p class="mt-1 font-inter text-xs text-[#171E26]/50">
                            Configure the limits available to pharmacies on this plan.
                        </p>
                    </div>


                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">

                        {{-- Max Branches --}}
                        <div>

                            <label
                                for="maxBranches"
                                class="font-inter text-sm font-semibold text-[#171E26]"
                            >
                                Max Branches
                            </label>

                            <input
                                id="maxBranches"
                                type="number"
                                wire:model="maxBranches"
                                min="0"
                                placeholder="e.g. 1"
                                class="mt-2 w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                            >

                            @error('maxBranches')
                                <p class="mt-1.5 font-inter text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Max Staff --}}
                        <div>

                            <label
                                for="maxStaff"
                                class="font-inter text-sm font-semibold text-[#171E26]"
                            >
                                Max Staff
                            </label>

                            <input
                                id="maxStaff"
                                type="number"
                                wire:model="maxStaff"
                                min="0"
                                placeholder="e.g. 5"
                                class="mt-2 w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                            >

                            @error('maxStaff')
                                <p class="mt-1.5 font-inter text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Max Products --}}
                        <div>

                            <label
                                for="maxProducts"
                                class="font-inter text-sm font-semibold text-[#171E26]"
                            >
                                Max Products
                            </label>

                            <input
                                id="maxProducts"
                                type="number"
                                wire:model="maxProducts"
                                min="0"
                                placeholder="e.g. 500"
                                class="mt-2 w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                            >

                            @error('maxProducts')
                                <p class="mt-1.5 font-inter text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Allowed Durations --}}
                <div class="mt-8 border-t border-[#EAF1FB] pt-6">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        <div>
                            <h2 class="font-manrope text-base font-bold text-[#171E26]">
                                Allowed Durations
                            </h2>

                            <p class="mt-1 font-inter text-xs text-[#171E26]/50">
                                Define the subscription durations available for this plan.
                            </p>
                        </div>


                        <button
                            type="button"
                            wire:click="addDuration"
                            class="inline-flex w-fit items-center gap-2 rounded-xl border border-[#D8E5F5] px-3 py-2 font-inter text-xs font-semibold text-[#2775E4] transition hover:bg-[#E9F3FE]"
                        >
                            <i class="ph ph-plus"></i>
                            Add Duration
                        </button>

                    </div>


                    @if (count($allowedDurations) > 0)

                        <div class="mt-4 space-y-3">

                            @foreach ($allowedDurations as $index => $duration)

                                <div class="flex items-center gap-3">

                                    <div class="flex-1">

                                        <input
                                            type="text"
                                            wire:model="allowedDurations.{{ $index }}"
                                            placeholder="e.g. 30 days"
                                            class="w-full rounded-xl border border-[#D8E5F5] bg-white px-4 py-3 font-inter text-sm text-[#171E26] outline-none transition placeholder:text-[#171E26]/30 focus:border-[#2775E4] focus:ring-2 focus:ring-[#2775E4]/10"
                                        >

                                        @error("allowedDurations.$index")
                                            <p class="mt-1.5 font-inter text-xs text-red-500">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>


                                    <button
                                        type="button"
                                        wire:click="removeDuration({{ $index }})"
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-[#171E26]/40 transition hover:bg-red-50 hover:text-red-500"
                                        title="Remove duration"
                                    >
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="mt-4 rounded-xl border border-dashed border-[#D8E5F5] px-5 py-7 text-center">

                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-[#E9F3FE]">
                                <i class="ph ph-calendar-blank text-xl text-[#2775E4]"></i>
                            </div>

                            <p class="mt-3 font-inter text-sm font-medium text-[#171E26]/60">
                                No durations added
                            </p>

                            <p class="mt-1 font-inter text-xs text-[#171E26]/40">
                                Add a duration if this plan supports specific subscription periods.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- Status --}}
                <div class="mt-8 border-t border-[#EAF1FB] pt-6">

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-[#D8E5F5] px-4 py-4">

                        <div>

                            <p class="font-inter text-sm font-semibold text-[#171E26]">
                                Active Plan
                            </p>

                            <p class="mt-1 font-inter text-xs text-[#171E26]/50">
                                Make this plan available for pharmacy subscriptions.
                            </p>

                        </div>


                        <button
                            type="button"
                            wire:click="$toggle('isActive')"
                            class="relative h-6 w-11 shrink-0 rounded-full transition
                                {{ $isActive ? 'bg-[#2775E4]' : 'bg-[#CBD5E1]' }}"
                        >

                            <span
                                class="absolute top-1 h-4 w-4 rounded-full bg-white shadow-sm transition
                                    {{ $isActive ? 'right-1' : 'left-1' }}"
                            ></span>

                        </button>

                    </div>

                </div>


                {{-- Form Actions --}}
                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-[#EAF1FB] pt-6 sm:flex-row sm:justify-end">

                    <a
                        href="{{ \App\Filament\Pages\SubscriptionPlans::getUrl() }}"
                        class="inline-flex items-center justify-center rounded-xl border border-[#D8E5F5] px-5 py-2.5 font-inter text-sm font-semibold text-[#171E26]/70 transition hover:bg-[#F8FAFC]"
                    >
                        Cancel
                    </a>


                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#2775E4] px-5 py-2.5 font-inter text-sm font-semibold text-white transition hover:bg-[#1f68cf] disabled:cursor-not-allowed disabled:opacity-60"
                    >

                        <i
                            class="ph ph-check"
                            wire:loading.remove
                            wire:target="save"
                        ></i>

                        <i
                            class="ph ph-spinner-gap animate-spin"
                            wire:loading
                            wire:target="save"
                        ></i>

                        <span
                            wire:loading.remove
                            wire:target="save"
                        >
                            Save Plan
                        </span>

                        <span
                            wire:loading
                            wire:target="save"
                        >
                            Saving...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>
    
</x-filament-panels::page>