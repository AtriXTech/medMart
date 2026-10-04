<x-filament-panels::page>

    {{-- Back --}}
    <div class="mb-6">
        <a
            href="{{ url('/admin/customers') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-primary-600 transition"
        >
            ← Back to Customers
        </a>
    </div>

    {{-- Customer Header --}}
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $customer->name }}
                    </h2>

                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        Active
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $customer->username }}
                </p>

                <div class="mt-3 flex flex-col gap-1 text-sm text-gray-600 sm:flex-row sm:gap-5">
                    <span>{{ $customer->email }}</span>
                    <span>{{ $customer->phone }}</span>
                </div>
            </div>

            <div class="text-sm text-gray-500">
                <span class="font-medium text-gray-700">Joined:</span>
                {{ $customer->created_at?->format('M d, Y') ?? '—' }}
            </div>

        </div>
    </div>

    {{-- Usage KPI Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Orders</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                {{ $totalOrders }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                All customer orders
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Processed Orders</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                {{ $processedOrders }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Processing or completed
            </p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Ready for Pickups </p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                {{  $readyForPickup }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Ready for Pickup 
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Spent</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                ₦{{ number_format($totalSpent, 2) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Completed orders
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Linked Pharmacies</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                {{ $linkedPharmacies }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Pharmacies linked to customer
            </p>
        </div>

    </div>

    {{-- Pharmacy Usage --}}
<div class="mt-8 rounded-2xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-5">
        <h3 class="text-lg font-semibold text-gray-900">
            Pharmacy Usage
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Pharmacies this customer actually uses through MedMart.
        </p>
    </div>

    @if ($pharmacyUsage->isEmpty())

        <div class="px-6 py-10 text-center">
            <p class="text-sm text-gray-500">
                This customer has not placed any orders yet.
            </p>
        </div>

    @else

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <th class="px-6 py-4 font-medium">
                            Pharmacy
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Orders
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Total Spent
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach ($pharmacyUsage as $usage)

                        <tr class="text-sm">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $usage->pharmacy?->name ?? 'Unknown Pharmacy' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ number_format($usage->orders_count) }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                ₦{{ number_format((float) $usage->total_spent, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>
            </table>
        </div>

    @endif

</div>

{{-- Transaction History --}}
<div class="mt-8 rounded-2xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-5">
        <h3 class="text-lg font-semibold text-gray-900">
            Transaction History
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Order activity across all pharmacies used by this customer.
        </p>
    </div>

    @if ($transactions->isEmpty())

        <div class="px-6 py-10 text-center">
            <p class="text-sm text-gray-500">
                No transactions found for this customer.
            </p>
        </div>

    @else

        <div class="overflow-x-auto">
            <table class="w-full text-left">

                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-4 font-medium">
                            Date
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Pharmacy
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Status
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Amount
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach ($transactions as $transaction)

                        <tr class="text-sm">

                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                {{ $transaction->created_at?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $transaction->pharmacy?->name ?? 'Unknown Pharmacy' }}
                            </td>

                            <td class="px-6 py-4">

                                @php
                                    $status = $transaction->status;

                                    $statusLabel = match ($status->value) {
                                        'pending_payment' => 'Pending Payment',
                                        'paid' => 'Paid',
                                        'received' => 'Received',
                                        'processing' => 'Processing',
                                        'ready_for_pickup' => 'Ready for Pickup',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        default => ucfirst(str_replace('_', ' ', $status->value)),
                                    };

                                    $statusColor = match ($status->value) {
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'processing',
                                        'ready_for_pickup' => 'bg-blue-100 text-blue-700',
                                        'paid',
                                        'received' => 'bg-cyan-100 text-cyan-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                                ₦{{ number_format((float) $transaction->total, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    @endif

</div>

</x-filament-panels::page>