const settlementError = document.getElementById('settlement-error');
const settlementLoading = document.getElementById('settlement-loading');
const settlementContent = document.getElementById('settlement-content');
const currentAccountInfo = document.getElementById('current-account-info');
const accountForm = document.getElementById('account-form');
const bankSelect = document.getElementById('bank-id');
const accountNumberInput = document.getElementById('account-number');
const accountNameInput = document.getElementById('account-name');
const accountFormError = document.getElementById('account-form-error');
const accountSubmitBtn = document.getElementById('account-submit-btn');
const accountStatus = document.getElementById('account-status');

function badgeForStatus(status) {
    const map = {
        pending: 'badge-warning',
        active: 'badge-success',
        rejected: 'badge-danger',
    };
    const cls = map[status] || 'badge-muted';
    return `<span class="badge ${cls}">${status}</span>`;
}

function renderCurrentAccount(account) {
    if (!account) {
        currentAccountInfo.innerHTML = '<p class="empty-state">No settlement account added yet.</p>';
        accountStatus.innerHTML = '';
        return;
    }
    
    currentAccountInfo.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <strong>Bank:</strong> ${account.bank ? account.bank.name : account.bank_name}
            </div>
            <div>
                <strong>Account Name:</strong> ${account.account_name}
            </div>
            <div>
                <strong>Account Number:</strong> ${account.account_number}
            </div>
            <div>
                <strong>Status:</strong> ${badgeForStatus(account.status)}
            </div>
            ${account.rejection_reason ? `
                <div>
                    <strong>Rejection Reason:</strong> ${account.rejection_reason}
                </div>
            ` : ''}
        </div>
    `;
    
    if (account.status === 'pending') {
        accountStatus.innerHTML = '<div class="alert alert-warning">Your settlement account is pending review. You will be notified once verified.</div>';
    } else if (account.status === 'active') {
        accountStatus.innerHTML = '<div class="alert alert-success">Your settlement account is active and verified.</div>';
    } else if (account.status === 'rejected') {
        accountStatus.innerHTML = '<div class="alert alert-error">Your settlement account was rejected. Please update your details.</div>';
    }
}

async function loadBanks() {
    try {
        const data = await Api.get('/staff/banks');
        const banks = data.data || data;
        
        bankSelect.innerHTML = '<option value="">Select Bank</option>';
        
        banks.forEach(function(bank) {
            const option = document.createElement('option');
            option.value = bank.id;
            option.textContent = bank.name;
            bankSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Unable to load banks:', error);
    }
}

async function loadSettlementAccount() {
    if (!Auth.requireAuth()) return;
    
    settlementLoading.style.display = 'block';
    settlementContent.style.display = 'none';
    settlementError.style.display = 'none';
    
    try {
        const account = await Api.get('/staff/settlement-account');
        renderCurrentAccount(account);
        settlementLoading.style.display = 'none';
        settlementContent.style.display = 'block';
    } catch (error) {
        settlementLoading.style.display = 'none';
        settlementError.textContent = error.message || 'Unable to load settlement account.';
        settlementError.style.display = 'block';
    }
}

accountForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    accountSubmitBtn.disabled = true;
    accountFormError.style.display = 'none';
    
    if (!bankSelect.value) {
        accountFormError.textContent = 'Please select a bank.';
        accountFormError.style.display = 'block';
        accountSubmitBtn.disabled = false;
        return;
    }
    
    const formData = {
        bank_id: parseInt(bankSelect.value),
        account_number: accountNumberInput.value.trim(),
        account_name: accountNameInput.value.trim(),
    };
    
    try {
        await Api.post('/staff/settlement-account', formData);
        accountForm.reset();
        loadSettlementAccount();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function(key) {
                messages.push(...error.data.errors[key]);
            });
            accountFormError.textContent = messages.join(', ');
        } else {
            accountFormError.textContent = error.message || 'Unable to save settlement account.';
        }
        accountFormError.style.display = 'block';
    } finally {
        accountSubmitBtn.disabled = false;
    }
});

loadBanks();
loadSettlementAccount();