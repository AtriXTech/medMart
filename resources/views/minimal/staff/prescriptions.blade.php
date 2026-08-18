<x-layouts.staff title="Prescriptions" active="prescriptions">
    <div class="alert alert-error" id="prescriptions-error" style="display: none;"></div>

    <div id="prescriptions-loading" class="loading-state">Loading prescriptions...</div>

    <div id="prescriptions-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div class="field" style="margin: 0; max-width: 300px;">
                <label for="status-filter">Filter by Status</label>
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="fulfilled">Fulfilled</option>
                </select>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="prescriptions-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/prescriptions.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>