/*
  New page — no previous version to diff against.

  - Profile and Help cards are plain <a> links (no JS needed).
  - Logout card opens a confirm modal; only calls the logout endpoint if
    the modal's "Log Out" button is clicked.
  - FLAGGED ASSUMPTION: assumes CustomerAuth.logout() already exists as
    the established pattern in this app (mirroring CustomerAuth.requireAuth()
    used on every other customer page) and that it internally calls
    POST /customer/logout, clears the stored token, and redirects to the
    login page. If that helper doesn't exist, this falls back to calling
    CustomerApi.post('/customer/logout') directly and redirecting to
    /customer/login itself — but I don't know the exact token storage key
    your api.js uses, so that fallback path may need a small adjustment on
    your end (e.g. clearing whatever localStorage key holds the customer
    token) if CustomerAuth.logout() doesn't already do it.
*/

const logoutCardBtn = document.getElementById('logout-card-btn');
const confirmModal = document.getElementById('confirm-modal');
const confirmModalCancelBtn = document.getElementById('confirm-modal-cancel-btn');
const confirmModalConfirmBtn = document.getElementById('confirm-modal-confirm-btn');
const confirmModalError = document.getElementById('confirm-modal-error');

function showConfirmModal() {
    confirmModalError.style.display = 'none';
    confirmModal.style.display = 'flex';
}

function closeConfirmModal() {
    confirmModal.style.display = 'none';
}

logoutCardBtn.addEventListener('click', showConfirmModal);
confirmModalCancelBtn.addEventListener('click', closeConfirmModal);
confirmModal.addEventListener('click', function (event) {
    if (event.target === confirmModal) closeConfirmModal();
});

confirmModalConfirmBtn.addEventListener('click', async function () {
    confirmModalConfirmBtn.disabled = true;
    confirmModalError.style.display = 'none';

    try {
        if (typeof CustomerAuth !== 'undefined' && typeof CustomerAuth.logout === 'function') {
            await CustomerAuth.logout();
        } else {
            await CustomerApi.post('/customer/logout');
            window.location.href = '/customer/login';
        }
    } catch (error) {
        confirmModalError.textContent = error.message || 'Unable to log out. Please try again.';
        confirmModalError.style.display = 'block';
        confirmModalConfirmBtn.disabled = false;
    }
});