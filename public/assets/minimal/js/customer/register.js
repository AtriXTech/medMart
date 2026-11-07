const registerForm = document.getElementById('register-form');
const registerError = document.getElementById('register-error');
const registerSubmit = document.getElementById('register-submit');
const nameError = document.getElementById('name-error');
const usernameError = document.getElementById('username-error');
const emailError = document.getElementById('email-error');
const passwordError = document.getElementById('password-error');
const pharmacyCodeError = document.getElementById('pharmacy-code-error');

function clearErrors() {
  registerError.style.display = 'none';
  registerError.textContent = '';
  nameError.textContent = '';
  usernameError.textContent = '';
  emailError.textContent = '';
  passwordError.textContent = '';
  pharmacyCodeError.textContent = '';
}

function showFieldErrors(errors) {
  if (errors.name) nameError.textContent = errors.name[0];
  if (errors.username) usernameError.textContent = errors.username[0];
  if (errors.email) emailError.textContent = errors.email[0];
  if (errors.password) passwordError.textContent = errors.password[0];
  if (errors.pharmacy_code) pharmacyCodeError.textContent = errors.pharmacy_code[0];
}
function showRegisterToast(message) {
  const toast = document.createElement('div');
  toast.setAttribute('role', 'status');
  toast.textContent = message;

  const styles = {
    'position': 'fixed',
    'top': '20px',
    'right': '20px',
    'z-index': '2147483647',
    'max-width': '360px',
    'padding': '12px 16px',
    'border-radius': '12px',
    'background': '#ECFDF5',
    'border': '1px solid #A7F3D0',
    'color': '#047857',
    'font-family': 'Inter, sans-serif',
    'font-size': '14px',
    'font-weight': '500',
    'box-shadow': '0 10px 25px rgba(0,0,0,0.1)',
    'opacity': '0',
    'transform': 'translateY(-10px)',
    'transition': 'opacity 0.3s ease, transform 0.3s ease',
  };

  Object.keys(styles).forEach(function (prop) {
    toast.style.setProperty(prop, styles[prop], 'important');
  });

  document.body.appendChild(toast);

  requestAnimationFrame(function () {
    toast.style.setProperty('opacity', '1', 'important');
    toast.style.setProperty('transform', 'translateY(0)', 'important');
  });

  setTimeout(function () {
    toast.style.setProperty('opacity', '0', 'important');
    toast.style.setProperty('transform', 'translateY(-10px)', 'important');
    setTimeout(function () { toast.remove(); }, 300);
  }, 2700);
}

registerForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  clearErrors();
  registerSubmit.disabled = true;
  registerSubmit.textContent = 'Creating Account...';

  const formData = {
    name: document.getElementById('name').value.trim(),
    username: document.getElementById('username').value.trim(),
    email: document.getElementById('email').value.trim(),
    password: document.getElementById('password').value,
    password_confirmation: document.getElementById('password-confirmation').value,
    pharmacy_code: document.getElementById('pharmacy-code').value.trim().toUpperCase(),
  };

  try {
    const response = await fetch('/api/v1/customer/register', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      if (response.status === 422 && data.errors) {
        showFieldErrors(data.errors);
        return;
      }
      throw new Error(data.message || 'Unable to create account.');
    }
    
    showRegisterToast('Registration successful! Please check your email to verify your account.');
    
    setTimeout(function() {
      window.location.href = '/customer/login';
    }, 3000);
  } catch (error) {
    registerError.textContent = error.message || 'Unable to create account.';
    registerError.className = 'alert alert-error';
    registerError.style.display = 'block';
  } finally {
    registerSubmit.disabled = false;
    registerSubmit.textContent = 'Create Account';
  }
});