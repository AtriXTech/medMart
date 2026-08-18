<x-layouts.customer-guest title="Email Verification">
    <x-slot:subtitle>
        <p class="sub">Verifying your email address</p>
    </x-slot:subtitle>

    <div id="verification-loading" style="text-align: center;">
        <p>Please wait while we verify your email...</p>
    </div>

    <div class="alert alert-error" id="verification-error" style="display: none;"></div>
    <div class="alert alert-success" id="verification-success" style="display: none;"></div>

    <div id="verification-success-message" style="display: none; text-align: center;">
        <p>Your email has been verified successfully!</p>
        <a href="/customer/login" class="btn btn-primary">Login to Continue</a>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/verify-email.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer-guest>