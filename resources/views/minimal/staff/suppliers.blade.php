<x-layouts.staff title="Suppliers" active="suppliers">
    <div class="alert alert-error" id="suppliers-error" style="display: none;"></div>

    <div id="suppliers-loading" class="loading-state">Loading suppliers...</div>

    <div id="suppliers-content" style="display: none;">
        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" id="create-supplier-btn">Create Supplier</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="suppliers-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="supplier-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="supplier-form-title">Create Supplier</h3>
                <button type="button" class="close-btn" id="close-supplier-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="supplier-form-error" style="display: none;"></div>
            <form id="supplier-form">
                <input type="hidden" id="supplier-id">
                <div class="field">
                    <label for="supplier-name">Name</label>
                    <input type="text" id="supplier-name" required>
                </div>
                <div class="field">
                    <label for="supplier-contact-name">Contact Name</label>
                    <input type="text" id="supplier-contact-name">
                </div>
                <div class="field">
                    <label for="supplier-email">Email</label>
                    <input type="email" id="supplier-email">
                </div>
                <div class="field">
                    <label for="supplier-phone">Phone</label>
                    <input type="text" id="supplier-phone">
                </div>
                <div class="field">
                    <label for="supplier-address">Address</label>
                    <textarea id="supplier-address" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-supplier-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="supplier-submit-btn">Create Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/suppliers.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>