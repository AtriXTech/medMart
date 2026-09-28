<x-filament-panels::page>
    <div class="space-y-6">
@once
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
@endonce

        {{-- Back --}}
        <div>
            <a
                href="{{ \App\Filament\Pages\Orders::getUrl() }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700"
            >
                <x-heroicon-m-arrow-left class="w-4 h-4" />
                Back to Orders
            </a>
        </div>

        {{-- Pharmacy Header --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Pharmacy Order Performance
                    </p>

                    <h2 class="mt-1 text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                        {{ $pharmacy->name }}
                    </h2>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Registered {{ $pharmacy->created_at?->format('M d, Y') }}
                    </p>
                </div>

                <div>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        {{ $pharmacy->status->value === 'active'
                            ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400'
                            : 'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400' }}"
                    >
                        {{ ucfirst($pharmacy->status->value) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Order KPIs --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Orders
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                    {{ number_format($totalOrders) }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Processed
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                    {{ number_format($processedOrders) }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Completed
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                    {{ number_format($completedOrders) }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Cancelled
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                    {{ number_format($cancelledOrders) }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Completion Rate
                </p>

                <p class="mt-2 text-2xl font-semibold text-primary-600">
                    {{ $completionRate }}%
                </p>
            </div>

        </div>

        {{-- Pharmacy Order Details --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                    Pharmacy Order Details
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Breakdown of orders by current status.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                @php
                    $statuses = [
                        [
                            'key' => 'pending_payment',
                            'label' => 'Pending Payment',
                            'description' => 'Awaiting payment',
                            'icon' => 'clock',
                        ],
                        [
                            'key' => 'paid',
                            'label' => 'Paid',
                            'description' => 'Payment confirmed',
                            'icon' => 'check',
                        ],
                        [
                            'key' => 'received',
                            'label' => 'Received',
                            'description' => 'Received by pharmacy',
                            'icon' => 'inbox',
                        ],
                        [
                            'key' => 'processing',
                            'label' => 'Processing',
                            'description' => 'Currently being processed',
                            'icon' => 'arrow-path',
                        ],
                        [
                            'key' => 'ready_for_pickup',
                            'label' => 'Ready for Pickup',
                            'description' => 'Ready for customer',
                            'icon' => 'shopping-bag',
                        ],
                        [
                            'key' => 'completed',
                            'label' => 'Completed',
                            'description' => 'Successfully completed',
                            'icon' => 'check-circle',
                        ],
                        [
                            'key' => 'cancelled',
                            'label' => 'Cancelled',
                            'description' => 'Cancelled orders',
                            'icon' => 'x-circle',
                        ],
                    ];
                @endphp

                @foreach ($statuses as $status)
                    @php
                        $count = (int) ($statusCounts[$status['key']] ?? 0);
                    @endphp

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-3">

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ $status['label'] }}
                                </p>

                                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                                    {{ number_format($count) }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $status['description'] }}
                                </p>
                            </div>

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                                @if ($status['icon'] === 'clock')
                                    <x-heroicon-m-clock class="h-5 w-5" />
                                @elseif ($status['icon'] === 'check')
                                    <x-heroicon-m-check class="h-5 w-5" />
                                @elseif ($status['icon'] === 'inbox')
                                    <x-heroicon-m-inbox class="h-5 w-5" />
                                @elseif ($status['icon'] === 'arrow-path')
                                    <x-heroicon-m-arrow-path class="h-5 w-5" />
                                @elseif ($status['icon'] === 'shopping-bag')
                                    <x-heroicon-m-shopping-bag class="h-5 w-5" />
                                @elseif ($status['icon'] === 'check-circle')
                                    <x-heroicon-m-check-circle class="h-5 w-5" />
                                @elseif ($status['icon'] === 'x-circle')
                                    <x-heroicon-m-x-circle class="h-5 w-5" />
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Pharmacy Performance --}}
@php
    $pendingOrders = (int) ($statusCounts['pending_payment'] ?? 0);

    $processingOrders = (int) ($statusCounts['processing'] ?? 0);
    $readyForPickupOrders = (int) ($statusCounts['ready_for_pickup'] ?? 0);

    $currentProcessingOrders = $processingOrders + $readyForPickupOrders;

    $processingRate = $totalOrders > 0
        ? round(($processedOrders / $totalOrders) * 100, 1)
        : 0;

    $cancellationRate = $totalOrders > 0
        ? round(($cancelledOrders / $totalOrders) * 100, 1)
        : 0;
@endphp

<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
            Pharmacy Performance
        </h3>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Key order processing and completion metrics for this pharmacy.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Processing Rate --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Processing Rate
            </p>

            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $processingRate }}%
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Orders processed or completed
            </p>
        </div>

        {{-- Completion Rate --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Completion Rate
            </p>

            <p class="mt-2 text-2xl font-semibold text-primary-600">
                {{ $completionRate }}%
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Completed vs completed + cancelled
            </p>
        </div>

        {{-- Cancellation Rate --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Cancellation Rate
            </p>

            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $cancellationRate }}%
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Cancelled orders
            </p>
        </div>

        {{-- Pending Orders --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pending Orders
            </p>

            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ number_format($pendingOrders) }}
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Awaiting payment
            </p>
        </div>

        {{-- Currently Processing --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Currently Processing
            </p>

            <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ number_format($currentProcessingOrders) }}
            </p>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Processing or ready for pickup
            </p>
        </div>

    </div>
</div>

        {{-- Performance Summary --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                Performance Summary
            </h3>

            <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                {{ $pharmacy->name }} has processed
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ number_format($processedOrders) }}
                </span>
                of
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ number_format($totalOrders) }}
                </span>
                total orders, with
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ number_format($completedOrders) }}
                </span>
                completed and
                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ number_format($cancelledOrders) }}
                </span>
                cancelled.
            </p>

        </div>

    </div>
</x-filament-panels::page>