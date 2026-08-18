const onboardingError = document.getElementById('onboarding-error');
const onboardingLoading = document.getElementById('onboarding-loading');
const onboardingContent = document.getElementById('onboarding-content');
const plansContainer = document.getElementById('plans-container');
const selectedPlanInfo = document.getElementById('selected-plan-info');
const confirmSubscriptionBtn = document.getElementById('confirm-subscription-btn');
const skipForNowBtn = document.getElementById('skip-for-now-btn');
const subscriptionMessage = document.getElementById('subscription-message');

let selectedPlanId = null;

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
    plansContainer.innerHTML = '<div class="empty-state">No subscription plans available</div>';
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
    planCard.className = 'card';
    planCard.style.cssText = 'margin-bottom: 16px; cursor: pointer; transition: all 0.2s;';
    planCard.dataset.planId = plan.id;
    planCard.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
          <h3 style="margin: 0 0 8px 0;">${plan.name}</h3>
          <p style="margin: 0 0 8px 0;">
            <strong>${formatCurrency(plan.price)}</strong> / ${formatBillingInterval(plan.billing_interval)}
          </p>
          <p style="margin: 0 0 12px 0; color: var(--text-muted);">${featuresText}</p>
        </div>
        <span class="badge badge-success" style="display: none;">Selected</span>
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
  
  const allCards = plansContainer.querySelectorAll('.card');
  allCards.forEach(function(card) {
    card.style.border = '1px solid var(--border)';
    const badge = card.querySelector('.badge');
    if (badge) badge.style.display = 'none';
  });
  
  const selectedCard = plansContainer.querySelector(`[data-plan-id="${plan.id}"]`);
  if (selectedCard) {
    selectedCard.style.border = '2px solid var(--primary)';
    const badge = selectedCard.querySelector('.badge');
    if (badge) badge.style.display = 'inline-block';
  }
  
  selectedPlanInfo.innerHTML = `
    <strong>Selected Plan:</strong> ${plan.name} - ${formatCurrency(plan.price)}/${formatBillingInterval(plan.billing_interval)}
  `;
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
    onboardingError.style.display = 'block';
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
      subscriptionMessage.className = 'alert alert-success';
      subscriptionMessage.style.display = 'block';
      
      setTimeout(function() {
        window.location.href = '/staff/dashboard';
      }, 2000);
    }
  } catch (error) {
    subscriptionMessage.textContent = error.message || 'Unable to subscribe to plan.';
    subscriptionMessage.className = 'alert alert-error';
    subscriptionMessage.style.display = 'block';
  } finally {
    confirmSubscriptionBtn.disabled = false;
    confirmSubscriptionBtn.textContent = 'Confirm Subscription';
  }
});

skipForNowBtn.addEventListener('click', function() {
  window.location.href = '/staff/dashboard';
});

loadPlans();