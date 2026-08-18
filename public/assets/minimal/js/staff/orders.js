const ordersError = document.getElementById("orders-error");
const ordersLoading = document.getElementById("orders-loading");
const ordersContent = document.getElementById("orders-content");
const ordersTableBody = document.getElementById("orders-table-body");
const statusFilter = document.getElementById("status-filter");
const paginationContainer = document.getElementById("pagination-container");

let currentPage = 1;
let totalPages = 1;

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return "₦" + value.toLocaleString();
}

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleString();
}

function badgeForStatus(status) {
    const map = {
        pending: "badge-warning",
        processing: "badge-warning",
        shipped: "badge-warning",
        delivered: "badge-success",
        completed: "badge-success",
        cancelled: "badge-danger",
    };
    const cls = map[status] || "badge-muted";
    return `<span class="badge ${cls}">${status}</span>`;
}

function renderOrders(orders) {
    ordersTableBody.innerHTML = "";

    if (!orders || orders.length === 0) {
        ordersTableBody.innerHTML =
            '<tr><td colspan="8" class="empty-state">No orders found</td></tr>';
        return;
    }

    orders.forEach(function (order) {
        const tr = document.createElement("tr");
        tr.innerHTML = `
      <td>${order.order_number || order.id}</td>
      <td>${order.customer ? order.customer.name : "N/A"}</td>
      <td>${formatCurrency(order.total_amount || order.total)}</td>
      <td>${badgeForStatus(order.status)}</td>
      <td>${badgeForStatus(order.delivery_status || "pending")}</td>
      <td>${order.items_count || 0}</td>
      <td>${formatDate(order.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewOrder(${order.id})">View</button>
      </td>
    `;
        ordersTableBody.appendChild(tr);
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
        loadOrders(currentPage - 1);
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
        loadOrders(currentPage + 1);
    };
    paginationContainer.appendChild(nextBtn);
}

async function loadOrders(page = 1) {
    if (!Auth.requireAuth()) return;

    currentPage = page;
    ordersLoading.style.display = "block";
    ordersContent.style.display = "none";
    ordersError.style.display = "none";

    const params = new URLSearchParams();
    params.append("page", currentPage);
    params.append("per_page", 20);

    if (statusFilter.value) {
        params.append("status", statusFilter.value);
    }

    try {
        const data = await Api.get(`/staff/orders?${params.toString()}`);
        renderOrders(data.data);
        totalPages = data.meta ? data.meta.last_page : 1;
        renderPagination();
        ordersLoading.style.display = "none";
        ordersContent.style.display = "block";
    } catch (error) {
        ordersLoading.style.display = "none";
        ordersError.textContent = error.message || "Unable to load orders.";
        ordersError.style.display = "block";
    }
}

window.viewOrder = function (id) {
    window.location.href = `/staff/order-details?id=${id}`;
};

statusFilter.addEventListener("change", function () {
    loadOrders(1);
});

loadOrders();
