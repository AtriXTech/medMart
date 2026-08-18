<x-layouts.staff title="Create Customer Account" active="customers">
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <p class="section-title">Create Customer Account</p>
        <div class="alert alert-error" id="customer-error" style="display: none;"></div>
        <form id="customer-form">
            <div class="field">
                <label for="customer-name">Full Name</label>
                <input type="text" id="customer-name" required>
                <div class="field-error" id="name-error"></div>
            </div>
            <div class="field">
                <label for="customer-email">Email</label>
                <input type="email" id="customer-email" required>
                <div class="field-error" id="email-error"></div>
            </div>
            <div class="field">
                <label for="customer-username">Username</label>
                <input type="text" id="customer-username" required>
                <div class="field-error" id="username-error"></div>
            </div>
            <div class="field">
                <label for="customer-password">Password</label>
                <input type="password" id="customer-password" required>
                <div class="field-error" id="password-error"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="customer-submit">Create Account</button>
        </form>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customer-create.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>