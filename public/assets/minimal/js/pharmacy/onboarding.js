const onboardingError = document.getElementById('onboarding-error');
const onboardingLoading = document.getElementById('onboarding-loading');
const onboardingContent = document.getElementById('onboarding-content');
const plansContainer = document.getElementById('plans-container');
const selectedPlanInfo = document.getElementById('selected-plan-info');
const confirmSubscriptionBtn = document.getElementById('confirm-subscription-btn');
const skipForNowBtn = document.getElementById('skip-for-now-btn');
const subscriptionMessage = document.getElementById('subscription-message');

let selectedPlanId = null;

// Full class strings for plan cards — swapped entirely on select/deselect
// instead of raw inline style manipulation. Both include the 'plan-card'
// token so querySelectorAll('.plan-card') keeps working.
const PLAN_CARD_BASE = 'plan-card bg-white rounded-2xl border-2 border-[#EAF1FB] p-5 cursor-pointer hover:shadow-md transition mb-4';
const PLAN_CARD_SELECTED = 'plan-card bg-[#DBEBFB]/30 rounded-2xl border-2 border-[#2775E4] p-5 cursor-pointer hover:shadow-md transition mb-4';

// Full class strings for subscription-message, reused for both error and
// success states by swapping className entirely (same trick as the original
// code, just with real Tailwind classes instead of dead ones).
const MESSAGE_ERROR_CLASSES = 'mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 font-inter text-sm font-medium';
const MESSAGE_SUCCESS_CLASSES = 'mb-5 flex items-center gap-2.5 bg-[#DBEBFB] border border-[#B1D0FB] text-[#2775E4] rounded-xl px-4 py-3 font-inter text-sm font-medium';

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

function renderPlans(plans) {
  plansContainer.innerHTML = '';

  if (!plans || plans.length === 0) {
    plansContainer.innerHTML = `
      <div class="text-center py-14">
        <i class="ph ph-crown text-3xl text-[#171E26]/20"></i>
        <p class="font-inter text-sm text-[#171E26]/45 mt-2">No subscription plans available</p>
      </div>
    `;
    return;
  }

  plans.forEach(function(plan) {
    const features = [];

    if (plan.max_branches) {
      features.push(`${plan.max_branches} branch(es)`);
    }
    if (plan.max_staff) {
      features.push(`${plan.max_staff} staff member(s)`);
    }
    if (plan.max_products) {
      features.push(`${plan.max_products} product(s)`);
    }

    const featuresText = features.length > 0 ? features.join(', ') : 'Basic features';

    const planCard = document.createElement('div');
    planCard.className = PLAN_CARD_BASE;
    planCard.dataset.planId = plan.id;
    planCard.innerHTML = `
      <div class="flex justify-between items-start gap-4">
        <div>
          <h3 class="font-manrope text-[17px] font-bold text-[#171E26] mb-1.5">${plan.name}</h3>
          <p class="font-inter text-[15px] text-[#171E26] mb-1.5">
            <strong class="font-manrope font-extrabold text-[#2775E4]">${formatCurrency(plan.price)}</strong> / ${formatBillingInterval(plan.billing_interval)}
          </p>
          <p class="font-inter text-[13px] text-[#171E26]/55">${featuresText}</p>
        </div>
        <span class="plan-badge flex-shrink-0 font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full bg-[#2775E4] text-white" style="display: none;">Selected</span>
      </div>
    `;

    planCard.onclick = function() {
      selectPlan(plan);
    };

    plansContainer.appendChild(planCard);
  });
}

function selectPlan(plan) {
  selectedPlanId = plan.id;

  const allCards = plansContainer.querySelectorAll('.plan-card');
  allCards.forEach(function(card) {
    card.className = PLAN_CARD_BASE;
    const badge = card.querySelector('.plan-badge');
    if (badge) badge.style.display = 'none';
  });

  const selectedCard = plansContainer.querySelector(`[data-plan-id="${plan.id}"]`);
  if (selectedCard) {
    selectedCard.className = PLAN_CARD_SELECTED;
    const badge = selectedCard.querySelector('.plan-badge');
    if (badge) badge.style.display = 'inline-block';
  }

  selectedPlanInfo.innerHTML = `<strong>Selected Plan:</strong> ${plan.name} - ${formatCurrency(plan.price)}/${formatBillingInterval(plan.billing_interval)}`;
  selectedPlanInfo.style.display = 'block';
  confirmSubscriptionBtn.disabled = false;
}

async function loadPlans() {
  if (!Auth.requireAuth()) return;

  onboardingLoading.style.display = 'block';
  onboardingContent.style.display = 'none';
  onboardingError.style.display = 'none';

  try {
    const plansData = await Api.get('/staff/subscription-plans');
    renderPlans(plansData.data || plansData);

    onboardingLoading.style.display = 'none';
    onboardingContent.style.display = 'block';
  } catch (error) {
    onboardingLoading.style.display = 'none';
    onboardingError.textContent = error.message || 'Unable to load subscription plans.';
    onboardingError.style.display = 'flex';
  }
}

confirmSubscriptionBtn.addEventListener('click', async function() {
  if (!selectedPlanId) return;

  confirmSubscriptionBtn.disabled = true;
  confirmSubscriptionBtn.textContent = 'Processing...';
  subscriptionMessage.style.display = 'none';

  try {
    const result = await Api.post('/staff/subscription', { subscription_plan_id: selectedPlanId });

    if (result.authorization_url) {
      window.location.href = result.authorization_url;
    } else {
      subscriptionMessage.textContent = 'Subscription activated successfully!';
      subscriptionMessage.className = MESSAGE_SUCCESS_CLASSES;
      subscriptionMessage.style.display = 'flex';

      setTimeout(function() {
        window.location.href = '/staff/dashboard';
      }, 2000);
    }
  } catch (error) {
    subscriptionMessage.textContent = error.message || 'Unable to subscribe to plan.';
    subscriptionMessage.className = MESSAGE_ERROR_CLASSES;
    subscriptionMessage.style.display = 'flex';
  } finally {
    confirmSubscriptionBtn.disabled = false;
    confirmSubscriptionBtn.textContent = 'Confirm Subscription';
  }
});

skipForNowBtn.addEventListener('click', function() {
  window.location.href = '/staff/dashboard';
});

loadPlans();