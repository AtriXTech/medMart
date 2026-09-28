{{--
    Intended path: resources/views/customer/extra.blade.php
    Route: /customer/extra (name chosen to match your own wording —
    rename easily if you'd rather call it "more"/"account"/etc.)

    NOTES:
    - Profile card links straight to your existing /customer/profile page.
    - Help card links to the new /customer/support page (built alongside
      this one).
    - Logout card opens a confirm modal (same pattern used across the
      staff pages) — logout only happens if "Log Out" is confirmed. See
      extra.js for the flagged assumption about CustomerAuth.logout().
--}}
<x-layouts.customer title="More" active="extra">

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[22px] md:text-[26px] text-[#171E26]">More</h2>
    </div>

    <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm overflow-hidden">

        <a href="{{ route('profile') }}" class="flex items-center gap-4 px-5 py-5 hover:bg-[#F7FAFD] transition">
            <i class="ph-light ph-user-circle text-[26px] text-[#171E26]"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[16px] text-[#171E26]">Profile</p>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-0.5">View and edit your account details</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        <a href="{{ route('support') }}" class="flex items-center gap-4 px-5 py-5 hover:bg-[#F7FAFD] transition">
            <i class="ph-light ph-lifebuoy text-[26px] text-[#171E26]"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[16px] text-[#171E26]">Help</p>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-0.5">FAQs and live chat support</p>
            </div>
            <i class="ph-light ph-caret-right text-[18px] text-[#171E26]/30"></i>
        </a>

        <div class="border-t border-[#F3F7FC]"></div>

        <button type="button" id="logout-card-btn" class="w-full flex items-center gap-4 px-5 py-5 hover:bg-[#FDEDEC] transition text-left">
            <i class="ph-light ph-sign-out text-[26px] text-[#9C3A32]"></i>
            <div class="flex-1">
                <p class="font-manrope font-bold text-[16px] text-[#9C3A32]">Log Out</p>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-0.5">Sign out of your account</p>
            </div>
        </button>

    </div>

    {{-- Logout confirm modal --}}
    <div id="confirm-modal" style="display: none;" class="fixed inset-0 z-50 items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[380px] p-6 text-center">
            <div class="mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-[#FDEDEC]">
                <i class="ph-light ph-sign-out text-2xl text-[#9C3A32]"></i>
            </div>
            <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-1.5">Log out?</h3>
            <p class="font-inter text-[13px] text-[#171E26]/60 leading-relaxed mb-4">Are you sure you want to log out of your account?</p>
            <p id="confirm-modal-error" style="display: none;" class="font-inter text-[12px] text-[#9C3A32] mb-3"></p>
            <div class="flex items-center gap-2.5">
                <button type="button" id="confirm-modal-cancel-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#171E26] hover:bg-[#F7FAFD]">Cancel</button>
                <button type="button" id="confirm-modal-confirm-btn"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-[#F5C9C4] font-inter font-semibold text-[13px] text-[#9C3A32] hover:bg-[#FDEDEC] disabled:opacity-60">Log Out</button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/extra.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>