<x-layouts.customer title="Cart" active="cart">
    <div class="alert alert-error" id="cart-error" style="display: none;"></div>
    <div id="cart-loading" class="loading-state">Loading cart...</div>
    <div id="cart-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/cart.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>