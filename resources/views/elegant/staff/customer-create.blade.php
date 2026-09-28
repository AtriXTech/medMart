<x-layouts.staff title="Create Customer Account" active="customers">
    <div class='flex justify-end my-3'>
        <a href='{{ route('customers') }}'>
                 <button 
                    class="w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                Back to customers
            </button>
               </a>
    </div>
    <div class="bg-white rounded-2xl border border-[#EAF1FB] p-6 md:p-8 max-w-[480px] mx-auto">
        <p class="font-manrope font-bold text-[18px] text-[#171E26] mb-4">Create Customer Account</p>

        {{-- Note: customer-error is reused for both error AND success states by
             swapping its full className — kept exactly as-is. --}}
        <div id="customer-error"
             style="display: none;"
             class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium">
        </div>

        <form id="customer-form" class="space-y-4">

            <div>
                <label for="customer-name" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Full Name</label>
                <input type="text" id="customer-name" required
                       class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                <div id="name-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
            </div>

            <div>
                <label for="customer-email" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Email</label>
                <input type="email" id="customer-email" required
                       class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                <div id="email-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
            </div>

            <div>
                <label for="customer-username" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Username</label>
                <input type="text" id="customer-username" required
                       class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                <div id="username-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
            </div>

            <div>
                <label for="customer-password" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Password</label>
                <input type="password" id="customer-password" required autocomplete="new-password"
                       class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                <div id="password-error" class="font-inter text-xs text-red-500 mt-1.5"></div>
            </div>

            
           
               
                <button type="submit" id="customer-submit"
                    class="w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                Create Account
            </button>
            
        </form>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customer-create.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>