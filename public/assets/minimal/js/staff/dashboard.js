const dashboardError = document.getElementById('dashboard-error');
const dashboardLoading = document.getElementById('dashboard-loading');
const dashboardContent = document.getElementById('dashboard-content');
const statGrid = document.getElementById('stat-grid');
const statusTableBody = document.getElementById('status-table-body');

function statCard(label, value) {
  const div = document.createElement('div');
  div.className = 'stat-card';
  div.innerHTML = `<div class="label">${label}</div><div class="value">${value}</div>`;
  return div;
}

function badgeForStatus(status) {
  const map = {
    pending: 'badge-warning',
    processing: 'badge-warning',
    shipped: 'badge-warning',
    delivered: 'badge-success',
    completed: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function renderStats(data) {
  statGrid.innerHTML = '';
  statGrid.appendChild(statCard('Total Orders', data.orders.total));
  statGrid.appendChild(statCard('Low Stock Products', data.low_stock_products_count));
  statGrid.appendChild(statCard('Pending Prescriptions', data.pending_prescriptions_count));
  statGrid.appendChild(statCard('POS Sales Today', data.pos_sales_today.count));
  statGrid.appendChild(statCard('POS Revenue Today', formatCurrency(data.pos_sales_today.total)));
  statGrid.appendChild(statCard('Customer Orders Today', data.customer_orders_today.count));
  statGrid.appendChild(statCard('Customer Revenue Today', formatCurrency(data.customer_orders_today.total)));
}

function renderStatusBreakdown(byStatus) {
  statusTableBody.innerHTML = '';
  Object.keys(byStatus).forEach(function (status) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${badgeForStatus(status)}</td>
      <td>${byStatus[status]}</td>
    `;
    statusTableBody.appendChild(tr);
  });
}

async function loadDashboard() {
  if (!Auth.requireAuth()) return;
  try {
    const data = await Api.get('/staff/dashboard');
    renderStats(data);
    renderStatusBreakdown(data.orders.by_status);
    dashboardLoading.style.display = 'none';
    dashboardContent.style.display = 'block';
  } catch (error) {
    dashboardLoading.style.display = 'none';
    dashboardError.textContent = error.status === 403
      ? 'Your subscription is inactive. Please subscribe to access the dashboard.'
      : (error.message || 'Unable to load dashboard.');
    dashboardError.style.display = 'block';
  }
}

loadDashboard();