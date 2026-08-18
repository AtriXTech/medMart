<x-layouts.staff title="Choose Your Plan" active="subscription">
    <div class="alert alert-error" id="onboarding-error" style="display: none;"></div>

    <div id="onboarding-loading" class="loading-state">Loading plans...</div>

    <div id="onboarding-content" style="display: none;">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 24px; margin-bottom: 8px;">Welcome to MedMart!</h1>
                <p style="color: var(--text-muted); margin: 0;">Choose a subscription plan to get started</p>
            </div>

            <div class="alert" id="subscription-message" style="display: none;"></div>

            <div id="plans-container"></div>

            <div id="selected-plan-info" style="display: none; margin: 20px 0; padding: 16px; background: #eff4ff; border-radius: var(--radius);"></div>

            <div style="display: flex; gap: 12px; justify-content: center; margin-top: 24px;">
                <button class="btn btn-secondary" id="skip-for-now-btn">Skip for Now</button>
                <button class="btn btn-primary" id="confirm-subscription-btn" disabled>Confirm Subscription</button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/pharmacy/onboarding.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>