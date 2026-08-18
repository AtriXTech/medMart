<x-layouts.customer title="My Orders" active="orders">
    <div class="alert alert-error" id="orders-error" style="display: none;"></div>
    <div id="orders-loading" class="loading-state">Loading orders...</div>
    <div id="orders-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>