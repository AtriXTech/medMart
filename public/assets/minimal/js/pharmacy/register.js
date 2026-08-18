const registerForm = document.getElementById('register-form');
const registerError = document.getElementById('register-error');
const registerSubmit = document.getElementById('register-submit');
const pharmacyNameError = document.getElementById('pharmacy-name-error');
const ownerNameError = document.getElementById('owner-name-error');
const emailError = document.getElementById('email-error');
const phoneError = document.getElementById('phone-error');
const passwordError = document.getElementById('password-error');
const passwordConfirmationError = document.getElementById('password-confirmation-error');

function clearErrors() {
  registerError.style.display = 'none';
  registerError.textContent = '';
  pharmacyNameError.textContent = '';
  ownerNameError.textContent = '';
  emailError.textContent = '';
  phoneError.textContent = '';
  passwordError.textContent = '';
  passwordConfirmationError.textContent = '';
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

registerForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  clearErrors();
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