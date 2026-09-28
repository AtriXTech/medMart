{{--
    Intended path: resources/views/staff/pharmacy-settings.blade.php

    CHANGE SUMMARY:
    - Every ID pharmacy-settings.js binds to is preserved exactly:
      settings-error, settings-loading, settings-content, settings-form,
      pharmacy-name, pharmacy-email, pharmacy-phone, pharmacy-address,
      pharmacy-timezone, pharmacy-currency, settings-form-error,
      settings-submit-btn, settings-success.
    - Timezone and currency <option> values unchanged.
    - #settings-error / #settings-loading / #settings-content /
      #settings-form-error / #settings-success use plain inline
      style="display:none" matching what the JS toggles.
    - #settings-success gets real green success styling now (it used
      .alert-success from app.css before, which no longer exists) —
      styled directly in this Blade file since pharmacy-settings.js
      never had to set its color dynamically (it's always just the
      one "updated successfully" message), so no JS change was needed
      there, unlike the subscription page's message banner.
    - pharmacy-settings.js itself: same endpoint (GET/PATCH
      /staff/pharmacy-settings), same field population, same 422 error
      handling — completely untouched, no rendering functions to rebuild
      on this page since it's a plain form.
--}}
<x-layouts.staff title="Pharmacy Settings" active="pharmacy-settings">

    <div id="settings-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>

    <div class="mb-6">
        <h2 class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">Pharmacy Settings</h2>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">Manage your pharmacy's profile and details</p>
    </div>

    <div id="settings-loading">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-6 space-y-3 w-full">
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-10 w-full"></div>
            <div class="skel h-20 w-full"></div>
        </div>
    </div>

    <div id="settings-content" style="display: none;" class="w-full">
        <div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-5 md:p-7">
            <h3 class="font-manrope font-bold text-[15px] text-[#171E26] mb-4">Pharmacy Information</h3>

            <div id="settings-form-error" style="display: none;" class="rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] text-[#9C3A32] font-inter text-[13px] px-4 py-3 mb-4"></div>
            <div id="settings-success" style="display: none;" class="rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] text-[#1F7A44] font-inter text-[13px] px-4 py-3 mb-4"></div>

            <form id="settings-form" class="space-y-4">
                <div>
                    <label for="pharmacy-name" class="field-label">Pharmacy Name</label>
                    <input type="text" id="pharmacy-name" required class="field-input">
                </div>
                <div>
                    <label for="pharmacy-email" class="field-label">Email</label>
                    <input type="email" id="pharmacy-email" required class="field-input">
                </div>
                <div>
                    <label for="pharmacy-phone" class="field-label">Phone</label>
                    <input type="text" id="pharmacy-phone" class="field-input">
                </div>
                <div>
                    <label for="pharmacy-address" class="field-label">Address</label>
                    <textarea id="pharmacy-address" rows="3" class="field-input resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="pharmacy-timezone" class="field-label">Timezone</label>
                        <select id="pharmacy-timezone" class="field-input">
                            <option value="Africa/Lagos">Africa/Lagos (WAT)</option>
                            <option value="Africa/Nairobi">Africa/Nairobi (EAT)</option>
                            <option value="Africa/Johannesburg">Africa/Johannesburg (SAST)</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                    <div>
                        <label for="pharmacy-currency" class="field-label">Currency</label>
                        <select id="pharmacy-currency" class="field-input">
                            <option value="NGN">NGN - Nigerian Naira</option>
                            <option value="GHS">GHS - Ghanaian Cedi</option>
                            <option value="KES">KES - Kenyan Shilling</option>
                            <option value="ZAR">ZAR - South African Rand</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" id="settings-submit-btn"
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 disabled:opacity-60">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/pharmacy-settings.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>