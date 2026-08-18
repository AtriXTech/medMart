<x-layouts.guest title="Forgot Password">
    <x-slot:subtitle>
        <p class="sub">Enter your email to receive a password reset link</p>
    </x-slot:subtitle>

    <div class="alert alert-error" id="forgot-error" style="display: none;"></div>
    <div class="alert alert-success" id="forgot-success" style="display: none;"></div>

    <form id="forgot-form" novalidate>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="username" required>
            <div class="field-error" id="email-error"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="forgot-submit">Send Reset Link</button>
    </form>

    <div class="guest-links">
        <a href="/staff/login">Back to login</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/forgot-password.js') }}"></script>
    </x-slot:scripts>
</x-layouts.guest>