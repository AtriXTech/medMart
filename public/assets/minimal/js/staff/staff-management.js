const staffError = document.getElementById("staff-error");
const staffLoading = document.getElementById("staff-loading");
const staffContent = document.getElementById("staff-content");
const staffTableBody = document.getElementById("staff-table-body");
const createStaffBtn = document.getElementById("create-staff-btn");
const staffModal = document.getElementById("staff-modal");
const staffForm = document.getElementById("staff-form");
const staffFormTitle = document.getElementById("staff-form-title");
const staffNameInput = document.getElementById('staff-name');
const staffEmailInput = document.getElementById('staff-email');
const staffPhoneInput = document.getElementById('staff-phone');
const staffPasswordInput = document.getElementById('staff-password');
const staffRoleSelect = document.getElementById('staff-role');
const staffIdInput = document.getElementById('staff-id');
const staffFormError = document.getElementById("staff-form-error");
const staffSubmitBtn = document.getElementById("staff-submit-btn");
const closeStaffModalBtn = document.getElementById("close-staff-modal-btn");
const cancelStaffModalBtn = document.getElementById("cancel-staff-modal-btn");

const rolesError = document.getElementById("roles-error");
const rolesLoading = document.getElementById("roles-loading");
const rolesContent = document.getElementById("roles-content");
const rolesTableBody = document.getElementById("roles-table-body");
const createRoleBtn = document.getElementById("create-role-btn");
const roleModal = document.getElementById("role-modal");
const roleForm = document.getElementById("role-form");
const roleFormTitle = document.getElementById("role-form-title");
const roleNameInput = document.getElementById("role-name");
const roleDescriptionInput = document.getElementById("role-description");
const rolePermissionsContainer = document.getElementById("role-permissions");
const roleIdInput = document.getElementById("role-id");
const roleFormError = document.getElementById("role-form-error");
const roleSubmitBtn = document.getElementById("role-submit-btn");
const closeRoleModalBtn = document.getElementById("close-role-modal-btn");
const cancelRoleModalBtn = document.getElementById("cancel-role-modal-btn");

const permissions = [
    'view_dashboard',
    'manage_products',
    'manage_categories',
    'manage_suppliers',
    'manage_purchase_orders',
    'process_sales',
    'view_sales',
    'manage_orders',
    'manage_prescriptions',
    'manage_customers',
    'manage_staff',
    'manage_roles',
    'manage_subscription',
    'manage_settlement',
    'generate_pharmacy_codes',
    'manage_pharmacy_settings',
];

function closeStaffModal() {
    staffModal.style.display = "none";
}

function openRoleModal(title, role = null) {
    roleFormTitle.textContent = title;
    roleFormError.style.display = "none";
    roleFormError.textContent = "";

    if (role) {
        roleIdInput.value = role.id;
        roleNameInput.value = role.name;
        roleDescriptionInput.value = role.description || "";
        roleSubmitBtn.textContent = "Update Role";

        const selectedPermissions = role.permissions || [];
        document
            .querySelectorAll(".permission-checkbox")
            .forEach(function (checkbox) {
                checkbox.checked = selectedPermissions.includes(checkbox.value);
            });
    } else {
        roleIdInput.value = "";
        roleNameInput.value = "";
        roleDescriptionInput.value = "";
        roleSubmitBtn.textContent = "Create Role";

        document
            .querySelectorAll(".permission-checkbox")
            .forEach(function (checkbox) {
                checkbox.checked = false;
            });
    }

    roleModal.style.display = "flex";
}

function closeRoleModal() {
    roleModal.style.display = "none";
}

function renderPermissionCheckboxes() {
    rolePermissionsContainer.innerHTML = "";

    permissions.forEach(function (permission) {
        const label = document.createElement("label");
        label.style.cssText =
            "display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer;";
        label.innerHTML = `
            <input type="checkbox" class="permission-checkbox" value="${permission}">
            <span>${permission.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase())}</span>
        `;
        rolePermissionsContainer.appendChild(label);
    });
}

