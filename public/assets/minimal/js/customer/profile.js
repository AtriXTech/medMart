/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: all element IDs/lookups, loadProfile() (GET /customer/profile),
    the profile-form submit handler (PATCH /customer/profile, same 422
    handling, same 3-second success auto-hide), the password-form submit
    handler (POST /customer/profile/password, same 422 handling, same
    3-second success auto-hide, same form.reset()).
  - CHANGED (presentation only): renderProfile()'s injected markup rebuilt
    with Tailwind to match the rest of the customer app's card styling.
  - Switched all style.display assignments on the four alert banners from
    'block' to 'flex', since the Blade page now styles them with a
    flex-based icon+text layout — matches the pattern used on every other
    page in this app.
*/

const profileError = document.getElementById('profile-error');
const profileLoading = document.getElementById('profile-loading');
const profileContent = document.getElementById('profile-content');
const profileInfo = document.getElementById('profile-info');
const profileForm = document.getElementById('profile-form');
const profileNameInput = document.getElementById('profile-name');
const profileEmailInput = document.getElementById('profile-email');
const profilePhoneInput = document.getElementById('profile-phone');
const profileFormError = document.getElementById('profile-form-error');
const profileFormSuccess = document.getElementById('profile-form-success');
const profileSubmitBtn = document.getElementById('profile-submit-btn');
const passwordForm = document.getElementById('password-form');
const currentPasswordInput = document.getElementById('current-password');
const newPasswordInput = document.getElementById('new-password');
const newPasswordConfirmationInput = document.getElementById('new-password-confirmation');
const passwordFormError = document.getElementById('password-form-error');
const passwordFormSuccess = document.getElementById('password-form-success');
const passwordSubmitBtn = document.getElementById('password-submit-btn');

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function renderProfile(profile) {
    profileInfo.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4">
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Username</p>
                <p class="font-inter text-[14px] font-semibold text-[#171E26]">${profile.username || 'N/A'}</p>
            </div>
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Email Verified</p>
                <p class="font-inter text-[14px] text-[#171E26]">${profile.email_verified ? 'Yes' : 'No'}</p>
            </div>
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Member Since</p>
                <p class="font-inter text-[14px] text-[#171E26]">${formatDate(profile.created_at)}</p>
            </div>
        </div>
    `;
    
    profileNameInput.value = profile.name || '';
    profileEmailInput.value = profile.email || '';
    profilePhoneInput.value = profile.phone || '';
}

async function loadProfile() {
    if (!CustomerAuth.requireAuth()) return;
    
    profileLoading.style.display = 'block';
    profileContent.style.display = 'none';
    profileError.style.display = 'none';
    
    try {
        const profile = await CustomerApi.get('/customer/profile');
        renderProfile(profile);
        profileLoading.style.display = 'none';
        profileContent.style.display = 'block';
    } catch (error) {
        profileLoading.style.display = 'none';
        profileError.textContent = error.message || 'Unable to load profile.';
        profileError.style.display = 'flex';
    }
}

profileForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    profileSubmitBtn.disabled = true;
    profileFormError.style.display = 'none';
    profileFormSuccess.style.display = 'none';
    
    const formData = {
        name: profileNameInput.value.trim(),
        email: profileEmailInput.value.trim(),
        phone: profilePhoneInput.value.trim(),
    };
    
    try {
        const updated = await CustomerApi.patch('/customer/profile', formData);
        renderProfile(updated);
        profileFormSuccess.textContent = 'Profile updated successfully!';
        profileFormSuccess.style.display = 'flex';
        
        setTimeout(function() {
            profileFormSuccess.style.display = 'none';
        }, 3000);
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function(key) {
                messages.push(...error.data.errors[key]);
            });
            profileFormError.textContent = messages.join(', ');
        } else {
            profileFormError.textContent = error.message || 'Unable to update profile.';
        }
        profileFormError.style.display = 'flex';
    } finally {
        profileSubmitBtn.disabled = false;
    }
});

passwordForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    passwordSubmitBtn.disabled = true;
    passwordFormError.style.display = 'none';
    passwordFormSuccess.style.display = 'none';
    
    const formData = {
        current_password: currentPasswordInput.value,
        new_password: newPasswordInput.value,
        new_password_confirmation: newPasswordConfirmationInput.value,
    };
    
    try {
        await CustomerApi.post('/customer/profile/password', formData);
        passwordForm.reset();
        passwordFormSuccess.textContent = 'Password changed successfully!';
        passwordFormSuccess.style.display = 'flex';
        
        setTimeout(function() {
            passwordFormSuccess.style.display = 'none';
        }, 3000);
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function(key) {
                messages.push(...error.data.errors[key]);
            });
            passwordFormError.textContent = messages.join(', ');
        } else {
            passwordFormError.textContent = error.message || 'Unable to change password.';
        }
        passwordFormError.style.display = 'flex';
    } finally {
        passwordSubmitBtn.disabled = false;
    }
});

loadProfile();