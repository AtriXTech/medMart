<x-layouts.staff title="Purchase Order Details" active="purchase-orders">
    <div class="alert alert-error" id="po-error" style="display: none;"></div>

    <div id="po-loading" class="loading-state">Loading purchase order...</div>

    <div id="po-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Purchase Order Information</p>
                <div style="display: flex; gap: 8px;">
                    <a href="/staff/purchase-orders" class="btn btn-secondary">Back</a>
                    <button class="btn btn-success" id="receive-btn" style="display: none;">Receive Items</button>
                    <button class="btn btn-danger" id="cancel-btn" style="display: none;">Cancel Order</button>
                </div>
            </div>
            <div id="po-info"></div>
        </div>

        <div class="card">
            <p class="section-title">Order Items</p>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Ordered</th>
                        <th>Received</th>
                        <th>Cost Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="po-items-table"></tbody>
            </table>
        </div>
    </div>

    <div id="receive-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">Receive Items</h3>
                <button type="button" class="close-btn" id="close-receive-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="receive-error" style="display: none;"></div>
            <form id="receive-form">
                <div id="receive-items"></div>
                <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 16px;">
                    <button type="button" class="btn btn-secondary" id="cancel-receive-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="receive-submit-btn">Receive Items</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/purchase-order-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>