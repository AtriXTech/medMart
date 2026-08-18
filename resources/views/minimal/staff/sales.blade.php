<x-layouts.staff title="Sales" active="sales">
    <div class="alert alert-error" id="sales-error" style="display: none;"></div>

    <div id="sales-loading" class="loading-state">Loading sales...</div>

    <div id="sales-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div class="field" style="margin: 0; max-width: 300px;">
                <label for="date-filter">Filter by Date</label>
                <input type="date" id="date-filter">
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Items</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sales-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <div id="sale-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title">Sale Details</h3>
                <button type="button" class="close-btn" id="close-sale-modal-btn">&times;</button>
            </div>
            <div id="sale-details"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/sales.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>