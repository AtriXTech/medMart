/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadSubscription() (GET /staff/subscription, GET
    /staff/subscription-plans, same sequencing), loadPaymentHistory()
    (GET /staff/subscription/payment-history), window.selectPlan (same
    confirm() dialog, same POST /staff/subscription payload shape, same
    authorization_url redirect for external payment, same 422 error
    handling), the duration <select> data-plan-id lookup pattern.
  - CHANGED (behavior, flagging clearly): subscriptionMessage previously
    got its color from toggling .alert / .alert-success / .alert-error
    classes defined in app.css. Since that stylesheet is gone from this
    layout, I replaced the className toggle with direct inline
    background/color styling (same two states: success, error) so the
    message still visually communicates success vs. failure — this is
    a necessary substitution, not an optional style choice, since the
    old classes literally no longer exist anywhere.
  - CHANGED (presentation only): renderPlans(), renderPaymentHistory(),
    and the status badges rebuilt with Tailwind markup.
*/

const subscriptionError = document.getElementById('subscription-error');
const subscriptionLoading = document.getElementById('subscription-loading');
const subscriptionContent = document.getElementById('subscription-content');
const currentPlan = document.getElementById('current-plan');
const currentStatus = document.getElementById('current-status');
const currentExpiry = document.getElementById('current-expiry');
const plansContainer = document.getElementById('plans-container');
const subscriptionMessage = document.getElementById('subscription-message');
const paymentHistoryTable = document.getElementById('payment-history-table');

let selectedPlanId = null;
let selectedDuration = 1;

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

function formatBillingInterval(interval) {
    const map = {
        monthly: 'month',
        yearly: 'year',
        quarterly: 'quarter',
        weekly: 'week',
        daily: 'day',
    };
    return map[interval] || interval || 'month';
}

function humanize(str) {
    return String(str || '').split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
}

const SUB_STATUS_STYLE = {
    active: { bg: '#E9F8EF', text: '#1F7A44' },
    trialing: { bg: '#FFF8EC', text: '#8A6116' },
    past_due: { bg: '#FDEDEC', text: '#9C3A32' },
    cancelled: { bg: '#FDEDEC', text: '#9C3A32' },
    unpaid: { bg: '#FDEDEC', text: '#9C3A32' },
    incomplete: { bg: '#FFF8EC', text: '#8A6116' },
    incomplete_expired: { bg: '#FDEDEC', text: '#9C3A32' },
};
function badgeForStatus(status) {
    const s = SUB_STATUS_STYLE[status] || { bg: '#F1F3F6', text: '#4B5563' };
    return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${status ? humanize(status) : 'N/A'}</span>`;
}

const PAYMENT_STATUS_STYLE = {
    paid: { bg: '#E9F8EF', text: '#1F7A44' },
    unpaid: { bg: '#FFF8EC', text: '#8A6116' },
    failed: { bg: '#FDEDEC', text: '#9C3A32' },
    refunded: { bg: '#F1F3F6', text: '#4B5563' },
};
function badgeForPaymentStatus(status) {
    const s = PAYMENT_STATUS_STYLE[status] || { bg: '#F1F3F6', text: '#4B5563' };
    return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${humanize(status)}</span>`;
}

function renderCurrentSubscription(subscription) {
    if (!subscription) {
        currentPlan.textContent = 'No active plan';
        currentStatus.textContent = 'N/A';
        currentExpiry.textContent = 'N/A';
        return;
    }

    currentPlan.innerHTML = subscription.plan ? subscription.plan.name : 'N/A';
    currentStatus.innerHTML = badgeForStatus(subscription.status);
    currentExpiry.textContent = formatDate(subscription.current_period_ends_at);
}

