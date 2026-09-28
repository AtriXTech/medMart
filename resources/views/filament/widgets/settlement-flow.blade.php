<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Settlement Flow
                </h2>

                <p class="text-sm text-gray-500">
                    Track customer revenue from generation to pharmacy settlement.
                </p>
            </div>

            @php
                $flow = $this->getFlowData();
            @endphp

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Revenue Generated --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">
                            Revenue Generated
                        </span>

                        <div class="rounded-lg bg-blue-50 p-2">
                            <x-heroicon-o-banknotes class="h-5 w-5 text-[#2775E4]" />
                        </div>
                    </div>

                    <p class="mt-3 text-2xl font-bold text-gray-900">
                        ₦{{ number_format($flow['revenueGenerated'], 2) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Platform revenue generated
                    </p>
                </div>

                {{-- Pending Settlement --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">
                            Pending Settlement
                        </span>

                        <div class="rounded-lg bg-amber-50 p-2">
                            <x-heroicon-o-clock class="h-5 w-5 text-amber-500" />
                        </div>
                    </div>

                    <p class="mt-3 text-2xl font-bold text-gray-900">
                        ₦{{ number_format($flow['pendingSettlement'], 2) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Awaiting settlement
                    </p>
                </div>

                {{-- Settled / Credited --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">
                            Settled / Credited
                        </span>

                        <div class="rounded-lg bg-green-50 p-2">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-green-600" />
                        </div>
                    </div>

                    <p class="mt-3 text-2xl font-bold text-gray-900">
                        ₦{{ number_format($flow['settledCredited'], 2) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Successfully credited
                    </p>
                </div>

            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>