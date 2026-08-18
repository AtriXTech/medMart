<x-layouts.customer-guest title="Customer Login">
    <x-slot:subtitle>
        <p class="sub">Sign in to your account</p>
    </x-slot:subtitle>

    <div class="alert alert-error" id="login-error" style="display: none;"></div>

    <form id="login-form" novalidate>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="username" required>
            <div class="field-error" id="email-error"></div>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
            <div class="field-error" id="password-error"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="login-submit">Sign In</button>
    </form>

    <div class="guest-links">
        <a href="/customer/forgot-password">Forgot your password?</a>
        <span style="margin: 0 8px;">|</span>
        <a href="/customer/register">Create account</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/login.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>