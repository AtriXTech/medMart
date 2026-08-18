const orderError = document.getElementById('order-error');
const orderLoading = document.getElementById('order-loading');
const orderContent = document.getElementById('order-content');
const orderInfo = document.getElementById('order-info');
const orderItemsTable = document.getElementById('order-items-table');
const updateStatusBtn = document.getElementById('update-status-btn');
const updateDeliveryBtn = document.getElementById('update-delivery-btn');
const statusSelect = document.getElementById('status-select');
const deliveryStatusSelect = document.getElementById('delivery-status-select');
const statusReasonInput = document.getElementById('status-reason');

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

function renderOrderInfo(order) {
  orderInfo.innerHTML = `
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
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
  
  try {
    await Api.patch(`/staff/orders/${orderId}/status`, {
      status: newStatus,
      reason: reason || undefined
    });
    loadOrder();
  } catch (error) {
    alert(error.message || 'Unable to update order status.');
  }
});

updateDeliveryBtn.addEventListener('click', async function() {
  const newStatus = deliveryStatusSelect.value;
  if (!newStatus) return;
  
  try {
    await Api.patch(`/staff/orders/${orderId}/delivery-status`, {
      delivery_status: newStatus
    });
    loadOrder();
  } catch (error) {
    alert(error.message || 'Unable to update delivery status.');
  }
});

loadOrder();