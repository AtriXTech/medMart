<div class="space-y-6">

    
<div>
    <p class="text-sm text-gray-500">Reference</p>

    <p class="mt-1 break-all font-semibold text-gray-900">
        {{ $transaction->reference }}
    </p>
</div>



   <div class="border-t border-gray-200 pt-5">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

        <div>
            <p class="text-sm text-gray-500">Transaction Type</p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $transaction->type }}
            </p>
        </div>

<div>
    <p class="text-sm text-gray-500">Amount</p>

    <p class="mt-1 text-xl font-bold text-gray-900">
        ₦{{ number_format((float) $transaction->amount, 2) }}
    </p>
</div>



       
<div>
    <p class="text-sm text-gray-500">Status</p>

    <div class="mt-2">
        <span
            @class([
                'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                'bg-green-100 text-green-700' => $transaction->status === 'paid',
                'bg-amber-100 text-amber-700' => $transaction->status === 'unpaid',
                'bg-red-100 text-red-700' => $transaction->status === 'failed',
                'bg-purple-100 text-purple-700' => $transaction->status === 'refunded',
                'bg-gray-100 text-gray-700' => ! in_array(
                    $transaction->status,
                    ['paid', 'unpaid', 'failed', 'refunded']
                ),
            ])
        >
            {{ match ($transaction->status) {
                'paid' => 'Successful',
                'unpaid' => 'Pending',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
                default => ucfirst($transaction->status),
            } }}
        </span>
    </div>
</div>


        <div>
            <p class="text-sm text-gray-500">Pharmacy</p>
            <p class="mt-1 font-medium text-gray-900">
                {{ $transaction->pharmacy?->name ?? '—' }}
            </p>
        </div>

        <div>
    <p class="text-sm text-gray-500">Related Entity</p>

    <p class="mt-1 font-medium text-gray-900">
        @if (str_starts_with($transaction->id, 'payment-'))
            @if ($transaction->payment?->order)
                Order #{{ $transaction->payment->order->id }}
            @else
                —
            @endif
        @elseif (str_starts_with($transaction->id, 'subscription-'))
            @if ($transaction->subscriptionPayment?->plan)
                {{ $transaction->subscriptionPayment->plan->name }}
            @else
                —
            @endif
        @else
            —
        @endif
    </p>
</div>

    
<div>
    <p class="text-sm text-gray-500">Gateway Reference</p>

    <p class="mt-1 break-all font-medium text-gray-900">
        {{ $transaction->gateway_reference ?? '—' }}
    </p>
</div>



    
<div>
    <p class="text-sm text-gray-500">Created</p>

    <p class="mt-1 font-medium text-gray-900">
        {{ $transaction->created_at?->format('M d, Y') ?? '—' }}
    </p>

    @if ($transaction->created_at)
        <p class="mt-0.5 text-xs text-gray-500">
            {{ $transaction->created_at->format('h:i A') }}
        </p>
    @endif
</div>



    </div></div>

</div>