<x-layouts.staff title="Customers" active="customers">
    <div class="alert alert-error" id="customers-error" style="display: none;"></div>

    <div id="customers-loading" class="loading-state">Loading customers...</div>

    <div id="customers-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div class="field" style="margin: 0;">
                <label for="customer-search">Search Customers</label>
                <input type="text" id="customer-search" placeholder="Search by name or username...">
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Link ID</th>
                        <th>Status</th>
                        <th>Linked At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="customers-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/customers.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>