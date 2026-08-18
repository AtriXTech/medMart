<x-layouts.staff title="Purchase Orders" active="purchase-orders">
    <div class="alert alert-error" id="purchase-orders-error" style="display: none;"></div>

    <div id="purchase-orders-loading" class="loading-state">Loading purchase orders...</div>

    <div id="purchase-orders-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 12px; align-items: end;">
                <div class="field" style="margin: 0; max-width: 300px;">
                    <label for="status-filter">Filter by Status</label>
                    <select id="status-filter">
                        <option value="">All Statuses</option>
                        <option value="ordered">Ordered</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <button class="btn btn-primary" id="create-po-btn">Create Purchase Order</button>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>PO ID</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Expected Date</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="purchase-orders-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>