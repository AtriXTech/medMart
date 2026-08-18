<x-layouts.staff title="Order Details" active="orders">
    <div class="alert alert-error" id="order-error" style="display: none;"></div>

    <div id="order-loading" class="loading-state">Loading order details...</div>

    <div id="order-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Order Information</p>
                <a href="/staff/orders" class="btn btn-secondary">Back to Orders</a>
            </div>
            <div id="order-info"></div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <p class="section-title">Order Items</p>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="order-items-table"></tbody>
            </table>
        </div>

        <div class="card">
            <p class="section-title">Update Status</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div class="field">
                    <label for="status-select">Order Status</label>
                    <select id="status-select">
                        <option value="processing">Processing</option>
                        <option value="ready_for_pickup">Ready for Pickup</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="field">
                    <label for="status-reason">Reason (for cancellation)</label>
                    <input type="text" id="status-reason">
                </div>
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-primary" id="update-status-btn">Update Status</button>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 16px;">
                <div class="field">
                    <label for="delivery-status-select">Delivery Status</label>
                    <select id="delivery-status-select">
                        <option value="">Select Delivery Status</option>
                        <option value="pending">Pending</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div style="display: flex; align-items: end;">
                    <button class="btn btn-secondary" id="update-delivery-btn">Update Delivery Status</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>