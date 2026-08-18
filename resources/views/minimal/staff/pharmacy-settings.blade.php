<x-layouts.staff title="Pharmacy Settings" active="pharmacy-settings">
    <div class="alert alert-error" id="settings-error" style="display: none;"></div>

    <div id="settings-loading" class="loading-state">Loading settings...</div>

    <div id="settings-content" style="display: none;">
        <div class="card" style="max-width: 500px;">
            <p class="section-title">Pharmacy Information</p>
            <div class="alert alert-error" id="settings-form-error" style="display: none;"></div>
            <div class="alert alert-success" id="settings-success" style="display: none;"></div>
            <form id="settings-form">
                <div class="field">
                    <label for="pharmacy-name">Pharmacy Name</label>
                    <input type="text" id="pharmacy-name" required>
                </div>
                <div class="field">
                    <label for="pharmacy-email">Email</label>
                    <input type="email" id="pharmacy-email" required>
                </div>
                <div class="field">
                    <label for="pharmacy-phone">Phone</label>
                    <input type="text" id="pharmacy-phone">
                </div>
                <div class="field">
                    <label for="pharmacy-address">Address</label>
                    <textarea id="pharmacy-address" rows="3"></textarea>
                </div>
                <div class="field">
                    <label for="pharmacy-timezone">Timezone</label>
                    <select id="pharmacy-timezone">
                        <option value="Africa/Lagos">Africa/Lagos (WAT)</option>
                        <option value="Africa/Nairobi">Africa/Nairobi (EAT)</option>
                        <option value="Africa/Johannesburg">Africa/Johannesburg (SAST)</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
                <div class="field">
                    <label for="pharmacy-currency">Currency</label>
                    <select id="pharmacy-currency">
                        <option value="NGN">NGN - Nigerian Naira</option>
                        <option value="GHS">GHS - Ghanaian Cedi</option>
                        <option value="KES">KES - Kenyan Shilling</option>
                        <option value="ZAR">ZAR - South African Rand</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" id="settings-submit-btn">Save Settings</button>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/pharmacy-settings.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>