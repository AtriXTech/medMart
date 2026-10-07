const orderError = document.getElementById('order-error');
const orderLoading = document.getElementById('order-loading');
const orderContent = document.getElementById('order-content');
const orderInfo = document.getElementById('order-info');
const orderItemsTable = document.getElementById('order-items-table');
const orderReceiptContent = document.getElementById('order-receipt-content');
const updateStatusBtn = document.getElementById('update-status-btn');
const updateDeliveryBtn = document.getElementById('update-delivery-btn');
const statusSelect = document.getElementById('status-select');
const deliveryStatusSelect = document.getElementById('delivery-status-select');
const deliverySection = document.getElementById('delivery-section');
const statusReasonInput = document.getElementById('status-reason');
const printOrderBtn = document.getElementById('print-order-btn');

const infoModal = document.getElementById('info-modal');
const infoModalTitle = document.getElementById('info-modal-title');
const infoModalMessage = document.getElementById('info-modal-message');
const infoModalCloseBtn = document.getElementById('info-modal-close-btn');

const orderId = new URLSearchParams(window.location.search).get('id');

const STATUS_FLOW = ['received', 'processing', 'ready_for_pickup', 'completed', 'cancelled'];

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function statusLabel(status, fulfillmentType) {
  const isDelivery = fulfillmentType === 'delivery';
  const labels = {
    pending_payment: 'Pending Payment',
    paid: 'Paid',
    received: 'Received',
    processing: 'Processing',
    ready_for_pickup: isDelivery ? 'Ready for Dispatch' : 'Ready for Pickup',
    completed: isDelivery ? 'Delivered' : 'Picked Up',
    cancelled: 'Cancelled',
  };
  return labels[status] || String(status || 'N/A').replace(/_/g, ' ');
}

function deliveryLabel(status) {
  const labels = {
    pending: 'Pending',
    dispatched: 'Dispatched',
    delivered: 'Delivered',
  };
  return labels[status] || 'Pending';
}

function badgeForStatus(label, status) {
  const map = {
    pending: 'badge-warning',
    pending_payment: 'badge-warning',
    paid: 'badge-warning',
    received: 'badge-warning',
    processing: 'badge-warning',
    dispatched: 'badge-warning',
    ready_for_pickup: 'badge-success',
    delivered: 'badge-success',
    completed: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${escapeHtml(label)}</span>`;
}

function showInfoModal(message, title) {
  infoModalTitle.textContent = title || 'Unable to Update Status';
  infoModalMessage.textContent = message;
  infoModal.style.display = 'flex';
}

function closeInfoModal() {
  infoModal.style.display = 'none';
}

infoModalCloseBtn.addEventListener('click', closeInfoModal);
infoModal.addEventListener('click', function (event) {
  if (event.target === infoModal) closeInfoModal();
});

function renderStatusOptions(order) {
  const options = ['<option value="">Select Order Status</option>'];

  STATUS_FLOW.forEach(function (status) {
    options.push(`<option value="${status}">${escapeHtml(statusLabel(status, order.fulfillment_type))}</option>`);
  });

  statusSelect.innerHTML = options.join('');
  statusSelect.value = STATUS_FLOW.includes(order.status) ? order.status : '';
}

function renderDeliverySection(order) {
  const isDelivery = order.fulfillment_type === 'delivery';
  deliverySection.style.display = isDelivery ? 'grid' : 'none';

  if (!isDelivery) return;

  const closed = order.status === 'completed' || order.status === 'cancelled';
  deliveryStatusSelect.value = order.delivery_status || 'pending';
  deliveryStatusSelect.disabled = closed;
  updateDeliveryBtn.disabled = closed;
}

function renderOrderInfo(order) {
  const isDelivery = order.fulfillment_type === 'delivery';

  let fields = `
      <div>
        <strong>Order ID:</strong> ${escapeHtml(order.id)}
      </div>
      <div>
        <strong>Status:</strong> ${badgeForStatus(statusLabel(order.status, order.fulfillment_type), order.status)}
      </div>
      <div>
        <strong>Customer:</strong> ${order.customer ? escapeHtml(order.customer.name) : 'N/A'}
      </div>
      <div>
        <strong>Subtotal:</strong> ${formatCurrency(order.subtotal)}
      </div>
      <div>
        <strong>Total:</strong> ${formatCurrency(order.total)}
      </div>
      <div>
        <strong>Fulfillment:</strong> ${isDelivery ? 'Delivery' : 'Pickup'}
      </div>
  `;

  if (isDelivery) {
    fields += `
      <div>
        <strong>Delivery Status:</strong> ${badgeForStatus(deliveryLabel(order.delivery_status), order.delivery_status || 'pending')}
      </div>
    `;
  }

  fields += `
      <div>
        <strong>Created:</strong> ${formatDate(order.created_at)}
      </div>
  `;

  if (order.delivery_address) {
    fields += `
      <div>
        <strong>Delivery Address:</strong> ${escapeHtml(order.delivery_address)}
      </div>
    `;
  }

  orderInfo.innerHTML = `
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      ${fields}
    </div>
  `;
}

function renderOrderItems(items) {
  orderItemsTable.innerHTML = '';

  if (!items || items.length === 0) {
    orderItemsTable.innerHTML = '<tr><td colspan="4" class="empty-state">No items</td></tr>';
    return;
  }

  items.forEach(function (item) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.product ? escapeHtml(item.product.name) : 'N/A'}</td>
      <td>${escapeHtml(item.quantity)}</td>
      <td>${formatCurrency(item.unit_price)}</td>
      <td>${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
    `;
    orderItemsTable.appendChild(tr);
  });
}

