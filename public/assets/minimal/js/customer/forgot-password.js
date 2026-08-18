const forgotForm = document.getElementById('forgot-form');
const forgotError = document.getElementById('forgot-error');
const forgotSuccess = document.getElementById('forgot-success');
const forgotSubmit = document.getElementById('forgot-submit');
const emailError = document.getElementById('email-error');

forgotForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  forgotError.style.display = 'none';
  forgotSuccess.style.display = 'none';
  emailError.textContent = '';
  forgotSubmit.disabled = true;
  forgotSubmit.textContent = 'Sending...';

  const email = document.getElementById('email').value.trim();

  try {
    const result = await CustomerApi.post('/customer/forgot-password', { email });
    forgotSuccess.textContent = result.message || 'Reset link sent to your email.';
    forgotSuccess.style.display = 'block';
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      if (error.data.errors.email) emailError.textContent = error.data.errors.email[0];
    } else {
      forgotError.textContent = error.message || 'Unable to send reset link.';
      forgotError.style.display = 'block';
    }
  } finally {
    forgotSubmit.disabled = false;
    forgotSubmit.textContent = 'Send Reset Link';
  }
});