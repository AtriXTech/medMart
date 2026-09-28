<x-layouts.customer title="Notifications" active="notifications">

    <div class="mb-4">
        <button type="button" id="mark-all-read"
                class="w-full py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
            Mark All as Read
        </button>
    </div>

    {{-- Error banner --}}
    <div id="notifications-error"
         style="display: none;"
         class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="notifications-loading" class="py-16 text-center">
        <i class="ph-light ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading notifications...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="notifications-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/notifications.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>