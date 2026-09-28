/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadCustomers() (same endpoint/params, same
    data.meta.last_page pagination), the 500ms search debounce,
    window.viewCustomer, renderCustomers(), renderPagination(), and
    every existing element ID.
  - CHANGED: window.suspendCustomer AND window.unsuspendCustomer —
    neither calls native confirm() anymore. Both open the same
    #confirm-modal and only run their actual PATCH call (same two
    endpoints, same error handling/alert() on failure — untouched) if
    the modal's confirm button is clicked.
  - NEW: confirm modal DOM refs + showConfirmModal()/closeConfirmModal().
    showConfirmModal() now takes a `kind` ('danger' or 'success') and
    swaps the modal's icon + confirm-button colors accordingly — Suspend
    uses the existing red/danger tokens (#9C3A32/#FDEDEC), Unsuspend
    uses the app's existing success tokens (#1F7A44/#E9F8EF, the same
    pair used for the "Active" status badge) since restoring access
    isn't a destructive action and a red modal would misrepresent it.
    This is a small addition beyond a literal copy of the Suspend
    modal — flagging it since you only asked to reuse the modal, not
    add a second visual variant.
*/

const customersError = document.getElementById("customers-error");
const customersLoading = document.getElementById("customers-loading");
const customersContent = document.getElementById("customers-content");
const customersTableBody = document.getElementById("customers-table-body");
const searchInput = document.getElementById("customer-search");
const paginationContainer = document.getElementById("pagination-container");

// NEW — confirm modal elements (used only by the Suspend action)
const confirmModal = document.getElementById("confirm-modal");
const confirmModalTitle = document.getElementById("confirm-modal-title");
const confirmModalMessage = document.getElementById("confirm-modal-message");
const confirmModalIcon = document.getElementById("confirm-modal-icon");
const confirmModalCancelBtn = document.getElementById("confirm-modal-cancel-btn");
const confirmModalConfirmBtn = document.getElementById("confirm-modal-confirm-btn");

let currentPage = 1;
let totalPages = 1;
let searchTimeout = null;

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function badgeForStatus(isSuspended) {
    if (isSuspended) {
        return '<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#FDEDEC;color:#9C3A32">Suspended</span>';
    }
    return '<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#E9F8EF;color:#1F7A44">Active</span>';
}

