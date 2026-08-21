{{--
    Intended path: resources/views/staff/customers.blade.php

    CHANGE SUMMARY:
    - Every ID customers.js binds to is preserved exactly: customers-error,
      customers-loading, customers-content, customers-table-body,
      customer-search, pagination-container.
    - No "Create Customer" button — matches the original, since customers
      link to a pharmacy via pharmacy codes rather than being created by
      staff directly.
    - #customers-error / #customers-loading / #customers-content use
      plain inline style="display:none" matching what the JS toggles.
    - customers.js: same endpoint (GET /staff/customers with page/per_page
      /search params), same 500ms search debounce, same pagination source
      (data.meta.last_page), same suspend/unsuspend endpoints and confirm()
      dialogs — only renderCustomers()/renderPagination() output changed.
--}}
<x-layouts.staff title="Customers" active="customers">

    <div id="customers-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Customers</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Everyone linked to your pharmacy</p>
    </div>

    <div id="customers-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
        </div>
    </div>

    <div id="customers-content" style="display: none;">

        <div class="mb-4 max-w-sm">
            <label for="customer-search" class="field-label">Search Customers</label>
            <div class="relative">
                <i class="ph-light ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#171E26]/35 text-[16px]"></i>
                <input type="text" id="customer-search" placeholder="Search by name or username..." class="field-input pl-10">
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Name</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Email</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Username</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Link ID</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Status</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 pr-4 text-left">Linked At</th>
                            <th class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customers-table-body"></tbody>
                </table>
            </div>
            <div id="pagination-container" class="flex items-center justify-center gap-2 mt-5 pt-4 border-t border-[#EAF1FB]"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customers.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>