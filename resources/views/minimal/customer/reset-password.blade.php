<x-layouts.customer-guest title="Reset Password">
    <x-slot:subtitle>
        <p class="sub">Enter your new password</p>
    </x-slot:subtitle>

    <div class="alert alert-error" id="reset-error" style="display: none;"></div>
    <div class="alert alert-success" id="reset-success" style="display: none;"></div>

    <form id="reset-form" novalidate>
        <div class="field">
            <label for="token">Reset Token</label>
            <input type="text" id="token" name="token" required>
            <div class="field-error" id="token-error"></div>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <div class="field-error" id="email-error"></div>
        </div>
        <div class="field">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required>
            <div class="field-error" id="password-error"></div>
        </div>
        <div class="field">
            <label for="password-confirmation">Confirm Password</label>
            <input type="password" id="password-confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="reset-submit">Reset Password</button>
    </form>

    <div class="guest-links">
        <a href="/customer/login">Back to login</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/reset-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>