const forgotForm = document.getElementById('forgot-form');
const forgotError = document.getElementById('forgot-error');
const forgotSuccess = document.getElementById('forgot-success');
const forgotSubmit = document.getElementById('forgot-submit');
const emailError = document.getElementById('email-error');

function clearMessages() {
  forgotError.style.display = 'none';
  forgotError.textContent = '';
  forgotSuccess.style.display = 'none';
  forgotSuccess.textContent = '';
  emailError.textContent = '';
}

forgotForm.addEventListener('submit', async function (event) {
  event.preventDefault();
  clearMessages();
  forgotSubmit.disabled = true;
  forgotSubmit.textContent = 'Sending...';

  const email = document.getElementById('email').value.trim();

  try {
    const result = await Api.post('/staff/forgot-password', { email });
    forgotSuccess.textContent = result.message || 'Password reset link has been sent to your email.';
    forgotSuccess.style.display = 'block';
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      if (error.data.errors.email) {
        emailError.textContent = error.data.errors.email[0];
      }
    } else {
      forgotError.textContent = error.message || 'Unable to send reset link. Please try again.';
      forgotError.style.display = 'block';
    }
  } finally {
    forgotSubmit.disabled = false;
    forgotSubmit.textContent = 'Send Reset Link';
  }
});