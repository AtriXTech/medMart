<x-layouts.staff title="Product Categories" active="product-categories">
    <div class="alert alert-error" id="categories-error" style="display: none;"></div>

    <div id="categories-loading" class="loading-state">Loading categories...</div>

    <div id="categories-content" style="display: none;">
        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" id="create-category-btn">Create Category</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="categories-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="category-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="category-form-title">Create Category</h3>
                <button type="button" class="close-btn" id="close-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="category-form-error" style="display: none;"></div>
            <form id="category-form">
                <input type="hidden" id="category-id">
                <div class="field">
                    <label for="category-name">Name</label>
                    <input type="text" id="category-name" required>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="category-submit-btn">Create Category</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/product-categories.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>