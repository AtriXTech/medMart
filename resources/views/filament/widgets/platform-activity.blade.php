<x-filament-widgets::widget>
    <x-filament::section>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            @vite('resources/css/app.css')
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link
                href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
                rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
            <style>
                .font-manrope {
                    font-family: 'Manrope', sans-serif;
                }

                .font-inter {
                    font-family: 'Inter', sans-serif;
                }
            </style>
        </head>

        <body>



            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Recent Platform Activity --}}
                <div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                            Recent Platform Activity
                        </h2>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Latest activity across the MedMart platform
                        </p>
                    </div>

                    <div class="mt-6 space-y-5">

                        @forelse ($activities as $activity)
                            <div class="flex items-start gap-3">

                                {{-- Icon --}}
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
                                @if ($activity['type'] === 'pharmacy') bg-blue-50 text-[#2775E4] dark:bg-blue-500/10
                                @elseif ($activity['type'] === 'customer')
                                    bg-cyan-50 text-[#08AEBC] dark:bg-cyan-500/10
                                @else
                                    bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 @endif
                            ">
                                    @if ($activity['type'] === 'pharmacy')
                                        <i class="ph ph-storefront text-lg"></i>
                                    @elseif ($activity['type'] === 'customer')
                                        <i class="ph ph-user-plus text-lg"></i>
                                    @else
                                        <i class="ph ph-credit-card text-lg"></i>
                                    @endif
                                </div>

                                {{-- Activity --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-950 dark:text-white">
                                        {{ $activity['title'] }}
                                    </p>

                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity['name'] }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                        {{ $activity['time']->diffForHumans() }}
                                    </p>
                                </div>

                            </div>

                        @empty

                            <div class="py-8 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    No recent activity.
                                </p>
                            </div>
                        @endforelse

                    </div>
                </div>


                {{-- Needs Attention --}}
                <div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                            Needs Attention
                        </h2>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Items that require your attention
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">

                        {{-- Pending Settlement Accounts --}}
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">

                            <div class="flex items-start justify-between gap-4">

                                <div>
                                    <p class="text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ $pendingSettlementAccounts }}
                                        settlement
                                        {{ $pendingSettlementAccounts === 1 ? 'account' : 'accounts' }}
                                        awaiting approval
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Settlement accounts still pending approval.
                                    </p>
                                </div>

                                @if ($pendingSettlementAccounts > 0)
                                    <span
                                        class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                        Pending
                                    </span>
                                @endif

                            </div>

                            @if ($pendingSettlementAccounts > 0)
                                <div class="mt-4">
                                    <a href="/superadmin/settlement-accounts?filter=pending"
                                        class="text-sm font-semibold text-[#2775E4] hover:text-[#058A98]">
                                        View
                                    </a>
                                </div>
                            @endif

                        </div>


                        {{-- Unpaid Subscription Payments --}}
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">

                            <div class="flex items-start justify-between gap-4">

                                <div>
                                    <p class="text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ $unpaidSubscriptionPayments }}
                                        subscription
                                        {{ $unpaidSubscriptionPayments === 1 ? 'payment' : 'payments' }}
                                        require attention
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Subscription payments that are still unpaid.
                                    </p>
                                </div>

                                @if ($unpaidSubscriptionPayments > 0)
                                    <span
                                        class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                        Unpaid
                                    </span>
                                @endif

                            </div>

                            @if ($unpaidSubscriptionPayments > 0)
                                <div class="mt-4">
                                    <a href="/superadmin/subscription-payments?filter=unpaid"
                                        class="text-sm font-semibold text-[#2775E4] hover:text-[#058A98]">
                                        View
                                    </a>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>


        </body>

        </html>
    </x-filament::section>
</x-filament-widgets::widget>
