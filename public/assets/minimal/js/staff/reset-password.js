const resetForm = document.getElementById('reset-form');
const resetError = document.getElementById('reset-error');
const resetSuccess = document.getElementById('reset-success');
const resetSubmit = document.getElementById('reset-submit');
const emailError = document.getElementById('email-error');
const passwordError = document.getElementById('password-error');
const passwordConfirmationError = document.getElementById('password-confirmation-error');
const tokenError = document.getElementById('token-error');

function clearMessages() {
  resetError.style.display = 'none';
  resetError.textContent = '';
  resetSuccess.style.display = 'none';
  resetSuccess.textContent = '';
  emailError.textContent = '';
  passwordError.textContent = '';
  passwordConfirmationError.textContent = '';
  tokenError.textContent = '';
}

resetForm.addEventListener('submit', async function (event) {
  event.preventDefault();
  clearMessages();
  resetSubmit.disabled = true;
  resetSubmit.textContent = 'Resetting...';

  const token = document.getElementById('token').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const passwordConfirmation = document.getElementById('password-confirmation').value;

  try {
    const result = await Api.post('/staff/reset-password', { 
      token,
      email,
      password,
      password_confirmation: passwordConfirmation
    });
    resetSuccess.textContent = result.message || 'Password reset successful. You can now login.';
    resetSuccess.style.display = 'block';
    setTimeout(function() {
      window.location.href = '/staff/login';
    }, 2000);
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      if (error.data.errors.email) {
        emailError.textContent = error.data.errors.email[0];
      }
      if (error.data.errors.password) {
        passwordError.textContent = error.data.errors.password[0];
      }
      if (error.data.errors.token) {
        tokenError.textContent = error.data.errors.token[0];
      }
    } else {
      resetError.textContent = error.message || 'Unable to reset password. Please try again.';
      resetError.style.display = 'block';
    }
  } finally {
    resetSubmit.disabled = false;
    resetSubmit.textContent = 'Reset Password';
  }
});