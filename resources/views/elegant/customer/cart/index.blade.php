<x-layouts.customer title="Shopping Cart" active="cart">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-manrope font-bold text-2xl text-[#171E26]">Shopping Cart</h1>
            <p class="font-inter text-xs text-[#171E26]/50 mt-0.5">Review items in your active pharmacy order</p>
        </div>
    </div>

    {{-- Error Banner --}}
    <div id="cart-error" style="display:none;" class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 font-inter text-xs"></div>

    {{-- Skeleton Loading State --}}
    <div id="cart-skeleton" class="space-y-4">
        <div class="skel h-24 w-full"></div>
        <div class="skel h-24 w-full"></div>
        <div class="skel h-36 w-full mt-6"></div>
    </div>

    {{-- Dynamic Cart Content --}}
    <div id="cart-content" style="display:none;" ></div>

    {{-- PAGE-LOCAL ALERT MODAL --}}
    <div id="alert-modal" style="display:none;" class="fixed inset-0 z-[200] items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[360px] p-6 text-center">
            <div class="h-12 w-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-warning text-2xl"></i>
            </div>
            <h3 id="alert-modal-title" class="font-manrope font-bold text-[16px] text-[#171E26]">Notice</h3>
            <p id="alert-modal-message" class="font-inter text-[13px] text-[#171E26]/60 mt-1.5 mb-6 leading-relaxed"></p>
            <button type="button" id="alert-modal-ok-btn" class="w-full py-2.5 rounded-xl bg-[#2775E4] text-white font-inter text-xs font-semibold shadow-sm hover:opacity-95 transition">
                OK
            </button>
        </div>
    </div>

    {{-- PAGE-LOCAL CONFIRM MODAL --}}
    <div id="confirm-modal" style="display:none;" class="fixed inset-0 z-[200] items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[360px] p-6 text-center">
            <div class="h-12 w-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-trash text-2xl"></i>
            </div>
            <h3 id="confirm-modal-title" class="font-manrope font-bold text-[16px] text-[#171E26]">Confirm Action</h3>
            <p id="confirm-modal-message" class="font-inter text-[13px] text-[#171E26]/60 mt-1.5 mb-6 leading-relaxed"></p>
            <div class="flex items-center gap-2.5">
                <button type="button" id="confirm-modal-cancel-btn" class="flex-1 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-xs font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    Cancel
                </button>
                <button type="button" id="confirm-modal-confirm-btn" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white font-inter text-xs font-semibold shadow-sm hover:bg-red-600 transition">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/cart.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>