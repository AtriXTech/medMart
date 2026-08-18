<x-layouts.staff title="Product Details" active="products">
    <div class="alert alert-error" id="product-error" style="display: none;"></div>

    <div id="product-loading" class="loading-state">Loading product details...</div>

    <div id="product-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Product Information</p>
                <a href="/staff/products" class="btn btn-secondary">Back to Products</a>
            </div>
            <div id="product-info"></div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Batches</p>
                <button class="btn btn-primary" id="add-batch-btn">Add Batch</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Cost Price</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="batches-table-body"></tbody>
            </table>
        </div>

        <div class="card">
            <p class="section-title">Stock Movements</p>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Staff</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="movements-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="batch-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Batch</h3>
                <button type="button" class="close-btn" id="close-batch-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="batch-form-error" style="display: none;"></div>
            <form id="batch-form">
                <div class="field">
                    <label for="batch-number">Batch Number</label>
                    <input type="text" id="batch-number" required>
                </div>
                <div class="field">
                    <label for="batch-expiry">Expiry Date</label>
                    <input type="date" id="batch-expiry" required>
                </div>
                <div class="field">
                    <label for="batch-quantity">Quantity</label>
                    <input type="number" id="batch-quantity" min="1" required>
                </div>
                <div class="field">
                    <label for="batch-cost">Cost Price</label>
                    <input type="number" id="batch-cost" step="0.01" min="0" required>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-batch-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="batch-submit-btn">Add Batch</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/product-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>