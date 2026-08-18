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

function badgeForStatus(status) {
  const map = {
    ordered: 'badge-warning',
    partially_received: 'badge-warning',
    received: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

function renderPurchaseOrders(orders) {
  purchaseOrdersTableBody.innerHTML = '';
  
  if (!orders || orders.length === 0) {
    purchaseOrdersTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">No purchase orders found</td></tr>';
    return;
  }

  orders.forEach(function(order) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${order.id}</td>
      <td>${order.supplier ? order.supplier.name : 'N/A'}</td>
      <td>${badgeForStatus(order.status)}</td>
      <td>${formatDate(order.expected_date)}</td>
      <td>${formatDate(order.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewPurchaseOrder(${order.id})">View</button>
      </td>
    `;
    purchaseOrdersTableBody.appendChild(tr);
  });
}

function renderPagination() {
  paginationContainer.innerHTML = '';
  
  if (totalPages <= 1) return;
  
  const prevBtn = document.createElement('button');
  prevBtn.className = 'btn btn-secondary';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function() { loadPurchaseOrders(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);
  
  const pageInfo = document.createElement('span');
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  pageInfo.style.margin = '0 12px';
  paginationContainer.appendChild(pageInfo);
  
  const nextBtn = document.createElement('button');
  nextBtn.className = 'btn btn-secondary';
  nextBtn.textContent = 'Next';
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = function() { loadPurchaseOrders(currentPage + 1); };
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

window.viewPurchaseOrder = function(id) {
  window.location.href = `/staff/purchase-order-details?id=${id}`;
};

createPOBtn.addEventListener('click', function() {
  window.location.href = '/staff/purchase-order-create';
});

statusFilter.addEventListener('change', function() {
  loadPurchaseOrders(1);
});

loadPurchaseOrders();