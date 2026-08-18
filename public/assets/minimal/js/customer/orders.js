const ordersError = document.getElementById('orders-error');
const ordersLoading = document.getElementById('orders-loading');
const ordersContent = document.getElementById('orders-content');

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
        pending_payment: 'badge-warning',
        paid: 'badge-warning',
        received: 'badge-warning',
        processing: 'badge-warning',
        ready_for_pickup: 'badge-success',
        completed: 'badge-success',
        cancelled: 'badge-danger',
    };
    const cls = map[status] || 'badge-muted';
    const label = status.replace(/_/g, ' ');
    return `<span class="badge ${cls}">${label}</span>`;
}

function renderOrders(orders) {
    if (!orders || orders.length === 0) {
        ordersContent.innerHTML = `
            <div class="card">
                <div class="empty-state">No orders yet</div>
                <a href="/customer/products" class="btn btn-primary btn-block" style="margin-top: 16px;">Browse Products</a>
            </div>
        `;
        return;
    }
    
    let ordersHtml = '';
    orders.forEach(function(order) {
        ordersHtml += `
            <div class="card" style="margin-bottom: 12px; cursor: pointer;" onclick="window.location.href='/customer/orders/${order.id}'">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong>Order #${order.id}</strong>
                    ${badgeForStatus(order.status)}
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted);">
                    <span>${formatDate(order.created_at)}</span>
                    <strong style="color: var(--text);">${formatCurrency(order.total)}</strong>
                </div>
            </div>
        `;
    });
    
    ordersContent.innerHTML = ordersHtml;
}

async function loadOrders() {
    if (!CustomerAuth.requireAuth()) return;
    
    ordersLoading.style.display = 'block';
    ordersContent.style.display = 'none';
    ordersError.style.display = 'none';
    
    try {
        const data = await CustomerApi.get('/customer/orders?per_page=50');
        renderOrders(data.data || data);
        ordersLoading.style.display = 'none';
        ordersContent.style.display = 'block';
    } catch (error) {
        ordersLoading.style.display = 'none';
        ordersError.textContent = error.message || 'Unable to load orders.';
        ordersError.style.display = 'block';
    }
}

loadOrders();