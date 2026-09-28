/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadBanks() (GET /staff/banks), loadSettlementAccount()
    (GET /staff/settlement-account, same loading/content/error toggle),
    the account-form submit handler's endpoint (POST
    /staff/settlement-account, same bank_id/account_number/account_name
    payload, same 422 error handling) — only now also closes the modal
    on success.
  - REDESIGNED per your layout: renderCurrentAccount() completely
    rebuilt. #current-account-info now renders the "Settlement Account"
    card (bank name, masked account number •••• last4, account name,
    Verified/Pending/Rejected label, Update/Add Account button).
    #account-status now renders the separate "Settlement Status" card
    (verified/pending/rejected state with icon). The account-form itself
    moved into a new modal (#account-modal), opened via the Update/Add
    Account button — still the exact same form fields/IDs, just no
    longer permanently visible on the page.
  - REMOVED (superseded by the new design): badgeForStatus()/humanize()/
    infoField()/statusBanner() — the old pill-badge + grid + alert-banner
    presentation isn't used by the new layout. Replaced by
    accountStatusLabel() and statusCard(), built for this design.
  - MOCKED — no endpoint in the API spec supplies these, so they're
    static placeholder data. Full list also in my reply:
    * Customer Order Revenue hero card (total + "last updated" text) —
      MOCK_REVENUE. The refresh button re-fetches the real settlement
      account but can't actually refresh this figure.
    * "Next Settlement" date on the Settlement Status card —
      MOCK_NEXT_SETTLEMENT.
    * The three stat tiles (Total Settled / Pending / Last Settlement) —
      MOCK_STATS.
    * Settlement History table rows — MOCK_HISTORY. The Date/Status
      filters above the table are real, working client-side filters —
      they just filter this static array instead of a live paginated
      endpoint.
*/

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

// NEW — modal elements
const accountModal = document.getElementById('account-modal');
const closeAccountModalBtn = document.getElementById('close-account-modal-btn');
const cancelAccountModalBtn = document.getElementById('cancel-account-modal-btn');

// NEW — revenue hero card elements
const revenueAmountEl = document.getElementById('revenue-amount');
const revenueToggleBtn = document.getElementById('revenue-toggle-btn');
const revenueToggleIcon = document.getElementById('revenue-toggle-icon');
const revenueRefreshBtn = document.getElementById('revenue-refresh-btn');
const revenueUpdatedText = document.getElementById('revenue-updated-text');

// NEW — history filter elements
const historyStatusFilter = document.getElementById('history-status-filter');
const historyDateFilter = document.getElementById('history-date-filter');

function formatCurrency(amount) {
    return '₦' + Number(amount || 0).toLocaleString();
}

function maskAccountNumber(number) {
    const str = String(number || '');
    if (str.length <= 4) return str;
    return '•••• ' + str.slice(-4);
}

/* ==================== MOCK DATA — no backing endpoint yet ==================== */
/* See change summary / chat reply for the full list of missing endpoints. */

const MOCK_REVENUE = 1284500;
const MOCK_NEXT_SETTLEMENT = 'Aug 30';
const MOCK_STATS = {
    totalSettled: 2450300,
    pending: 198200,
    lastSettlement: 185400,
};
const MOCK_HISTORY = [
    { date: '2026-08-29', label: 'Aug 29', amount: 185400, status: 'settled', reference: 'ST-92831' },
    { date: '2026-08-28', label: 'Aug 28', amount: 241700, status: 'settled', reference: 'ST-92784' },
    { date: '2026-08-27', label: 'Aug 27', amount: 198200, status: 'pending', reference: 'ST-92720' },
];

/* ==================== Revenue hero card (mock figure, real refresh of account) ==================== */

let revenueVisible = true;

function renderRevenue() {
    revenueAmountEl.textContent = revenueVisible ? formatCurrency(MOCK_REVENUE) : '₦ ••••••••';
    revenueToggleIcon.className = revenueVisible ? 'ph-light ph-eye text-[17px]' : 'ph-light ph-eye-slash text-[17px]';
}

revenueToggleBtn.addEventListener('click', function () {
    revenueVisible = !revenueVisible;
    renderRevenue();
});

revenueRefreshBtn.addEventListener('click', function () {
    // The revenue figure itself doesn't change here (mock, no endpoint to
    // refetch it from) — this only re-fetches the real settlement account.
    revenueUpdatedText.textContent = 'Last updated just now';
    loadSettlementAccount();
});

/* ==================== Settlement Account + Settlement Status cards ==================== */

function accountStatusLabel(status) {
    if (status === 'approved') {
        return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#1F7A44"><i class="ph-fill ph-check-circle"></i>Verified</span>`;
    }
    if (status === 'pending') {
        return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#8A6116"><i class="ph-fill ph-clock"></i>Pending</span>`;
    }
    return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#9C3A32"><i class="ph-fill ph-warning-circle"></i>Rejected</span>`;
}

function statusCard(bg, text, icon, title, message) {
    return `<div class="flex items-center gap-2.5 mb-3">
      <div class="h-9 w-9 rounded-full flex items-center justify-center" style="background:${bg}"><i class="ph-light ${icon} text-[17px]" style="color:${text}"></i></div>
      <p class="font-inter text-[14px] font-semibold text-[#171E26]">${title}</p>
    </div>
    <p class="font-inter text-[13px] text-[#171E26]/60 leading-relaxed">${message}</p>`;
}

function renderCurrentAccount(account) {
    if (!account) {
        currentAccountInfo.innerHTML = `<div class="text-center py-6">
          <i class="ph-light ph-bank text-2xl text-[#171E26]/20 mb-1.5"></i>
          <p class="font-inter text-[13px] text-[#171E26]/40 mb-4">No settlement account added yet.</p>
          <button type="button" onclick="openAccountModal()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[12.5px] font-semibold">Add Account</button>
        </div>`;
        accountStatus.innerHTML = `<div class="text-center py-6">
          <p class="font-inter text-[13px] text-[#171E26]/40">Add a settlement account to see its status here.</p>
        </div>`;
        return;
    }

    const bankName = account.bank ? account.bank.name : account.bank_name;

    currentAccountInfo.innerHTML = `
        <p class="font-manrope font-bold text-[16px] text-[#171E26]">${bankName}</p>
        <p class="font-inter text-[13px] text-[#171E26]/50 mt-0.5">${maskAccountNumber(account.account_number)}</p>
        <p class="font-inter text-[13px] text-[#171E26]/70 mt-3">${account.account_name}</p>
        <div class="flex items-center justify-between mt-5 pt-4 border-t border-[#EAF1FB]">
            ${accountStatusLabel(account.status)}
            <button type="button" onclick="openAccountModal()" class="font-inter text-[12.5px] font-semibold text-[#2775E4] hover:underline">Update Account</button>
        </div>
    `;

    if (account.status === 'pending') {
        accountStatus.innerHTML = statusCard('#FFF8EC', '#8A6116', 'ph-clock', 'Pending Review', 'Your settlement account is pending review. You will be notified once verified.');
    } else if (account.status === 'approved') {
        accountStatus.innerHTML = `
            <div class="flex items-center gap-2.5 mb-4">
              <div class="h-9 w-9 rounded-full flex items-center justify-center" style="background:#E9F8EF"><i class="ph-fill ph-check-circle text-[17px]" style="color:#1F7A44"></i></div>
              <p class="font-inter text-[14px] font-semibold text-[#171E26]">Account Verified</p>
            </div>
            <div class="pt-3 border-t border-[#EAF1FB]">
              <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1">Next Settlement</p>
              <p class="font-inter text-[14px] text-[#171E26]">${MOCK_NEXT_SETTLEMENT}</p>
            </div>`;
    } else if (account.status === 'rejected') {
        accountStatus.innerHTML = statusCard('#FDEDEC', '#9C3A32', 'ph-warning-circle', 'Account Rejected', account.rejection_reason || 'Your settlement account was rejected. Please update your details.');
    }
}

/* ==================== Stat tiles (mock) ==================== */

function statTile(label, value) {
    return `<div class="rounded-2xl bg-white border border-[#EAF1FB] shadow-sm p-4 md:p-5">
      <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1.5">${label}</p>
      <p class="font-manrope font-extrabold text-[19px] text-[#171E26]">${formatCurrency(value)}</p>
    </div>`;
}

function renderStats() {
    document.getElementById('stats-grid').innerHTML =
        statTile('Total Settled', MOCK_STATS.totalSettled) +
        statTile('Pending', MOCK_STATS.pending) +
        statTile('Last Settlement', MOCK_STATS.lastSettlement);
}

/* ==================== Settlement History (mock data, real client-side filters) ==================== */

function historyStatusBadge(status) {
    const s = status === 'settled' ? { bg: '#E9F8EF', text: '#1F7A44' } : { bg: '#FFF8EC', text: '#8A6116' };
    return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${status === 'settled' ? 'Settled' : 'Pending'}</span>`;
}

function renderHistory() {
    const statusValue = historyStatusFilter.value;
    const dateValue = historyDateFilter.value;

    const filtered = MOCK_HISTORY.filter(function (row) {
        if (statusValue && row.status !== statusValue) return false;
        if (dateValue && row.date !== dateValue) return false;
        return true;
    });

    const body = document.getElementById('history-table-body');

    if (filtered.length === 0) {
        body.innerHTML = `<tr><td colspan="5" class="text-center py-10"><p class="font-inter text-[13px] text-[#171E26]/45">No settlements match this filter.</p></td></tr>`;
        return;
    }

    body.innerHTML = filtered.map(function (row) {
        return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
          <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${row.label}</td>
          <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(row.amount)}</td>
          <td class="py-3 pr-4">${historyStatusBadge(row.status)}</td>
          <td class="py-3 pr-4 font-inter text-[12px] text-[#171E26]/45">${row.reference}</td>
          <td class="py-3 text-right"><i class="ph-light ph-arrow-right text-[#171E26]/30"></i></td>
        </tr>`;
    }).join('');
}

