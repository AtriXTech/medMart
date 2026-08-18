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

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function renderProfile(profile) {
    profileInfo.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <strong>Role:</strong> ${profile.role || 'N/A'}
            </div>
            <div>
                <strong>Status:</strong> ${profile.status || 'N/A'}
            </div>
            <div>
                <strong>Pharmacy:</strong> ${profile.pharmacy ? profile.pharmacy.name : 'N/A'}
            </div>
            <div>
                <strong>Member Since:</strong> ${formatDate(profile.created_at)}
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
        profileError.style.display = 'block';
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
        profileFormError.className = 'alert alert-success';
        profileFormError.style.display = 'block';
        
        const updatedProfile = await Api.get('/staff/profile');
        renderProfile(updatedProfile);
        
        setTimeout(function() {
            profileFormError.style.display = 'none';
            profileFormError.className = 'alert alert-error';
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
        profileFormError.style.display = 'block';
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
        passwordSuccess.style.display = 'block';
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
        passwordFormError.style.display = 'block';
    } finally {
        passwordSubmitBtn.disabled = false;
    }
});

loadProfile();