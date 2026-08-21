/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadCustomers() (same endpoint GET /staff/customers with
    page/per_page/search params, same data.meta.last_page pagination),
    the 500ms search debounce, window.viewCustomer (same redirect),
    window.suspendCustomer / window.unsuspendCustomer (same PATCH
    endpoints, same confirm() dialogs).
  - CHANGED (presentation only): renderCustomers() now emits Tailwind
    table rows with a proper status badge instead of plain <td> text;
    renderPagination() rebuilt to match the styled Previous/Next pattern
    used on the other paginated pages (Purchase Orders).
*/

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
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${customer.email || "N/A"}</td>
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