const verificationLoading = document.getElementById('verification-loading');
const verificationError = document.getElementById('verification-error');
const verificationSuccess = document.getElementById('verification-success');
const verificationSuccessMessage = document.getElementById('verification-success-message');

// Hide an element
function hideElement(element) {
    element.classList.add('hidden');
}

// Show an element
function showElement(element, display = 'block') {
    element.classList.remove('hidden');

    if (display === 'flex') {
        element.classList.add('flex');
    }
}

// Display an error message
function showVerificationError(message) {
    hideElement(verificationLoading);

    verificationError.textContent = message;
    showElement(verificationError);
}

async function verifyEmail() {
    const urlParams = new URLSearchParams(window.location.search);

    const token = urlParams.get('token');
    const email = urlParams.get('email');

    // Validate verification parameters
    if (!token || !email) {
        showVerificationError('Invalid verification link.');
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

       // Show success state
hideElement(verificationLoading);
hideElement(verificationError);
hideElement(verificationSuccess);

verificationSuccessMessage.classList.remove('hidden');
verificationSuccessMessage.classList.add('flex');

    } catch (error) {
        showVerificationError(
            error.message || 'Unable to verify email.'
        );
    }
}

verifyEmail();