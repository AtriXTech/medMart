{{--
    Intended path: resources/views/staff/subscription.blade.php

    CHANGE SUMMARY:
    - Every ID subscription.js binds to is preserved exactly:
      subscription-error, subscription-loading, subscription-content,
      current-plan, current-status, current-expiry, plans-container,
      subscription-message, payment-history-table.
    - #subscription-error / #subscription-loading / #subscription-content /
      #subscription-message use plain inline style="display:none" matching
      what the JS toggles.
    - subscription.js: same endpoints (GET /staff/subscription, GET
      /staff/subscription-plans, GET /staff/subscription/payment-history,
      POST /staff/subscription with subscription_plan_id/duration_months),
      same authorization_url redirect for external payment, same confirm()
      dialog, same 422 error handling. The subscription-message success/error
      styling previously relied on .alert-success/.alert-error classes from
      app.css — since that's gone, the JS now sets those colors via inline
      style directly (see change summary in subscription.js).
--}}
<x-layouts.staff title="Subscription" active="subscription">

    <div id="subscription-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Subscription</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Manage your MedMart plan and billing</p>
    </div>

    <div id="subscription-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3 mb-4">
            <div class="skel h-6 w-1/3"></div>
            <div class="skel h-14 w-full"></div>
        </div>
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="skel h-24 w-full"></div>
        </div>
    </div>

    <div id="subscription-content" style="display: none;">

        <div id="subscription-message" style="display: none;" class="rounded-xl font-inter text-[13px] px-4 py-3 mb-5"></div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Current Subscription</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 gap-y-5">
                <div>
                    <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1">Plan</p>
                    <p class="font-inter text-[14px] text-[#171E26]"><span id="current-plan">No active plan</span></p>
                </div>
                <div>
                    <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1">Status</p>
                    <p class="font-inter text-[14px] text-[#171E26]"><span id="current-status">N/A</span></p>
                </div>
                <div>
                    <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1">Expires</p>
                    <p class="font-inter text-[14px] text-[#171E26]"><span id="current-expiry">N/A</span></p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Available Plans</h3>
            <div id="plans-container" class="space-y-4"></div>
        </div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Payment History</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Reference</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Plan</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Amount</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody id="payment-history-table"></tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/subscription.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>