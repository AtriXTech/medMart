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
                <div class="flex items-center justify-between py-2.5 border-b border-[#EAF1FB] last:border-0">
                    <div>
                        <strong class="font-inter text-[14px] font-semibold text-[#171E26]">${item.product.name}</strong>
                        <div class="font-inter text-[12px] text-[#171E26]/50 mt-0.5">${item.quantity} × ${formatCurrency(item.unit_price)}</div>
                    </div>
                    <strong class="font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(item.line_total)}</strong>
                </div>
            `;
        });

        const canCancel = ['pending_payment', 'paid'].includes(order.status);
        const needsPayment = order.status === 'pending_payment';

        const metaRows = [
            `<div><strong class="text-[#171E26]">Placed:</strong> ${formatDate(order.created_at)}</div>`,
            `<div><strong class="text-[#171E26]">Fulfillment:</strong> <span class="capitalize">${order.fulfillment_type || 'N/A'}</span></div>`,
        ];
        if (order.delivery_address) {
            metaRows.push(`<div><strong class="text-[#171E26]">Address:</strong> ${order.delivery_address}</div>`);
        }
        if (order.delivery_status) {
            metaRows.push(`<div><strong class="text-[#171E26]">Delivery Status:</strong> <span class="capitalize">${order.delivery_status}</span></div>`);
        }
        if (order.ready_at) {
            metaRows.push(`<div><strong class="text-[#171E26]">Ready At:</strong> ${formatDate(order.ready_at)}</div>`);
        }

        orderContent.innerHTML = `
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5 mb-4">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <strong class="font-inter text-[15px] font-semibold text-[#171E26]">Order #${order.id}</strong>
                    ${badgeForStatus(order.status)}
                </div>
                <div class="font-inter text-[13px] text-[#171E26]/60 space-y-1.5 mb-4">
                    ${metaRows.join('')}
                </div>
                ${needsPayment ? `
                    <button type="button" id="pay-order-btn"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition disabled:opacity-60">
                        Pay Now
                    </button>
                ` : ''}
                ${canCancel ? `
                    <button type="button" id="cancel-order-btn"
                            class="w-full py-3 rounded-xl border border-red-200 text-red-500 font-inter text-[14px] font-semibold hover:bg-red-50 transition ${needsPayment ? 'mt-2.5' : ''}">
                        Cancel Order
                    </button>
                ` : ''}
            </div>

            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 md:p-5">
                <p class="font-manrope font-bold text-[15px] text-[#171E26] mb-1">Items</p>
                ${itemsHtml}
                <div class="flex items-center justify-between mt-4 pt-4 border-t-2 border-[#EAF1FB]">
                    <strong class="font-inter text-[14px] text-[#171E26]">Total:</strong>
                    <strong class="font-manrope text-xl font-extrabold text-[#2775E4]">${formatCurrency(order.total)}</strong>
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
        orderError.style.display = 'flex';
    }
}

loadOrder();