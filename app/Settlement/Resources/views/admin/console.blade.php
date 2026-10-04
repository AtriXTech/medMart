<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Settlement Admin Console</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/light/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        manrope: ['Manrope', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-[#F5F8FD] font-inter text-[#171E26]">
    <header class="border-b border-[#EAF1FB] bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 md:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background: linear-gradient(135deg, #2775E4 0%, #08AEBC 100%);">
                    <i class="ph-light ph-bank text-[20px] text-white"></i>
                </div>
                <div>
                    <p class="font-manrope text-[17px] font-extrabold leading-tight">Settlement Admin Console</p>
                    <p class="text-[12px] text-[#171E26]/50">Test and operate payouts</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <input type="password" id="token-input" placeholder="Super admin API token" autocomplete="off" class="w-64 rounded-xl border border-[#EAF1FB] bg-white px-3 py-2 text-[13px]">
                <button type="button" id="token-save-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 text-[12.5px] font-semibold text-white">Connect</button>
                <button type="button" id="token-clear-btn" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold text-[#171E26]/70 hover:bg-[#F3F7FC]">Clear</button>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 pb-2 md:px-6">
            <p id="token-status" class="text-[12px] text-[#171E26]/50">Not connected. Generate a token with: php artisan settlement:admin-token your-admin@email.com</p>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 md:px-6">
        <div class="mb-5 flex flex-wrap gap-1 border-b border-[#EAF1FB]">
            <button type="button" data-tab="controls" class="tab-btn -mb-px border-b-2 px-4 py-2.5 text-[13px] font-semibold">Controls</button>
            <button type="button" data-tab="accounts" class="tab-btn -mb-px border-b-2 px-4 py-2.5 text-[13px] font-semibold">Account Review</button>
            <button type="button" data-tab="settlements" class="tab-btn -mb-px border-b-2 px-4 py-2.5 text-[13px] font-semibold">Settlements</button>
            <button type="button" data-tab="payments" class="tab-btn -mb-px border-b-2 px-4 py-2.5 text-[13px] font-semibold">Payments</button>
            <button type="button" data-tab="pharmacy" class="tab-btn -mb-px border-b-2 px-4 py-2.5 text-[13px] font-semibold">Pharmacy</button>
        </div>

        <div id="gateway-banner" class="mb-5 rounded-xl border px-4 py-3 text-[13px]" style="display: none;"></div>

        <section id="panel-controls" class="space-y-5">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Payouts</p>
                    <div id="payout-state" class="mb-4"></div>
                    <div class="flex gap-2">
                        <button type="button" id="pause-btn" class="rounded-xl bg-[#9C3A32] px-4 py-2 text-[12.5px] font-semibold text-white">Pause all payouts</button>
                        <button type="button" id="resume-btn" class="rounded-xl bg-[#1F7A44] px-4 py-2 text-[12.5px] font-semibold text-white">Resume payouts</button>
                    </div>
                    <div id="config-readout" class="mt-5 grid grid-cols-3 gap-3"></div>
                </div>

                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Commission and fees</p>
                    <form id="commission-form" class="space-y-3">
                        <label class="flex items-center gap-2 text-[13px] font-semibold">
                            <input type="checkbox" id="commission-enabled" class="h-4 w-4"> Take commission on sales
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="commission-rate" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Rate (%)</label>
                                <input type="number" id="commission-rate" min="0" max="100" step="0.01" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            </div>
                            <div>
                                <label for="commission-flat" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Flat (kobo)</label>
                                <input type="number" id="commission-flat" min="0" step="1" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            </div>
                            <div>
                                <label for="commission-cap" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Cap (kobo)</label>
                                <input type="number" id="commission-cap" min="0" step="1" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            </div>
                        </div>
                        <div>
                            <label for="fee-bearer" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Paystack fee is paid by</label>
                            <select id="fee-bearer" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                                <option value="pharmacy">Pharmacy (deducted from payout)</option>
                                <option value="platform">Platform (absorbed)</option>
                            </select>
                        </div>
                        <p class="text-[12px] text-[#171E26]/45">Changes apply to payments recorded from now on. Cap 0 means no cap.</p>
                        <button type="submit" id="commission-save-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 text-[12.5px] font-semibold text-white disabled:opacity-60">Save settings</button>
                    </form>
                </div>
            </div>

            <div id="gateway-card" class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm" style="display: none;">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Paystack balance (live from Paystack)</p>
                        <p id="gateway-balance-text" class="font-manrope text-[20px] font-extrabold"></p>
                        <p id="gateway-error-text" class="mt-1 text-[12px] text-[#9C3A32]"></p>
                    </div>
                    <button type="button" id="gateway-refresh-btn" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC] disabled:opacity-60">Refresh balance</button>
                </div>
                <p class="mt-3 text-[12px] text-[#171E26]/45">Transfers need this balance to cover the payout plus Paystack's transfer fee. Top up your test balance from the Paystack dashboard (test mode) if it is too low.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Run settlement batch</p>
                    <p class="mb-3 text-[12.5px] text-[#171E26]/60">Creates settlements from eligible payments and dispatches transfers. With a pharmacy ID, the pharmacy schedule is ignored.</p>
                    <div class="flex gap-2">
                        <input type="number" id="run-pharmacy-id" min="1" placeholder="Pharmacy ID (optional)" class="w-56 rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                        <button type="button" id="run-batch-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 text-[12.5px] font-semibold text-white disabled:opacity-60">Run now</button>
                    </div>
                </div>

                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Maintenance jobs</p>
                    <div class="mb-3 flex flex-wrap gap-2">
                        <button type="button" data-job="record-payments" class="job-btn rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Record missed payments</button>
                        <button type="button" data-job="promote-eligible" class="job-btn rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Promote eligible</button>
                        <button type="button" data-job="reconcile-transfers" class="job-btn rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Reconcile transfers</button>
                        <button type="button" data-job="reconcile-ledger" class="job-btn rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Reconcile ledger</button>
                    </div>
                    <div class="mb-3 flex items-center gap-2">
                        <label for="job-since" class="text-[12px] text-[#171E26]/60">Record payments since</label>
                        <input type="date" id="job-since" class="rounded-xl border border-[#EAF1FB] px-3 py-1.5 text-[12.5px]">
                    </div>
                    <pre id="job-output" class="max-h-48 overflow-auto rounded-xl bg-[#F5F8FD] p-3 text-[12px] text-[#171E26]/70" style="display: none;"></pre>
                </div>
            </div>
        </section>

        <section id="panel-accounts" class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm" style="display: none;">
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <select id="accounts-status-filter" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="superseded">Superseded</option>
                    <option value="">All</option>
                </select>
                <button type="button" id="accounts-refresh-btn" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Refresh</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Pharmacy</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Bank</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Account</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Name match</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Status</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Submitted</th>
                            <th class="py-2 text-right text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="accounts-body"></tbody>
                </table>
            </div>
            <div id="accounts-pagination" class="mt-4 flex items-center justify-between"></div>
        </section>

        <section id="panel-settlements" class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm" style="display: none;">
            <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-5">
                <select id="settlements-status-filter" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="on_hold">On hold</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="reversed">Reversed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <input type="number" id="settlements-pharmacy-filter" min="1" placeholder="Pharmacy ID" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                <input type="text" id="settlements-search" placeholder="Reference" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                <button type="button" id="settlements-refresh-btn" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Refresh</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Reference</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Pharmacy</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Net</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Status</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Tries</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Created</th>
                            <th class="py-2 text-right text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="settlements-body"></tbody>
                </table>
            </div>
            <div id="settlements-pagination" class="mt-4 flex items-center justify-between"></div>
        </section>

        <section id="panel-payments" class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm" style="display: none;">
            <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-5">
                <select id="payments-status-filter" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                    <option value="">All statuses</option>
                    <option value="unrecorded">Paid, not recorded</option>
                    <option value="pending">Pending (hold window)</option>
                    <option value="eligible">Eligible</option>
                    <option value="settled">Settled</option>
                    <option value="held">Held</option>
                    <option value="refunded">Refunded</option>
                </select>
                <input type="number" id="payments-pharmacy-filter" min="1" placeholder="Pharmacy ID" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                <input type="number" id="payments-order-filter" min="1" placeholder="Order ID" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                <input type="text" id="payments-search" placeholder="Reference" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                <button type="button" id="payments-refresh-btn" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[12.5px] font-semibold hover:bg-[#F3F7FC]">Refresh</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Payment</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Pharmacy</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Gross</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Fees</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Net</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Status</th>
                            <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Eligible at</th>
                            <th class="py-2 text-right text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payments-body"></tbody>
                </table>
            </div>
            <div id="payments-pagination" class="mt-4 flex items-center justify-between"></div>
        </section>

        <section id="panel-pharmacy" class="space-y-5" style="display: none;">
            <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <input type="number" id="pharmacy-id-input" min="1" placeholder="Pharmacy ID" class="w-48 rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                    <button type="button" id="pharmacy-load-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 text-[12.5px] font-semibold text-white">Load</button>
                    <p id="pharmacy-name" class="font-manrope text-[15px] font-bold"></p>
                </div>
            </div>

            <div id="pharmacy-detail" style="display: none;" class="space-y-5">
                <div id="pharmacy-balance" class="grid grid-cols-2 gap-3 md:grid-cols-4"></div>

                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Payout settings</p>
                    <form id="pharmacy-form" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-[13px] font-semibold">
                                <input type="checkbox" id="pharmacy-hold" class="h-4 w-4"> Hold payouts for this pharmacy
                            </label>
                            <input type="text" id="pharmacy-hold-reason" maxlength="255" placeholder="Hold reason" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            <p id="pharmacy-hold-info" class="text-[12px] text-[#171E26]/50"></p>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label for="pharmacy-commission" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Commission override (%), blank uses the global rate</label>
                                <input type="number" id="pharmacy-commission" min="0" max="100" step="0.01" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            </div>
                            <div>
                                <label for="pharmacy-schedule" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">Payout schedule</label>
                                <select id="pharmacy-schedule" class="w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="manual">Manual only</option>
                                </select>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" id="pharmacy-save-btn" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 text-[12.5px] font-semibold text-white disabled:opacity-60">Save payout settings</button>
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-[#EAF1FB] bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Ledger</p>
                        <select id="ledger-type-filter" class="rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]">
                            <option value="">All types</option>
                            <option value="sale_credit">sale_credit</option>
                            <option value="gateway_fee">gateway_fee</option>
                            <option value="platform_fee">platform_fee</option>
                            <option value="refund_debit">refund_debit</option>
                            <option value="fee_reversal">fee_reversal</option>
                            <option value="payout_debit">payout_debit</option>
                            <option value="payout_reversal">payout_reversal</option>
                            <option value="adjustment">adjustment</option>
                        </select>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-[#EAF1FB]">
                                    <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Date</th>
                                    <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Type</th>
                                    <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Payment</th>
                                    <th class="py-2 pr-4 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Settlement</th>
                                    <th class="py-2 text-right text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="ledger-body"></tbody>
                        </table>
                    </div>
                    <div id="ledger-pagination" class="mt-4 flex items-center justify-between"></div>
                </div>
            </div>
        </section>
    </main>

    <div id="toast-area" class="fixed bottom-4 right-4 z-[70] space-y-2"></div>

    <script>
        const API = @json(url('/api/v1'));
        const TOKEN_KEY = 'settlement_admin_token';
        const PAGE_SIZE = 20;

        const ui = {
            tokenInput: document.getElementById('token-input'),
            tokenStatus: document.getElementById('token-status'),
            toastArea: document.getElementById('toast-area'),
        };

        const state = {
            tab: 'controls',
            loaded: {},
            controls: null,
            gateway: { mode: null, balance_kobo: null, error: null },
            pharmacyId: null,
            pages: {
                accounts: { page: 1, lastPage: 1, total: 0 },
                settlements: { page: 1, lastPage: 1, total: 0 },
                payments: { page: 1, lastPage: 1, total: 0 },
                ledger: { page: 1, lastPage: 1, total: 0 },
            },
            searchTimers: {},
        };

        const STATUS_STYLES = {
            pending: ['#FFF8EC', '#8A6116'],
            processing: ['#EAF1FB', '#2775E4'],
            on_hold: ['#FFF1E6', '#B4540A'],
            held: ['#FFF1E6', '#B4540A'],
            eligible: ['#EAF1FB', '#2775E4'],
            success: ['#E9F8EF', '#1F7A44'],
            approved: ['#E9F8EF', '#1F7A44'],
            settled: ['#E9F8EF', '#1F7A44'],
            failed: ['#FDEDEC', '#9C3A32'],
            reversed: ['#FDEDEC', '#9C3A32'],
            rejected: ['#FDEDEC', '#9C3A32'],
            cancelled: ['#F1F3F5', '#5B6570'],
            superseded: ['#F1F3F5', '#5B6570'],
            refunded: ['#F1F3F5', '#5B6570'],
        };

        function esc(value) {
            return String(value === null || value === undefined ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function money(kobo) {
            const value = Number(kobo || 0) / 100;
            const sign = value < 0 ? '-' : '';
            return sign + '₦' + Math.abs(value).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function dateTime(iso) {
            if (!iso) return '—';
            const date = new Date(iso);
            if (Number.isNaN(date.getTime())) return '—';
            return date.toLocaleString('en-NG', { day: 'numeric', month: 'short', hour: 'numeric', minute: '2-digit' });
        }

        function badge(status) {
            const style = STATUS_STYLES[status] || ['#F1F3F5', '#5B6570'];
            return `<span class="inline-block rounded-full px-2.5 py-1 text-[11px] font-semibold" style="background:${style[0]};color:${style[1]}">${esc(status || '—')}</span>`;
        }

        function getToken() {
            return sessionStorage.getItem(TOKEN_KEY) || '';
        }

        async function request(method, path, body) {
            const headers = { Accept: 'application/json' };
            const token = getToken();
            if (token) headers.Authorization = 'Bearer ' + token;
            const options = { method: method, headers: headers };
            if (body !== undefined) {
                headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(body);
            }

            let response;
            try {
                response = await fetch(API + path, options);
            } catch (networkError) {
                throw { status: 0, message: 'Network error. Check your connection.' };
            }

            let data = null;
            try {
                data = await response.json();
            } catch (parseError) {
                data = null;
            }

            if (!response.ok) {
                let message = (data && data.message) || 'Request failed (' + response.status + ').';
                if (data && data.errors) {
                    message = Object.values(data.errors).flat().join(', ');
                }
                if (response.status === 401) {
                    message = 'Unauthenticated. Connect with a valid super admin token.';
                }
                throw { status: response.status, data: data, message: message };
            }

            return data;
        }

        const api = {
            get: function (path) { return request('GET', path); },
            post: function (path, body) { return request('POST', path, body === undefined ? {} : body); },
            put: function (path, body) { return request('PUT', path, body); },
            patch: function (path, body) { return request('PATCH', path, body); },
        };

        function query(params) {
            const search = new URLSearchParams();
            Object.keys(params).forEach(function (key) {
                const value = params[key];
                if (value !== '' && value !== null && value !== undefined) search.set(key, String(value));
            });
            const text = search.toString();
            return text ? '?' + text : '';
        }

        function toast(message, kind) {
            const colors = kind === 'error' ? ['#FDEDEC', '#9C3A32', '#F5C6C2'] : ['#E9F8EF', '#1F7A44', '#BFE6CF'];
            const node = document.createElement('div');
            node.className = 'max-w-sm rounded-xl border px-4 py-3 text-[13px] shadow-lg';
            node.style.background = colors[0];
            node.style.color = colors[1];
            node.style.borderColor = colors[2];
            node.textContent = message;
            ui.toastArea.appendChild(node);
            window.setTimeout(function () { node.remove(); }, kind === 'error' ? 9000 : 5000);
        }

        function fail(error, fallback) {
            toast((error && error.message) || fallback, 'error');
        }

        function fieldHtml(field) {
            const label = field.label ? `<label for="ask-${esc(field.name)}" class="mb-1 block text-[12px] font-semibold text-[#171E26]/60">${esc(field.label)}</label>` : '';
            const base = 'w-full rounded-xl border border-[#EAF1FB] px-3 py-2 text-[13px]';
            if (field.type === 'checkbox') {
                return `<label class="flex items-center gap-2 text-[13px] font-semibold"><input type="checkbox" id="ask-${esc(field.name)}" name="${esc(field.name)}" class="h-4 w-4" ${field.value ? 'checked' : ''}> ${esc(field.label)}</label>`;
            }
            if (field.type === 'textarea') {
                return `<div>${label}<textarea id="ask-${esc(field.name)}" name="${esc(field.name)}" rows="3" maxlength="255" class="${base}" placeholder="${esc(field.placeholder || '')}" ${field.required ? 'required' : ''}>${esc(field.value || '')}</textarea></div>`;
            }
            return `<div>${label}<input type="${esc(field.type || 'text')}" id="ask-${esc(field.name)}" name="${esc(field.name)}" class="${base}" placeholder="${esc(field.placeholder || '')}" value="${esc(field.value || '')}" ${field.required ? 'required' : ''}></div>`;
        }

        function ask(config) {
            return new Promise(function (resolve) {
                const fields = config.fields || [];
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 z-[60] flex items-center justify-center bg-black/40 p-4';
                overlay.innerHTML = `<form class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                    <p class="mb-1 font-manrope text-[17px] font-bold">${esc(config.title)}</p>
                    ${config.message ? `<p class="mb-4 text-[12.5px] leading-relaxed text-[#171E26]/60">${esc(config.message)}</p>` : '<div class="mb-3"></div>'}
                    <div class="mb-5 space-y-3">${fields.map(fieldHtml).join('')}</div>
                    <div class="flex justify-end gap-2">
                        <button type="button" data-cancel class="rounded-xl border border-[#EAF1FB] px-4 py-2 text-[12.5px] font-semibold text-[#171E26]/70 hover:bg-[#F3F7FC]">Cancel</button>
                        <button type="submit" class="rounded-xl px-4 py-2 text-[12.5px] font-semibold text-white" style="background:${config.danger ? '#9C3A32' : '#2775E4'}">${esc(config.confirmText || 'Confirm')}</button>
                    </div>
                </form>`;
                document.body.appendChild(overlay);

                const form = overlay.querySelector('form');

                function close(value) {
                    overlay.remove();
                    resolve(value);
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {};
                    fields.forEach(function (field) {
                        const input = form.elements[field.name];
                        values[field.name] = field.type === 'checkbox' ? input.checked : input.value.trim();
                    });
                    close(values);
                });
                overlay.querySelector('[data-cancel]').addEventListener('click', function () { close(null); });
                overlay.addEventListener('click', function (event) {
                    if (event.target === overlay) close(null);
                });

                const first = form.querySelector('input, textarea');
                if (first) first.focus();
            });
        }

        function showInfo(title, html) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-[60] flex items-center justify-center bg-black/40 p-4';
            overlay.innerHTML = `<div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <p class="font-manrope text-[17px] font-bold">${esc(title)}</p>
                    <button type="button" data-close class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-[#F3F7FC]" aria-label="Close"><i class="ph-light ph-x text-[17px]"></i></button>
                </div>
                <div>${html}</div>
            </div>`;
            document.body.appendChild(overlay);
            overlay.querySelector('[data-close]').addEventListener('click', function () { overlay.remove(); });
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) overlay.remove();
            });
        }

        function emptyRow(colspan, message) {
            return `<tr><td colspan="${colspan}" class="py-10 text-center text-[13px] text-[#171E26]/45">${esc(message)}</td></tr>`;
        }

        const pagerLoaders = {};

        function renderPager(containerId, info, loader) {
            const container = document.getElementById(containerId);
            pagerLoaders[containerId] = loader;
            if (!info.total) {
                container.innerHTML = '';
                return;
            }
            container.innerHTML = `<p class="text-[12px] text-[#171E26]/45">Page ${info.page} of ${info.lastPage} · ${info.total} records</p>
                <div class="flex gap-2">
                    <button type="button" data-page="${info.page - 1}" ${info.page <= 1 ? 'disabled' : ''} class="rounded-lg border border-[#EAF1FB] px-3 py-1.5 text-[12px] font-semibold text-[#171E26]/70 disabled:opacity-40">Previous</button>
                    <button type="button" data-page="${info.page + 1}" ${info.page >= info.lastPage ? 'disabled' : ''} class="rounded-lg border border-[#EAF1FB] px-3 py-1.5 text-[12px] font-semibold text-[#171E26]/70 disabled:opacity-40">Next</button>
                </div>`;
        }

        ['accounts-pagination', 'settlements-pagination', 'payments-pagination', 'ledger-pagination'].forEach(function (id) {
            document.getElementById(id).addEventListener('click', function (event) {
                const button = event.target.closest('button[data-page]');
                if (button && !button.disabled && pagerLoaders[id]) pagerLoaders[id](parseInt(button.dataset.page, 10));
            });
        });

        function pageInfo(response, page) {
            const meta = response.meta || {};
            return {
                page: meta.current_page || page,
                lastPage: meta.last_page || 1,
                total: meta.total || (response.data || []).length,
            };
        }

        function actionButton(action, id, label, tone) {
            const colors = tone === 'danger' ? 'border-[#F5C6C2] text-[#9C3A32] hover:bg-[#FDEDEC]' : (tone === 'primary' ? 'border-[#C9DBF6] text-[#2775E4] hover:bg-[#EAF1FB]' : 'border-[#EAF1FB] text-[#171E26]/70 hover:bg-[#F3F7FC]');
            return `<button type="button" data-action="${action}" data-id="${id}" class="ml-1 rounded-lg border px-2.5 py-1 text-[11.5px] font-semibold ${colors}">${esc(label)}</button>`;
        }

        function setToken() {
            const value = ui.tokenInput.value.trim();
            if (!value) {
                toast('Paste an API token first.', 'error');
                return;
            }
            sessionStorage.setItem(TOKEN_KEY, value);
            ui.tokenInput.value = '';
            connect();
        }

        async function connect() {
            if (!getToken()) {
                ui.tokenStatus.textContent = 'Not connected. Generate a token with: php artisan settlement:admin-token your-admin@email.com';
                return;
            }
            ui.tokenStatus.textContent = 'Connecting...';
            try {
                await loadControls();
                ui.tokenStatus.textContent = 'Connected as super admin.';
                state.loaded = { controls: true };
            } catch (error) {
                ui.tokenStatus.textContent = error.message;
                fail(error, 'Unable to connect.');
            }
        }

        document.getElementById('token-save-btn').addEventListener('click', setToken);
        ui.tokenInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') setToken();
        });
        document.getElementById('token-clear-btn').addEventListener('click', function () {
            sessionStorage.removeItem(TOKEN_KEY);
            state.loaded = {};
            connect();
        });

        const payoutState = document.getElementById('payout-state');
        const configReadout = document.getElementById('config-readout');
        const commissionForm = document.getElementById('commission-form');
        const commissionSaveBtn = document.getElementById('commission-save-btn');

        function renderControls() {
            const c = state.controls;
            if (!c) return;

            payoutState.innerHTML = c.payouts_paused
                ? `<div class="rounded-xl border border-[#F5C6C2] bg-[#FDEDEC] px-4 py-3 text-[13px] text-[#9C3A32]"><p class="font-semibold">Payouts are PAUSED</p><p class="mt-0.5">${esc(c.pause_reason || 'No reason given')}</p></div>`
                : `<div class="rounded-xl border border-[#BFE6CF] bg-[#E9F8EF] px-4 py-3 text-[13px] font-semibold text-[#1F7A44]">Payouts are active</div>`;

            document.getElementById('pause-btn').style.display = c.payouts_paused ? 'none' : 'inline-block';
            document.getElementById('resume-btn').style.display = c.payouts_paused ? 'inline-block' : 'none';

            const readout = function (label, value) {
                return `<div class="rounded-xl bg-[#F5F8FD] p-3"><p class="text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">${esc(label)}</p><p class="mt-1 text-[13px] font-semibold">${value}</p></div>`;
            };
            configReadout.innerHTML =
                readout('Minimum payout', money(c.minimum_payout_kobo)) +
                readout('Payout ceiling', c.max_single_payout_kobo > 0 ? money(c.max_single_payout_kobo) : 'None') +
                readout('Hold window', esc(c.hold_hours) + ' hours');

            document.getElementById('commission-enabled').checked = !!c.commission_enabled;
            document.getElementById('commission-rate').value = c.commission_rate;
            document.getElementById('commission-flat').value = c.commission_flat_kobo;
            document.getElementById('commission-cap').value = c.commission_cap_kobo;
            document.getElementById('fee-bearer').value = c.gateway_fee_bearer;
        }

        function renderGateway() {
            const g = state.gateway;
            const banner = document.getElementById('gateway-banner');
            const card = document.getElementById('gateway-card');
            if (!g.mode) {
                banner.style.display = 'none';
                card.style.display = 'none';
                return;
            }
            const isTest = g.mode === 'test';
            banner.style.display = 'block';
            banner.style.background = isTest ? '#E9F8EF' : '#FDEDEC';
            banner.style.borderColor = isTest ? '#BFE6CF' : '#F5C6C2';
            banner.style.color = isTest ? '#1F7A44' : '#9C3A32';
            banner.innerHTML = isTest
                ? '<strong>Paystack TEST mode.</strong> No real money moves. Replay buttons are available.'
                : '<strong>Paystack LIVE mode.</strong> Transfers send real money. Replay buttons are disabled.';
            card.style.display = 'block';
            document.getElementById('gateway-balance-text').textContent = g.balance_kobo === null ? 'Unavailable' : money(g.balance_kobo);
            document.getElementById('gateway-error-text').textContent = g.error || '';
        }

        async function loadGateway() {
            try {
                const response = await api.get('/admin/settlement/gateway');
                state.gateway = response.data;
            } catch (error) {
                state.gateway = { mode: null, balance_kobo: null, error: error.message };
            }
            renderGateway();
        }

        async function loadControls() {
            const response = await api.get('/admin/settlement/controls');
            state.controls = response.data;
            renderControls();
            await loadGateway();
        }

        document.getElementById('gateway-refresh-btn').addEventListener('click', async function () {
            const button = this;
            button.disabled = true;
            await loadGateway();
            button.disabled = false;
        });

        document.getElementById('pause-btn').addEventListener('click', async function () {
            const values = await ask({
                title: 'Pause all payouts',
                message: 'No new settlements will be created and in-flight ones that have not been sent will be held.',
                fields: [{ name: 'reason', label: 'Reason', type: 'textarea', required: true }],
                confirmText: 'Pause payouts',
                danger: true,
            });
            if (!values) return;
            try {
                const response = await api.post('/admin/settlement/controls/pause', { reason: values.reason });
                state.controls = response.data;
                renderControls();
                toast('Payouts paused.');
            } catch (error) {
                fail(error, 'Unable to pause payouts.');
            }
        });

        document.getElementById('resume-btn').addEventListener('click', async function () {
            const values = await ask({ title: 'Resume payouts', message: 'Settlements held because of the pause will be released and sent.', confirmText: 'Resume payouts' });
            if (!values) return;
            try {
                const response = await api.post('/admin/settlement/controls/resume');
                state.controls = response.data;
                renderControls();
                toast('Payouts resumed. Released settlements: ' + response.released_settlements + '.');
            } catch (error) {
                fail(error, 'Unable to resume payouts.');
            }
        });

        commissionForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const rate = Number(document.getElementById('commission-rate').value || 0);
            const flat = Number(document.getElementById('commission-flat').value || 0);
            const cap = Number(document.getElementById('commission-cap').value || 0);
            if ([rate, flat, cap].some(Number.isNaN) || rate < 0 || rate > 100 || flat < 0 || cap < 0) {
                toast('Enter valid commission values.', 'error');
                return;
            }
            commissionSaveBtn.disabled = true;
            try {
                const response = await api.put('/admin/settlement/controls', {
                    commission_enabled: document.getElementById('commission-enabled').checked,
                    commission_rate: rate,
                    commission_flat_kobo: Math.round(flat),
                    commission_cap_kobo: Math.round(cap),
                    gateway_fee_bearer: document.getElementById('fee-bearer').value,
                });
                state.controls = response.data;
                renderControls();
                toast('Settings saved.');
            } catch (error) {
                fail(error, 'Unable to save settings.');
            } finally {
                commissionSaveBtn.disabled = false;
            }
        });

        document.getElementById('run-batch-btn').addEventListener('click', async function () {
            const button = this;
            const pharmacyId = document.getElementById('run-pharmacy-id').value.trim();
            button.disabled = true;
            try {
                const body = pharmacyId ? { pharmacy_id: parseInt(pharmacyId, 10) } : {};
                const response = await api.post('/admin/settlement/run', body);
                const data = response.data || {};
                const summary = pharmacyId
                    ? (data.created ? 'Settlement created: ' + data.reference : 'Nothing to settle for this pharmacy.')
                    : 'Created: ' + data.created + ', skipped: ' + data.skipped + ', failed: ' + data.failed + (data.paused ? ' (payouts paused)' : '');
                toast(summary);
                state.loaded.settlements = false;
            } catch (error) {
                fail(error, 'Unable to run the settlement batch.');
            } finally {
                button.disabled = false;
            }
        });

        document.querySelectorAll('.job-btn').forEach(function (button) {
            button.addEventListener('click', async function () {
                const job = button.dataset.job;
                const output = document.getElementById('job-output');
                button.disabled = true;
                output.style.display = 'block';
                output.textContent = 'Running ' + job + '...';
                try {
                    const body = {};
                    const since = document.getElementById('job-since').value;
                    if (job === 'record-payments' && since) body.since = since;
                    const response = await api.post('/admin/settlement/jobs/' + job, body);
                    const data = response.data || {};
                    output.textContent = '[' + data.job + '] exit code ' + data.exit_code + '\n' + (data.output || '(no output)');
                    state.loaded.payments = false;
                    state.loaded.settlements = false;
                } catch (error) {
                    output.textContent = error.message;
                    fail(error, 'Job failed.');
                } finally {
                    button.disabled = false;
                }
            });
        });

        const accountsBody = document.getElementById('accounts-body');

        function matchColor(score) {
            if (score === null || score === undefined) return '#5B6570';
            if (score >= 80) return '#1F7A44';
            if (score >= 50) return '#8A6116';
            return '#9C3A32';
        }

        async function loadAccounts(page) {
            accountsBody.innerHTML = emptyRow(7, 'Loading...');
            try {
                const response = await api.get('/admin/settlement/accounts' + query({
                    page: page,
                    per_page: PAGE_SIZE,
                    status: document.getElementById('accounts-status-filter').value,
                }));
                state.pages.accounts = pageInfo(response, page);
                const rows = response.data || [];
                accountsBody.innerHTML = rows.length ? rows.map(function (row) {
                    const actions = row.status === 'pending'
                        ? actionButton('approve', row.id, 'Approve', 'primary') + actionButton('reject', row.id, 'Reject', 'danger')
                        : '';
                    const pharmacy = row.pharmacy ? esc(row.pharmacy.name) + `<span class="block text-[11px] text-[#171E26]/40">ID ${esc(row.pharmacy.id)}</span>` : esc(row.pharmacy_id);
                    return `<tr class="border-b border-[#F3F7FC] last:border-0">
                        <td class="py-3 pr-4 text-[13px] font-semibold">${pharmacy}</td>
                        <td class="py-3 pr-4 text-[13px]">${esc(row.bank_name)}</td>
                        <td class="py-3 pr-4 text-[13px]">${esc(row.account_name)}<span class="block text-[12px] text-[#171E26]/50">${esc(row.account_number)}</span></td>
                        <td class="py-3 pr-4 text-[13px] font-semibold" style="color:${matchColor(row.name_match_score)}">${row.name_match_score === null ? '—' : esc(row.name_match_score) + '%'}</td>
                        <td class="py-3 pr-4">${badge(row.status)}${row.rejection_reason ? `<span class="block text-[11px] text-[#171E26]/45">${esc(row.rejection_reason)}</span>` : ''}</td>
                        <td class="py-3 pr-4 text-[12px] text-[#171E26]/50">${esc(dateTime(row.created_at))}</td>
                        <td class="py-3 text-right">${actions}</td>
                    </tr>`;
                }).join('') : emptyRow(7, 'No accounts found.');
                renderPager('accounts-pagination', state.pages.accounts, loadAccounts);
            } catch (error) {
                accountsBody.innerHTML = emptyRow(7, error.message);
            }
        }

        accountsBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button[data-action]');
            if (!button) return;
            const id = button.dataset.id;

            if (button.dataset.action === 'approve') {
                const values = await ask({ title: 'Approve settlement account', message: 'This creates a Paystack transfer recipient. If the pharmacy already has an approved account, payouts pause for the security cooldown.', confirmText: 'Approve' });
                if (!values) return;
                button.disabled = true;
                try {
                    await api.post('/admin/settlement/accounts/' + id + '/approve');
                    toast('Account approved.');
                    loadAccounts(state.pages.accounts.page);
                } catch (error) {
                    button.disabled = false;
                    fail(error, 'Unable to approve account.');
                }
            }

            if (button.dataset.action === 'reject') {
                const values = await ask({ title: 'Reject settlement account', fields: [{ name: 'reason', label: 'Reason shown to the pharmacy', type: 'textarea', required: true }], confirmText: 'Reject', danger: true });
                if (!values) return;
                button.disabled = true;
                try {
                    await api.post('/admin/settlement/accounts/' + id + '/reject', { reason: values.reason });
                    toast('Account rejected.');
                    loadAccounts(state.pages.accounts.page);
                } catch (error) {
                    button.disabled = false;
                    fail(error, 'Unable to reject account.');
                }
            }
        });

        document.getElementById('accounts-status-filter').addEventListener('change', function () { loadAccounts(1); });
        document.getElementById('accounts-refresh-btn').addEventListener('click', function () { loadAccounts(state.pages.accounts.page); });

        const settlementsBody = document.getElementById('settlements-body');

        function replayButton(id, event, label, tone) {
            return actionButton('replay', id, label, tone).replace('data-action="replay"', 'data-action="replay" data-event="' + event + '"');
        }

        function settlementActions(row) {
            const view = actionButton('view', row.id, 'View', 'neutral');
            const test = state.gateway.mode === 'test';
            switch (row.status) {
                case 'pending':
                    return view + actionButton('retry', row.id, 'Dispatch', 'primary') + actionButton('hold', row.id, 'Hold', 'neutral') + actionButton('cancel', row.id, 'Cancel', 'danger');
                case 'processing':
                    return view + actionButton('retry', row.id, 'Verify', 'primary') + (test ? replayButton(row.id, 'success', 'Replay success', 'neutral') + replayButton(row.id, 'failed', 'Replay failed', 'danger') : '');
                case 'on_hold':
                    return view + actionButton('release', row.id, 'Release', 'primary') + actionButton('cancel', row.id, 'Cancel', 'danger');
                case 'success':
                    return view + (test ? replayButton(row.id, 'reversed', 'Replay reversed', 'danger') : '');
                case 'failed':
                case 'reversed':
                case 'cancelled':
                    return view + actionButton('retry', row.id, 'Re-batch', 'primary');
                default:
                    return view;
            }
        }

        async function loadSettlements(page) {
            settlementsBody.innerHTML = emptyRow(7, 'Loading...');
            try {
                const response = await api.get('/admin/settlement/settlements' + query({
                    page: page,
                    per_page: PAGE_SIZE,
                    status: document.getElementById('settlements-status-filter').value,
                    pharmacy_id: document.getElementById('settlements-pharmacy-filter').value.trim(),
                    search: document.getElementById('settlements-search').value.trim(),
                }));
                state.pages.settlements = pageInfo(response, page);
                const rows = response.data || [];
                settlementsBody.innerHTML = rows.length ? rows.map(function (row) {
                    const note = row.status === 'on_hold' ? row.hold_reason : row.failure_reason;
                    return `<tr class="border-b border-[#F3F7FC] last:border-0">
                        <td class="py-3 pr-4 text-[12px]">${esc(row.reference)}</td>
                        <td class="py-3 pr-4 text-[13px]">${row.pharmacy ? esc(row.pharmacy.name) : esc(row.pharmacy_id)}<span class="block text-[11px] text-[#171E26]/40">ID ${esc(row.pharmacy_id)}</span></td>
                        <td class="py-3 pr-4 text-[13px] font-semibold">${money(row.net_kobo)}</td>
                        <td class="py-3 pr-4">${badge(row.status)}${note ? `<span class="block max-w-[220px] text-[11px] text-[#171E26]/45">${esc(note)}</span>` : ''}</td>
                        <td class="py-3 pr-4 text-[13px]">${esc(row.attempts)}</td>
                        <td class="py-3 pr-4 text-[12px] text-[#171E26]/50">${esc(dateTime(row.created_at))}</td>
                        <td class="py-3 text-right">${settlementActions(row)}</td>
                    </tr>`;
                }).join('') : emptyRow(7, 'No settlements found.');
                renderPager('settlements-pagination', state.pages.settlements, loadSettlements);
            } catch (error) {
                settlementsBody.innerHTML = emptyRow(7, error.message);
            }
        }

        async function showSettlement(id) {
            try {
                const response = await api.get('/admin/settlement/settlements/' + id);
                const s = response.data;
                const payments = (s.payments || []).map(function (p) {
                    return `<tr class="border-b border-[#F3F7FC] last:border-0">
                        <td class="py-2 pr-3 text-[12.5px]">#${esc(p.id)}</td>
                        <td class="py-2 pr-3 text-[12.5px]">Order #${esc(p.order_id)}</td>
                        <td class="py-2 pr-3 text-right text-[12.5px]">${money(p.gross_kobo)}</td>
                        <td class="py-2 pr-3 text-right text-[12.5px]">${money(p.platform_fee_kobo)}</td>
                        <td class="py-2 pr-3 text-right text-[12.5px]">${money(p.gateway_fee_kobo)}</td>
                        <td class="py-2 text-right text-[12.5px] font-semibold">${money(p.net_kobo)}</td>
                    </tr>`;
                }).join('') || emptyRow(6, 'No payments linked.');
                const row = function (label, value) {
                    return `<div class="flex justify-between gap-4 border-b border-[#F3F7FC] py-2 text-[13px]"><span class="text-[#171E26]/50">${esc(label)}</span><span class="text-right">${value}</span></div>`;
                };
                showInfo('Settlement ' + s.reference,
                    `<div class="mb-4 grid grid-cols-1 gap-x-8 md:grid-cols-2">
                        <div>
                            ${row('Status', badge(s.status))}
                            ${row('Pharmacy', esc(s.pharmacy ? s.pharmacy.name : s.pharmacy_id))}
                            ${row('Gross', money(s.gross_kobo))}
                            ${row('Fees', money(s.fee_kobo))}
                            ${row('Adjustment', money(s.adjustment_kobo))}
                            ${row('Net', `<strong>${money(s.net_kobo)}</strong>`)}
                        </div>
                        <div>
                            ${row('Account', s.account ? esc(s.account.bank_name) + ' · ' + esc(s.account.account_number) : '—')}
                            ${row('Account name', s.account ? esc(s.account.account_name) : '—')}
                            ${row('Paystack transfer', esc(s.gateway_reference || '—'))}
                            ${row('Attempts', esc(s.attempts))}
                            ${row('Created', esc(dateTime(s.created_at)))}
                            ${row('Processed', esc(dateTime(s.processed_at)))}
                            ${row('Hold / failure', esc(s.hold_reason || s.failure_reason || '—'))}
                        </div>
                    </div>
                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Payments</p>
                    <div class="overflow-x-auto"><table class="w-full text-left"><thead><tr class="border-b border-[#EAF1FB]">
                        <th class="py-2 pr-3 text-[11px] font-semibold uppercase text-[#171E26]/40">ID</th>
                        <th class="py-2 pr-3 text-[11px] font-semibold uppercase text-[#171E26]/40">Order</th>
                        <th class="py-2 pr-3 text-right text-[11px] font-semibold uppercase text-[#171E26]/40">Gross</th>
                        <th class="py-2 pr-3 text-right text-[11px] font-semibold uppercase text-[#171E26]/40">Commission</th>
                        <th class="py-2 pr-3 text-right text-[11px] font-semibold uppercase text-[#171E26]/40">Gateway</th>
                        <th class="py-2 text-right text-[11px] font-semibold uppercase text-[#171E26]/40">Net</th>
                    </tr></thead><tbody>${payments}</tbody></table></div>`
                );
            } catch (error) {
                fail(error, 'Unable to load settlement.');
            }
        }

        settlementsBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button[data-action]');
            if (!button) return;
            const id = button.dataset.id;
            const action = button.dataset.action;

            if (action === 'view') {
                showSettlement(id);
                return;
            }

            if (action === 'replay') {
                const event = button.dataset.event;
                const confirmed = await ask({
                    title: 'Replay transfer.' + event,
                    message: 'Test key only. This feeds the same event Paystack would send for this transfer into the webhook handler.',
                    confirmText: 'Replay',
                    danger: event !== 'success',
                });
                if (!confirmed) return;
                button.disabled = true;
                try {
                    await api.post('/admin/settlement/settlements/' + id + '/replay', { event: event });
                    toast('Replayed transfer.' + event + '.');
                    loadSettlements(state.pages.settlements.page);
                } catch (error) {
                    button.disabled = false;
                    fail(error, 'Replay failed.');
                }
                return;
            }

            let values;
            let path = '/admin/settlement/settlements/' + id + '/' + action;
            let body = {};
            let success = 'Done.';

            if (action === 'retry') {
                values = await ask({ title: 'Retry settlement', message: 'Pending settlements are dispatched, processing ones are verified with Paystack, and failed ones are re-batched.', confirmText: 'Retry' });
                if (!values) return;
                success = 'Retry requested.';
            } else if (action === 'hold') {
                values = await ask({ title: 'Hold settlement', fields: [{ name: 'reason', label: 'Reason', type: 'textarea', required: true }], confirmText: 'Hold' });
                if (!values) return;
                body = { reason: values.reason };
                success = 'Settlement held.';
            } else if (action === 'release') {
                values = await ask({ title: 'Release settlement', message: 'The transfer will be dispatched.', confirmText: 'Release' });
                if (!values) return;
                success = 'Settlement released.';
            } else if (action === 'cancel') {
                values = await ask({ title: 'Cancel settlement', message: 'Funds return to the pharmacy balance and payments become eligible again.', fields: [{ name: 'reason', label: 'Reason', type: 'textarea', required: true }], confirmText: 'Cancel settlement', danger: true });
                if (!values) return;
                body = { reason: values.reason };
                success = 'Settlement cancelled.';
            } else {
                return;
            }

            button.disabled = true;
            try {
                const response = await api.post(path, body);
                toast(action === 'retry' && response.outcome ? 'Retry outcome: ' + response.outcome : success);
                loadSettlements(state.pages.settlements.page);
            } catch (error) {
                button.disabled = false;
                fail(error, 'Action failed.');
            }
        });

        document.getElementById('settlements-status-filter').addEventListener('change', function () { loadSettlements(1); });
        document.getElementById('settlements-pharmacy-filter').addEventListener('change', function () { loadSettlements(1); });
        document.getElementById('settlements-refresh-btn').addEventListener('click', function () { loadSettlements(state.pages.settlements.page); });
        document.getElementById('settlements-search').addEventListener('input', function () {
            window.clearTimeout(state.searchTimers.settlements);
            state.searchTimers.settlements = window.setTimeout(function () { loadSettlements(1); }, 400);
        });

        const paymentsBody = document.getElementById('payments-body');

        function paymentActions(row) {
            const status = row.settlement_status;
            let html = '';
            if (status === 'pending' || status === 'eligible') html += actionButton('hold', row.id, 'Hold', 'neutral');
            if (status === 'held') html += actionButton('release', row.id, 'Release', 'primary');
            if (status === 'pending' || status === 'eligible' || status === 'held' || status === 'settled') html += actionButton('refund', row.id, 'Refund', 'danger');
            return html;
        }

        async function loadPayments(page) {
            paymentsBody.innerHTML = emptyRow(8, 'Loading...');
            try {
                const response = await api.get('/admin/settlement/payments' + query({
                    page: page,
                    per_page: PAGE_SIZE,
                    settlement_status: document.getElementById('payments-status-filter').value,
                    pharmacy_id: document.getElementById('payments-pharmacy-filter').value.trim(),
                    order_id: document.getElementById('payments-order-filter').value.trim(),
                    search: document.getElementById('payments-search').value.trim(),
                }));
                state.pages.payments = pageInfo(response, page);
                const rows = response.data || [];
                paymentsBody.innerHTML = rows.length ? rows.map(function (row) {
                    const fees = row.gross_kobo - row.net_kobo;
                    const status = row.settlement_status || 'unrecorded';
                    return `<tr class="border-b border-[#F3F7FC] last:border-0">
                        <td class="py-3 pr-4 text-[13px] font-semibold">#${esc(row.id)}<span class="block text-[11px] font-normal text-[#171E26]/40">Order #${esc(row.order_id)}</span></td>
                        <td class="py-3 pr-4 text-[13px]">${row.pharmacy ? esc(row.pharmacy.name) : esc(row.pharmacy_id)}</td>
                        <td class="py-3 pr-4 text-[13px]">${row.gross_kobo ? money(row.gross_kobo) : '—'}</td>
                        <td class="py-3 pr-4 text-[13px]">${row.gross_kobo ? money(fees) : '—'}</td>
                        <td class="py-3 pr-4 text-[13px] font-semibold">${row.gross_kobo ? money(row.net_kobo) : '—'}</td>
                        <td class="py-3 pr-4">${badge(status)}${row.hold_reason ? `<span class="block text-[11px] text-[#171E26]/45">${esc(row.hold_reason)}</span>` : ''}</td>
                        <td class="py-3 pr-4 text-[12px] text-[#171E26]/50">${esc(dateTime(row.eligible_at))}</td>
                        <td class="py-3 text-right">${paymentActions(row)}</td>
                    </tr>`;
                }).join('') : emptyRow(8, 'No payments found.');
                renderPager('payments-pagination', state.pages.payments, loadPayments);
            } catch (error) {
                paymentsBody.innerHTML = emptyRow(8, error.message);
            }
        }

        paymentsBody.addEventListener('click', async function (event) {
            const button = event.target.closest('button[data-action]');
            if (!button) return;
            const id = button.dataset.id;
            const action = button.dataset.action;
            let body = {};
            let success = 'Done.';

            if (action === 'hold') {
                const values = await ask({ title: 'Hold payment', message: 'The payment will not be settled until released.', fields: [{ name: 'reason', label: 'Reason', type: 'textarea', required: true }], confirmText: 'Hold' });
                if (!values) return;
                body = { reason: values.reason };
                success = 'Payment held.';
            } else if (action === 'release') {
                const values = await ask({ title: 'Release payment', message: 'The payment returns to the normal settlement flow.', confirmText: 'Release' });
                if (!values) return;
                success = 'Payment released.';
            } else if (action === 'refund') {
                const values = await ask({
                    title: 'Refund payment',
                    message: 'The full amount is refunded to the customer and reversed from the pharmacy ledger. If it was already paid out, the amount is deducted from the next payout.',
                    fields: [
                        { name: 'reason', label: 'Reason', type: 'textarea', required: true },
                        { name: 'via_gateway', label: 'Also refund through Paystack', type: 'checkbox', value: true },
                    ],
                    confirmText: 'Refund',
                    danger: true,
                });
                if (!values) return;
                body = { reason: values.reason, via_gateway: values.via_gateway };
                success = 'Payment refunded.';
            } else {
                return;
            }

            button.disabled = true;
            try {
                await api.post('/admin/settlement/payments/' + id + '/' + action, body);
                toast(success);
                loadPayments(state.pages.payments.page);
            } catch (error) {
                button.disabled = false;
                fail(error, 'Action failed.');
            }
        });

        ['payments-status-filter', 'payments-pharmacy-filter', 'payments-order-filter'].forEach(function (id) {
            document.getElementById(id).addEventListener('change', function () { loadPayments(1); });
        });
        document.getElementById('payments-refresh-btn').addEventListener('click', function () { loadPayments(state.pages.payments.page); });
        document.getElementById('payments-search').addEventListener('input', function () {
            window.clearTimeout(state.searchTimers.payments);
            state.searchTimers.payments = window.setTimeout(function () { loadPayments(1); }, 400);
        });

        const pharmacyDetail = document.getElementById('pharmacy-detail');
        const pharmacyForm = document.getElementById('pharmacy-form');
        const pharmacySaveBtn = document.getElementById('pharmacy-save-btn');
        const ledgerBody = document.getElementById('ledger-body');

        function tile(label, kobo) {
            return `<div class="rounded-2xl border border-[#EAF1FB] bg-white p-4 shadow-sm"><p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">${esc(label)}</p><p class="font-manrope text-[17px] font-extrabold">${money(kobo)}</p></div>`;
        }

        function renderPharmacy(data) {
            const p = data.pharmacy;
            const b = data.balance;
            document.getElementById('pharmacy-name').textContent = p.name + (p.is_test_account ? ' (test account)' : '');
            document.getElementById('pharmacy-balance').innerHTML =
                tile('Ledger balance', b.ledger_balance_kobo) +
                tile('In hold window', b.pending_kobo) +
                tile('Held', b.held_kobo) +
                tile('Eligible', b.eligible_kobo) +
                tile('Available', b.available_kobo) +
                tile('Carried debt', b.carried_debt_kobo) +
                tile('In transit', b.in_transit_kobo) +
                tile('Paid out', b.paid_out_kobo);

            document.getElementById('pharmacy-hold').checked = !!p.payout_hold;
            document.getElementById('pharmacy-hold-reason').value = p.payout_hold_reason || '';
            document.getElementById('pharmacy-commission').value = p.commission_rate === null || p.commission_rate === undefined ? '' : p.commission_rate;
            document.getElementById('pharmacy-schedule').value = p.payout_schedule;
            const info = [];
            if (p.consecutive_payout_failures > 0) info.push('Consecutive failures: ' + p.consecutive_payout_failures);
            if (p.payout_hold_until) info.push('Cooldown until ' + dateTime(p.payout_hold_until));
            document.getElementById('pharmacy-hold-info').textContent = info.join(' · ');
            pharmacyDetail.style.display = 'block';
        }

        async function loadLedger(page) {
            if (!state.pharmacyId) return;
            ledgerBody.innerHTML = emptyRow(5, 'Loading...');
            try {
                const response = await api.get('/admin/settlement/pharmacies/' + state.pharmacyId + '/ledger' + query({
                    page: page,
                    per_page: PAGE_SIZE,
                    type: document.getElementById('ledger-type-filter').value,
                }));
                state.pages.ledger = pageInfo(response, page);
                const rows = response.data || [];
                ledgerBody.innerHTML = rows.length ? rows.map(function (row) {
                    const positive = row.amount_kobo >= 0;
                    return `<tr class="border-b border-[#F3F7FC] last:border-0">
                        <td class="py-3 pr-4 text-[12.5px] text-[#171E26]/60">${esc(dateTime(row.created_at))}</td>
                        <td class="py-3 pr-4 text-[13px]">${esc(row.type)}</td>
                        <td class="py-3 pr-4 text-[12.5px] text-[#171E26]/50">${row.payment_id ? '#' + esc(row.payment_id) : '—'}</td>
                        <td class="py-3 pr-4 text-[12.5px] text-[#171E26]/50">${row.settlement_id ? '#' + esc(row.settlement_id) : '—'}</td>
                        <td class="py-3 text-right text-[13px] font-semibold" style="color:${positive ? '#1F7A44' : '#9C3A32'}">${positive ? '+' : ''}${money(row.amount_kobo)}</td>
                    </tr>`;
                }).join('') : emptyRow(5, 'No ledger entries.');
                renderPager('ledger-pagination', state.pages.ledger, loadLedger);
            } catch (error) {
                ledgerBody.innerHTML = emptyRow(5, error.message);
            }
        }

        async function loadPharmacy() {
            const id = parseInt(document.getElementById('pharmacy-id-input').value, 10);
            if (!id || id < 1) {
                toast('Enter a valid pharmacy ID.', 'error');
                return;
            }
            try {
                const response = await api.get('/admin/settlement/pharmacies/' + id);
                state.pharmacyId = id;
                renderPharmacy(response.data);
                loadLedger(1);
            } catch (error) {
                pharmacyDetail.style.display = 'none';
                fail(error, 'Unable to load pharmacy.');
            }
        }

        document.getElementById('pharmacy-load-btn').addEventListener('click', loadPharmacy);
        document.getElementById('pharmacy-id-input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') loadPharmacy();
        });
        document.getElementById('ledger-type-filter').addEventListener('change', function () { loadLedger(1); });

        pharmacyForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            if (!state.pharmacyId) return;
            const commission = document.getElementById('pharmacy-commission').value.trim();
            const hold = document.getElementById('pharmacy-hold').checked;
            pharmacySaveBtn.disabled = true;
            try {
                await api.patch('/admin/settlement/pharmacies/' + state.pharmacyId + '/payout', {
                    payout_hold: hold,
                    payout_hold_reason: hold ? (document.getElementById('pharmacy-hold-reason').value.trim() || null) : null,
                    commission_rate: commission === '' ? null : Number(commission),
                    payout_schedule: document.getElementById('pharmacy-schedule').value,
                });
                toast('Payout settings saved.');
                const response = await api.get('/admin/settlement/pharmacies/' + state.pharmacyId);
                renderPharmacy(response.data);
            } catch (error) {
                fail(error, 'Unable to save payout settings.');
            } finally {
                pharmacySaveBtn.disabled = false;
            }
        });

        function setTab(tab) {
            state.tab = tab;
            ['controls', 'accounts', 'settlements', 'payments', 'pharmacy'].forEach(function (name) {
                document.getElementById('panel-' + name).style.display = name === tab ? 'block' : 'none';
            });
            document.querySelectorAll('.tab-btn').forEach(function (button) {
                const active = button.dataset.tab === tab;
                button.style.borderColor = active ? '#2775E4' : 'transparent';
                button.style.color = active ? '#2775E4' : 'rgba(23,30,38,0.5)';
            });
            if (!getToken()) return;
            if (tab === 'accounts' && !state.loaded.accounts) { state.loaded.accounts = true; loadAccounts(1); }
            if (tab === 'settlements' && !state.loaded.settlements) { state.loaded.settlements = true; loadSettlements(1); }
            if (tab === 'payments' && !state.loaded.payments) { state.loaded.payments = true; loadPayments(1); }
        }

        document.querySelectorAll('.tab-btn').forEach(function (button) {
            button.addEventListener('click', function () { setTab(button.dataset.tab); });
        });

        setTab('controls');
        connect();
    </script>
</body>
</html>
