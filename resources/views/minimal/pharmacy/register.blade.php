<x-layouts.guest title="Pharmacy Registration">
    <x-slot:subtitle>
        <p class="sub">Create your pharmacy account</p>
    </x-slot:subtitle>

    <div class="alert alert-error" id="register-error" style="display: none;"></div>

    <form id="register-form" novalidate>
        <div class="field">
            <label for="pharmacy-name">Pharmacy Name</label>
            <input type="text" id="pharmacy-name" name="pharmacy_name" required>
            <div class="field-error" id="pharmacy-name-error"></div>
        </div>
        <div class="field">
            <label for="owner-name">Owner Name</label>
            <input type="text" id="owner-name" name="owner_name" required>
            <div class="field-error" id="owner-name-error"></div>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <div class="field-error" id="email-error"></div>
        </div>
        <div class="field">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" required>
            <div class="field-error" id="phone-error"></div>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="field-error" id="password-error"></div>
        </div>
        <div class="field">
            <label for="password-confirmation">Confirm Password</label>
            <input type="password" id="password-confirmation" name="password_confirmation" required>
            <div class="field-error" id="password-confirmation-error"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="register-submit">Create Account</button>
    </form>

    <div class="guest-links">
        <a href="/staff/login">Already have an account? Login</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/pharmacy/register.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>