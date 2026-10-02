(function () {
    const el = function (id) {
        return document.getElementById(id);
    };

    const settlementError = el('settlement-error');
    const settlementSuccess = el('settlement-success');
    const settlementLoading = el('settlement-loading');
    const settlementContent = el('settlement-content');
    const noticeArea = el('notice-area');
    const currentAccountInfo = el('current-account-info');
    const accountStatus = el('account-status');
    const statsGrid = el('stats-grid');
    const revenueAmountEl = el('revenue-amount');
    const revenueToggleBtn = el('revenue-toggle-btn');
    const revenueToggleIcon = el('revenue-toggle-icon');
    const revenueRefreshBtn = el('revenue-refresh-btn');
    const revenueUpdatedText = el('revenue-updated-text');
    const nextPayoutText = el('next-payout-text');
    const accountModal = el('account-modal');
    const closeAccountModalBtn = el('close-account-modal-btn');
    const cancelAccountModalBtn = el('cancel-account-modal-btn');
    const accountForm = el('account-form');
    const accountFormError = el('account-form-error');
    const accountSubmitBtn = el('account-submit-btn');
    const bankSelect = el('bank-id');
    const accountNumberInput = el('account-number');
    const detailModal = el('detail-modal');
    const detailBody = el('detail-body');
    const closeDetailModalBtn = el('close-detail-modal-btn');
    const historyStatusFilter = el('history-status-filter');
    const historyFrom = el('history-from');
    const historyTo = el('history-to');
    const historySearch = el('history-search');
    const historyBody = el('history-table-body');
    const historyPagination = el('history-pagination');
    const ledgerTypeFilter = el('ledger-type-filter');
    const ledgerBody = el('ledger-table-body');
    const ledgerPagination = el('ledger-pagination');

    const REQUIRED_IDS = [
        'account-form',
        'account-form-error',
        'account-modal',
        'account-number',
        'account-status',
        'account-submit-btn',
        'bank-id',
        'cancel-account-modal-btn',
        'close-account-modal-btn',
        'close-detail-modal-btn',
        'current-account-info',
        'detail-body',
        'detail-modal',
        'history-from',
        'history-pagination',
        'history-search',
        'history-status-filter',
        'history-table-body',
        'history-to',
        'ledger-pagination',
        'ledger-table-body',
        'ledger-type-filter',
        'next-payout-text',
        'notice-area',
        'revenue-amount',
        'revenue-refresh-btn',
        'revenue-toggle-btn',
        'revenue-toggle-icon',
        'revenue-updated-text',
        'settlement-content',
        'settlement-error',
        'settlement-loading',
        'settlement-success',
        'stats-grid',
        'tab-ledger',
        'tab-settlements',
    ];


    const PAGE_SIZE = 15;

    const state = {
        balance: null,
        accounts: { approved: null, pending: null, rejected: null },
        revenueVisible: true,
        settlements: { page: 1, lastPage: 1, total: 0 },
        ledger: { page: 1, lastPage: 1, total: 0 },
        activeTab: 'settlements',
        searchTimer: null,
        ledgerLoaded: false,
    };

    const SETTLEMENT_STATUS = {
        success: { label: 'Paid', bg: '#E9F8EF', text: '#1F7A44' },
        processing: { label: 'Processing', bg: '#EAF1FB', text: '#2775E4' },
        pending: { label: 'Queued', bg: '#FFF8EC', text: '#8A6116' },
        on_hold: { label: 'On hold', bg: '#FFF1E6', text: '#B4540A' },
        failed: { label: 'Failed', bg: '#FDEDEC', text: '#9C3A32' },
        reversed: { label: 'Reversed', bg: '#FDEDEC', text: '#9C3A32' },
        cancelled: { label: 'Cancelled', bg: '#F1F3F5', text: '#5B6570' },
    };

    const HOLD_REASONS = {
        exceeds_payout_ceiling: 'Awaiting platform approval for a large payout.',
        payouts_paused: 'Payouts are temporarily paused.',
        pharmacy_payout_hold: 'Payouts are on hold for your pharmacy.',
        account_not_approved: 'Your settlement account is not approved yet.',
        max_attempts_exceeded: 'Awaiting platform review after repeated attempts.',
        otp_required: 'Awaiting platform review.',
    };

    const LEDGER_TYPES = {
        sale_credit: 'Sale',
        gateway_fee: 'Payment processing fee',
        platform_fee: 'Platform commission',
        refund_debit: 'Refund',
        fee_reversal: 'Fee reversal',
        payout_debit: 'Payout',
        payout_reversal: 'Payout reversed',
        adjustment: 'Adjustment',
    };

    const WEEKDAYS = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatKobo(kobo) {
        const value = Number(kobo || 0) / 100;
        const sign = value < 0 ? '-' : '';
        return sign + '₦' + Math.abs(value).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDateTime(iso) {
        if (!iso) return '—';
        const date = new Date(iso);
        if (Number.isNaN(date.getTime())) return '—';
        return date.toLocaleString('en-NG', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' });
    }

    function formatDate(iso) {
        if (!iso) return '—';
        const date = new Date(iso);
        if (Number.isNaN(date.getTime())) return '—';
        return date.toLocaleDateString('en-NG', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function formatTime12(value) {
        const parts = String(value || '').split(':');
        const hour = parseInt(parts[0], 10);
        const minute = parts[1] || '00';
        if (Number.isNaN(hour)) return value;
        const suffix = hour >= 12 ? 'PM' : 'AM';
        const display = hour % 12 === 0 ? 12 : hour % 12;
        return display + ':' + minute + ' ' + suffix;
    }

    function maskAccountNumber(number) {
        const str = String(number || '');
        if (str.length <= 4) return str;
        return '•••• ' + str.slice(-4);
    }

    function describeError(error, fallback) {
        if (error && error.status === 403) {
            return 'You do not have permission to manage settlement.';
        }
        if (error && error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function (key) {
                messages.push(...error.data.errors[key]);
            });
            if (messages.length) return messages.join(', ');
        }
        return (error && error.message) || fallback;
    }

    function showError(message) {
        settlementError.textContent = message;
        settlementError.style.display = 'block';
    }

    function showSuccess(message) {
        settlementSuccess.textContent = message;
        settlementSuccess.style.display = 'block';
        window.setTimeout(function () {
            settlementSuccess.style.display = 'none';
        }, 7000);
    }

    function statusBadge(status) {
        const meta = SETTLEMENT_STATUS[status] || { label: status, bg: '#F1F3F5', text: '#5B6570' };
        return `<span class="inline-block rounded-full px-2.5 py-1 font-inter text-[11px] font-semibold" style="background:${meta.bg};color:${meta.text}">${escapeHtml(meta.label)}</span>`;
    }

    function noticeBox(bg, border, text, icon, message) {
        return `<div class="flex items-start gap-3 rounded-xl border px-4 py-3" style="background:${bg};border-color:${border}">
            <i class="ph-fill ${icon} mt-0.5 text-[16px]" style="color:${text}"></i>
            <p class="font-inter text-[13px] leading-relaxed" style="color:${text}">${message}</p>
        </div>`;
    }

    function renderRevenue() {
        const balance = state.balance;
        const kobo = balance ? balance.available_kobo : 0;
        revenueAmountEl.textContent = state.revenueVisible ? formatKobo(kobo) : '₦ ••••••••';
        revenueToggleIcon.className = state.revenueVisible ? 'ph-light ph-eye text-[17px]' : 'ph-light ph-eye-slash text-[17px]';
    }

    function nextPayoutMessage() {
        const balance = state.balance;
        if (!balance) return '';
        if (!state.accounts.approved) return 'Add and verify a settlement account to start receiving payouts.';
        if (balance.is_test_account) return 'This is a test account. Payouts are not sent for test accounts.';
        if (balance.payouts_paused) return 'Payouts are temporarily paused by the platform.';
        if (balance.payout_on_hold) return 'Payouts are on hold for your pharmacy. Please contact support.';
        if (balance.payout_schedule === 'manual') return 'Payouts for your pharmacy are triggered manually by the platform team.';
        if (balance.available_kobo < balance.minimum_payout_kobo) {
            return 'Payouts start once your available balance reaches ' + formatKobo(balance.minimum_payout_kobo) + '.';
        }
        const time = formatTime12(balance.batch_at) + ' (' + balance.timezone + ')';
        if (balance.payout_schedule === 'weekly') {
            return 'Next payout: ' + (WEEKDAYS[balance.weekly_day] || 'weekly') + ' at ' + time + '.';
        }
        return 'Next payout: daily at ' + time + '.';
    }

    function renderHero() {
        renderRevenue();
        nextPayoutText.textContent = nextPayoutMessage();
        revenueUpdatedText.textContent = 'Last updated ' + new Date().toLocaleTimeString('en-NG', { hour: 'numeric', minute: '2-digit' });
    }

    function statTile(label, kobo, hint) {
        return `<div class="rounded-2xl border border-[#EAF1FB] bg-white p-4 shadow-sm md:p-5">
            <p class="mb-1.5 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">${escapeHtml(label)}</p>
            <p class="font-manrope text-[19px] font-extrabold text-[#171E26]">${formatKobo(kobo)}</p>
            <p class="mt-1 font-inter text-[11.5px] text-[#171E26]/45">${escapeHtml(hint)}</p>
        </div>`;
    }

    function renderStats() {
        const b = state.balance;
        if (!b) {
            statsGrid.innerHTML = '';
            return;
        }
        statsGrid.innerHTML =
            statTile('In hold window', b.pending_kobo, 'Released ' + b.hold_hours + 'h after order completion') +
            statTile('Ready to settle', b.eligible_kobo, 'Included in the next payout run') +
            statTile('In transit', b.in_transit_kobo, 'Payouts being processed') +
            statTile('Total paid out', b.paid_out_kobo, 'Successfully sent to your bank');
    }

    function renderNotices() {
        const b = state.balance;
        const notices = [];
        if (!b) {
            noticeArea.innerHTML = '';
            return;
        }
        if (!state.accounts.approved && !state.accounts.pending) {
            notices.push(noticeBox('#FFF8EC', '#F3DDAF', '#8A6116', 'ph-warning', 'You have not added a settlement account yet. Add one so we can pay out your sales.'));
        }
        if (b.payouts_paused) {
            notices.push(noticeBox('#FFF8EC', '#F3DDAF', '#8A6116', 'ph-pause-circle', 'Payouts are temporarily paused by the platform. Your balance is safe and will be paid once payouts resume.'));
        }
        if (b.payout_on_hold) {
            notices.push(noticeBox('#FDEDEC', '#F5C6C2', '#9C3A32', 'ph-warning-circle', 'Payouts are on hold for your pharmacy. Please contact support for help.'));
        }
        if (b.payout_cooldown_until) {
            notices.push(noticeBox('#EAF1FB', '#C9DBF6', '#2775E4', 'ph-shield-check', 'For your security, payouts to your updated account begin after ' + escapeHtml(formatDateTime(b.payout_cooldown_until)) + '.'));
        }
        if (b.carried_debt_kobo > 0) {
            notices.push(noticeBox('#FFF1E6', '#F3D2B5', '#B4540A', 'ph-info', formatKobo(b.carried_debt_kobo) + ' will be deducted from your next payout because of refunds issued after an earlier payout.'));
        }
        if (b.held_kobo > 0) {
            notices.push(noticeBox('#FFF1E6', '#F3D2B5', '#B4540A', 'ph-info', formatKobo(b.held_kobo) + ' is on hold because of a cancelled order or a payment under review.'));
        }
        noticeArea.innerHTML = notices.join('');
    }

    function accountStatusLabel(status) {
        if (status === 'approved') {
            return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#1F7A44"><i class="ph-fill ph-check-circle"></i>Verified</span>`;
        }
        if (status === 'pending') {
            return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#8A6116"><i class="ph-fill ph-clock"></i>Pending review</span>`;
        }
        return `<span class="inline-flex items-center gap-1.5 font-inter text-[12.5px] font-semibold" style="color:#9C3A32"><i class="ph-fill ph-warning-circle"></i>Rejected</span>`;
    }

    function statusCard(bg, text, icon, title, message, extra) {
        return `<div class="mb-3 flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-full" style="background:${bg}"><i class="ph-light ${icon} text-[17px]" style="color:${text}"></i></div>
            <p class="font-inter text-[14px] font-semibold text-[#171E26]">${escapeHtml(title)}</p>
        </div>
        <p class="font-inter text-[13px] leading-relaxed text-[#171E26]/60">${escapeHtml(message)}</p>${extra || ''}`;
    }

    function renderCurrentAccount() {
        const accounts = state.accounts;
        const shown = accounts.approved || accounts.pending || accounts.rejected;

        if (!shown) {
            currentAccountInfo.innerHTML = `<div class="py-6 text-center">
                <i class="ph-light ph-bank mb-1.5 text-2xl text-[#171E26]/20"></i>
                <p class="mb-4 font-inter text-[13px] text-[#171E26]/40">No settlement account added yet.</p>
                <button type="button" data-action="open-account" class="rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] px-4 py-2 font-inter text-[12.5px] font-semibold text-white">Add Account</button>
            </div>`;
            accountStatus.innerHTML = `<div class="py-6 text-center"><p class="font-inter text-[13px] text-[#171E26]/40">Add a settlement account to see its status here.</p></div>`;
            return;
        }

        const actionLabel = accounts.approved ? 'Change Account' : 'Update Account';

        currentAccountInfo.innerHTML = `
            <p class="font-manrope text-[16px] font-bold text-[#171E26]">${escapeHtml(shown.bank_name)}</p>
            <p class="mt-0.5 font-inter text-[13px] text-[#171E26]/50">${escapeHtml(maskAccountNumber(shown.account_number))}</p>
            <p class="mt-3 font-inter text-[13px] text-[#171E26]/70">${escapeHtml(shown.account_name)}</p>
            <div class="mt-5 flex items-center justify-between border-t border-[#EAF1FB] pt-4">
                ${accountStatusLabel(shown.status)}
                <button type="button" data-action="open-account" class="font-inter text-[12.5px] font-semibold text-[#2775E4] hover:underline">${actionLabel}</button>
            </div>`;

        if (accounts.approved && accounts.pending) {
            const pending = accounts.pending;
            const cooldown = state.balance ? state.balance.account_change_cooldown_hours : 48;
            accountStatus.innerHTML = statusCard(
                '#FFF8EC', '#8A6116', 'ph-clock', 'Change Under Review',
                'Your current account stays active until the new one is approved. After approval, payouts to the new account begin ' + cooldown + ' hours later for your security.',
                `<div class="mt-3 border-t border-[#EAF1FB] pt-3">
                    <p class="mb-1 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Requested account</p>
                    <p class="font-inter text-[13px] text-[#171E26]">${escapeHtml(pending.bank_name)} · ${escapeHtml(maskAccountNumber(pending.account_number))}</p>
                    <p class="font-inter text-[12.5px] text-[#171E26]/60">${escapeHtml(pending.account_name)}</p>
                </div>`
            );
        } else if (accounts.approved) {
            accountStatus.innerHTML = statusCard('#E9F8EF', '#1F7A44', 'ph-check-circle', 'Account Verified', 'Payouts are sent to this account.', `<div class="mt-3 border-t border-[#EAF1FB] pt-3">
                <p class="mb-1 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Next Settlement</p>
                <p class="font-inter text-[13px] text-[#171E26]">${escapeHtml(nextPayoutMessage())}</p>
            </div>`);
        } else if (accounts.pending) {
            accountStatus.innerHTML = statusCard('#FFF8EC', '#8A6116', 'ph-clock', 'Pending Review', 'Your settlement account is pending review. You will be emailed once it is verified.');
        } else {
            accountStatus.innerHTML = statusCard('#FDEDEC', '#9C3A32', 'ph-warning-circle', 'Account Rejected', accounts.rejected.rejection_reason || 'Your settlement account was rejected. Please submit corrected details.');
        }
    }

    function holdReasonText(reason) {
        if (!reason) return '';
        return HOLD_REASONS[reason] || 'Awaiting platform review.';
    }

    function renderSettlementRows(rows) {
        if (!rows.length) {
            historyBody.innerHTML = `<tr><td colspan="5" class="py-10 text-center"><p class="font-inter text-[13px] text-[#171E26]/45">No settlements match this filter.</p></td></tr>`;
            return;
        }

        historyBody.innerHTML = rows.map(function (row) {
            const note = row.status === 'on_hold' ? holdReasonText(row.hold_reason) : (row.status === 'failed' || row.status === 'reversed' ? 'Funds returned to your balance.' : '');
            return `<tr data-id="${row.id}" class="cursor-pointer border-b border-[#F3F7FC] last:border-0 hover:bg-[#F8FBFF]">
                <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${escapeHtml(formatDate(row.created_at))}</td>
                <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${formatKobo(row.net_kobo)}</td>
                <td class="py-3 pr-4">${statusBadge(row.status)}${note ? `<p class="mt-1 font-inter text-[11px] text-[#171E26]/45">${escapeHtml(note)}</p>` : ''}</td>
                <td class="py-3 pr-4 font-inter text-[12px] text-[#171E26]/45">${escapeHtml(row.reference)}</td>
                <td class="py-3 text-right"><i class="ph-light ph-arrow-right text-[#171E26]/30"></i></td>
            </tr>`;
        }).join('');
    }

    function renderLedgerRows(rows) {
        if (!rows.length) {
            ledgerBody.innerHTML = `<tr><td colspan="4" class="py-10 text-center"><p class="font-inter text-[13px] text-[#171E26]/45">No transactions yet.</p></td></tr>`;
            return;
        }

        ledgerBody.innerHTML = rows.map(function (row) {
            const positive = row.amount_kobo >= 0;
            return `<tr class="border-b border-[#F3F7FC] last:border-0">
                <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${escapeHtml(formatDateTime(row.created_at))}</td>
                <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]">${escapeHtml(LEDGER_TYPES[row.type] || row.type)}</td>
                <td class="py-3 pr-4 font-inter text-[12px] text-[#171E26]/45">${row.order_id ? '#' + escapeHtml(row.order_id) : '—'}</td>
                <td class="py-3 text-right font-inter text-[13px] font-semibold" style="color:${positive ? '#1F7A44' : '#9C3A32'}">${positive ? '+' : ''}${formatKobo(row.amount_kobo)}</td>
            </tr>`;
        }).join('');
    }

    function renderPagination(container, info) {
        if (info.total === 0) {
            container.innerHTML = '';
            return;
        }
        container.innerHTML = `<p class="font-inter text-[12px] text-[#171E26]/45">Page ${info.page} of ${info.lastPage} · ${info.total} records</p>
            <div class="flex gap-2">
                <button type="button" data-page="${info.page - 1}" ${info.page <= 1 ? 'disabled' : ''} class="rounded-lg border border-[#EAF1FB] px-3 py-1.5 font-inter text-[12px] font-semibold text-[#171E26]/70 disabled:opacity-40">Previous</button>
                <button type="button" data-page="${info.page + 1}" ${info.page >= info.lastPage ? 'disabled' : ''} class="rounded-lg border border-[#EAF1FB] px-3 py-1.5 font-inter text-[12px] font-semibold text-[#171E26]/70 disabled:opacity-40">Next</button>
            </div>`;
    }

    async function loadBalance() {
        const response = await Api.get('/staff/settlement-balance');
        state.balance = response.data || response;
    }

    async function loadAccounts() {
        const response = await Api.get('/staff/settlement-account');
        const data = response.data || response;
        state.accounts = {
            approved: data.approved || null,
            pending: data.pending || null,
            rejected: data.rejected || null,
        };
    }

    async function loadSettlements(page) {
        const params = new URLSearchParams();
        params.set('page', String(page));
        params.set('per_page', String(PAGE_SIZE));
        if (historyStatusFilter.value) params.set('status', historyStatusFilter.value);
        if (historyFrom.value) params.set('from', historyFrom.value);
        if (historyTo.value) params.set('to', historyTo.value);
        if (historySearch.value.trim()) params.set('search', historySearch.value.trim());

        historyBody.innerHTML = `<tr><td colspan="5" class="py-10 text-center font-inter text-[13px] text-[#171E26]/45">Loading...</td></tr>`;

        try {
            const response = await Api.get('/staff/settlements?' + params.toString());
            const meta = response.meta || {};
            state.settlements = {
                page: meta.current_page || page,
                lastPage: meta.last_page || 1,
                total: meta.total || (response.data || []).length,
            };
            renderSettlementRows(response.data || []);
            renderPagination(historyPagination, state.settlements);
        } catch (error) {
            historyBody.innerHTML = `<tr><td colspan="5" class="py-10 text-center font-inter text-[13px] text-[#9C3A32]">${escapeHtml(describeError(error, 'Unable to load settlements.'))}</td></tr>`;
            historyPagination.innerHTML = '';
        }
    }

    async function loadLedger(page) {
        const params = new URLSearchParams();
        params.set('page', String(page));
        params.set('per_page', String(PAGE_SIZE));
        if (ledgerTypeFilter.value) params.set('type', ledgerTypeFilter.value);

        ledgerBody.innerHTML = `<tr><td colspan="4" class="py-10 text-center font-inter text-[13px] text-[#171E26]/45">Loading...</td></tr>`;

        try {
            const response = await Api.get('/staff/settlement-ledger?' + params.toString());
            const meta = response.meta || {};
            state.ledger = {
                page: meta.current_page || page,
                lastPage: meta.last_page || 1,
                total: meta.total || (response.data || []).length,
            };
            state.ledgerLoaded = true;
            renderLedgerRows(response.data || []);
            renderPagination(ledgerPagination, state.ledger);
        } catch (error) {
            ledgerBody.innerHTML = `<tr><td colspan="4" class="py-10 text-center font-inter text-[13px] text-[#9C3A32]">${escapeHtml(describeError(error, 'Unable to load transactions.'))}</td></tr>`;
            ledgerPagination.innerHTML = '';
        }
    }

    async function loadBanks() {
        try {
            const response = await Api.get('/staff/banks');
            const banks = response.data || response;
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

    function renderOverview() {
        renderHero();
        renderStats();
        renderNotices();
        renderCurrentAccount();
    }

    async function loadOverview(showSpinner) {
        if (!Auth.requireAuth()) return;

        if (showSpinner) {
            settlementLoading.style.display = 'block';
            settlementContent.style.display = 'none';
        }
        settlementError.style.display = 'none';

        try {
            await Promise.all([loadBalance(), loadAccounts()]);
            renderOverview();
            settlementLoading.style.display = 'none';
            settlementContent.style.display = 'block';
        } catch (error) {
            settlementLoading.style.display = 'none';
            showError(describeError(error, 'Unable to load settlement information.'));
        }
    }

    function setActiveTab(tab) {
        state.activeTab = tab;
        el('tab-settlements').style.display = tab === 'settlements' ? 'block' : 'none';
        el('tab-ledger').style.display = tab === 'ledger' ? 'block' : 'none';
        document.querySelectorAll('.tab-btn').forEach(function (button) {
            const active = button.dataset.tab === tab;
            button.style.borderColor = active ? '#2775E4' : 'transparent';
            button.style.color = active ? '#2775E4' : 'rgba(23,30,38,0.5)';
        });
        if (tab === 'ledger' && !state.ledgerLoaded) {
            loadLedger(1);
        }
    }

    function openAccountModal() {
        accountFormError.style.display = 'none';
        accountModal.style.display = 'flex';
    }

    function closeAccountModal() {
        accountModal.style.display = 'none';
    }

    function closeDetailModal() {
        detailModal.style.display = 'none';
        detailBody.innerHTML = '';
    }

    function detailRow(label, value) {
        return `<div class="flex items-start justify-between gap-4 border-b border-[#F3F7FC] py-2.5 last:border-0">
            <p class="font-inter text-[12.5px] text-[#171E26]/50">${escapeHtml(label)}</p>
            <p class="text-right font-inter text-[13px] text-[#171E26]">${value}</p>
        </div>`;
    }

    function renderDetail(settlement) {
        const account = settlement.account;
        const payments = settlement.payments || [];
        const reason = settlement.status === 'on_hold' ? holdReasonText(settlement.hold_reason) : (settlement.failure_reason || '');

        const paymentRows = payments.length
            ? payments.map(function (payment) {
                const fees = payment.gross_kobo - payment.net_kobo;
                return `<tr class="border-b border-[#F3F7FC] last:border-0">
                    <td class="py-2 pr-3 font-inter text-[12.5px] text-[#171E26]">Order #${escapeHtml(payment.order_id)}</td>
                    <td class="py-2 pr-3 font-inter text-[12px] text-[#171E26]/50">${escapeHtml(formatDate(payment.paid_at))}</td>
                    <td class="py-2 pr-3 text-right font-inter text-[12.5px] text-[#171E26]/70">${formatKobo(payment.gross_kobo)}</td>
                    <td class="py-2 pr-3 text-right font-inter text-[12.5px] text-[#171E26]/50">${formatKobo(fees)}</td>
                    <td class="py-2 text-right font-inter text-[12.5px] font-semibold text-[#171E26]">${formatKobo(payment.net_kobo)}</td>
                </tr>`;
            }).join('')
            : `<tr><td colspan="5" class="py-4 text-center font-inter text-[12.5px] text-[#171E26]/45">No payments linked.</td></tr>`;

        detailBody.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <p class="font-inter text-[12.5px] text-[#171E26]/50">${escapeHtml(settlement.reference)}</p>
                ${statusBadge(settlement.status)}
            </div>
            ${reason ? `<div class="mb-4 rounded-xl bg-[#FFF8EC] px-4 py-3 font-inter text-[12.5px] text-[#8A6116]">${escapeHtml(reason)}</div>` : ''}
            <div class="mb-5 rounded-xl border border-[#EAF1FB] px-4">
                ${detailRow('Sales total', formatKobo(settlement.gross_kobo))}
                ${detailRow('Fees and commission', '−' + formatKobo(settlement.fee_kobo))}
                ${detailRow('Adjustments', formatKobo(settlement.adjustment_kobo))}
                ${detailRow('Amount paid out', `<span class="font-manrope text-[15px] font-extrabold">${formatKobo(settlement.net_kobo)}</span>`)}
            </div>
            <div class="mb-5 rounded-xl border border-[#EAF1FB] px-4">
                ${detailRow('Bank', account ? escapeHtml(account.bank_name) : '—')}
                ${detailRow('Account', account ? escapeHtml(account.account_name) + '<br><span class="text-[12px] text-[#171E26]/50">' + escapeHtml(account.account_number) + '</span>' : '—')}
                ${detailRow('Created', escapeHtml(formatDateTime(settlement.created_at)))}
                ${detailRow('Completed', escapeHtml(formatDateTime(settlement.processed_at)))}
            </div>
            <p class="mb-2 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Included payments</p>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#EAF1FB]">
                            <th class="py-2 pr-3 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Order</th>
                            <th class="py-2 pr-3 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Paid</th>
                            <th class="py-2 pr-3 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Gross</th>
                            <th class="py-2 pr-3 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Fees</th>
                            <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Net</th>
                        </tr>
                    </thead>
                    <tbody>${paymentRows}</tbody>
                </table>
            </div>`;
    }

    async function openSettlementDetail(id) {
        detailBody.innerHTML = `<p class="py-10 text-center font-inter text-[13px] text-[#171E26]/45">Loading...</p>`;
        detailModal.style.display = 'flex';

        try {
            const response = await Api.get('/staff/settlements/' + id);
            renderDetail(response.data || response);
        } catch (error) {
            detailBody.innerHTML = `<p class="py-10 text-center font-inter text-[13px] text-[#9C3A32]">${escapeHtml(describeError(error, 'Unable to load settlement.'))}</p>`;
        }
    }

    revenueToggleBtn.addEventListener('click', function () {
        state.revenueVisible = !state.revenueVisible;
        renderRevenue();
    });

    revenueRefreshBtn.addEventListener('click', async function () {
        revenueRefreshBtn.disabled = true;
        await loadOverview(false);
        await loadSettlements(state.settlements.page);
        if (state.ledgerLoaded) await loadLedger(state.ledger.page);
        revenueRefreshBtn.disabled = false;
    });

    document.querySelectorAll('.tab-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            setActiveTab(button.dataset.tab);
        });
    });

    historyStatusFilter.addEventListener('change', function () {
        loadSettlements(1);
    });
    historyFrom.addEventListener('change', function () {
        loadSettlements(1);
    });
    historyTo.addEventListener('change', function () {
        loadSettlements(1);
    });
    historySearch.addEventListener('input', function () {
        window.clearTimeout(state.searchTimer);
        state.searchTimer = window.setTimeout(function () {
            loadSettlements(1);
        }, 400);
    });
    ledgerTypeFilter.addEventListener('change', function () {
        loadLedger(1);
    });

    historyPagination.addEventListener('click', function (event) {
        const button = event.target.closest('button[data-page]');
        if (button && !button.disabled) loadSettlements(parseInt(button.dataset.page, 10));
    });
    ledgerPagination.addEventListener('click', function (event) {
        const button = event.target.closest('button[data-page]');
        if (button && !button.disabled) loadLedger(parseInt(button.dataset.page, 10));
    });

    historyBody.addEventListener('click', function (event) {
        const row = event.target.closest('tr[data-id]');
        if (row) openSettlementDetail(row.dataset.id);
    });

    currentAccountInfo.addEventListener('click', function (event) {
        if (event.target.closest('[data-action="open-account"]')) openAccountModal();
    });

    closeAccountModalBtn.addEventListener('click', closeAccountModal);
    cancelAccountModalBtn.addEventListener('click', closeAccountModal);
    accountModal.addEventListener('click', function (event) {
        if (event.target === accountModal) closeAccountModal();
    });
    closeDetailModalBtn.addEventListener('click', closeDetailModal);
    detailModal.addEventListener('click', function (event) {
        if (event.target === detailModal) closeDetailModal();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAccountModal();
            closeDetailModal();
        }
    });

    accountNumberInput.addEventListener('input', function () {
        accountNumberInput.value = accountNumberInput.value.replace(/\D/g, '').slice(0, 10);
    });

    accountForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        accountFormError.style.display = 'none';

        if (!bankSelect.value) {
            accountFormError.textContent = 'Please select a bank.';
            accountFormError.style.display = 'block';
            return;
        }

        const accountNumber = accountNumberInput.value.trim();
        if (!/^\d{10}$/.test(accountNumber)) {
            accountFormError.textContent = 'Account number must be exactly 10 digits.';
            accountFormError.style.display = 'block';
            return;
        }

        accountSubmitBtn.disabled = true;
        accountSubmitBtn.textContent = 'Verifying...';

        try {
            const response = await Api.post('/staff/settlement-account', {
                bank_id: parseInt(bankSelect.value, 10),
                account_number: accountNumber,
            });
            const created = response.data || {};
            accountForm.reset();
            closeAccountModal();
            showSuccess('Account verified as "' + (created.account_name || 'your account') + '" and submitted for review.');
            await loadOverview(false);
        } catch (error) {
            accountFormError.textContent = describeError(error, 'Unable to save settlement account.');
            accountFormError.style.display = 'block';
        } finally {
            accountSubmitBtn.disabled = false;
            accountSubmitBtn.textContent = 'Verify & Submit';
        }
    });

    window.setInterval(function () {
        if (document.visibilityState === 'visible' && settlementContent.style.display !== 'none') {
            loadOverview(false);
        }
    }, 60000);

    setActiveTab('settlements');
    loadBanks();
    loadOverview(true).then(function () {
        loadSettlements(1);
    });
})();
