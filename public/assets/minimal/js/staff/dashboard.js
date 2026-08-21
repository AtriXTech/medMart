/*
  CHANGE SUMMARY (vs. previous version):
  - Auth.requireAuth() guard: unchanged.
  - Api.get('/staff/dashboard') call: unchanged.
  - 403 "subscription inactive" error handling: unchanged, same message.
  - #dashboard-loading / #dashboard-content / #dashboard-error show/hide
    pattern: unchanged in spirit, now toggles the `hidden` Tailwind class
    instead of inline style.display.
  - REMOVED: #stat-grid, #status-table-body (old markup no longer exists).
  - ADDED: renderPrimaryStats(), renderOperationalStats(),
    renderStatusChart() (Chart.js horizontal bar, replaces the old table),
    renderPerformanceChart() (Customer vs POS today, Chart.js bar),
    renderAttention(), and a second fetch to /staff/orders for
    renderRecentOrders() (client-side sorted newest-first, first 5 shown).
  - Assumption flagged: recent orders are fetched via Api.get('/staff/orders'),
    consistent with how /staff/dashboard is already called through the same
    Api wrapper. If your real endpoint path or response envelope differs
    from the paginated { data, links, meta } shape you shared, only the
    fetchRecentOrders() function needs to change — nothing else depends on it.
*/

const dashboardError = document.getElementById('dashboard-error');
const dashboardErrorText = document.getElementById('dashboard-error-text');
const dashboardLoading = document.getElementById('dashboard-loading');
const dashboardContent = document.getElementById('dashboard-content');

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  if (isNaN(d)) return iso;
  return d.toLocaleDateString('en-NG', { day: 'numeric', month: 'short' }) + ', ' +
         d.toLocaleTimeString('en-NG', { hour: 'numeric', minute: '2-digit' });
}

function humanizeStatus(status) {
  return String(status || '')
    .split('_')
    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
    .join(' ');
}

/* Exact map you confirmed, plus a safe fallback for anything unmapped
   (e.g. "pending_payment" seen on /staff/orders but not in this map). */