function renderStaff(staff) {
    staffTableBody.innerHTML = "";

    if (!staff || staff.length === 0) {
        staffTableBody.innerHTML =
            '<tr><td colspan="5" class="empty-state">No staff found</td></tr>';
        return;
    }

    staff.forEach(function (member) {
        const tr = document.createElement("tr");
        const roleDisplay = member.staffRole
            ? member.staffRole.name
            : member.role || "N/A";
        const statusDisplay =
            member.status === "active" ? "Active" : "Inactive";
        const statusClass =
            member.status === "active" ? "badge-success" : "badge-danger";

        tr.innerHTML = `
            <td>${member.name}</td>
            <td>${member.email}</td>
            <td>${roleDisplay}</td>
            <td>
                <span class="badge ${statusClass}">
                    ${statusDisplay}
                </span>
            </td>
            <td>
                <button class="btn btn-secondary" onclick='editStaff(${JSON.stringify(member)})'>Edit</button>
                <button class="btn btn-danger" onclick="deactivateStaff(${member.id})">Deactivate</button>
            </td>
        `;
        staffTableBody.appendChild(tr);
    });
}

function renderRoles(roles) {
    rolesTableBody.innerHTML = "";

    if (!roles || roles.length === 0) {
        rolesTableBody.innerHTML =
            '<tr><td colspan="4" class="empty-state">No roles found</td></tr>';
        return;
    }

    roles.forEach(function (role) {
        const permissionCount = role.permissions ? role.permissions.length : 0;
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${role.name}</td>
            <td>${role.description || "N/A"}</td>
            <td>${permissionCount} permissions</td>
            <td>
                <button class="btn btn-secondary" onclick='editRole(${JSON.stringify(role)})'>Edit</button>
                ${!role.is_system ? `<button class="btn btn-danger" onclick="deleteRole(${role.id})">Delete</button>` : ""}
            </td>
        `;
        rolesTableBody.appendChild(tr);
    });
}

async function loadStaff() {
    if (!Auth.requireAuth()) return;

    staffLoading.style.display = "block";
    staffContent.style.display = "none";
    staffError.style.display = "none";

    try {
        const data = await Api.get("/staff/staff");
        renderStaff(data.data || data);
        staffLoading.style.display = "none";
        staffContent.style.display = "block";
    } catch (error) {
        staffLoading.style.display = "none";
        staffError.textContent = error.message || "Unable to load staff.";
        staffError.style.display = "block";
    }
}

async function loadRoles() {
    if (!Auth.requireAuth()) return;

    rolesLoading.style.display = "block";
    rolesContent.style.display = "none";
    rolesError.style.display = "none";

    try {
        const data = await Api.get("/staff/roles?per_page=100");
        renderRoles(data.data || data);
        rolesLoading.style.display = "none";
        rolesContent.style.display = "block";
    } catch (error) {
        rolesLoading.style.display = "none";
        rolesError.textContent = error.message || "Unable to load roles.";
        rolesError.style.display = "block";
    }
}

function openStaffModal(title, staff = null) {
    staffFormTitle.textContent = title;
    staffFormError.style.display = "none";
    staffFormError.textContent = "";

    if (staff) {
        staffIdInput.value = staff.id;
        staffNameInput.value = staff.name;
        staffEmailInput.value = staff.email;
        staffPhoneInput.value = staff.phone || "";
        staffPasswordInput.value = "";
        staffPasswordInput.placeholder = "Leave blank to keep current password";
        staffRoleSelect.value = staff.staff_role_id || staff.role || "";
        staffSubmitBtn.textContent = "Update Staff";
    } else {
        staffIdInput.value = "";
        staffNameInput.value = "";
        staffEmailInput.value = "";
        staffPhoneInput.value = "";
        staffPasswordInput.value = "";
        staffPasswordInput.placeholder = "Enter password";
        staffRoleSelect.value = "";
        staffSubmitBtn.textContent = "Create Staff";
    }

    staffModal.style.display = "flex";
}

async function loadRolesForDropdown() {
    try {
        const data = await Api.get("/staff/roles?per_page=100");
        const roles = data.data || data;

        staffRoleSelect.innerHTML = '<option value="">Select Role</option>';

        roles.forEach(function (role) {
            const option = document.createElement("option");
            option.value = role.id;
            option.textContent =
                role.name + (role.is_system ? " (System)" : " (Custom)");
            staffRoleSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Unable to load roles:", error);
    }
}

window.editStaff = function (staff) {
    openStaffModal("Edit Staff", staff);
};

window.deactivateStaff = async function (id) {
    if (!confirm("Are you sure you want to deactivate this staff member?"))
        return;

    try {
        await Api.patch(`/staff/staff/${id}/deactivate`);
        loadStaff();
    } catch (error) {
        alert(error.message || "Unable to deactivate staff.");
    }
};

window.editRole = function (role) {
    openRoleModal("Edit Role", role);
};

window.deleteRole = async function (id) {
    if (!confirm("Are you sure you want to delete this role?")) return;

    try {
        await Api.delete(`/staff/roles/${id}`);
        loadRoles();
    } catch (error) {
        alert(error.message || "Unable to delete role.");
    }
};

createStaffBtn.addEventListener("click", function () {
    openStaffModal("Create Staff");
});

closeStaffModalBtn.addEventListener("click", closeStaffModal);
cancelStaffModalBtn.addEventListener("click", closeStaffModal);

staffModal.addEventListener("click", function (event) {
    if (event.target === staffModal) {
        closeStaffModal();
    }
});

staffForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    staffSubmitBtn.disabled = true;
    staffFormError.style.display = "none";

    const formData = {
        name: staffNameInput.value.trim(),
        email: staffEmailInput.value.trim(),
        phone: staffPhoneInput.value.trim(),
        staff_role_id: staffRoleSelect.value
            ? parseInt(staffRoleSelect.value)
            : null,
    };

    if (staffPasswordInput.value) {
        formData.password = staffPasswordInput.value;
    }

    try {
        const staffId = staffIdInput.value;
        if (staffId) {
            await Api.patch(`/staff/staff/${staffId}`, formData);
        } else {
            await Api.post("/staff/staff", formData);
        }
        closeStaffModal();
        loadStaff();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function (key) {
                messages.push(...error.data.errors[key]);
            });
            staffFormError.textContent = messages.join(", ");
        } else {
            staffFormError.textContent =
                error.message || "Unable to save staff member.";
        }
        staffFormError.style.display = "block";
    } finally {
        staffSubmitBtn.disabled = false;
    }
});

createRoleBtn.addEventListener("click", function () {
    openRoleModal("Create Role");
});

closeRoleModalBtn.addEventListener("click", closeRoleModal);
cancelRoleModalBtn.addEventListener("click", closeRoleModal);

roleModal.addEventListener("click", function (event) {
    if (event.target === roleModal) {
        closeRoleModal();
    }
});

roleForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    roleSubmitBtn.disabled = true;
    roleFormError.style.display = "none";

    const selectedPermissions = [];
    document
        .querySelectorAll(".permission-checkbox:checked")
        .forEach(function (checkbox) {
            selectedPermissions.push(checkbox.value);
        });

    const formData = {
        name: roleNameInput.value.trim(),
        description: roleDescriptionInput.value.trim(),
        permissions: selectedPermissions,
    };

    try {
        const roleId = roleIdInput.value;
        if (roleId) {
            await Api.patch(`/staff/roles/${roleId}`, formData);
        } else {
            await Api.post("/staff/roles", formData);
        }
        closeRoleModal();
        loadRoles();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            const messages = [];
            Object.keys(error.data.errors).forEach(function (key) {
                messages.push(...error.data.errors[key]);
            });
            roleFormError.textContent = messages.join(", ");
        } else {
            roleFormError.textContent = error.message || "Unable to save role.";
        }
        roleFormError.style.display = "block";
    } finally {
        roleSubmitBtn.disabled = false;
    }
});

renderPermissionCheckboxes();
loadStaff();
loadRoles();
loadRolesForDropdown();
