<x-layouts.staff title="Orders" active="orders">

    <div class="alert alert-error mb-4 rounded-xl border border-[#F5C9C4] bg-[#FEF2F2] text-[#9C3A32]
                font-inter text-[13.5px] font-medium px-4 py-3"
         id="orders-error" style="display: none;"></div>

    <div id="orders-loading" class="loading-state py-16 text-center">
        <div class="inline-flex flex-col items-center gap-3">
            <div class="h-10 w-10 rounded-full border-2 border-[#DBEBFB] border-t-[#2775E4] animate-spin"></div>
            <p class="font-inter text-sm text-[#171E26]/50">Loading orders...</p>
        </div>
    </div>

    <div id="orders-content" style="display: none;">

        <div class="card mb-4 md:mb-5 rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-5">
            <div class="field max-w-[280px] m-0">
                <label for="status-filter" class="field-label">Filter by Status</label>
                <div class="relative">
                    <select id="status-filter" class="field-input appearance-none pr-9">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[#171E26]/35 pointer-events-none text-sm"></i>
                </div>
            </div>
        </div>

        <div class="card rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto -mx-1 px-1">
                <table class="w-full min-w-[860px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Order #</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Customer</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Total</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Delivery</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Items</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4">Date</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body"
                           class="[&_td]:font-inter [&_td]:text-[13.5px] [&_td]:text-[#171E26]
                                  [&_td]:py-3.5 [&_td]:pr-4 [&_td]:border-b [&_td]:border-[#EAF1FB] [&_td]:align-middle
                                  [&_tr:last-child_td]:border-b-0
                                  [&_tr:hover_td]:bg-[#F7FAFD]"></tbody>
                </table>
            </div>

            <div id="pagination-container"
                 class="mt-5 flex flex-wrap items-center justify-center gap-2
                        [&_span]:font-inter [&_span]:text-[13px] [&_span]:text-[#171E26]/55"
                 style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <x-slot:scripts>
        <style type="text/tailwindcss">
            .badge {
                @apply inline-flex items-center rounded-full px-2.5 py-0.5 font-inter text-[11px] font-semibold tracking-wide capitalize whitespace-nowrap;
            }
            .badge-success { @apply bg-emerald-50 text-emerald-700; }
            .badge-danger  { @apply bg-red-50 text-red-700; }
            .badge-warning { @apply bg-amber-50 text-amber-700; }
            .badge-muted   { @apply bg-[#EAF1FB] text-[#171E26]/70; }

            .btn {
                @apply inline-flex items-center justify-center rounded-[0.65rem] px-3.5 py-2
                       font-inter text-[12.5px] font-semibold cursor-pointer transition
                       disabled:opacity-45 disabled:cursor-not-allowed;
            }
            .btn-secondary {
                @apply bg-white border border-[#DBEBFB] text-[#171E26]
                       hover:bg-[#F7FAFD] hover:border-[#2775E4] hover:text-[#2775E4];
            }

            .empty-state {
                @apply text-center py-10 px-4 font-inter text-sm text-[#171E26]/45;
            }

            #pagination-container .btn {
                @apply min-w-[5.5rem];
            }
        </style>
        <script src="{{ asset('assets/minimal/js/staff/orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>