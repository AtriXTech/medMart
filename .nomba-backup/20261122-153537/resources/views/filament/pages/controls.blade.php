<x-filament-panels::page>
    @php
        $controls = $this->controls;
        $gateway = $this->gateway;
        $kobo = static fn ($value): string => '₦' . number_format(((int) $value) / 100, 2);
    @endphp

    {{-- Paystack mode --}}
    @if ($gateway)
        @php $isTest = $gateway['mode'] === 'test'; @endphp

        <div
            class="rounded-xl p-4 text-sm"
            style="border: 1px solid {{ $isTest ? '#BFE6CF' : '#F5C6C2' }}; background: {{ $isTest ? '#E9F8EF' : '#FDEDEC' }}; color: {{ $isTest ? '#1F7A44' : '#9C3A32' }};"
        >
            @if ($isTest)
                <strong>Paystack TEST mode.</strong> No real money moves.
            @else
                <strong>Paystack LIVE mode.</strong> Transfers send real money.
            @endif
        </div>

        <x-filament::section heading="Paystack balance (live from Paystack)">
            <p class="text-xl font-bold text-gray-900">
                {{ $gateway['balance_kobo'] === null ? 'Unavailable' : $kobo($gateway['balance_kobo']) }}
            </p>

            @if ($gateway['error'])
                <p class="mt-1 text-sm" style="color: #9C3A32;">
                    {{ $gateway['error'] }}
                </p>
            @endif

            <p class="mt-3 text-sm text-gray-500">
                Transfers need this balance to cover the payout plus Paystack's transfer fee. Top up your test balance from the Paystack dashboard (test mode) if it is too low.
            </p>
        </x-filament::section>
    @endif

    {{-- Payouts --}}
    <x-filament::section heading="Payouts">
        @if ($controls['payouts_paused'])
            <div
                class="rounded-xl p-4 text-sm"
                style="border: 1px solid #F5C6C2; background: #FDEDEC; color: #9C3A32;"
            >
                <p class="font-semibold">Payouts are PAUSED</p>
                <p class="mt-1">{{ $controls['pause_reason'] ?: 'No reason given' }}</p>
            </div>
        @else
            <div
                class="rounded-xl p-4 text-sm font-semibold"
                style="border: 1px solid #BFE6CF; background: #E9F8EF; color: #1F7A44;"
            >
                Payouts are active
            </div>
        @endif

        <div class="mt-5" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
            <div class="rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Minimum payout</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $kobo($controls['minimum_payout_kobo']) }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Payout ceiling</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $controls['max_single_payout_kobo'] > 0 ? $kobo($controls['max_single_payout_kobo']) : 'None' }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Hold window</p>
                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $controls['hold_hours'] }} hours</p>
            </div>
        </div>
    </x-filament::section>

    {{-- Commission and fees --}}
    <x-filament::section heading="Commission and fees">
        <div class="divide-y divide-gray-100 rounded-xl border border-gray-200">

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Take commission on sales</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $controls['commission_enabled'] ? 'Yes' : 'No' }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Rate</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $controls['commission_rate'] }}%</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Flat</span>
                <span class="text-right text-sm font-medium text-gray-900">{{ $kobo($controls['commission_flat_kobo']) }}</span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Cap</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $controls['commission_cap_kobo'] > 0 ? $kobo($controls['commission_cap_kobo']) : 'No cap' }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">Paystack fee is paid by</span>
                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $controls['gateway_fee_bearer'] === 'platform' ? 'Platform (absorbed)' : 'Pharmacy (deducted from payout)' }}
                </span>
            </div>

        </div>

        <p class="mt-3 text-sm text-gray-500">
            Changes apply to payments recorded from now on. Cap 0 means no cap.
        </p>
    </x-filament::section>

    {{-- Maintenance job output --}}
    @if ($this->jobOutput !== '')
        <x-filament::section heading="Last job output">
            <pre style="max-height: 12rem; overflow: auto; white-space: pre-wrap; font-size: 12px;">{{ $this->jobOutput }}</pre>
        </x-filament::section>
    @endif
</x-filament-panels::page>