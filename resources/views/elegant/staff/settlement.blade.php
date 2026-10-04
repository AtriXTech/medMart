<x-layouts.staff title="Settlement" active="settlement">
    <div id="settlement-error" class="mb-4 rounded-xl border border-[#F5C6C2] bg-[#FDEDEC] px-4 py-3 font-inter text-[13px] text-[#9C3A32]" style="display: none;"></div>
    <div id="settlement-success" class="mb-4 rounded-xl border border-[#BFE6CF] bg-[#E9F8EF] px-4 py-3 font-inter text-[13px] text-[#1F7A44]" style="display: none;"></div>

    <div id="settlement-loading" class="py-16 text-center font-inter text-[13px] text-[#171E26]/50">Loading settlement...</div>

    <div id="settlement-content" style="display: none;">
        <div id="notice-area" class="mb-5 space-y-3"></div>

        <div class="mb-5 rounded-3xl p-6 text-white shadow-sm md:p-8" style="background: linear-gradient(135deg, #2775E4 0%, #08AEBC 100%);">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-inter text-[12px] font-semibold uppercase tracking-wide text-white/70">Available for payout</p>
                    <div class="mt-2 flex items-center gap-3">
                        <p id="revenue-amount" class="font-manrope text-[30px] font-extrabold leading-none md:text-[38px]">₦0.00</p>
                        <button type="button" id="revenue-toggle-btn" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 hover:bg-white/25" aria-label="Show or hide balance">
                            <i id="revenue-toggle-icon" class="ph-light ph-eye text-[17px]"></i>
                        </button>
                    </div>
                </div>
                <button type="button" id="revenue-refresh-btn" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 hover:bg-white/25" aria-label="Refresh">
                    <i class="ph-light ph-arrows-clockwise text-[17px]"></i>
                </button>
            </div>
            <p id="next-payout-text" class="mt-4 font-inter text-[13px] text-white/85"></p>
            <p id="revenue-updated-text" class="mt-1 font-inter text-[11.5px] text-white/60"></p>
        </div>

        <div id="stats-grid" class="mb-5 grid grid-cols-2 gap-3 md:grid-cols-4"></div>

        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                <p class="mb-3 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Settlement Account</p>
                <div id="current-account-info"></div>
            </div>
            <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                <p class="mb-3 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Account Status</p>
                <div id="account-status"></div>
            </div>
        </div>

        <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-2 border-b border-[#EAF1FB]">
                <button type="button" data-tab="settlements" class="tab-btn -mb-px border-b-2 px-3 py-2 font-inter text-[13px] font-semibold">Settlements</button>
                <button type="button" data-tab="ledger" class="tab-btn -mb-px border-b-2 px-3 py-2 font-inter text-[13px] font-semibold">Transactions</button>
            </div>

            <div id="tab-settlements">
                <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                    <select id="history-status-filter" class="rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 font-inter text-[13px] text-[#171E26]">
                        <option value="">All statuses</option>
                        <option value="success">Paid</option>
                        <option value="processing">Processing</option>
                        <option value="pending">Queued</option>
                        <option value="on_hold">On hold</option>
                        <option value="failed">Failed</option>
                        <option value="reversed">Reversed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <input type="date" id="history-from" class="rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 font-inter text-[13px] text-[#171E26]" aria-label="From date">
                    <input type="date" id="history-to" class="rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 font-inter text-[13px] text-[#171E26]" aria-label="To date">
                    <input type="text" id="history-search" placeholder="Search reference" class="rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 font-inter text-[13px] text-[#171E26]">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-[#EAF1FB]">
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Date</th>
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Amount</th>
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Status</th>
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Reference</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body"></tbody>
                    </table>
                </div>
                <div id="history-pagination" class="mt-4 flex items-center justify-between"></div>
            </div>

            <div id="tab-ledger" style="display: none;">
                <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                    <select id="ledger-type-filter" class="rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 font-inter text-[13px] text-[#171E26]">
                        <option value="">All types</option>
                        <option value="sale_credit">Sales</option>
                        <option value="gateway_fee">Processing fees</option>
                        <option value="platform_fee">Platform commission</option>
                        <option value="refund_debit">Refunds</option>
                        <option value="fee_reversal">Fee reversals</option>
                        <option value="payout_debit">Payouts</option>
                        <option value="payout_reversal">Payout reversals</option>
                        <option value="adjustment">Adjustments</option>
                    </select>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-[#EAF1FB]">
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Date</th>
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Type</th>
                                <th class="py-2 pr-4 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Order</th>
                                <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="ledger-table-body"></tbody>
                    </table>
                </div>
                <div id="ledger-pagination" class="mt-4 flex items-center justify-between"></div>
            </div>
        </div>
    </div>

    <div id="account-modal" class="fixed inset-0 z-50 items-center justify-center bg-black/40 p-4" style="display: none;">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-manrope text-[17px] font-bold text-[#171E26]">Settlement Account</p>
                <button type="button" id="close-account-modal-btn" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-[#F3F7FC]" aria-label="Close">
                    <i class="ph-light ph-x text-[17px]"></i>
                </button>
            </div>
            <p class="mb-4 font-inter text-[12.5px] leading-relaxed text-[#171E26]/60">We verify the account name with your bank. It should match your pharmacy's registered name. New accounts are reviewed before payouts begin.</p>
            <div id="account-form-error" class="mb-3 rounded-xl border border-[#F5C6C2] bg-[#FDEDEC] px-4 py-3 font-inter text-[13px] text-[#9C3A32]" style="display: none;"></div>
            <form id="account-form">
                <div class="mb-4">
                    <label for="bank-id" class="mb-1.5 block font-inter text-[12px] font-semibold text-[#171E26]/70">Bank</label>
                    <select id="bank-id" required class="w-full rounded-xl border border-[#EAF1FB] bg-white px-3 py-2.5 font-inter text-[13px] text-[#171E26]">
                        <option value="">Select Bank</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="account-number" class="mb-1.5 block font-inter text-[12px] font-semibold text-[#171E26]/70">Account Number</label>
                    <input type="text" id="account-number" required inputmode="numeric" maxlength="10" pattern="[0-9]{10}" placeholder="10-digit NUBAN" class="w-full rounded-xl border border-[#EAF1FB] bg-white px-3 py-2.5 font-inter text-[13px] text-[#171E26]">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-account-modal-btn" class="rounded-xl border border-[#EAF1FB] px-4 py-2 font-inter text-[12.5px] font-semibold text-[#171E26]/70 hover:bg-[#F3F7FC]">Cancel</button>
                    <button type="submit" id="account-submit-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 font-inter text-[12.5px] font-semibold text-white disabled:opacity-60">Verify &amp; Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detail-modal" class="fixed inset-0 z-50 items-center justify-center bg-black/40 p-4" style="display: none;">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <p class="font-manrope text-[17px] font-bold text-[#171E26]">Settlement Details</p>
                <button type="button" id="close-detail-modal-btn" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-[#F3F7FC]" aria-label="Close">
                    <i class="ph-light ph-x text-[17px]"></i>
                </button>
            </div>
            <div id="detail-body"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/settlement.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>
