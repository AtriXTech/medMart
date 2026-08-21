{{--
    Intended path: resources/views/staff/expiring-batches.blade.php

    CHANGE SUMMARY:
    - Every ID expiring-batches.js binds to is preserved exactly:
      batches-error, batches-loading, batches-content, batches-table-body.
    - active="products" kept exactly as you had it (not switched to
      "expiring-batches") — assuming this is intentional so "Products"
      shows as the active sidebar section. Flag me if that's wrong.
    - #batches-error / #batches-loading / #batches-content use plain
      inline style="display:none" matching what the JS toggles.
    - expiring-batches.js: same endpoint (GET /staff/batches/expiring-soon
      ?per_page=50), same days-left thresholds (<=30 danger, <=60 warning,
      else muted) — only renderBatches() output is rebuilt visually.
--}}
<x-layouts.staff title="Expiring Batches" active="products">

    <div id="batches-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Expiring Batches</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Batches expiring within 90 days</p>
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
                <table class="w-full">
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