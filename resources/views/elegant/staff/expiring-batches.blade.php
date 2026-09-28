{{--
    Intended path: resources/views/staff/expiring-batches.blade.php
    (unchanged from previous version — this bug fix is entirely in
    expiring-batches.js, see the change summary there)
--}}
<x-layouts.staff title="Expiring Batches" active="expiring-batches">

    <div id="batches-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Expiring Batches</h2>
            <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Track batches that are expiring soon or have already expired</p>
        </div>

        <div class="flex flex-wrap items-end gap-2.5">
            <div>
                <label for="status-filter" class="field-label">Show</label>
                <select id="status-filter" class="field-input">
                    <option value="expiring" selected>Expiring soon</option>
                    <option value="expired">Already expired</option>
                </select>
            </div>
            <div id="days-filter-wrapper">
                <label for="days-filter" class="field-label">Within</label>
                <select id="days-filter" class="field-input">
                    <option value="30">30 days</option>
                    <option value="60">60 days</option>
                    <option value="90" selected>90 days</option>
                    <option value="180">180 days</option>
                    <option value="365">365 days</option>
                </select>
            </div>
        </div>
    </div>

    <div id="batches-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="batches-content" style="display: none;">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px]">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Product</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Batch Number</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Quantity</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Expiry Date</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="batches-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/expiring-batches.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>