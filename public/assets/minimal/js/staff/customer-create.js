const customerForm = document.getElementById('customer-form');
const customerError = document.getElementById('customer-error');
const customerSubmit = document.getElementById('customer-submit');
const nameError = document.getElementById('name-error');
const emailError = document.getElementById('email-error');
const usernameError = document.getElementById('username-error');
const passwordError = document.getElementById('password-error');

function clearErrors() {
    customerError.style.display = 'none';
    customerError.textContent = '';
    nameError.textContent = '';
    emailError.textContent = '';
    usernameError.textContent = '';
    passwordError.textContent = '';
}

customerForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    clearErrors();
    customerSubmit.disabled = true;
    customerSubmit.textContent = 'Creating...';
    
    const formData = {
        name: document.getElementById('customer-name').value.trim(),
        email: document.getElementById('customer-email').value.trim(),
        username: document.getElementById('customer-username').value.trim(),
        password: document.getElementById('customer-password').value,
    };
    
    try {
        const result = await Api.post('/staff/customer-accounts', formData);
        customerError.textContent = 'Customer account created successfully!';
        customerError.className = 'alert alert-success';
        customerError.style.display = 'block';
        customerForm.reset();
        
        setTimeout(function() {
            customerError.style.display = 'none';
            customerError.className = 'alert alert-error';
        }, 3000);
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            if (error.data.errors.name) nameError.textContent = error.data.errors.name[0];
            if (error.data.errors.email) emailError.textContent = error.data.errors.email[0];
            if (error.data.errors.username) usernameError.textContent = error.data.errors.username[0];
            if (error.data.errors.password) passwordError.textContent = error.data.errors.password[0];
        } else {
            customerError.textContent = error.message || 'Unable to create customer account.';
            customerError.style.display = 'block';
        }
    } finally {
        customerSubmit.disabled = false;
        customerSubmit.textContent = 'Create Account';
    }
});