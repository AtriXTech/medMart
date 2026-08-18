const subscriptionError = document.getElementById('subscription-error');
const subscriptionLoading = document.getElementById('subscription-loading');
const subscriptionContent = document.getElementById('subscription-content');
const currentPlan = document.getElementById('current-plan');
const currentStatus = document.getElementById('current-status');
const currentExpiry = document.getElementById('current-expiry');
const plansContainer = document.getElementById('plans-container');
const subscriptionMessage = document.getElementById('subscription-message');
const paymentHistoryTable = document.getElementById('payment-history-table');

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

function badgeForStatus(status) {
    const statusMap = {
        active: 'badge-success',
        trialing: 'badge-warning',
        past_due: 'badge-danger',
        cancelled: 'badge-danger',
        unpaid: 'badge-danger',
        incomplete: 'badge-warning',
        incomplete_expired: 'badge-danger',
    };
    const cls = statusMap[status] || 'badge-muted';
    return `<span class="badge ${cls}">${status || 'N/A'}</span>`;
}

function badgeForPaymentStatus(status) {
    const statusMap = {
        paid: 'badge-success',
        unpaid: 'badge-warning',
        failed: 'badge-danger',
        refunded: 'badge-muted',
    };
    const cls = statusMap[status] || 'badge-muted';
    return `<span class="badge ${cls}">${status}</span>`;
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
    plansContainer.innerHTML = '';
    
    if (!plans || plans.length === 0) {
        plansContainer.innerHTML = '<div class="empty-state">No subscription plans available</div>';
        return;
    }

    const buttonText = hasActiveSubscription ? 'Renew' : 'Subscribe';

    plans.forEach(function(plan) {
        const features = [];
        const allowedDurations = Array.isArray(plan.allowed_durations) ? plan.allowed_durations : [1, 6, 12, 24];
        
        if (plan.max_branches) features.push(`${plan.max_branches} branch(es)`);
        if (plan.max_staff) features.push(`${plan.max_staff} staff member(s)`);
        if (plan.max_products) features.push(`${plan.max_products} product(s)`);
        
        const featuresText = features.length > 0 ? features.join(', ') : 'Basic features';
        
        const planCard = document.createElement('div');
        planCard.className = 'card';
        planCard.style.marginBottom = '16px';
        
        let durationOptions = '';
        allowedDurations.forEach(function(duration) {
            const totalPrice = plan.price * duration;
            durationOptions += `
                <option value="${duration}" data-plan-id="${plan.id}">
                    ${duration} month${duration > 1 ? 's' : ''} - ${formatCurrency(totalPrice)}
                </option>
            `;
        });
        
        planCard.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px 0;">${plan.name}</h3>
                    <p style="margin: 0 0 8px 0;">
                        <strong>${formatCurrency(plan.price)}</strong> / ${formatBillingInterval(plan.billing_interval)}
                    </p>
                    <p style="margin: 0 0 12px 0; color: var(--text-muted);">${featuresText}</p>
                    <div class="field" style="margin: 0;">
                        <label for="duration-${plan.id}">Duration</label>
                        <select id="duration-${plan.id}" class="duration-select" data-plan-id="${plan.id}">
                            ${durationOptions}
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" id="subscribe-btn-${plan.id}" onclick="selectPlan(${plan.id})">
                    ${buttonText}
                </button>
            </div>
        `;
        
        plansContainer.appendChild(planCard);
        
        const select = document.getElementById(`duration-${plan.id}`);
        select.addEventListener('change', function() {
            if (selectedPlanId === plan.id) {
                selectedDuration = parseInt(this.value);
            }
        });
    });
}

function renderPaymentHistory(payments) {
    paymentHistoryTable.innerHTML = '';
    
    if (!payments || payments.length === 0) {
        paymentHistoryTable.innerHTML = '<tr><td colspan="5" class="empty-state">No payment history</td></tr>';
        return;
    }
    
    payments.forEach(function(payment) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${payment.reference}</td>
            <td>${payment.plan ? payment.plan.name : 'N/A'}</td>
            <td>${formatCurrency(payment.amount)}</td>
            <td>${badgeForPaymentStatus(payment.status)}</td>
            <td>${formatDate(payment.paid_at || payment.created_at)}</td>
        `;
        paymentHistoryTable.appendChild(tr);
    });
}

window.selectPlan = async function(planId) {
    const planSelect = document.querySelector(`.duration-select[data-plan-id="${planId}"]`);
    selectedPlanId = planId;
    selectedDuration = parseInt(planSelect ? planSelect.value : 1);
    
    if (!confirm(`Subscribe to this plan for ${selectedDuration} month(s)?`)) return;
    
    subscriptionMessage.style.display = 'none';
    subscriptionMessage.textContent = '';
    subscriptionMessage.className = 'alert';
    
    try {
        const result = await Api.post('/staff/subscription', { 
            subscription_plan_id: planId,
            duration_months: selectedDuration
        });
        
        if (result.authorization_url) {
            window.location.href = result.authorization_url;
        } else {
            subscriptionMessage.textContent = result.message || 'Subscription initiated successfully!';
            subscriptionMessage.className = 'alert alert-success';
            subscriptionMessage.style.display = 'block';
            loadSubscription();
        }
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function(key) {
                messages.push(...error.data.errors[key]);
            });
            subscriptionMessage.textContent = messages.join(', ');
        } else {
            subscriptionMessage.textContent = error.message || 'Unable to subscribe to plan.';
        }
        subscriptionMessage.className = 'alert alert-error';
        subscriptionMessage.style.display = 'block';
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

let selectedPlanId = null;
let selectedDuration = 1;

loadSubscription();