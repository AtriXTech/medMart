const poError = document.getElementById('po-error');
const poLoading = document.getElementById('po-loading');
const poContent = document.getElementById('po-content');
const poInfo = document.getElementById('po-info');
const poItemsTable = document.getElementById('po-items-table');
const receiveBtn = document.getElementById('receive-btn');
const cancelBtn = document.getElementById('cancel-btn');
const receiveModal = document.getElementById('receive-modal');
const receiveForm = document.getElementById('receive-form');
const receiveItems = document.getElementById('receive-items');
const receiveError = document.getElementById('receive-error');
const receiveSubmitBtn = document.getElementById('receive-submit-btn');
const closeReceiveBtn = document.getElementById('close-receive-btn');
const cancelReceiveBtn = document.getElementById('cancel-receive-btn');

const poId = new URLSearchParams(window.location.search).get('id');

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
    ordered: 'badge-warning',
    partially_received: 'badge-warning',
    received: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

function renderPOInfo(po) {
  poInfo.innerHTML = `
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <div>
        <strong>PO ID:</strong> ${po.id}
      </div>
      <div>
        <strong>Status:</strong> ${badgeForStatus(po.status)}
      </div>
      <div>
        <strong>Supplier:</strong> ${po.supplier ? po.supplier.name : 'N/A'}
      </div>
      <div>
        <strong>Expected Date:</strong> ${formatDate(po.expected_date)}
      </div>
      <div>
        <strong>Placed By:</strong> ${po.placed_by || 'N/A'}
      </div>
      <div>
        <strong>Created:</strong> ${formatDate(po.created_at)}
      </div>
      ${po.notes ? `
        <div>
          <strong>Notes:</strong> ${po.notes}
        </div>
      ` : ''}
    </div>
  `;
  
  if (po.status === 'ordered' || po.status === 'partially_received') {
    receiveBtn.style.display = 'inline-flex';
  } else {
    receiveBtn.style.display = 'none';
  }
  
  if (po.status === 'ordered') {
    cancelBtn.style.display = 'inline-flex';
  } else {
    cancelBtn.style.display = 'none';
  }
}

function renderPOItems(items) {
  poItemsTable.innerHTML = '';
  
  if (!items || items.length === 0) {
    poItemsTable.innerHTML = '<tr><td colspan="5" class="empty-state">No items</td></tr>';
    return;
  }
  
  items.forEach(function(item) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.product ? item.product.name : 'N/A'}</td>
      <td>${item.quantity_ordered || 0}</td>
      <td>${item.quantity_received || 0}</td>
      <td>${formatCurrency(item.cost_price)}</td>
      <td>${formatCurrency((item.quantity_ordered || 0) * (item.cost_price || 0))}</td>
    `;
    poItemsTable.appendChild(tr);
  });
}

function renderReceiveForm(items) {
  receiveItems.innerHTML = '';
  
  let hasPendingItems = false;
  
  items.forEach(function(item) {
    const remaining = (item.quantity_ordered || 0) - (item.quantity_received || 0);
    
    if (remaining <= 0) return;
    
    hasPendingItems = true;
    
    const div = document.createElement('div');
    div.style.cssText = 'padding: 10px; border-bottom: 1px solid var(--border);';
    div.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <div style="flex: 1; min-width: 150px;">
          <strong>${item.product ? item.product.name : 'Product'}</strong>
          <div style="color: var(--text-muted); font-size: 12px;">Remaining: ${remaining}</div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
          <input type="number" 
                 id="receive-quantity-${item.id}" 
                 value="${remaining}" 
                 min="1" 
                 max="${remaining}"
                 style="width: 80px; padding: 6px; border: 1px solid var(--border); border-radius: 4px;">
          <input type="text" 
                 id="receive-batch-${item.id}" 
                 placeholder="Batch #"
                 style="width: 120px; padding: 6px; border: 1px solid var(--border); border-radius: 4px;">
          <input type="date" 
                 id="receive-expiry-${item.id}"
                 style="width: 150px; padding: 6px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
      </div>
    `;
    receiveItems.appendChild(div);
  });
  
  if (!hasPendingItems) {
    receiveItems.innerHTML = '<div class="empty-state">All items have been received</div>';
    receiveSubmitBtn.disabled = true;
  } else {
    receiveSubmitBtn.disabled = false;
  }
}