function renderPlans(plans, hasActiveSubscription) {
    if (!plans || plans.length === 0) {
        plansContainer.innerHTML = `<div class="text-center py-8">
          <p class="font-inter text-[13px] text-[#171E26]/40">No subscription plans available</p>
        </div>`;
        return;
    }

    const buttonText = hasActiveSubscription ? 'Renew' : 'Subscribe';
    plansContainer.innerHTML = '';

    plans.forEach(function (plan) {
        const features = [];
        const allowedDurations = Array.isArray(plan.allowed_durations) ? plan.allowed_durations : [1, 6, 12, 24];

        if (plan.max_branches) features.push(`${plan.max_branches} branch(es)`);
        if (plan.max_staff) features.push(`${plan.max_staff} staff member(s)`);
        if (plan.max_products) features.push(`${plan.max_products} product(s)`);

        const featuresText = features.length > 0 ? features.join(', ') : 'Basic features';

        let durationOptions = '';
        allowedDurations.forEach(function (duration) {
            const totalPrice = plan.price * duration;
            durationOptions += `<option value="${duration}" data-plan-id="${plan.id}">${duration} month${duration > 1 ? 's' : ''} — ${formatCurrency(totalPrice)}</option>`;
        });

        const planCard = document.createElement('div');
        planCard.className = 'rounded-xl border border-[#EAF1FB] p-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4';

        planCard.innerHTML = `
            <div class="flex-1 min-w-0">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">${plan.name}</h3>
                <p class="font-inter text-[15px] font-semibold text-[#2775E4] mt-1">${formatCurrency(plan.price)} <span class="text-[#171E26]/45 font-normal text-[13px]">/ ${formatBillingInterval(plan.billing_interval)}</span></p>
                <p class="font-inter text-[13px] text-[#171E26]/55 mt-1.5">${featuresText}</p>
                <div class="mt-3 max-w-[260px]">
                    <label for="duration-${plan.id}" class="field-label">Duration</label>
                    <select id="duration-${plan.id}" class="duration-select field-input" data-plan-id="${plan.id}">
                        ${durationOptions}
                    </select>
                </div>
            </div>
            <button id="subscribe-btn-${plan.id}" onclick="selectPlan(${plan.id})"
                class="flex-shrink-0 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[13px] shadow-sm shadow-[#2775E4]/20 hover:scale-[1.02] transition">
                ${buttonText}
            </button>
        `;

        plansContainer.appendChild(planCard);

        const select = document.getElementById(`duration-${plan.id}`);
        select.addEventListener('change', function () {
            if (selectedPlanId === plan.id) {
                selectedDuration = parseInt(this.value);
            }
        });
    });
}

function renderPaymentHistory(payments) {
    if (!payments || payments.length === 0) {
        paymentHistoryTable.innerHTML = `<tr><td colspan="5" class="text-center py-14">
          <i class="ph-light ph-receipt text-3xl text-[#171E26]/20 block mb-2"></i>
          <p class="font-inter text-[13px] text-[#171E26]/45">No payment history</p>
        </td></tr>`;
        return;
    }

    paymentHistoryTable.innerHTML = payments.map(function (payment) {
        return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
            <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${payment.reference}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${payment.plan ? payment.plan.name : 'N/A'}</td>
            <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(payment.amount)}</td>
            <td class="py-3 pr-4">${badgeForPaymentStatus(payment.status)}</td>
            <td class="py-3 font-inter text-[12px] text-[#171E26]/45">${formatDate(payment.paid_at || payment.created_at)}</td>
        </tr>`;
    }).join('');
}

function showMessage(text, kind) {
    subscriptionMessage.textContent = text;
    if (kind === 'success') {
        subscriptionMessage.style.background = '#E9F8EF';
        subscriptionMessage.style.color = '#1F7A44';
    } else {
        subscriptionMessage.style.background = '#FDEDEC';
        subscriptionMessage.style.color = '#9C3A32';
    }
    subscriptionMessage.style.display = 'block';
}

window.selectPlan = async function (planId) {
    const planSelect = document.querySelector(`.duration-select[data-plan-id="${planId}"]`);
    selectedPlanId = planId;
    selectedDuration = parseInt(planSelect ? planSelect.value : 1);

    if (!confirm(`Subscribe to this plan for ${selectedDuration} month(s)?`)) return;

    subscriptionMessage.style.display = 'none';
    subscriptionMessage.textContent = '';

    try {
        const result = await Api.post('/staff/subscription', {
            subscription_plan_id: planId,
            duration_months: selectedDuration
        });

        if (result.authorization_url) {
            window.location.href = result.authorization_url;
        } else {
            showMessage(result.message || 'Subscription initiated successfully!', 'success');
            loadSubscription();
        }
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function (key) {
                messages.push(...error.data.errors[key]);
            });
            showMessage(messages.join(', '), 'error');
        } else {
            showMessage(error.message || 'Unable to subscribe to plan.', 'error');
        }
    }
};

async function loadPaymentHistory() {
    try {
        const data = await Api.get('/staff/subscription/payment-history');
        renderPaymentHistory(data.data || []);
    } catch (error) {
        console.error('Unable to load payment history:', error);
    }
}

async function loadSubscription() {
    if (!Auth.requireAuth()) return;

    subscriptionLoading.style.display = 'block';
    subscriptionContent.style.display = 'none';
    subscriptionError.style.display = 'none';

    try {
        const subscription = await Api.get('/staff/subscription');
        const plansData = await Api.get('/staff/subscription-plans');

        renderCurrentSubscription(subscription);
        renderPlans(plansData.data || plansData, subscription !== null);

        subscriptionLoading.style.display = 'none';
        subscriptionContent.style.display = 'block';

        loadPaymentHistory();
    } catch (error) {
        subscriptionLoading.style.display = 'none';
        subscriptionError.textContent = error.message || 'Unable to load subscription.';
        subscriptionError.style.display = 'block';
    }
}

loadSubscription();