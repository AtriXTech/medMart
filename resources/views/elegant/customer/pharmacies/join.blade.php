<x-layouts.customer title="Join Pharmacy" active="pharmacies">

    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl border border-[#EAF1FB] p-6">

            <div class="h-12 w-12 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                <i class="ph-fill ph-storefront text-[#2775E4] text-2xl"></i>
            </div>

            <h2 class="font-manrope font-bold text-[18px] text-[#171E26] mt-4">Join a Pharmacy</h2>
            <p class="font-inter text-[13px] text-[#171E26]/50 mt-1">
                Enter the code your pharmacy shared with you to link your account.
            </p>

            <div class="alert alert-error mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium mt-5"
                 id="join-error"
                 style="display: none;">
            </div>
            <div class="alert alert-success flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium mb-4"
                 id="join-success"
                 style="display: none;">
            </div>

            <form id="join-form" class="space-y-4 mt-5">
                <div class="field">
                    <label for="pharmacy-code" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Pharmacy Code</label>
                    <input type="text" id="pharmacy-code" required
                           placeholder="e.g. MED-1234"
                           class="w-full rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] tracking-wider uppercase text-[#171E26] placeholder:text-[#171E26]/30 placeholder:normal-case focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <div class="field-error" id="pharmacy-code-error"></div>
                </div>

                <button type="submit" id="join-submit" class="btn btn-primary btn-block w-full px-5 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                    Join Pharmacy
                </button>
            </form>

        </div>
    </div>

    <x-slot:scripts>
        <script>
            const joinForm = document.getElementById('join-form');
            const joinError = document.getElementById('join-error');
            const joinSuccess = document.getElementById('join-success');
            const joinSubmit = document.getElementById('join-submit');
            const pharmacyCodeError = document.getElementById('pharmacy-code-error');
            
            joinForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                joinError.style.display = 'none';
                joinSuccess.style.display = 'none';
                pharmacyCodeError.textContent = '';
                joinSubmit.disabled = true;
                joinSubmit.textContent = 'Joining...';
                
                const code = document.getElementById('pharmacy-code').value.trim().toUpperCase();
                
                try {
                    await CustomerApi.post('/customer/pharmacies/join', { pharmacy_code: code });
                    joinSuccess.textContent = 'Pharmacy joined successfully!';
                    joinSuccess.style.display = 'block';
                    
                    setTimeout(function() {
                        window.location.href = '/customer/products';
                    }, 2000);
                } catch (error) {
                    if (error.status === 422 && error.data && error.data.errors) {
                        if (error.data.errors.pharmacy_code) {
                            pharmacyCodeError.textContent = error.data.errors.pharmacy_code[0];
                        }
                    } else {
                        joinError.textContent = error.message || 'Unable to join pharmacy.';
                        joinError.style.display = 'block';
                    }
                } finally {
                    joinSubmit.disabled = false;
                    joinSubmit.textContent = 'Join Pharmacy';
                }
            });
        </script>
    </x-slot:scripts>
</x-layouts.customer>