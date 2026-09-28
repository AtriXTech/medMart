@php
$status = $settlement->status;


$statusLabel = match ($status) {
    'settled' => 'Settled',
    'pending' => 'Pending',
    'failed' => 'Failed',
    default => ucfirst($status),
};

$statusColor = match ($status) {
    'settled' => 'success',
    'pending' => 'warning',
    'failed' => 'danger',
    default => 'gray',
};

$account = $settlement->settlementAccount;
$payments = $settlement->payments;


@endphp

<div class="space-y-6">


    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                Settlement Details
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                {{ $settlement->reference }}
            </p>
        </div>

        <x-filament::badge :color="$statusColor">
            {{ $statusLabel }}
        </x-filament::badge>
    </div>

    {{-- Settlement Amount --}}
    <div class="rounded-xl bg-gray-50 p-5">
        <p class="text-sm text-gray-500">
            Settlement Amount
        </p>

        <p class="mt-1 text-2xl font-bold text-gray-900">
            ₦{{ number_format((float) $settlement->amount, 2) }}
        </p>
    </div>

    {{-- Settlement Information --}}
    <div>
        <h4 class="text-sm font-semibold text-gray-900">
            Settlement Information
        </h4>

        <div class="mt-3 divide-y divide-gray-100 rounded-xl border border-gray-200">

            {{-- Settlement Reference --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Settlement Reference
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $settlement->reference }}
                </span>
            </div>

            {{-- Gateway Reference --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Gateway Reference
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $settlement->gateway_reference ?? '—' }}
                </span>
            </div>

         

            {{-- Destination Account --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Destination Account
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $account?->bank_name ?? '—' }}
                    @if ($account?->account_number)
                        · {{ $account->account_number }}
                    @endif
                </span>
            </div>

            {{-- Account Name --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Account Name
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $account?->account_name ?? '—' }}
                </span>
            </div>

            {{-- Batch Initialization --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Batch Initialization
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    {{ $settlement->created_at?->format('M j, Y · H:i:s') ?? '—' }}
                    @if ($settlement->created_at)
                        WAT
                    @endif
                </span>
            </div>

            {{-- Bank Credit Time --}}
            <div class="flex items-center justify-between gap-4 p-4">
                <span class="text-sm text-gray-500">
                    Bank Credit Time
                </span>

                <span class="text-right text-sm font-medium text-gray-900">
                    @if ($settlement->processed_at)
                        {{ $settlement->processed_at->format('M j, Y · H:i:s') }}
                        WAT
                    @else
                        —
                    @endif
                </span>
            </div>

        </div>
    </div>

    {{-- Included Customer Orders --}}
    <div>
        <div>
            <h4 class="text-sm font-semibold text-gray-900">
                Included Customer Orders ({{ $payments->count() }})
            </h4>

            <p class="mt-1 text-sm text-gray-500">
                Customer payments cleared in this settlement disbursement
            </p>
        </div>

        @if ($payments->isNotEmpty())

            <div class="mt-3 overflow-hidden rounded-xl border border-gray-200">

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">
                                    Tx Ref
                                </th>

                                <th class="px-4 py-3 font-medium">
                                    Date
                                </th>

                                <th class="px-4 py-3 text-right font-medium">
                                    Gross Amount
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($payments as $payment)
                                <tr>

                                    {{-- Transaction Reference --}}
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                        {{ $payment->reference }}
                                    </td>

                                    {{-- Payment Date --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                        {{ $payment->paid_at?->format('M j, Y') ?? ($payment->created_at?->format('M j, Y') ?? '—') }}
                                    </td>

                                    {{-- Gross Amount --}}
                                    <td class="whitespace-nowrap px-4 py-3 text-right font-medium text-gray-900">
                                        ₦{{ number_format((float) $payment->amount, 2) }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>
        @else
            <div class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm text-gray-500">
                    No customer payments are linked to this settlement.
                </p>
            </div>

        @endif
    </div>

    {{-- Pending --}}
    @if ($status === 'pending')
        <div class="rounded-xl bg-amber-50 p-4">
            <p class="text-sm text-amber-800">
                This settlement has not yet been credited.
            </p>
        </div>
    @endif

    {{-- Failed --}}
    @if ($status === 'failed' && $settlement->failure_reason)
        <div class="rounded-xl bg-red-50 p-4">
            <h4 class="text-sm font-semibold text-red-800">
                Failure Reason
            </h4>

            <p class="mt-1 text-sm text-red-700">
                {{ $settlement->failure_reason }}
            </p>
        </div>
    @endif


</div>
