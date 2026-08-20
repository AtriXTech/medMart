<x-layouts.staff title="Dashboard" active="dashboard">
    <div class="alert alert-error" id="dashboard-error" style="display: none;"></div>

    <div id="dashboard-loading" class="loading-state">Loading dashboard... </div>

    <div id="dashboard-content" style="display: none;">
        <div class="stat-grid" id="stat-grid"></div>

        <div class="card">
            <p class="section-title">Orders by Status</p>
            <table id="status-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody id="status-table-body"></tbody>
            </table>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/dashboard.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>