async function loadPODetails() {
  if (!Auth.requireAuth()) return;
  if (!poId) {
    window.location.href = '/staff/purchase-orders';
    return;
  }
  
  poLoading.style.display = 'block';
  poContent.style.display = 'none';
  poError.style.display = 'none';
  
  try {
    const po = await Api.get(`/staff/purchase-orders/${poId}`);
    
    renderPOInfo(po);
    renderPOItems(po.items || []);
    renderReceiveForm(po.items || []);
    
    poLoading.style.display = 'none';
    poContent.style.display = 'block';
  } catch (error) {
    poLoading.style.display = 'none';
    poError.textContent = error.message || 'Unable to load purchase order.';
    poError.style.display = 'block';
  }
}

receiveBtn.addEventListener('click', function() {
  receiveError.style.display = 'none';
  receiveModal.style.display = 'flex';
});

cancelBtn.addEventListener('click', async function() {
  if (!confirm('Are you sure you want to cancel this purchase order?')) return;
  
  try {
    await Api.post(`/staff/purchase-orders/${poId}/cancel`);
    loadPODetails();
  } catch (error) {
    alert(error.message || 'Unable to cancel purchase order.');
  }
});

closeReceiveBtn.addEventListener('click', function() {
  receiveModal.style.display = 'none';
});

cancelReceiveBtn.addEventListener('click', function() {
  receiveModal.style.display = 'none';
});

receiveModal.addEventListener('click', function(event) {
  if (event.target === receiveModal) {
    receiveModal.style.display = 'none';
  }
});

receiveForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  receiveSubmitBtn.disabled = true;
  receiveError.style.display = 'none';
  
  const items = [];
  const po = await Api.get(`/staff/purchase-orders/${poId}`);
  
  po.items.forEach(function(item) {
    const quantityInput = document.getElementById(`receive-quantity-${item.id}`);
    const batchInput = document.getElementById(`receive-batch-${item.id}`);
    const expiryInput = document.getElementById(`receive-expiry-${item.id}`);
    
    if (quantityInput && Number(quantityInput.value) > 0) {
      if (!batchInput || !batchInput.value.trim()) {
        receiveError.textContent = 'Please enter batch numbers for all items.';
        receiveError.style.display = 'block';
        receiveSubmitBtn.disabled = false;
        return;
      }
      
      if (!expiryInput || !expiryInput.value) {
        receiveError.textContent = 'Please enter expiry dates for all items.';
        receiveError.style.display = 'block';
        receiveSubmitBtn.disabled = false;
        return;
      }
      
      items.push({
        purchase_order_item_id: item.id,
        quantity_received: Number(quantityInput.value),
        batch_number: batchInput.value.trim(),
        expiry_date: expiryInput.value,
      });
    }
  });
  
  if (items.length === 0) {
    receiveError.textContent = 'Please enter quantities to receive.';
    receiveError.style.display = 'block';
    receiveSubmitBtn.disabled = false;
    return;
  }
  
  try {
    await Api.post(`/staff/purchase-orders/${poId}/receive`, { items });
    receiveModal.style.display = 'none';
    loadPODetails();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function(key) {
        if (Array.isArray(error.data.errors[key])) {
          messages.push(...error.data.errors[key]);
        } else {
          messages.push(error.data.errors[key]);
        }
      });
      receiveError.textContent = messages.join(', ');
    } else {
      receiveError.textContent = error.message || 'Unable to receive items.';
    }
    receiveError.style.display = 'block';
  } finally {
    receiveSubmitBtn.disabled = false;
  }
});

loadPODetails();