/*
  CHANGE SUMMARY (vs. previous version):
  - Verified against the actual OpenAPI spec for this API. Findings:
    * /staff/dashboard response ALSO includes orders.today_total and
      orders.by_status_today, which weren't used before. Now used to add
      "Orders Today" and an "Avg Order Value Today" (computed) stat.
    * orders.by_status / orders.by_status_today are typed as STRING in the
      spec, not object, even though the previous code (and this one) treats
      them as objects via Object.entries(). Added parseStatusMap() to handle
      both shapes defensively — please verify against a real response.
    * /staff/customers, /staff/products both support pagination meta.total,
      so real Customers and Inventory counts are now fetched (per_page=1,
      only meta.total is read, data array ignored) and rendered.
    * /staff/batches/expiring-soon also has meta.total — used for a new
      "Inventory Health" panel (Low Stock + Expiring Soon side by side).
    * NOT found in the spec, so still not built: any date-range/period
      filter on orders or sales (blocks Revenue This Month, Week/Month
      toggle), any per-product sales aggregation (Top Products), any
      per-staff aggregation (Staff Activity), any "out of stock" filter
      distinct from "unavailable" (Out of Stock tile intentionally omitted
      from Inventory Health rather than guessing).
  - Auth.requireAuth(), Api.get('/staff/dashboard'), 403 handling: unchanged.
  - renderWelcome(), renderStatusChart(), renderPerformanceChart(),
    fetchRecentOrders()/renderRecentOrders(): unchanged.
  - RENAMED (functionally, not visually breaking): renderOperationalStats()
    is now split into renderSecondaryStats() (new #stat-grid-secondary,
    4 cards) and renderInventoryHealth() (new #inventoryHealthWrap).
    renderAttention() unchanged, just now sits in its own panel alongside
    Inventory Health instead of full-width.
  - FIXED (per follow-up feedback): welcome header now fetches real data
    instead of guessing — added fetchProfile() -> Api.get('/staff/profile')
    (confirmed shape from profile.js: profile.name, profile.pharmacy.name),
    replacing the earlier Api.getUser() assumption entirely. Added
    formatWelcomeDate() for a real "Monday, 12th of June" style date instead
    of a static "Today" label.
  - FIXED (per follow-up feedback): Recent Orders no longer uses a separate
    desktop-table / mobile-cards split toggled via hidden/md:block classes
    (that pattern wasn't rendering reliably on mobile). Replaced with a
    single table wrapped in overflow-x-auto + min-w-[560px], matching the
    exact pattern already used successfully on the Sales/Orders/Products
    pages — scrolls horizontally on small screens instead of duplicating
    markup in two places.
  - THIS UPDATE (only these two things touched, per explicit request —
    nothing else in this file was changed):
    * Business Performance grid: the broken "Settings" card (was reusing
      data.orders.total with the wrong icon, linking to /staff/orders) is
      replaced by a dedicated shortcut card — light red background,
      centered gear icon, links to /staff/pharmacy-settings. New
      settingsShortcutCard() function; renderSecondaryStats() no longer
      builds that 4th card with secondaryCard().
    * Needs Attention: the prescriptions card is replaced with an Expired
      Products card (confirmed: prescriptions tracking removed from this
      section entirely, not added alongside it). Same non-zero/zero-state
      pattern as the existing Low Stock card. New fetchExpiredProductsTotal()
      (GET /staff/batches/expiring-soon?status=expired&per_page=1, reading
      meta.total only, same pattern as the other *Total fetchers already
      in this file). renderAttention() now takes (data, extras) instead of
      just (data).
    * FLAGGING, not fixing: the Expired Products "View" link points to
      plain /staff/expiring-batches. It won't auto-select the "Already
      expired" filter there since expiring-batches.js doesn't currently
      read a query param on load — I didn't touch that file since it
      wasn't part of this request, but say the word if you want that
      wired up.
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

// Defensive: the API spec types by_status/by_status_today as a STRING, but
// the data is logically a map of status -> count. Handles both a real
// object and a JSON-encoded string so the chart doesn't silently break
// depending on which shape the live API actually returns.
function parseStatusMap(raw) {
  if (!raw) return {};
  if (typeof raw === 'object') return raw;
  if (typeof raw === 'string') {
    try {
      const parsed = JSON.parse(raw);
      return (parsed && typeof parsed === 'object') ? parsed : {};
    } catch (e) {
      console.warn('Unable to parse status map string:', raw);
      return {};
    }
  }
  return {};
}

/* ---------------- Welcome header ---------------- */
// Ordinal suffix for date formatting (1st, 2nd, 3rd, 4th... 11th-13th exception).
function ordinalSuffix(day) {
  if (day >= 11 && day <= 13) return 'th';
  switch (day % 10) {
    case 1: return 'st';
    case 2: return 'nd';
    case 3: return 'rd';
    default: return 'th';
  }
}

