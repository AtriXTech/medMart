const verificationLoading = document.getElementById('verification-loading');
const verificationError = document.getElementById('verification-error');
const verificationSuccess = document.getElementById('verification-success');
const verificationSuccessMessage = document.getElementById('verification-success-message');

async function verifyEmail() {
  const urlParams = new URLSearchParams(window.location.search);
  const token = urlParams.get('token');
  const email = urlParams.get('email');

  if (!token || !email) {
    verificationLoading.style.display = 'none';
    verificationError.textContent = 'Invalid verification link.';
    verificationError.style.display = 'block';
    return;
  }

  try {
    const response = await fetch('/api/v1/customer/verify-email', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ token, email }),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || 'Unable to verify email.');
    }

    verificationLoading.style.display = 'none';
    verificationSuccessMessage.style.display = 'block';
  } catch (error) {
    verificationLoading.style.display = 'none';
    verificationError.textContent = error.message || 'Unable to verify email.';
    verificationError.style.display = 'block';
  }
}

verifyEmail();