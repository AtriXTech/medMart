<x-filament-panels::page>
    @php
        $data = $this->pharmacyData;
        $kobo = static fn ($value): string => '₦' . number_format(((int) $value) / 100, 2);
    @endphp

    @if ($data === null)
        <x-filament::section>
            <p class="text-sm text-gray-500">
                Select a pharmacy to see its balance, payout settings and ledger.
            </p>
        </x-filament::section>
    @else
        @php
            $p = $data['pharmacy'];
            $b = $data['balance'];

            $tiles = [
                'Ledger balance' => $b['ledger_balance_kobo'],
                'In hold window' => $b['pending_kobo'],
                'Held' => $b['held_kobo'],
                'Eligible' => $b['eligible_kobo'],
                'Available' => $b['available_kobo'],
                'Carried debt' => $b['carried_debt_kobo'],
                'In transit' => $b['in_transit_kobo'],
                'Paid out' => $b['paid_out_kobo'],
            ];
        @endphp

        <x-filament::section
            :heading="$p['name'] . ($p['is_test_account'] ? ' (test account)' : '')"
            :description="'Pharmacy ID ' . $p['id']"
        >
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                @foreach ($tiles as $label => $value)
                    <div class="rounded-xl border border-gray-200 p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500">
                            {{ $label }}
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $kobo($value) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section heading="Payout settings">
            <div class="divide-y divide-gray-100 rounded-xl border border-gray-200">

                <div class="flex items-center justify-between gap-4 p-4">
                    <span class="text-sm text-gray-500">Payout hold</span>
                    <span class="text-right text-sm font-medium text-gray-900">
                        @if ($p['payout_hold'])
                            On{{ $p['payout_hold_reason'] ? ' · ' . $p['payout_hold_reason'] : '' }}
                        @else
                            Off
                        @endif
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 p-4">
                    <span class="text-sm text-gray-500">Commission override</span>
                    <span class="text-right text-sm font-medium text-gray-900">
                        {{ $p['commission_rate'] === null ? 'Global rate' : $p['commission_rate'] . '%' }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 p-4">
                    <span class="text-sm text-gray-500">Payout schedule</span>
                    <span class="text-right text-sm font-medium text-gray-900">
                        {{ ucfirst($p['payout_schedule']) }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 p-4">
                    <span class="text-sm text-gray-500">Consecutive failures</span>
                    <span class="text-right text-sm font-medium text-gray-900">
                        {{ $p['consecutive_payout_failures'] }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4 p-4">
                    <span class="text-sm text-gray-500">Cooldown until</span>
                    <span class="text-right text-sm font-medium text-gray-900">
                        @if ($b['payout_cooldown_until'])
                            {{ \Illuminate\Support\Carbon::parse($b['payout_cooldown_until'])->timezone('Africa/Lagos')->format('M j, Y · H:i') }} WAT
                        @else
                            —
                        @endif
                    </span>
                </div>

            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>