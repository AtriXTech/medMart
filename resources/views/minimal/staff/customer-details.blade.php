<x-layouts.staff title="Customer Details" active="customers">
    <div class="alert alert-error" id="customer-error" style="display: none;"></div>

    <div id="customer-loading" class="loading-state">Loading customer details...</div>

    <div id="customer-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Customer Information</p>
                <a href="/staff/customers" class="btn btn-secondary">Back to Customers</a>
            </div>
            <div id="customer-info"></div>
        </div>

        <div class="card">
            <p class="section-title">Order History</p>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body"></tbody>
            </table>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customer-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>