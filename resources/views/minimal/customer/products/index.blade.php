<x-layouts.customer title="Products" active="products">
    <div style="margin-bottom: 16px;">
        <div class="field" style="margin: 0;">
            <input type="text" id="product-search" placeholder="Search products...">
        </div>
    </div>

    <div class="alert alert-error" id="products-error" style="display: none;"></div>
    <div id="products-loading" class="loading-state">Loading products...</div>
    <div id="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/products.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>