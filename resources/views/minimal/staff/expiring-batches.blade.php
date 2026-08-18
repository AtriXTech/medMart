<x-layouts.staff title="Expiring Batches" active="products">
    <div class="alert alert-error" id="batches-error" style="display: none;"></div>

    <div id="batches-loading" class="loading-state">Loading expiring batches...</div>

    <div id="batches-content" style="display: none;">
        <div class="card">
            <p class="section-title">Batches Expiring Within 90 Days</p>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="batches-table-body"></tbody>
            </table>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/expiring-batches.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>