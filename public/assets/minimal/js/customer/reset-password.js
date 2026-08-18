const resetForm = document.getElementById('reset-form');
const resetError = document.getElementById('reset-error');
const resetSuccess = document.getElementById('reset-success');
const resetSubmit = document.getElementById('reset-submit');
const emailError = document.getElementById('email-error');
const passwordError = document.getElementById('password-error');
const tokenError = document.getElementById('token-error');

resetForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  resetError.style.display = 'none';
  resetSuccess.style.display = 'none';
  emailError.textContent = '';
  passwordError.textContent = '';
  tokenError.textContent = '';
  resetSubmit.disabled = true;
  resetSubmit.textContent = 'Resetting...';

  const formData = {
    token: document.getElementById('token').value.trim(),
    email: document.getElementById('email').value.trim(),
    password: document.getElementById('password').value,
    password_confirmation: document.getElementById('password-confirmation').value,
  };

  try {
    const result = await CustomerApi.post('/customer/reset-password', formData);
    resetSuccess.textContent = result.message || 'Password reset successful.';
    resetSuccess.style.display = 'block';
    
    setTimeout(function() {
      window.location.href = '/customer/login';
    }, 2000);
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      if (error.data.errors.email) emailError.textContent = error.data.errors.email[0];
      if (error.data.errors.password) passwordError.textContent = error.data.errors.password[0];
      if (error.data.errors.token) tokenError.textContent = error.data.errors.token[0];
    } else {
      resetError.textContent = error.message || 'Unable to reset password.';
      resetError.style.display = 'block';
    }
  } finally {
    resetSubmit.disabled = false;
    resetSubmit.textContent = 'Reset Password';
  }
});