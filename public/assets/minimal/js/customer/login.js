CustomerAuth.requireGuest();

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

loginForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  clearErrors();
  loginSubmit.disabled = true;
  loginSubmit.textContent = 'Signing in...';

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  try {
    const result = await CustomerApi.post('/customer/login', { 
      email: email,
      password: password,
      device_name: 'web',
    });
    
    CustomerApi.setToken(result.token);
    CustomerApi.setCustomer(result.customer);
    window.location.href = '/customer/products';
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      if (error.data.errors.email) emailError.textContent = error.data.errors.email[0];
      if (error.data.errors.password) passwordError.textContent = error.data.errors.password[0];
    } else {
      loginError.textContent = error.message || 'Unable to sign in.';
      loginError.style.display = 'block';
    }
  } finally {
    loginSubmit.disabled = false;
    loginSubmit.textContent = 'Sign In';
  }
});