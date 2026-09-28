const profileError = document.getElementById('profile-error');
const profileLoading = document.getElementById('profile-loading');
const profileContent = document.getElementById('profile-content');
const profileInfo = document.getElementById('profile-info');
const profileForm = document.getElementById('profile-form');
const profileNameInput = document.getElementById('profile-name');
const profileEmailInput = document.getElementById('profile-email');
const profilePhoneInput = document.getElementById('profile-phone');
const profileFormError = document.getElementById('profile-form-error');
const profileSubmitBtn = document.getElementById('profile-submit-btn');
const passwordForm = document.getElementById('password-form');
const currentPasswordInput = document.getElementById('current-password');
const newPasswordInput = document.getElementById('new-password');
const newPasswordConfirmationInput = document.getElementById('new-password-confirmation');
const passwordFormError = document.getElementById('password-form-error');
const passwordSubmitBtn = document.getElementById('password-submit-btn');
const passwordSuccess = document.getElementById('password-success');

// Full class strings for the profile-form-error element, which is reused for
// both error and success states by swapping className entirely (same trick
// as the original code, just with real Tailwind classes instead of dead ones).
const PROFILE_ALERT_ERROR_CLASSES = 'mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium';
const PROFILE_ALERT_SUCCESS_CLASSES = 'mb-4 flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium';

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function renderProfile(profile) {
    profileInfo.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Role</p>
                <p class="font-inter text-[14px] font-medium text-[#171E26] capitalize">${profile.role || 'N/A'}</p>
            </div>
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Status</p>
                <p class="font-inter text-[14px] font-medium text-[#171E26] capitalize">${profile.status || 'N/A'}</p>
            </div>
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Pharmacy</p>
                <p class="font-inter text-[14px] font-medium text-[#171E26]">${profile.pharmacy ? profile.pharmacy.name : 'N/A'}</p>
            </div>
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Member Since</p>
                <p class="font-inter text-[14px] font-medium text-[#171E26]">${formatDate(profile.created_at)}</p>
            </div>
        </div>
    `;

    profileNameInput.value = profile.name || '';
    profileEmailInput.value = profile.email || '';
    profilePhoneInput.value = profile.phone || '';
}

async function loadProfile() {
    if (!Auth.requireAuth()) return;

    profileLoading.style.display = 'block';
    profileContent.style.display = 'none';
    profileError.style.display = 'none';

    try {
        const profile = await Api.get('/staff/profile');
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

    const formData = {
        name: profileNameInput.value.trim(),
        email: profileEmailInput.value.trim(),
        phone: profilePhoneInput.value.trim(),
    };

    try {
        await Api.patch('/staff/profile', formData);
        profileFormError.textContent = 'Profile updated successfully!';
        profileFormError.className = PROFILE_ALERT_SUCCESS_CLASSES;
        profileFormError.style.display = 'flex';

        const updatedProfile = await Api.get('/staff/profile');
        renderProfile(updatedProfile);

        setTimeout(function() {
            profileFormError.style.display = 'none';
            profileFormError.className = PROFILE_ALERT_ERROR_CLASSES;
        }, 3000);
    } catch (error) {
        profileFormError.className = PROFILE_ALERT_ERROR_CLASSES;
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
    passwordSuccess.style.display = 'none';

    const formData = {
        current_password: currentPasswordInput.value,
        new_password: newPasswordInput.value,
        new_password_confirmation: newPasswordConfirmationInput.value,
    };

    try {
        await Api.post('/staff/profile/password', formData);
        passwordForm.reset();
        passwordSuccess.textContent = 'Password changed successfully!';
        passwordSuccess.style.display = 'flex';
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