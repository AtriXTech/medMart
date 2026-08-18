Auth.requireGuest();

const loginForm = document.getElementById('login-form');
const loginError = document.getElementById('login-error');
const loginSubmit = document.getElementById('login-submit');
const emailError = document.getElementById('email-error');
const passwordError = document.getElementById('password-error');

function clearErrors() {
  loginError.style.display = 'none';
  loginError.textContent = '';
  emailError.textContent = '';
  passwordError.textContent = '';
}

function showFieldErrors(errors) {
  if (errors.email) {
    emailError.textContent = errors.email[0];
  }
  if (errors.password) {
    passwordError.textContent = errors.password[0];
  }
}

loginForm.addEventListener('submit', async function (event) {
  event.preventDefault();
  clearErrors();
  loginSubmit.disabled = true;
  loginSubmit.textContent = 'Signing in...';

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  try {
    const result = await Api.post('/staff/login', { email, password,  device_name: 'web'  });
    Api.setToken(result.token);
    Api.setUser(result.user);
    window.location.href = '/staff/dashboard';
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      showFieldErrors(error.data.errors);
    } else {
      loginError.textContent = error.message || 'Unable to sign in. Please try again.';
      loginError.style.display = 'block';
    }
  } finally {
    loginSubmit.disabled = false;
    loginSubmit.textContent = 'Sign In';
  }
});