function populateHistoryDateFilter() {
    MOCK_HISTORY.forEach(function (row) {
        const option = document.createElement('option');
        option.value = row.date;
        option.textContent = row.label;
        historyDateFilter.appendChild(option);
    });
}

historyStatusFilter.addEventListener('change', renderHistory);
historyDateFilter.addEventListener('change', renderHistory);

/* ==================== Account modal ==================== */

window.openAccountModal = function () {
    accountFormError.style.display = 'none';
    accountModal.style.display = 'flex';
};

function closeAccountModal() {
    accountModal.style.display = 'none';
}

closeAccountModalBtn.addEventListener('click', closeAccountModal);
cancelAccountModalBtn.addEventListener('click', closeAccountModal);
accountModal.addEventListener('click', function (event) {
    if (event.target === accountModal) closeAccountModal();
});

/* ==================== Data loading (unchanged endpoints) ==================== */

async function loadBanks() {
    try {
        const data = await Api.get('/staff/banks');
        const banks = data.data || data;

        bankSelect.innerHTML = '<option value="">Select Bank</option>';

        banks.forEach(function (bank) {
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

accountForm.addEventListener('submit', async function (event) {
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
        closeAccountModal();
        loadSettlementAccount();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function (key) {
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

renderRevenue();
renderStats();
populateHistoryDateFilter();
renderHistory();
loadBanks();
loadSettlementAccount();