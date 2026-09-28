/*
  CHANGE SUMMARY (vs. previous version):
  - NEW: registerSubmit is disabled by default in the Blade file now.
    termsCheckbox's 'change' event toggles it enabled/disabled to match
    whether the box is ticked.
  - NEW: submit handler now checks termsCheckbox.checked first, as a
    safety net (some browsers can still fire a form's submit event on
    Enter even when the submit button itself is disabled). If unchecked,
    shows "You must accept the Terms of Service and Privacy Policy to
    continue." beneath the checkbox and stops — no API call is made.
  - clearErrors() now also clears the new terms-error message.
  - UNCHANGED: every field validation, the fetch() call to
    /api/v1/pharmacy/register, the 422 field-error handling, the
    localStorage token/user storage, the success message + redirect
    to /staff/onboarding, the finally-block button reset.
*/

const registerForm = document.getElementById('register-form');
const registerError = document.getElementById('register-error');
const registerSubmit = document.getElementById('register-submit');
const pharmacyNameError = document.getElementById('pharmacy-name-error');
const ownerNameError = document.getElementById('owner-name-error');
const emailError = document.getElementById('email-error');
const phoneError = document.getElementById('phone-error');
const passwordError = document.getElementById('password-error');
const passwordConfirmationError = document.getElementById('password-confirmation-error');

// NEW — terms checkbox gating
const termsCheckbox = document.getElementById('terms');
const termsError = document.getElementById('terms-error');

// NEW — password show/hide toggles
function wireTogglePassword(inputId, btnId, iconId) {
  const input = document.getElementById(inputId);
  const btn = document.getElementById(btnId);
  const icon = document.getElementById(iconId);

  btn.addEventListener('click', function () {
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.classList.toggle('ph-eye', !isHidden);
    icon.classList.toggle('ph-eye-slash', isHidden);
    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
  });
}

wireTogglePassword('password', 'toggle-password-btn', 'toggle-password-icon');
wireTogglePassword('password-confirmation', 'toggle-password-confirmation-btn', 'toggle-password-confirmation-icon');

function clearErrors() {
  registerError.style.display = 'none';
  registerError.textContent = '';
  pharmacyNameError.textContent = '';
  ownerNameError.textContent = '';
  emailError.textContent = '';
  phoneError.textContent = '';
  passwordError.textContent = '';
  passwordConfirmationError.textContent = '';
  termsError.textContent = '';
}

function showFieldErrors(errors) {
  if (errors.pharmacy_name) {
    pharmacyNameError.textContent = errors.pharmacy_name[0];
  }
  if (errors.owner_name) {
    ownerNameError.textContent = errors.owner_name[0];
  }
  if (errors.email) {
    emailError.textContent = errors.email[0];
  }
  if (errors.phone) {
    phoneError.textContent = errors.phone[0];
  }
  if (errors.password) {
    passwordError.textContent = errors.password[0];
  }
}

// NEW — keep the submit button's enabled state in sync with the checkbox
termsCheckbox.addEventListener('change', function () {
  registerSubmit.disabled = !termsCheckbox.checked;
  if (termsCheckbox.checked) {
    termsError.textContent = '';
  }
});

registerForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  clearErrors();

  // NEW — safety net in case submit ever fires despite the disabled button
  if (!termsCheckbox.checked) {
    termsError.textContent = 'You must accept the Terms of Service and Privacy Policy to continue.';
    return;
  }

  registerSubmit.disabled = true;
  registerSubmit.textContent = 'Creating Account...';

  const formData = {
    pharmacy_name: document.getElementById('pharmacy-name').value.trim(),
    owner_name: document.getElementById('owner-name').value.trim(),
    email: document.getElementById('email').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    password: document.getElementById('password').value,
    password_confirmation: document.getElementById('password-confirmation').value
  };

  try {
    const response = await fetch('/api/v1/pharmacy/register', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    });

    const data = await response.json();

    if (!response.ok) {
      if (response.status === 422 && data.errors) {
        showFieldErrors(data.errors);
        return;
      }
      throw new Error(data.message || 'Unable to create account.');
    }

    localStorage.setItem('staff_token', data.token);
    localStorage.setItem('staff_user', JSON.stringify(data.user));

    registerError.textContent = 'Registration successful! Taking you to plan selection...';
    registerError.className = 'alert alert-success';
    registerError.style.display = 'block';

    setTimeout(function() {
      window.location.href = '/staff/onboarding';
    }, 1500);
  } catch (error) {
    registerError.textContent = error.message || 'Unable to create account.';
    registerError.className = 'alert alert-error';
    registerError.style.display = 'block';
  } finally {
    registerSubmit.disabled = false;
    registerSubmit.textContent = 'Create Account';
  }
});