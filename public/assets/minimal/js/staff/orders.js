const ordersError = document.getElementById("orders-error");
const ordersLoading = document.getElementById("orders-loading");
const ordersContent = document.getElementById("orders-content");
const ordersTableBody = document.getElementById("orders-table-body");
const statusFilter = document.getElementById("status-filter");
const paginationContainer = document.getElementById("pagination-container");

let currentPage = 1;
let totalPages = 1;

function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return "₦" + value.toLocaleString();
}

function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleString();
}

function statusLabel(status, fulfillmentType) {
    const isDelivery = fulfillmentType === "delivery";
    const labels = {
        pending_payment: "Pending Payment",
        paid: "Paid",
        received: "Received",
        processing: "Processing",
        ready_for_pickup: isDelivery ? "Ready for Dispatch" : "Ready for Pickup",
        completed: isDelivery ? "Delivered" : "Picked Up",
        cancelled: "Cancelled",
        pending: "Pending",
        dispatched: "Dispatched",
        delivered: "Delivered",
    };
    return labels[status] || String(status || "").replace(/_/g, " ");
}

function badgeForStatus(status, fulfillmentType) {
    const map = {
        pending: "bg-amber-50 text-amber-600",
        pending_payment: "bg-amber-50 text-amber-600",
        paid: "bg-amber-50 text-amber-600",
        received: "bg-amber-50 text-amber-600",
        processing: "bg-amber-50 text-amber-600",
        dispatched: "bg-amber-50 text-amber-600",
        ready_for_pickup: "bg-[#DBEBFB] text-[#2775E4]",
        delivered: "bg-[#DBEBFB] text-[#2775E4]",
        completed: "bg-[#DBEBFB] text-[#2775E4]",
        cancelled: "bg-red-50 text-red-500",
    };
    const cls = map[status] || "bg-[#F7FAFD] text-[#171E26]/50";
    return `<span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full ${cls}">${escapeHtml(statusLabel(status, fulfillmentType))}</span>`;
}

function totalItemQuantity(order) {
    if (!Array.isArray(order.items)) return 0;
    return order.items.reduce(function (sum, item) {
        return sum + Number((item && item.quantity) || 0);
    }, 0);
}

function renderOrders(orders) {
    ordersTableBody.innerHTML = "";

    if (!orders || orders.length === 0) {
        ordersTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="py-14 text-center">
                    <i class="ph ph-shopping-bag-open text-3xl text-[#171E26]/20"></i>
                    <p class="font-inter text-sm text-[#171E26]/45 mt-2">No orders found</p>
                </td>
            </tr>
        `;
        return;
    }

    orders.forEach(function (order) {
        const tr = document.createElement("tr");
        tr.className = "border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition";
        tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26]">${escapeHtml(order.order_number || order.id)}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]">${order.customer ? escapeHtml(order.customer.name) : "N/A"}</td>
      <td class="py-3 px-3 font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(order.total_amount || order.total)}</td>
      <td class="py-3 px-3">${badgeForStatus(order.status, order.fulfillment_type)}</td>
      <td class="py-3 px-3">${order.fulfillment_type === "delivery" ? badgeForStatus(order.delivery_status || "pending") : '<span class="font-inter text-[13px] text-[#171E26]/40">N/A</span>'}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${totalItemQuantity(order)}</td>
      <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60 whitespace-nowrap">${formatDate(order.created_at)}</td>
      <td class="py-3 px-3">
        <button type="button" onclick="viewOrder(${Number(order.id)})"
                class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
          View
        </button>
      </td>
    `;
        ordersTableBody.appendChild(tr);
    });
}

function renderPagination() {
    paginationContainer.innerHTML = "";

    if (totalPages <= 1) return;

    const prevBtn = document.createElement("button");
    prevBtn.className = "rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition";
    prevBtn.textContent = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = function () {
        loadOrders(currentPage - 1);
    };
    paginationContainer.appendChild(prevBtn);

    const pageInfo = document.createElement("span");
    pageInfo.className = "mx-3 font-inter text-sm text-[#171E26]/60";
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    paginationContainer.appendChild(pageInfo);

    const nextBtn = document.createElement("button");
    nextBtn.className = "rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition";
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
        ordersError.style.display = "flex";
    }
}

window.viewOrder = function (id) {
    window.location.href = `/staff/order-details?id=${id}`;
};

statusFilter.addEventListener("change", function () {
    loadOrders(1);
});

loadOrders();
