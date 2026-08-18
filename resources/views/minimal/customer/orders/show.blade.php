<x-layouts.customer title="Order Details" active="orders">
    <div class="alert alert-error" id="order-error" style="display: none;"></div>
    <div id="order-loading" class="loading-state">Loading order...</div>
    <div id="order-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/order-detail.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>