const customersError = document.getElementById("customers-error");
const customersLoading = document.getElementById("customers-loading");
const customersContent = document.getElementById("customers-content");
const customersTableBody = document.getElementById("customers-table-body");
const searchInput = document.getElementById("customer-search");
const paginationContainer = document.getElementById("pagination-container");

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
        return '<span class="badge badge-danger">Suspended</span>';
    }
    return '<span class="badge badge-success">Active</span>';
}

function renderCustomers(customers) {
    customersTableBody.innerHTML = "";

    if (!customers || customers.length === 0) {
        customersTableBody.innerHTML =
            '<tr><td colspan="7" class="empty-state">No customers found</td></tr>';
        return;
    }

    customers.forEach(function (link) {
        const customer = link.customer || {};
        const tr = document.createElement("tr");
        tr.innerHTML = `
      <td>${customer.name || "N/A"}</td>
      <td>${customer.email || "N/A"}</td>
      <td>${customer.username || "N/A"}</td>
      <td>${link.id || "N/A"}</td>
      <td>${badgeForStatus(link.is_suspended)}</td>
      <td>${formatDate(link.linked_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewCustomer(${link.id})">View</button>
        ${
            link.is_suspended
                ? `<button class="btn btn-success" onclick="unsuspendCustomer(${link.id})">Unsuspend</button>`
                : `<button class="btn btn-danger" onclick="suspendCustomer(${link.id})">Suspend</button>`
        }
      </td>
    `;
        customersTableBody.appendChild(tr);
    });
}

function renderPagination() {
    paginationContainer.innerHTML = "";

    if (totalPages <= 1) return;

    const prevBtn = document.createElement("button");
    prevBtn.className = "btn btn-secondary";
    prevBtn.textContent = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = function () {
        loadCustomers(currentPage - 1);
    };
    paginationContainer.appendChild(prevBtn);

    const pageInfo = document.createElement("span");
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    pageInfo.style.margin = "0 12px";
    paginationContainer.appendChild(pageInfo);

    const nextBtn = document.createElement("button");
    nextBtn.className = "btn btn-secondary";
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

window.suspendCustomer = async function (id) {
    if (!confirm("Are you sure you want to suspend this customer?")) return;

    try {
        await Api.patch(`/staff/customers/${id}/suspend`);
        loadCustomers();
    } catch (error) {
        alert(error.message || "Unable to suspend customer.");
    }
};

window.unsuspendCustomer = async function (id) {
    if (!confirm("Are you sure you want to unsuspend this customer?")) return;

    try {
        await Api.patch(`/staff/customers/${id}/unsuspend`);
        loadCustomers();
    } catch (error) {
        alert(error.message || "Unable to unsuspend customer.");
    }
};

searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
        loadCustomers(1);
    }, 500);
});

loadCustomers();
