const orderError = document.getElementById('order-error');
const orderLoading = document.getElementById('order-loading');
const orderContent = document.getElementById('order-content');

const pathParts = window.location.pathname.split('/');
const orderId = pathParts[pathParts.length - 1];

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

async function loadOrder() {
    if (!CustomerAuth.requireAuth()) return;
    if (!orderId || orderId === 'orders') {
        window.location.href = '/customer/orders';
        return;
    }
    
    orderLoading.style.display = 'block';
    orderContent.style.display = 'none';
    orderError.style.display = 'none';
    
    try {
        const order = await CustomerApi.get(`/customer/orders/${orderId}`);
        const items = order.items || [];
        
        let itemsHtml = '';
        items.forEach(function(item) {
            itemsHtml += `
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                    <div>
                        <strong>${item.product.name}</strong>
                        <div style="font-size: 12px; color: var(--text-muted);">${item.quantity} × ${formatCurrency(item.unit_price)}</div>
                    </div>
                    <strong>${formatCurrency(item.line_total)}</strong>
                </div>
            `;
        });
        
        const canCancel = ['pending_payment', 'paid'].includes(order.status);
        const needsPayment = order.status === 'pending_payment';
        
        orderContent.innerHTML = `
            <div class="card" style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <strong>Order #${order.id}</strong>
                    ${badgeForStatus(order.status)}
                </div>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                    <div><strong>Placed:</strong> ${formatDate(order.created_at)}</div>
                    <div><strong>Fulfillment:</strong> ${order.fulfillment_type || 'N/A'}</div>
                    ${order.delivery_address ? `<div><strong>Address:</strong> ${order.delivery_address}</div>` : ''}
                    ${order.delivery_status ? `<div><strong>Delivery Status:</strong> ${order.delivery_status}</div>` : ''}
                    ${order.ready_at ? `<div><strong>Ready At:</strong> ${formatDate(order.ready_at)}</div>` : ''}
                </div>
                ${needsPayment ? `
                    <button class="btn btn-primary btn-block" id="pay-order-btn">Pay Now</button>
                ` : ''}
                ${canCancel ? `
                    <button class="btn btn-danger btn-block" id="cancel-order-btn" style="${needsPayment ? 'margin-top: 8px;' : ''}">Cancel Order</button>
                ` : ''}
            </div>
            
            <div class="card">
                <p class="section-title">Items</p>
                ${itemsHtml}
                <div style="display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 2px solid var(--border);">
                    <strong>Total:</strong>
                    <strong style="font-size: 20px; color: var(--primary);">${formatCurrency(order.total)}</strong>
                </div>
            </div>
        `;
        
        orderLoading.style.display = 'none';
        orderContent.style.display = 'block';
        
        const payBtn = document.getElementById('pay-order-btn');
        if (payBtn) {
            payBtn.addEventListener('click', async function() {
                payBtn.disabled = true;
                payBtn.textContent = 'Redirecting...';
                
                try {
                    localStorage.setItem('pending_order_id', order.id);
                    
                    const payment = await CustomerApi.post(`/customer/orders/${order.id}/pay`);
                    
                    if (payment.authorization_url) {
                        window.location.href = payment.authorization_url;
                    } else {
                        alert('Payment initiation failed. Please try again.');
                    }
                } catch (error) {
                    alert(error.message || 'Unable to initiate payment.');
                } finally {
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay Now';
                }
            });
        }
        
        const cancelBtn = document.getElementById('cancel-order-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', async function() {
                const reason = prompt('Reason for cancellation (optional):');
                
                try {
                    await CustomerApi.post(`/customer/orders/${order.id}/cancel`, {
                        reason: reason || undefined,
                    });
                    loadOrder();
                } catch (error) {
                    alert(error.message || 'Unable to cancel order.');
                }
            });
        }
    } catch (error) {
        orderLoading.style.display = 'none';
        orderError.textContent = error.message || 'Unable to load order.';
        orderError.style.display = 'block';
    }
}

loadOrder();