<x-layouts.customer title="Checkout" active="cart">
    <div class="alert alert-error" id="checkout-error" style="display: none;"></div>
    <div id="checkout-loading" class="loading-state">Loading checkout...</div>
    <div id="checkout-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/checkout.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>