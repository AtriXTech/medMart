<x-layouts.staff title="Profile" active="profile">

    {{-- Error banner --}}
    <div id="profile-error"
         style="display: none;"
         class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
    </div>

    {{-- Loading state — JS toggles via style.display = 'block'/'none' --}}
    <div id="profile-loading" class="py-20 text-center">
        <i class="ph ph-circle-notch text-3xl text-[#2775E4] animate-spin inline-block"></i>
        <p class="font-inter text-sm text-[#171E26]/50 mt-3">Loading profile...</p>
    </div>

    {{-- Main content — JS toggles via style.display = 'block'/'none' --}}
    <div id="profile-content" style="display: none;">

        {{-- Profile Information --}}
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5 mb-5">
            <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Profile Information</p>
            <div id="profile-info">
                {{-- Populated by profile.js --}}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Edit Profile --}}
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
                <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Edit Profile</p>

                {{-- Note: profile.js reuses this single element for both error AND success
                     states by swapping its full className — kept exactly as-is. --}}
                <div id="profile-form-error"
                     style="display: none;"
                     class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
                </div>

                <form id="profile-form" class="space-y-4">
                    <div>
                        <label for="profile-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Name</label>
                        <input type="text" id="profile-name" required
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <div>
                        <label for="profile-email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Email</label>
                        <input type="email" id="profile-email" required
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <div>
                        <label for="profile-phone" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Phone</label>
                        <input type="text" id="profile-phone"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <button type="submit" id="profile-submit-btn"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Update Profile
                    </button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
                <p class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Change Password</p>

                <div id="password-form-error"
                     style="display: none;"
                     class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
                </div>
                <div id="password-success"
                     style="display: none;"
                     class="mb-4 flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium">
                </div>

                <form id="password-form" class="space-y-4">
                    <div>
                        <label for="current-password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Current Password</label>
                        <input type="password" id="current-password" required autocomplete="current-password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <div>
                        <label for="new-password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">New Password</label>
                        <input type="password" id="new-password" required autocomplete="new-password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <div>
                        <label for="new-password-confirmation" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Confirm New Password</label>
                        <input type="password" id="new-password-confirmation" required autocomplete="new-password"
                               class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    </div>
                    <button type="submit" id="password-submit-btn"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Change Password
                    </button>
                </form>
            </div>

        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/profile.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>