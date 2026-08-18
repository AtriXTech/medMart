<x-layouts.customer title="Join Pharmacy" active="pharmacies">
    <div class="card">
        <p class="section-title">Join a Pharmacy</p>
        <div class="alert alert-error" id="join-error" style="display: none;"></div>
        <div class="alert alert-success" id="join-success" style="display: none;"></div>
        
        <form id="join-form">
            <div class="field">
                <label for="pharmacy-code">Pharmacy Code</label>
                <input type="text" id="pharmacy-code" required>
                <div class="field-error" id="pharmacy-code-error"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="join-submit">Join Pharmacy</button>
        </form>
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