<x-layouts.customer title="My Orders" active="orders">

    {{-- Error banner --}}
    <div id="orders-error"
         style="display: none;"
         class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="orders-loading" class="py-16 text-center">
        <i class="ph-light ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading orders...</p>
    </div>

    {{-- NEW: status filter pills — JS toggles via style.display = 'flex'/'none',
         populated and wired entirely by orders.js --}}
    <div id="status-filter-bar" style="display: none;" class="flex items-center gap-2 overflow-x-auto pb-1 mb-4 -mx-1 px-1"></div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="orders-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>