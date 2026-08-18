<x-layouts.customer title="My Profile" active="profile">
    <div class="alert alert-error" id="profile-error" style="display: none;"></div>
    <div id="profile-loading" class="loading-state">Loading profile...</div>
    <div id="profile-content" style="display: none;">
        <div class="card" style="margin-bottom: 16px;">
            <p class="section-title">Profile Information</p>
            <div id="profile-info"></div>
        </div>

        <div class="card" style="margin-bottom: 16px;">
            <p class="section-title">Edit Profile</p>
            <div class="alert alert-error" id="profile-form-error" style="display: none;"></div>
            <div class="alert alert-success" id="profile-form-success" style="display: none;"></div>
            <form id="profile-form">
                <div class="field">
                    <label for="profile-name">Name</label>
                    <input type="text" id="profile-name" required>
                </div>
                <div class="field">
                    <label for="profile-email">Email</label>
                    <input type="email" id="profile-email" required>
                </div>
                <div class="field">
                    <label for="profile-phone">Phone</label>
                    <input type="text" id="profile-phone">
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="profile-submit-btn">Update Profile</button>
            </form>
        </div>

        <div class="card">
            <p class="section-title">Change Password</p>
            <div class="alert alert-error" id="password-form-error" style="display: none;"></div>
            <div class="alert alert-success" id="password-form-success" style="display: none;"></div>
            <form id="password-form">
                <div class="field">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" required>
                </div>
                <div class="field">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" required>
                </div>
                <div class="field">
                    <label for="new-password-confirmation">Confirm New Password</label>
                    <input type="password" id="new-password-confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="password-submit-btn">Change Password</button>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/profile.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>