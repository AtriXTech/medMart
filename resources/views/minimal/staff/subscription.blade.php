<x-layouts.staff title="Subscription" active="subscription">
    <div class="alert alert-error" id="subscription-error" style="display: none;"></div>

    <div id="subscription-loading" class="loading-state">Loading subscription...</div>

    <div id="subscription-content" style="display: none;">
        <div class="alert" id="subscription-message" style="display: none;"></div>

        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Current Subscription</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div>
                    <strong>Plan:</strong> <span id="current-plan">No active plan</span>
                </div>
                <div>
                    <strong>Status:</strong> <span id="current-status">N/A</span>
                </div>
                <div>
                    <strong>Expires:</strong> <span id="current-expiry">N/A</span>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Available Plans</p>
            <div id="plans-container"></div>
        </div>

        <div class="card">
            <p class="section-title">Payment History</p>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="payment-history-table"></tbody>
            </table>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/subscription.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>