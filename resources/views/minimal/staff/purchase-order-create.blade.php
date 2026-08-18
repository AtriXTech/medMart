<x-layouts.staff title="Create Purchase Order" active="purchase-orders">
    <div class="alert alert-error" id="po-error" style="display: none;"></div>

    <div id="po-content">
        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Order Details</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                <div class="field">
                    <label for="po-supplier">Supplier</label>
                    <select id="po-supplier" required>
                        <option value="">Select Supplier</option>
                    </select>
                </div>
                <div class="field">
                    <label for="po-expected-date">Expected Date</label>
                    <input type="date" id="po-expected-date">
                </div>
                <div class="field">
                    <label for="po-notes">Notes</label>
                    <input type="text" id="po-notes">
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Add Products</p>
            <div class="field" style="margin: 0;">
                <input type="text" id="po-product-search" placeholder="Search products...">
            </div>
            <div id="po-product-results" style="display: none; margin-top: 10px; max-height: 300px; overflow-y: auto; border: 1px solid var(--border); border-radius: var(--radius);"></div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Order Items</p>
            <div id="po-items"></div>
            <div style="margin-top: 16px; text-align: right;">
                <strong>Total: <span id="po-total">₦0</span></strong>
            </div>
        </div>

        <div style="display: flex; gap: 8px; justify-content: flex-end;">
            <a href="/staff/purchase-orders" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" id="po-submit-btn">Create Purchase Order</button>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-order-create.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>