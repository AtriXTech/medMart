<x-layouts.staff title="Settlement Account" active="settlement">
    <div class="alert alert-error" id="settlement-error" style="display: none;"></div>
    <div id="settlement-loading" class="loading-state">Loading settlement account...</div>
    <div id="settlement-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Current Settlement Account</p>
            <div id="account-status"></div>
            <div id="current-account-info"></div>
        </div>

        <div class="card">
            <p class="section-title">Update Settlement Account</p>
            <div class="alert alert-error" id="account-form-error" style="display: none;"></div>
            <form id="account-form">
                <div class="field">
                    <label for="bank-id">Bank Name</label>
                    <select id="bank-id" required>
                        <option value="">Select Bank</option>
                    </select>
                </div>
                <div class="field">
                    <label for="account-number">Account Number</label>
                    <input type="text" id="account-number" required>
                </div>
                <div class="field">
                    <label for="account-name">Account Name</label>
                    <input type="text" id="account-name" required>
                </div>
                <button type="submit" class="btn btn-primary" id="account-submit-btn">Save Settlement Account</button>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/settlement.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>