function renderCustomers(customers) {
    if (!customers || customers.length === 0) {
        customersTableBody.innerHTML =
            '<tr><td colspan="7" class="text-center py-14"><i class="ph-light ph-users text-3xl text-[#171E26]/20 block mb-2"></i><p class="font-inter text-[13px] text-[#171E26]/45">No customers found</p></td></tr>';
        return;
    }

    customersTableBody.innerHTML = customers.map(function (link) {
        const customer = link.customer || {};
        return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
      <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${customer.name || "N/A"}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${customer.phone || "N/A"}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${customer.username || "N/A"}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/45">${link.id || "N/A"}</td>
      <td class="py-3 pr-4">${badgeForStatus(link.is_suspended)}</td>
      <td class="py-3 pr-4 font-inter text-[12px] text-[#171E26]/45">${formatDate(link.linked_at)}</td>
      <td class="py-3 text-right whitespace-nowrap">
        <button onclick="viewCustomer(${link.id})" class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD]">View</button>
        ${
            link.is_suspended
                ? `<button onclick="unsuspendCustomer(${link.id})" class="px-3 py-1.5 rounded-lg font-inter text-[12px] font-semibold hover:bg-[#E9F8EF] ml-1.5" style="color:#1F7A44">Unsuspend</button>`
                : `<button onclick="suspendCustomer(${link.id})" class="px-3 py-1.5 rounded-lg font-inter text-[12px] font-semibold text-[#9C3A32] hover:bg-[#FDEDEC] ml-1.5">Suspend</button>`
        }
      </td>
    </tr>`;
    }).join('');
}

function renderPagination() {
    paginationContainer.innerHTML = '';

    if (totalPages <= 1) return;

    const prevBtn = document.createElement("button");
    prevBtn.className = 'h-9 px-3.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent';
    prevBtn.textContent = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = function () {
        loadCustomers(currentPage - 1);
    };
    paginationContainer.appendChild(prevBtn);

    const pageInfo = document.createElement("span");
    pageInfo.className = 'font-inter text-[12px] text-[#171E26]/50 px-2';
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    paginationContainer.appendChild(pageInfo);

    const nextBtn = document.createElement("button");
    nextBtn.className = 'h-9 px-3.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent';
    nextBtn.textContent = "Next";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = function () {
        loadCustomers(currentPage + 1);
    };
    paginationContainer.appendChild(nextBtn);
}

async function loadCustomers(page = 1) {
    if (!Auth.requireAuth()) return;

    currentPage = page;
    customersLoading.style.display = "block";
    customersContent.style.display = "none";
    customersError.style.display = "none";

    const params = new URLSearchParams();
    params.append("page", currentPage);
    params.append("per_page", 20);

    if (searchInput.value.trim()) {
        params.append("search", searchInput.value.trim());
    }

    try {
        const data = await Api.get(`/staff/customers?${params.toString()}`);
        renderCustomers(data.data);
        totalPages = data.meta ? data.meta.last_page : 1;
        renderPagination();
        customersLoading.style.display = "none";
        customersContent.style.display = "block";
    } catch (error) {
        customersLoading.style.display = "none";
        customersError.textContent =
            error.message || "Unable to load customers.";
        customersError.style.display = "block";
    }
}

window.viewCustomer = function (id) {
    window.location.href = `/staff/customer-details?id=${id}`;
};

/* ---------------- NEW: generic confirm modal (Suspend only) ---------------- */

let confirmModalAction = null;

function showConfirmModal({ title, message, confirmText, kind }) {
    confirmModalTitle.textContent = title;
    confirmModalMessage.textContent = message;
    confirmModalConfirmBtn.textContent = confirmText;

    if (kind === 'success') {
        confirmModalIcon.className = 'mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-[#E9F8EF]';
        confirmModalIcon.innerHTML = '<i class="ph-light ph-check-circle text-2xl text-[#1F7A44]"></i>';
        confirmModalConfirmBtn.className = 'flex-1 px-4 py-2.5 rounded-xl border border-[#1F7A44]/25 font-inter font-semibold text-[13px] text-[#1F7A44] hover:bg-[#E9F8EF]';
    } else {
        confirmModalIcon.className = 'mx-auto mb-4 h-14 w-14 rounded-full flex items-center justify-center bg-[#FDEDEC]';
        confirmModalIcon.innerHTML = '<i class="ph-light ph-warning text-2xl text-[#9C3A32]"></i>';
        confirmModalConfirmBtn.className = 'flex-1 px-4 py-2.5 rounded-xl border border-[#F5C9C4] font-inter font-semibold text-[13px] text-[#9C3A32] hover:bg-[#FDEDEC]';
    }

    confirmModal.style.display = 'flex';
}

function closeConfirmModal() {
    confirmModal.style.display = 'none';
    confirmModalAction = null;
}

confirmModalCancelBtn.addEventListener('click', closeConfirmModal);
confirmModal.addEventListener('click', function (event) {
    if (event.target === confirmModal) closeConfirmModal();
});
confirmModalConfirmBtn.addEventListener('click', function () {
    const action = confirmModalAction;
    closeConfirmModal();
    if (action) action();
});

/* CHANGED: now opens the custom confirm modal instead of native confirm() */
window.suspendCustomer = function (id) {
    confirmModalAction = async function () {
        try {
            await Api.patch(`/staff/customers/${id}/suspend`);
            loadCustomers();
        } catch (error) {
            alert(error.message || "Unable to suspend customer.");
        }
    };

    showConfirmModal({
        title: 'Suspend customer?',
        message: 'Are you sure you want to suspend this customer?',
        confirmText: 'Suspend',
        kind: 'danger',
    });
};

/* CHANGED: now opens the custom confirm modal (success/green variant)
   instead of native confirm() */
window.unsuspendCustomer = function (id) {
    confirmModalAction = async function () {
        try {
            await Api.patch(`/staff/customers/${id}/unsuspend`);
            loadCustomers();
        } catch (error) {
            alert(error.message || "Unable to unsuspend customer.");
        }
    };

    showConfirmModal({
        title: 'Unsuspend customer?',
        message: 'Are you sure you want to unsuspend this customer?',
        confirmText: 'Unsuspend',
        kind: 'success',
    });
};

searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
        loadCustomers(1);
    }, 500);
});

loadCustomers();