async function renderOrderReceipt(order) {
  const items = (order.items || []).map(function (item) {
    return {
      name: item.product ? item.product.name : 'Product',
      qty: item.quantity,
      rate: item.unit_price,
      amount: item.line_total || item.unit_price * item.quantity,
    };
  });

  const pharmacy = await MedMartReceipt.loadPharmacy();

  orderReceiptContent.innerHTML = MedMartReceipt.render({
    pharmacy,
    invoiceId: order.id,
    cashierName: MedMartReceipt.getCashierName(),
    date: order.created_at,
    items,
    subtotal: order.subtotal,
    total: order.total,
    paymentMethod: order.payment_method || null,
    status: order.status,
  });
}

async function loadOrder() {
  if (!Auth.requireAuth()) return;
  if (!orderId) {
    window.location.href = '/staff/orders';
    return;
  }

  orderLoading.style.display = 'block';
  orderContent.style.display = 'none';
  orderError.style.display = 'none';

  try {
    const order = await Api.get(`/staff/orders/${orderId}`);

    renderOrderInfo(order);
    renderOrderItems(order.items || []);
    renderOrderReceipt(order);
    renderStatusOptions(order);
    renderDeliverySection(order);

    orderLoading.style.display = 'none';
    orderContent.style.display = 'block';
  } catch (error) {
    orderLoading.style.display = 'none';
    orderError.textContent = error.message || 'Unable to load order.';
    orderError.style.display = 'block';
  }
}

updateStatusBtn.addEventListener('click', async function () {
  const newStatus = statusSelect.value;
  if (!newStatus) return;

  const reason = statusReasonInput.value.trim();

  updateStatusBtn.disabled = true;
  updateStatusBtn.textContent = 'Updating Status...';

  try {
    await Api.patch(`/staff/orders/${orderId}/status`, {
      status: newStatus,
      reason: reason || undefined,
    });
    await loadOrder();
  } catch (error) {
    showInfoModal(error.message || 'Unable to update order status.');
  } finally {
    updateStatusBtn.disabled = false;
    updateStatusBtn.textContent = 'Update Status';
  }
});

updateDeliveryBtn.addEventListener('click', async function () {
  const newStatus = deliveryStatusSelect.value;
  if (!newStatus) return;

  updateDeliveryBtn.disabled = true;
  updateDeliveryBtn.textContent = 'Updating Delivery Status...';

  try {
    await Api.patch(`/staff/orders/${orderId}/delivery-status`, {
      delivery_status: newStatus,
    });
    await loadOrder();
  } catch (error) {
    showInfoModal(error.message || 'Unable to update delivery status.', 'Unable to Update Delivery Status');
  } finally {
    updateDeliveryBtn.textContent = 'Update Delivery Status';
    updateDeliveryBtn.disabled = deliveryStatusSelect.disabled;
  }
});

printOrderBtn.addEventListener('click', function () {
  window.print();
});

loadOrder();
