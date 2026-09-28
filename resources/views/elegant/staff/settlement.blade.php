{{--
    Intended path: resources/views/staff/settlement.blade.php

    CHANGE SUMMARY:
    - Every ID settlement.js binds to is preserved: settlement-error,
      settlement-loading, settlement-content, current-account-info,
      account-form, bank-id, account-number, account-name,
      account-form-error, account-submit-btn, account-status.
    - REDESIGNED per your layout: the account-form is no longer always
      visible on the page — it now lives in #account-modal, opened via
      the "Update Account"/"Add Account" button that settlement.js
      renders inside #current-account-info.
    - NEW sections/IDs (none of these existed before, no JS bindings to
      preserve): the Customer Order Revenue hero card (#revenue-amount,
      #revenue-toggle-btn, #revenue-toggle-icon, #revenue-refresh-btn,
      #revenue-updated-text), the Settlement Status card (reuses
      #account-status), the 3 stat tiles (#stats-grid), and Settlement
      History (#history-status-filter, #history-date-filter,
      #history-table-body).
    - Several pieces of this layout aren't backed by any endpoint in the
      API spec — mocked with static data in settlement.js. Full list in
      my reply.
--}}
<x-layouts.staff title="Settlement Account" active="settlement">

    <div id="settlement-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Settlement Account</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Manage your pharmacy payouts and settlement activity.</p>
    </div>

    <div id="settlement-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3 mb-4">
            <div class="skel h-5 w-1/4"></div>
            <div class="skel h-10 w-1/2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
                <div class="skel h-16 w-full"></div>
            </div>
            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
                <div class="skel h-16 w-full"></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="skel h-24 w-full"></div>
        </div>
    </div>

    <div id="settlement-content" style="display: none;" class="w-full">

        {{-- Customer Order Revenue hero card (MOCK — see change summary) --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7 mb-5">
            <div class="flex items-center justify-between mb-1">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Customer Order Revenue</p>
                <div class="flex items-center gap-1.5">
                    <button type="button" id="revenue-toggle-btn" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50" aria-label="Toggle amount visibility">
                        <i class="ph-light ph-eye text-[17px]" id="revenue-toggle-icon"></i>
                    </button>
                    <button type="button" id="revenue-refresh-btn" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50" aria-label="Refresh">
                        <i class="ph-light ph-arrow-clockwise text-[17px]"></i>
                    </button>
                </div>
            </div>
            <p class="font-manrope font-extrabold text-[30px] md:text-[34px] text-[#171E26] mb-1.5" id="revenue-amount">₦0</p>
            <p class="font-inter text-[13px] text-[#171E26]/45 mb-3">Total revenue generated from customer orders</p>
            <div class="flex items-center gap-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-[#2E9E5B]"></span>
                <span class="font-inter text-[12px] text-[#171E26]/40" id="revenue-updated-text">Last updated 2 minutes ago</span>
            </div>
        </div>

        {{-- Settlement Account (real) + Settlement Status (real status, mock next-settlement date) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7">
                <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Settlement Account</h3>
                <div id="current-account-info"></div>
            </div>
            <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7">
                <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Settlement Status</h3>
                <div id="account-status"></div>
            </div>
        </div>

        {{-- Stat tiles (MOCK — see change summary) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5" id="stats-grid"></div>

        {{-- Settlement History (MOCK data, real client-side filters) --}}
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Settlement History</h3>
                <div class="flex items-center gap-2">
                    <select id="history-date-filter" class="field-input py-1.5! text-[12px]! w-[130px]">
                        <option value="">All dates</option>
                    </select>
                    <select id="history-status-filter" class="field-input py-1.5! text-[12px]! w-[130px]">
                        <option value="">All statuses</option>
                        <option value="settled">Settled</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px]">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Date</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Amount</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Reference</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Update/Add Account modal --}}
    <div id="account-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[460px] max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Update Settlement Account</h3>
                <button type="button" id="close-account-modal-btn" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50"><i class="ph ph-x text-lg"></i></button>
            </div>

            <div id="account-form-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

            <form id="account-form" class="space-y-4">
                <div>
                    <label for="bank-id" class="field-label">Bank Name</label>
                    <select id="bank-id" required class="field-input">
                        <option value="">Select Bank</option>
                    </select>
                </div>
                <div>
                    <label for="account-number" class="field-label">Account Number</label>
                    <input type="text" id="account-number" required class="field-input">
                </div>
                <div>
                    <label for="account-name" class="field-label">Account Name</label>
                    <input type="text" id="account-name" required class="field-input">
                </div>
                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" id="cancel-account-modal-btn" class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</button>
                    <button type="submit" id="account-submit-btn"
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 disabled:opacity-60">Save Settlement Account</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/settlement.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>