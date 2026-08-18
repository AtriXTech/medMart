<x-layouts.customer title="Product Details" active="products">
    <div class="alert alert-error" id="product-error" style="display: none;"></div>
    <div id="product-loading" class="loading-state">Loading product...</div>
    <div id="product-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/product-detail.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>