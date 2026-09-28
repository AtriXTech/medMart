/*
  CHANGE SUMMARY (vs. previous version):
  - NEW: updateStatusBtn now shows "Updating Status..." and disables
    itself while the request is in flight, reset in a finally block —
    guarantees it can never get stuck disabled after an error, which
    would produce exactly the "works sometimes, not others" symptom you
    described. Combined with the Blade file's class cleanup on this
    button, this addresses the inconsistent-click-area report.
  - NEW: updateDeliveryBtn gets the same treatment — "Updating Delivery
    Status..." while in flight, same finally-guaranteed reset.
  - CHANGED: updateDeliveryBtn's catch block now calls showInfoModal()
    instead of alert(), same as the order-status one already did.
  - UNCHANGED: loadOrder(), renderOrderInfo(), renderOrderItems(),
    renderOrderReceipt() (shared MedMartReceipt template), badgeForStatus(),
    printOrderBtn, both PATCH endpoints and their payload shapes.
*/

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
const statusReasonInput = document.getElementById('status-reason');
const printOrderBtn = document.getElementById('print-order-btn');

const infoModal = document.getElementById('info-modal');
const infoModalTitle = document.getElementById('info-modal-title');
const infoModalMessage = document.getElementById('info-modal-message');
const infoModalCloseBtn = document.getElementById('info-modal-close-btn');

const orderId = new URLSearchParams(window.location.search).get('id');

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
    pending: 'badge-warning',
    processing: 'badge-warning',
    ready_for_pickup: 'badge-success',
    completed: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

/* ---------------- Small info modal (order-status + delivery-status errors) ---------------- */

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

function renderOrderInfo(order) {
  let fields = `
      <div>
        <strong>Order ID:</strong> ${order.id}
      </div>
      <div>
        <strong>Status:</strong> ${badgeForStatus(order.status)}
      </div>
      <div>
        <strong>Customer:</strong> ${order.customer ? order.customer.name : 'N/A'}
      </div>
      <div>
        <strong>Subtotal:</strong> ${formatCurrency(order.subtotal)}
      </div>
      <div>
        <strong>Total:</strong> ${formatCurrency(order.total)}
      </div>
      <div>
        <strong>Fulfillment:</strong> ${order.fulfillment_type || 'N/A'}
      </div>
      <div>
        <strong>Delivery Status:</strong> ${badgeForStatus(order.delivery_status)}
      </div>
      <div>
        <strong>Created:</strong> ${formatDate(order.created_at)}
      </div>
  `;

  if (order.delivery_address) {
    fields += `
      <div>
        <strong>Delivery Address:</strong> ${order.delivery_address}
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

  items.forEach(function(item) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.product ? item.product.name : 'N/A'}</td>
      <td>${item.quantity}</td>
      <td>${formatCurrency(item.unit_price)}</td>
      <td>${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
    `;
    orderItemsTable.appendChild(tr);
  });
}

/* Uses the shared MedMartReceipt template — see receipt-template.js. */
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

    statusSelect.value = order.status;
    deliveryStatusSelect.value = order.delivery_status || '';

    orderLoading.style.display = 'none';
    orderContent.style.display = 'block';
  } catch (error) {
    orderLoading.style.display = 'none';
    orderError.textContent = error.message || 'Unable to load order.';
    orderError.style.display = 'block';
  }
}

updateStatusBtn.addEventListener('click', async function() {
  const newStatus = statusSelect.value;
  if (!newStatus) return;

  const reason = statusReasonInput.value.trim();

  updateStatusBtn.disabled = true;
  updateStatusBtn.textContent = 'Updating Status...';

  try {
    await Api.patch(`/staff/orders/${orderId}/status`, {
      status: newStatus,
      reason: reason || undefined
    });
    await loadOrder();
  } catch (error) {
    showInfoModal(error.message || 'Unable to update order status.');
  } finally {
    updateStatusBtn.disabled = false;
    updateStatusBtn.textContent = 'Update Status';
  }
});

updateDeliveryBtn.addEventListener('click', async function() {
  const newStatus = deliveryStatusSelect.value;
  if (!newStatus) return;

  updateDeliveryBtn.disabled = true;
  updateDeliveryBtn.textContent = 'Updating Delivery Status...';

  try {
    await Api.patch(`/staff/orders/${orderId}/delivery-status`, {
      delivery_status: newStatus
    });
    await loadOrder();
  } catch (error) {
    showInfoModal(error.message || 'Unable to update delivery status.', 'Unable to Update Delivery Status');
  } finally {
    updateDeliveryBtn.disabled = false;
    updateDeliveryBtn.textContent = 'Update Delivery Status';
  }
});

printOrderBtn.addEventListener('click', function() {
  window.print();
});

loadOrder();