<x-layouts.staff title="Products" active="products">
    <div class="alert alert-error" id="products-error" style="display: none;"></div>

    <div id="products-loading" class="loading-state">Loading products...</div>

    <div id="products-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: end;">
                <div class="field" style="margin: 0; flex: 1;">
                    <label for="product-search">Search</label>
                    <input type="text" id="product-search" placeholder="Search products...">
                </div>
                <div class="field" style="margin: 0;">
                    <label for="category-filter">Category</label>
                    <select id="category-filter">
                        <option value="">All Categories</option>
                    </select>
                </div>
                <div class="field" style="margin: 0;">
                    <label for="availability-filter">Availability</label>
                    <select id="availability-filter">
                        <option value="">All</option>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
                <div style="margin: 0;">
                    <button class="btn btn-primary" id="create-product-btn">Create Product</button>
                </div>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="products-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <div id="product-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title" id="product-form-title">Create Product</h3>
                <button type="button" class="close-btn" id="close-product-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="product-form-error" style="display: none;"></div>
            <form id="product-form">
                <input type="hidden" id="product-id">
                <div class="field">
                    <label for="product-name">Name</label>
                    <input type="text" id="product-name" required>
                </div>
                <div class="field">
                    <label for="product-generic-name">Generic Name</label>
                    <input type="text" id="product-generic-name">
                </div>
                <div class="field">
                    <label for="product-category">Category</label>
                    <select id="product-category">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div class="field">
                    <label for="product-price">Price</label>
                    <input type="number" id="product-price" step="0.01" min="0" required>
                </div>
                <div class="field">
                    <label for="product-reorder-level">Reorder Level</label>
                    <input type="number" id="product-reorder-level" min="0" value="0">
                </div>
                <div class="field">
                    <label for="product-barcode">Barcode</label>
                    <input type="text" id="product-barcode">
                </div>
                <div class="field">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" rows="3"></textarea>
                </div>
                <div class="field">
                    <label>
                        <input type="checkbox" id="product-requires-prescription">
                        Requires Prescription
                    </label>
                </div>
                <div class="field">
                    <label for="product-image">Product Image</label>
                    <input type="file" id="product-image" accept="image/*">
                    <img id="product-image-preview" style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px; border-radius: 4px;">
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-product-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="product-submit-btn">Create Product</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/products.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>