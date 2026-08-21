/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadPurchaseOrders() (same endpoint, same page/per_page/status
    params, same data.meta.last_page pagination source), window.viewPurchaseOrder
    (same redirect target), createPOBtn/statusFilter event bindings, the
    status option values (ordered / partially_received / received / cancelled).
  - CHANGED (presentation only): renderPurchaseOrders() now emits Tailwind
    table rows instead of plain <td> text; renderPagination() now builds
    styled Previous/Next buttons instead of default browser buttons.
  - Note: formatCurrency() was defined but unused in the original file too —
    left it in place untouched rather than removing it, since that's a
    logic/dead-code cleanup decision, not a styling one.
*/

const purchaseOrdersError = document.getElementById('purchase-orders-error');
const purchaseOrdersLoading = document.getElementById('purchase-orders-loading');
const purchaseOrdersContent = document.getElementById('purchase-orders-content');
const purchaseOrdersTableBody = document.getElementById('purchase-orders-table-body');
const statusFilter = document.getElementById('status-filter');
const paginationContainer = document.getElementById('pagination-container');
const createPOBtn = document.getElementById('create-po-btn');

let currentPage = 1;
let totalPages = 1;

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

function humanizeStatus(status) {
  return String(status || '')
    .split('_')
    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
    .join(' ');
}

const STATUS_STYLE = {
  ordered:            { bg: '#FFF8EC', text: '#8A6116' },
  partially_received: { bg: '#FFF8EC', text: '#8A6116' },
  received:           { bg: '#E9F8EF', text: '#1F7A44' },
  cancelled:          { bg: '#FDEDEC', text: '#9C3A32' },
};
function badgeForStatus(status) {
  const s = STATUS_STYLE[status] || { bg: '#F1F3F6', text: '#4B5563' };
  return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${humanizeStatus(status)}</span>`;
}

function renderPurchaseOrders(orders) {
  if (!orders || orders.length === 0) {
    purchaseOrdersTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-14">
      <i class="ph-light ph-clipboard-text text-3xl text-[#171E26]/20 block mb-2"></i>
      <p class="font-inter text-[13px] text-[#171E26]/45">No purchase orders found</p>
    </td></tr>`;
    return;
  }

  purchaseOrdersTableBody.innerHTML = orders.map(function (order) {
    return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
      <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">#${order.id}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${order.supplier ? order.supplier.name : 'N/A'}</td>
      <td class="py-3 pr-4">${badgeForStatus(order.status)}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${formatDate(order.expected_date)}</td>
      <td class="py-3 pr-4 font-inter text-[12px] text-[#171E26]/45">${formatDate(order.created_at)}</td>
      <td class="py-3 text-right">
        <button onclick="viewPurchaseOrder(${order.id})" class="font-inter text-[12px] font-semibold text-[#2775E4] hover:underline">View</button>
      </td>
    </tr>`;
  }).join('');
}

function renderPagination() {
  paginationContainer.innerHTML = '';

  if (totalPages <= 1) return;

  const prevBtn = document.createElement('button');
  prevBtn.className = 'h-9 px-3.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function () { loadPurchaseOrders(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);

  const pageInfo = document.createElement('span');
  pageInfo.className = 'font-inter text-[12px] text-[#171E26]/50 px-2';
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  paginationContainer.appendChild(pageInfo);

  const nextBtn = document.createElement('button');
  nextBtn.className = 'h-9 px-3.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent';
  nextBtn.textContent = 'Next';
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = function () { loadPurchaseOrders(currentPage + 1); };
  paginationContainer.appendChild(nextBtn);
}

async function loadPurchaseOrders(page = 1) {
  if (!Auth.requireAuth()) return;

  currentPage = page;
  purchaseOrdersLoading.style.display = 'block';
  purchaseOrdersContent.style.display = 'none';
  purchaseOrdersError.style.display = 'none';

  const params = new URLSearchParams();
  params.append('page', currentPage);
  params.append('per_page', 20);

  if (statusFilter.value) {
    params.append('status', statusFilter.value);
  }

  try {
    const data = await Api.get(`/staff/purchase-orders?${params.toString()}`);
    renderPurchaseOrders(data.data);
    totalPages = data.meta ? data.meta.last_page : 1;
    renderPagination();
    purchaseOrdersLoading.style.display = 'none';
    purchaseOrdersContent.style.display = 'block';
  } catch (error) {
    purchaseOrdersLoading.style.display = 'none';
    purchaseOrdersError.textContent = error.message || 'Unable to load purchase orders.';
    purchaseOrdersError.style.display = 'block';
  }
}

window.viewPurchaseOrder = function (id) {
  window.location.href = `/staff/purchase-order-details?id=${id}`;
};

createPOBtn.addEventListener('click', function () {
  window.location.href = '/staff/purchase-order-create';
});

statusFilter.addEventListener('change', function () {
  loadPurchaseOrders(1);
});

loadPurchaseOrders();