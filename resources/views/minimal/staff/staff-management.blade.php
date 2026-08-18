<x-layouts.staff title="Staff Management" active="staff-management">
    <div style="display: grid; gap: 24px;">
        <div>
            <h2>Staff Members</h2>
            <div class="alert alert-error" id="staff-error" style="display: none;"></div>
            <div id="staff-loading" class="loading-state">Loading staff...</div>
            <div id="staff-content" style="display: none;">
                <div style="margin-bottom: 16px;">
                    <button class="btn btn-primary" id="create-staff-btn">Add Staff</button>
                </div>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staff-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h2>Roles & Permissions</h2>
            <div class="alert alert-error" id="roles-error" style="display: none;"></div>
            <div id="roles-loading" class="loading-state">Loading roles...</div>
            <div id="roles-content" style="display: none;">
                <div style="margin-bottom: 16px;">
                    <button class="btn btn-primary" id="create-role-btn">Create Role</button>
                </div>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="roles-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="staff-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="staff-form-title">Add Staff</h3>
                <button type="button" class="close-btn" id="close-staff-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="staff-form-error" style="display: none;"></div>
            <form id="staff-form">
                <input type="hidden" id="staff-id">
                <div class="field">
                    <label for="staff-name">Name</label>
                    <input type="text" id="staff-name" required>
                </div>
                <div class="field">
                    <label for="staff-phone">Phone</label>
                    <input type="text" id="staff-phone">
                </div>
                <div class="field">
                    <label for="staff-email">Email</label>
                    <input type="email" id="staff-email" required>
                </div>
                <div class="field">
                    <label for="staff-password">Password</label>
                    <input type="password" id="staff-password" required>
                </div>
                <div class="field">
                    <label for="staff-role">Role</label>
                    <select id="staff-role" required>
                        <option value="">Select Role</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-staff-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="staff-submit-btn">Add Staff</button>
                </div>
            </form>
        </div>
    </div>

    <div id="role-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="role-form-title">Create Role</h3>
                <button type="button" class="close-btn" id="close-role-modal-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="role-form-error" style="display: none;"></div>
            <form id="role-form">
                <input type="hidden" id="role-id">
                <div class="field">
                    <label for="role-name">Role Name</label>
                    <input type="text" id="role-name" required>
                </div>
                <div class="field">
                    <label for="role-description">Description</label>
                    <textarea id="role-description" rows="3"></textarea>
                </div>
                <div class="field">
                    <label>Permissions</label>
                    <div id="role-permissions" style="max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-role-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="role-submit-btn">Create Role</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/staff-management.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>
