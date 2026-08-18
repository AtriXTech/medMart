<x-layouts.customer-guest title="Create Account">
    <x-slot:subtitle>
        <p class="sub">Join your pharmacy's platform</p>
    </x-slot:subtitle>

    <div class="alert alert-error" id="register-error" style="display: none;"></div>

    <form id="register-form" novalidate>
        <div class="field">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
            <div class="field-error" id="name-error"></div>
        </div>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <div class="field-error" id="username-error"></div>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <div class="field-error" id="email-error"></div>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="field-error" id="password-error"></div>
        </div>
        <div class="field">
            <label for="password-confirmation">Confirm Password</label>
            <input type="password" id="password-confirmation" name="password_confirmation" required>
        </div>
        <div class="field">
            <label for="pharmacy-code">Pharmacy Code</label>
            <input type="text" id="pharmacy-code" name="pharmacy_code" required>
            <div class="field-error" id="pharmacy-code-error"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="register-submit">Create Account</button>
    </form>

    <div class="guest-links">
        <a href="/customer/login">Already have an account? Login</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/register.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>