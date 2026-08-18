<x-layouts.staff title="Pharmacy Codes" active="pharmacy-codes">
    <div class="alert alert-error" id="pharmacy-codes-error" style="display: none;"></div>

    <div id="pharmacy-codes-loading" class="loading-state">Loading pharmacy codes...</div>

    <div id="pharmacy-codes-content" style="display: none;">
        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" id="create-code-btn">Generate Code</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Uses</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="pharmacy-codes-table-body"></tbody>
            </table>
            <div id="pagination-container" style="margin-top: 16px; display: flex; align-items: center; justify-content: center;"></div>
        </div>
    </div>

    <div id="code-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="code-form-title">Generate Pharmacy Code</h3>
                <button type="button" class="close-btn" id="close-code-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="code-form-error" style="display: none;"></div>
            <form id="code-form">
                <div class="field">
                    <label for="code">Custom Code (Optional)</label>
                    <input type="text" id="code" placeholder="Leave blank for auto-generated code">
                </div>
                <div class="field">
                    <label for="code-expires-at">Expires At (Optional)</label>
                    <input type="datetime-local" id="code-expires-at">
                </div>
                <div class="field">
                    <label for="code-max-uses">Max Uses (Optional)</label>
                    <input type="number" id="code-max-uses" min="1" placeholder="Leave blank for unlimited uses">
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-code-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="code-submit-btn">Generate Code</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/pharmacy-codes.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>