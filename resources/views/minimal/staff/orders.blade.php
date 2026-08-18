<x-layouts.staff title="Orders" active="orders">
    <div class="alert alert-error" id="orders-error" style="display: none;"></div>

    <div id="orders-loading" class="loading-state">Loading orders...</div>

    <div id="orders-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div class="field" style="margin: 0; max-width: 300px;">
                <label for="status-filter">Filter by Status</label>
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Items</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/orders.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>