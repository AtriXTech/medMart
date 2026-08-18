const settingsError = document.getElementById('settings-error');
const settingsLoading = document.getElementById('settings-loading');
const settingsContent = document.getElementById('settings-content');
const settingsForm = document.getElementById('settings-form');
const pharmacyNameInput = document.getElementById('pharmacy-name');
const pharmacyEmailInput = document.getElementById('pharmacy-email');
const pharmacyPhoneInput = document.getElementById('pharmacy-phone');
const pharmacyAddressInput = document.getElementById('pharmacy-address');
const pharmacyTimezoneInput = document.getElementById('pharmacy-timezone');
const pharmacyCurrencyInput = document.getElementById('pharmacy-currency');
const settingsFormError = document.getElementById('settings-form-error');
const settingsSubmitBtn = document.getElementById('settings-submit-btn');
const settingsSuccess = document.getElementById('settings-success');

async function loadSettings() {
    if (!Auth.requireAuth()) return;
    
    settingsLoading.style.display = 'block';
    settingsContent.style.display = 'none';
    settingsError.style.display = 'none';
    
    try {
        const data = await Api.get('/staff/pharmacy-settings');
        
        pharmacyNameInput.value = data.name || '';
        pharmacyEmailInput.value = data.email || '';
        pharmacyPhoneInput.value = data.phone || '';
        pharmacyAddressInput.value = data.address || '';
        pharmacyTimezoneInput.value = data.timezone || 'Africa/Lagos';
        pharmacyCurrencyInput.value = data.currency || 'NGN';
        
        settingsLoading.style.display = 'none';
        settingsContent.style.display = 'block';
    } catch (error) {
        settingsLoading.style.display = 'none';
        settingsError.textContent = error.message || 'Unable to load settings.';
        settingsError.style.display = 'block';
    }
}

settingsForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    settingsSubmitBtn.disabled = true;
    settingsFormError.style.display = 'none';
    settingsSuccess.style.display = 'none';
    
    const formData = {
        name: pharmacyNameInput.value.trim(),
        email: pharmacyEmailInput.value.trim(),
        phone: pharmacyPhoneInput.value.trim(),
        address: pharmacyAddressInput.value.trim(),
        timezone: pharmacyTimezoneInput.value.trim(),
        currency: pharmacyCurrencyInput.value.trim(),
    };
    
    try {
        await Api.patch('/staff/pharmacy-settings', formData);
        settingsSuccess.textContent = 'Settings updated successfully!';
        settingsSuccess.style.display = 'block';
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function(key) {
                messages.push(...error.data.errors[key]);
            });
            settingsFormError.textContent = messages.join(', ');
        } else {
            settingsFormError.textContent = error.message || 'Unable to update settings.';
        }
        settingsFormError.style.display = 'block';
    } finally {
        settingsSubmitBtn.disabled = false;
    }
});

loadSettings();