const STATUS_CLASS = {
  pending: 'warning',
  processing: 'warning',
  shipped: 'warning',
  delivered: 'success',
  completed: 'success',
  cancelled: 'danger',
};
const STATUS_STYLE = {
  warning: { bg: '#FFF8EC', text: '#8A6116', bar: '#D9A441' },
  success: { bg: '#E9F8EF', text: '#1F7A44', bar: '#2E9E5B' },
  danger:  { bg: '#FDEDEC', text: '#9C3A32', bar: '#D9564C' },
  muted:   { bg: '#F1F3F6', text: '#4B5563', bar: '#B1D0FB' },
};
function statusStyle(status) {
  const kind = STATUS_CLASS[status] || 'muted';
  return STATUS_STYLE[kind];
}
function statusBadgeHtml(status) {
  const s = statusStyle(status);
  return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${humanizeStatus(status)}</span>`;
}

/* ---------------- KPI cards ---------------- */
function primaryCard(label, value, icon, gradient) {
  if (gradient) {
    return `<div class="rounded-2xl p-4 md:p-5 bg-gradient-to-br from-[#2775E4] to-[#08AEBC] shadow-md shadow-[#2775E4]/15">
      <div class="flex items-center justify-between"><span class="font-inter text-[12px] font-medium text-white/80">${label}</span><i class="ph-light ${icon} text-white/70 text-lg"></i></div>
      <p class="font-manrope font-extrabold text-[22px] md:text-[24px] text-white mt-2.5">${value}</p>
    </div>`;
  }
  return `<div class="rounded-2xl p-4 md:p-5 bg-white border border-[#EAF1FB] shadow-sm">
    <div class="flex items-center justify-between"><span class="font-inter text-[12px] font-medium text-[#171E26]/55">${label}</span><i class="ph-light ${icon} text-[#2775E4] text-lg"></i></div>
    <p class="font-manrope font-extrabold text-[22px] md:text-[24px] text-[#171E26] mt-2.5">${value}</p>
  </div>`;
}

function operationalCard(label, value, icon, kind, href) {
  const s = STATUS_STYLE[kind];
  return `<div class="rounded-2xl p-4 flex items-center justify-between" style="background:${s.bg};border:1px solid ${s.bar}33">
    <div>
      <p class="font-inter text-[12px] font-medium" style="color:${s.text}">${label}</p>
      <p class="font-manrope font-extrabold text-[19px] mt-1" style="color:#171E26">${value}</p>
    </div>
    <a href="${href}" class="font-inter text-[12px] font-semibold flex-shrink-0" style="color:${s.text}">View <i class="ph-light ph-arrow-right"></i></a>
  </div>`;
}

function renderPrimaryStats(data) {
  const wrap = document.getElementById('stat-grid-primary');
  wrap.innerHTML =
    primaryCard('Customer Revenue Today', formatCurrency(data.customer_orders_today.total), 'ph-shopping-bag-open', true) +
    primaryCard('Customer Orders Today', data.customer_orders_today.count, 'ph-shopping-bag-open', false) +
    primaryCard('POS Revenue Today', formatCurrency(data.pos_sales_today.total), 'ph-cash-register', false) +
    primaryCard('POS Sales Today', data.pos_sales_today.count, 'ph-cash-register', false);
}

function renderOperationalStats(data) {
  const wrap = document.getElementById('stat-grid-operational');
  wrap.innerHTML =
    operationalCard('Pending Prescriptions', data.pending_prescriptions_count, 'ph-file-rx', 'warning', '/staff/prescriptions') +
    operationalCard('Low Stock Products', data.low_stock_products_count, 'ph-package', 'danger', '/staff/products') +
    operationalCard('Total Orders', data.orders.total, 'ph-shopping-bag-open', 'muted', '/staff/orders');
}

/* ---------------- Orders by Status chart ---------------- */
let statusChart = null;
function renderStatusChart(byStatus) {
  const wrap = document.getElementById('statusChartWrap');
  const entries = Object.entries(byStatus || {});

  if (entries.length === 0) {
    wrap.innerHTML = `<div class="h-[240px] flex flex-col items-center justify-center text-center px-6">
      <i class="ph-light ph-chart-bar text-3xl text-[#171E26]/20 mb-2"></i>
      <p class="font-inter text-[13px] text-[#171E26]/45">No order data available yet.</p>
    </div>`;
    return;
  }

  wrap.innerHTML = '<canvas id="statusCanvas" style="height:240px"></canvas>';
  const ctx = document.getElementById('statusCanvas').getContext('2d');
  const labels = entries.map(([status]) => humanizeStatus(status));
  const values = entries.map(([, v]) => v);
  const colors = entries.map(([status]) => statusStyle(status).bar);

  if (statusChart) statusChart.destroy();
  statusChart = new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets: [{ data: values, backgroundColor: colors, borderRadius: 6, barThickness: 16 }] },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => `${c.parsed.x} orders` } } },
      scales: {
        x: { grid: { color: '#EAF1FB' }, ticks: { precision: 0, font: { family: 'Inter', size: 11 }, color: '#171E2688' } },
        y: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12 }, color: '#171E26CC' } },
      },
    },
  });
}

/* ---------------- Today's Performance chart ---------------- */
let perfChart = null;
function renderPerformanceChart(data) {
  const wrap = document.getElementById('performanceChartWrap');
  wrap.innerHTML = '<canvas id="perfCanvas" style="height:240px"></canvas>';
  const ctx = document.getElementById('perfCanvas').getContext('2d');

  const customerTotal = Number(data.customer_orders_today.total || 0);
  const posTotal = Number(data.pos_sales_today.total || 0);

  if (perfChart) perfChart.destroy();
  perfChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Customer', 'POS'],
      datasets: [{
        data: [customerTotal, posTotal],
        backgroundColor: ['#2775E4', '#08AEBC'],
        borderRadius: 8,
        barThickness: 56,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => formatCurrency(c.parsed.y) } } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 12 }, color: '#171E26CC' } },
        y: { grid: { color: '#EAF1FB' }, ticks: { font: { family: 'Inter', size: 11 }, color: '#171E2688', callback: (v) => '₦' + (v / 1000) + 'k' } },
      },
    },
  });
}

/* ---------------- Needs Attention ---------------- */
function renderAttention(data) {
  const wrap = document.getElementById('attentionWrap');
  const rx = data.pending_prescriptions_count;
  const stock = data.low_stock_products_count;

  const rxCard = rx > 0
    ? `<div class="flex items-center justify-between rounded-xl bg-[#FFF8EC] border border-[#F5E3BF] p-4">
        <div class="flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-[#F5E3BF] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-file-rx text-[#8A6116] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]"><span class="font-semibold">${rx} prescriptions</span> waiting for review</p></div>
        <a href="/staff/prescriptions" class="font-inter text-[12px] font-semibold text-[#8A6116] flex-shrink-0">Review →</a>
      </div>`
    : `<div class="flex items-center gap-3 rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] p-4">
        <div class="h-10 w-10 rounded-lg bg-[#CFEBDB] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-check-circle text-[#1F7A44] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]">You're all caught up — no pending prescriptions.</p>
      </div>`;

  const stockCard = stock > 0
    ? `<div class="flex items-center justify-between rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] p-4">
        <div class="flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-[#F5C9C4] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-package text-[#9C3A32] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]"><span class="font-semibold">${stock} products</span> are low in stock</p></div>
        <a href="/staff/products" class="font-inter text-[12px] font-semibold text-[#9C3A32] flex-shrink-0">View →</a>
      </div>`
    : `<div class="flex items-center gap-3 rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] p-4">
        <div class="h-10 w-10 rounded-lg bg-[#CFEBDB] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-check-circle text-[#1F7A44] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]">Stock levels look healthy across your products.</p>
      </div>`;

  wrap.innerHTML = rxCard + stockCard;
}

/* ---------------- Recent Orders ---------------- */
function renderRecentOrders(orders) {
  const tableWrap = document.getElementById('ordersTableWrap');
  const cardsWrap = document.getElementById('ordersCardsWrap');
  const body = document.getElementById('ordersTableBody');

  if (!orders || orders.length === 0) {
    const empty = `<div class="text-center py-10"><i class="ph-light ph-tray text-3xl text-[#171E26]/20 mb-2"></i><p class="font-inter text-[13px] text-[#171E26]/45">No recent orders yet.</p></div>`;
    tableWrap.innerHTML = empty;
    cardsWrap.innerHTML = empty;
    return;
  }

  body.innerHTML = orders.map(o => {
    const customerName = (o.customer && (o.customer.name || o.customer.username)) || 'Customer';
    return `<tr class="border-b border-[#F3F7FC] last:border-0 hover:bg-[#F9FBFE]">
      <td class="py-3 font-inter text-[13px] font-semibold text-[#171E26]">#${o.id}</td>
      <td class="py-3 font-inter text-[13px] text-[#171E26]/70">${customerName}</td>
      <td class="py-3 font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(o.total)}</td>
      <td class="py-3">${statusBadgeHtml(o.status)}</td>
      <td class="py-3 font-inter text-[12px] text-[#171E26]/45">${formatDate(o.created_at)}</td>
      <td class="py-3 text-right"><a href="/staff/orders/${o.id}" class="font-inter text-[12px] font-semibold text-[#2775E4]">View</a></td>
    </tr>`;
  }).join('');
  tableWrap.classList.remove('hidden');

  cardsWrap.innerHTML = orders.map(o => {
    const customerName = (o.customer && (o.customer.name || o.customer.username)) || 'Customer';
    return `<div class="rounded-xl border border-[#EAF1FB] p-4">
      <div class="flex items-center justify-between mb-1.5"><p class="font-inter text-[13px] font-semibold text-[#171E26]">#${o.id}</p>${statusBadgeHtml(o.status)}</div>
      <p class="font-inter text-[13px] text-[#171E26]/70">${customerName}</p>
      <div class="flex items-center justify-between mt-2">
        <p class="font-manrope font-bold text-[15px] text-[#171E26]">${formatCurrency(o.total)}</p>
        <p class="font-inter text-[11px] text-[#171E26]/40">${formatDate(o.created_at)}</p>
      </div>
      <a href="/staff/orders/${o.id}" class="font-inter text-[12px] font-semibold text-[#2775E4] mt-2 inline-block">View →</a>
    </div>`;
  }).join('');
}

async function fetchRecentOrders() {
  try {
    const response = await Api.get('/staff/orders');
    const orders = (response && response.data) ? response.data.slice() : [];
    orders.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    return orders.slice(0, 5);
  } catch (error) {
    console.error('Unable to load recent orders:', error);
    return [];
  }
}

/* ---------------- Boot ---------------- */
async function loadDashboard() {
  if (!Auth.requireAuth()) return;
  try {
    const [data, recentOrders] = await Promise.all([
      Api.get('/staff/dashboard'),
      fetchRecentOrders(),
    ]);

    renderPrimaryStats(data);
    renderOperationalStats(data);
    renderStatusChart(data.orders.by_status);
    renderPerformanceChart(data);
    renderAttention(data);
    renderRecentOrders(recentOrders);

    dashboardLoading.classList.add('hidden');
    dashboardContent.classList.remove('hidden');
  } catch (error) {
    dashboardLoading.classList.add('hidden');
    dashboardErrorText.textContent = error.status === 403
      ? 'Your subscription is inactive. Please subscribe to access the dashboard.'
      : (error.message || 'Unable to load dashboard.');
    dashboardError.classList.remove('hidden');
  }
}

loadDashboard();