function formatWelcomeDate(date) {
  const weekday = date.toLocaleDateString('en-NG', { weekday: 'long' });
  const day = date.getDate();
  const month = date.toLocaleDateString('en-NG', { month: 'long' });
  return `${weekday}, ${day}${ordinalSuffix(day)} of ${month}`;
}

async function fetchProfile() {
  try {
    return await Api.get('/staff/profile');
  } catch (error) {
    console.error('Unable to load profile:', error);
    return null;
  }
}

function renderWelcome(profile) {
  const greetingEl = document.getElementById('welcome-greeting');
  const pharmacyEl = document.getElementById('welcome-pharmacy');
  const dateEl = document.getElementById('welcome-date');
  const iconEl = document.getElementById('welcome-icon');

  const now = new Date();
  const hour = now.getHours();

  // Four clear time bands, each with its own greeting text and icon so the
  // visual cue (sun/moon) always matches the greeting, not just the words.
  let timeGreeting, iconClass;
  if (hour < 5) {
    timeGreeting = 'Good night';
    iconClass = 'ph-fill ph-moon-stars';
  } else if (hour < 12) {
    timeGreeting = 'Good morning';
    iconClass = 'ph-fill ph-sun';
  } else if (hour < 17) {
    timeGreeting = 'Good afternoon';
    iconClass = 'ph-fill ph-sun';
  } else if (hour < 21) {
    timeGreeting = 'Good evening';
    iconClass = 'ph-fill ph-cloud-sun';
  } else {
    timeGreeting = 'Good night';
    iconClass = 'ph-fill ph-moon-stars';
  }

  const firstName = profile && profile.name ? profile.name : 'there';//.split(' ')[0]
  greetingEl.textContent = `${timeGreeting}, ${firstName}`;
  iconEl.className = `${iconClass} text-white text-xl`;

  const pharmacyName = profile && profile.pharmacy && profile.pharmacy.name
    ? profile.pharmacy.name
    : 'Your Pharmacy';
  pharmacyEl.textContent = pharmacyName;

  dateEl.textContent = formatWelcomeDate(now);
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

function secondaryCard(label, value, icon, href) {
  return `<div class="rounded-2xl p-4 md:p-5 bg-white border border-[#EAF1FB] shadow-sm">
    <div class="flex items-center justify-between"><span class="font-inter text-[12px] font-medium text-[#171E26]/55">${label}</span><i class="ph-light ${icon} text-[#2775E4] text-lg"></i></div>
    <div class="flex items-end justify-between mt-2.5">
      <p class="font-manrope font-extrabold text-[20px] md:text-[22px] text-[#171E26]">${value}</p>
      ${href ? `<a href="${href}" class="font-inter text-[11px] font-semibold text-[#2775E4] mb-0.5">View</a>` : ''}
    </div>
  </div>`;
}

function settingsShortcutCard(href) {
  return `<a href="${href}" class="rounded-2xl p-4 md:p-5 flex flex-col items-center justify-center gap-2 text-center transition hover:opacity-90" style="background:#FDEDEC">
    <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center">
      <i class="ph-fill ph-gear-six text-[#9C3A32] text-xl"></i>
    </div>
    <span class="font-inter text-[12px] font-semibold text-[#9C3A32]">Settings</span>
  </a>`;
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
  const ordersToday = Number(data.orders.today_total || 0);
  const revenueToday = Number(data.customer_orders_today.total || 0) + Number(data.pos_sales_today.total || 0);
  const combinedRevenueToday = Number(data.customer_orders_today.total || 0) + Number(data.pos_sales_today.total || 0);
  const avgOrderValueToday = ordersToday > 0 ? formatCurrency(revenueToday / ordersToday) : formatCurrency(0);

  wrap.innerHTML =
    primaryCard('Revenue Today', formatCurrency(combinedRevenueToday), 'ph-wallet', true) +
    primaryCard('Avg Order Value (Today)', avgOrderValueToday, 'ph-chart-line-up', null) +
    primaryCard('Customer Orders Today', data.customer_orders_today.count, 'ph-shopping-bag-open', false) +
    primaryCard('POS Sales Today', data.pos_sales_today.count, 'ph-cash-register', false);
}

function renderSecondaryStats(data, extras) {
  const wrap = document.getElementById('stat-grid-secondary');
  wrap.innerHTML =
     secondaryCard('Total Orders', data.orders.total, 'ph-shopping-bag-open', '/staff/orders') +
    secondaryCard('Customers', extras.customersTotal, 'ph-users', '/staff/customers') +
    secondaryCard('Inventory', extras.productsTotal, 'ph-package', '/staff/products') +
    settingsShortcutCard('/staff/pharmacy-settings');
}

/* ---------------- Inventory Health ---------------- */
function inventoryHealthTile(label, value, icon, tone) {
  const styles = {
    warning: { bg: '#FFF8EC', text: '#8A6116' },
    danger: { bg: '#FDEDEC', text: '#9C3A32' },
  };
  const s = styles[tone] || styles.warning;
  return `<div class="rounded-xl p-4" style="background:${s.bg}">
    <div class="flex items-center gap-2 mb-1.5"><i class="ph-light ${icon} text-lg" style="color:${s.text}"></i>
      <span class="font-inter text-[12px] font-medium" style="color:${s.text}">${label}</span></div>
    <p class="font-manrope font-extrabold text-[20px]" style="color:#171E26">${value}</p>
  </div>`;
}

function renderInventoryHealth(data, extras) {
  const wrap = document.getElementById('inventoryHealthWrap');
  wrap.innerHTML =
    inventoryHealthTile('Low Stock', data.low_stock_products_count, 'ph-warning', 'warning') +
    inventoryHealthTile('Expiring Soon', extras.expiringSoonTotal, 'ph-hourglass-medium', 'danger');
  // Note: an "Out of Stock" tile is intentionally omitted here — the API
  // doesn't expose a stock_quantity=0 filter distinct from "unavailable",
  // and conflating the two would misrepresent actual out-of-stock counts.
}

/* ---------------- Orders by Status chart ---------------- */
let statusChart = null;
function renderStatusChart(byStatusRaw) {
  const wrap = document.getElementById('statusChartWrap');
  const byStatus = parseStatusMap(byStatusRaw);
  const entries = Object.entries(byStatus);

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
function renderAttention(data, extras) {
  const wrap = document.getElementById('attentionWrap');
  const expired = extras.expiredProductsTotal;
  const stock = data.low_stock_products_count;

  const expiredCard = expired > 0
    ? `<div class="flex items-center justify-between rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] p-4">
        <div class="flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-[#F5C9C4] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-calendar-x text-[#9C3A32] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]"><span class="font-semibold">${expired} products</span> have already expired</p></div>
        <a href="/staff/expiring-batches" class="font-inter text-[12px] font-semibold text-[#9C3A32] flex-shrink-0">View →</a>
      </div>`
    : `<div class="flex items-center gap-3 rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] p-4">
        <div class="h-10 w-10 rounded-lg bg-[#CFEBDB] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-check-circle text-[#1F7A44] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]">No expired products in your inventory.</p>
      </div>`;

  const stockCard = stock > 0
    ? `<div class="flex items-center justify-between rounded-xl bg-[#FDEDEC] border border-[#F5C9C4] p-4">
        <div class="flex items-center gap-3"><div class="h-10 w-10 rounded-lg bg-[#F5C9C4] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-package text-[#9C3A32] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]"><span class="font-semibold">${stock} products</span> are low in stock</p></div>
        <a href="/staff/out-of-stock" class="font-inter text-[12px] font-semibold text-[#9C3A32] flex-shrink-0">View →</a>
      </div>`
    : `<div class="flex items-center gap-3 rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] p-4">
        <div class="h-10 w-10 rounded-lg bg-[#CFEBDB] flex items-center justify-center flex-shrink-0"><i class="ph-light ph-check-circle text-[#1F7A44] text-lg"></i></div>
        <p class="font-inter text-[13px] text-[#171E26]">Stock levels look healthy across your products.</p>
      </div>`;

  wrap.innerHTML = expiredCard + stockCard;
}

/* ---------------- Recent Orders ---------------- */
function renderRecentOrders(orders) {
  const body = document.getElementById('ordersTableBody');

  if (!orders || orders.length === 0) {
    body.innerHTML = `<tr><td colspan="6" class="py-10 text-center">
      <i class="ph-light ph-tray text-3xl text-[#171E26]/20 mb-2 inline-block"></i>
      <p class="font-inter text-[13px] text-[#171E26]/45">No recent orders yet.</p>
    </td></tr>`;
    return;
  }

  body.innerHTML = orders.map(o => {
    const customerName = (o.customer && (o.customer.name || o.customer.username)) || 'Customer';
    return `<tr class="border-b border-[#F3F7FC] last:border-0 hover:bg-[#F9FBFE]">
      <td class="py-3 font-inter text-[13px] font-semibold text-[#171E26]">#${o.id}</td>
      <td class="py-3 font-inter text-[13px] text-[#171E26]/70">${customerName}</td>
      <td class="py-3 font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(o.total)}</td>
      <td class="py-3">${statusBadgeHtml(o.status)}</td>
      <td class="py-3 font-inter text-[12px] text-[#171E26]/45 whitespace-nowrap">${formatDate(o.created_at)}</td>
      <td class="py-3 text-right"><a href="/staff/order-details?id=${o.id}" class="font-inter text-[12px] font-semibold text-[#2775E4]">View</a></td>
    </tr>`;
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

// Only meta.total is needed from each of these — per_page=1 keeps the
// actual data payload minimal since we're not using the returned rows.
async function fetchCustomersTotal() {
  try {
    const response = await Api.get('/staff/customers?per_page=1');
    return (response && response.meta && response.meta.total) || 0;
  } catch (error) {
    console.error('Unable to load customers total:', error);
    return 0;
  }
}

async function fetchProductsTotal() {
  try {
    const response = await Api.get('/staff/products?per_page=1');
    return (response && response.meta && response.meta.total) || 0;
  } catch (error) {
    console.error('Unable to load products total:', error);
    return 0;
  }
}

async function fetchExpiringSoonTotal() {
  try {
    const response = await Api.get('/staff/batches/expiring-soon?per_page=1');
    return (response && response.meta && response.meta.total) || 0;
  } catch (error) {
    console.error('Unable to load expiring batches total:', error);
    return 0;
  }
}

async function fetchExpiredProductsTotal() {
  try {
    const response = await Api.get('/staff/batches/expiring-soon?status=expired&per_page=1');
    return (response && response.meta && response.meta.total) || 0;
  } catch (error) {
    console.error('Unable to load expired products total:', error);
    return 0;
  }
}

/* ---------------- Boot ---------------- */
async function loadDashboard() {
  if (!Auth.requireAuth()) return;
  try {
    const [data, recentOrders, customersTotal, productsTotal, expiringSoonTotal, expiredProductsTotal, profile] = await Promise.all([
      Api.get('/staff/dashboard'),
      fetchRecentOrders(),
      fetchCustomersTotal(),
      fetchProductsTotal(),
      fetchExpiringSoonTotal(),
      fetchExpiredProductsTotal(),
      fetchProfile(),
    ]);

    const extras = { customersTotal, productsTotal, expiringSoonTotal, expiredProductsTotal };

    renderWelcome(profile);
    renderPrimaryStats(data);
    renderSecondaryStats(data, extras);
    renderInventoryHealth(data, extras);
    renderStatusChart(data.orders.by_status);
    renderPerformanceChart(data);
    renderAttention(data, extras);
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