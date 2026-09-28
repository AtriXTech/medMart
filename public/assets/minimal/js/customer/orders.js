/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: the GET /customer/orders?per_page=50 endpoint and payload
    shape, badgeForStatus() (colors/labels), formatCurrency(),
    formatDate(), the click-through-to-order-details behavior, every
    existing element ID.
  - NEW: a status filter bar (#status-filter-bar) — pills for All /
    Pending Payment / Paid / Received / Processing / Ready for Pickup /
    Completed / Cancelled. GET /customer/orders only documents a
    pharmacy_id query param, not a status param, so filtering happens
    client-side against the already-fetched list rather than guessing at
    an undocumented status= param. Switching filters just re-renders
    from the same fetched array — no extra API calls.
  - NEW: each order card now shows the pharmacy name it was placed with.
    This is real data — the customer OrderResource already includes
    pharmacy_id, and GET /customer/pharmacies (which every customer page
    with a pharmacy switcher already calls) supplies the id -> name
    mapping. No mocking needed here.
  - NEW: a distinct empty state for "no orders match this filter" vs the
    original "no orders yet" empty state (which still only shows the
    Browse Products CTA when the filter is "All").
*/

const ordersError = document.getElementById('orders-error');
const ordersLoading = document.getElementById('orders-loading');
const ordersContent = document.getElementById('orders-content');
const statusFilterBar = document.getElementById('status-filter-bar');

const STATUS_FILTERS = [
    { value: 'all', label: 'All' },
    { value: 'pending_payment', label: 'Pending Payment' },
    { value: 'paid', label: 'Paid' },
    { value: 'received', label: 'Received' },
    { value: 'processing', label: 'Processing' },
    { value: 'ready_for_pickup', label: 'Ready for Pickup' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' },
];

let allOrders = [];
let pharmacyNameById = {};
let activeFilter = 'all';

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString();
}

function badgeForStatus(status) {
    const map = {
        pending_payment: 'bg-amber-50 text-amber-600',
        paid: 'bg-amber-50 text-amber-600',
        received: 'bg-amber-50 text-amber-600',
        processing: 'bg-amber-50 text-amber-600',
        ready_for_pickup: 'bg-[#DBEBFB] text-[#2775E4]',
        completed: 'bg-[#DBEBFB] text-[#2775E4]',
        cancelled: 'bg-red-50 text-red-500',
    };
    const cls = map[status] || 'bg-[#F7FAFD] text-[#171E26]/50';
    const label = status.replace(/_/g, ' ');
    return `<span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full capitalize flex-shrink-0 ${cls}">${label}</span>`;
}

/* ---------------- NEW: status filter bar ---------------- */

function getFilteredOrders() {
    if (activeFilter === 'all') return allOrders;
    return allOrders.filter(function (order) { return order.status === activeFilter; });
}

function renderStatusFilterBar() {
    statusFilterBar.innerHTML = STATUS_FILTERS.map(function (filter) {
        const isActive = filter.value === activeFilter;
        const activeClass = isActive
            ? 'bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white'
            : 'bg-white border border-[#DBEBFB] text-[#171E26]/60 hover:bg-[#F7FAFD]';
        return `<button type="button" class="status-filter-btn flex-shrink-0 px-3.5 py-2 rounded-full font-inter text-[12.5px] font-semibold whitespace-nowrap transition ${activeClass}" data-status="${filter.value}">${filter.label}</button>`;
    }).join('');

    statusFilterBar.querySelectorAll('.status-filter-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeFilter = btn.dataset.status;
            renderStatusFilterBar();
            renderOrders(getFilteredOrders());
        });
    });
}

/* ---------------- Orders list ---------------- */

function renderOrders(orders) {
    if (!orders || orders.length === 0) {
        if (activeFilter !== 'all') {
            ordersContent.innerHTML = `
                <div class="bg-white rounded-2xl border border-[#EAF1FB] p-6 text-center">
                    <i class="ph-light ph-funnel text-3xl text-[#171E26]/20"></i>
                    <p class="font-inter text-sm text-[#171E26]/50 mt-2">No orders match this filter.</p>
                </div>
            `;
            return;
        }

        ordersContent.innerHTML = `
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-6 text-center">
                <i class="ph-light ph-package text-3xl text-[#171E26]/20"></i>
                <p class="font-inter text-sm text-[#171E26]/50 mt-2 mb-4">No orders yet</p>
                <a href="/customer/products"
                   class="block w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                    Browse Products
                </a>
            </div>
        `;
        return;
    }

    let ordersHtml = '';
    orders.forEach(function (order) {
        const pharmacyName = pharmacyNameById[order.pharmacy_id] || 'Pharmacy';

        ordersHtml += `
            <div onclick="window.location.href='/customer/orders/${order.id}'"
                 class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-3 cursor-pointer hover:shadow-md transition">
                <div class="flex items-center justify-between gap-3 mb-1.5">
                    <strong class="font-inter text-[14px] font-semibold text-[#171E26]">Order #${order.id}</strong>
                    ${badgeForStatus(order.status)}
                </div>
                <p class="font-inter text-[12px] text-[#171E26]/45 mb-2 flex items-center gap-1.5">
                    <i class="ph-light ph-storefront text-[13px]"></i> ${pharmacyName}
                </p>
                <div class="flex items-center justify-between">
                    <span class="font-inter text-[12px] text-[#171E26]/50">${formatDate(order.created_at)}</span>
                    <strong class="font-inter text-[14px] font-bold text-[#171E26]">${formatCurrency(order.total)}</strong>
                </div>
            </div>
        `;
    });

    ordersContent.innerHTML = ordersHtml;
}

/* ---------------- Data loading ---------------- */

async function fetchPharmacyNames() {
    try {
        const response = await CustomerApi.get('/customer/pharmacies');
        const pharmacies = response.data || response;
        const map = {};
        (pharmacies || []).forEach(function (pharmacy) {
            map[pharmacy.id] = pharmacy.name;
        });
        return map;
    } catch (error) {
        console.error('Unable to load pharmacies:', error);
        return {};
    }
}

async function loadOrders() {
    if (!CustomerAuth.requireAuth()) return;

    ordersLoading.style.display = 'block';
    ordersContent.style.display = 'none';
    statusFilterBar.style.display = 'none';
    ordersError.style.display = 'none';

    try {
        const [data, pharmacyMap] = await Promise.all([
            CustomerApi.get('/customer/orders?per_page=50'),
            fetchPharmacyNames(),
        ]);

        allOrders = data.data || data;
        pharmacyNameById = pharmacyMap;

        renderStatusFilterBar();
        renderOrders(getFilteredOrders());

        ordersLoading.style.display = 'none';
        ordersContent.style.display = 'block';
        statusFilterBar.style.display = 'flex';
    } catch (error) {
        ordersLoading.style.display = 'none';
        ordersError.textContent = error.message || 'Unable to load orders.';
        ordersError.style.display = 'flex';
    }
}

loadOrders();