/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadPODetails() (GET /staff/purchase-orders/:id, same
    loading/content/error toggle logic), receiveBtn/cancelBtn visibility
    rules (style.display = 'inline-flex' / 'none', same status conditions:
    receive shows for ordered/partially_received, cancel shows for ordered
    only), cancelBtn's confirm() + POST .../cancel, the entire receive
    modal open/close logic, receiveForm's submit handler (same validation
    requiring batch number + expiry date per item, same POST .../receive
    payload shape, same 422 error handling).
  - UNCHANGED: the dynamic ID pattern for per-item receive inputs
    (receive-quantity-${id}, receive-batch-${id}, receive-expiry-${id}) —
    the submit handler still looks these up by the same IDs, so
    renderReceiveForm() still generates them exactly the same way.
  - CHANGED (presentation only): renderPOInfo(), renderPOItems(), and
    renderReceiveForm() now emit Tailwind markup instead of inline
    style="..." strings and raw <strong> labels.
*/

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

function humanizeStatus(status) {
  return String(status || '')
    .split('_')
    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
    .join(' ');
}

const STATUS_STYLE = {
  ordered:            { bg: '#FFF8EC', text: '#8A6116' },
  partially_received: { bg: '#FFF8EC', text: '#8A6116' },
  received:           { bg: '#E9F8EF', text: '#1F7A44' },
  cancelled:          { bg: '#FDEDEC', text: '#9C3A32' },
};
function badgeForStatus(status) {
  const s = STATUS_STYLE[status] || { bg: '#F1F3F6', text: '#4B5563' };
  return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${s.bg};color:${s.text}">${humanizeStatus(status)}</span>`;
}

function infoField(label, valueHtml) {
  return `<div>
    <p class="font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40 mb-1">${label}</p>
    <p class="font-inter text-[14px] text-[#171E26]">${valueHtml}</p>
  </div>`;
}

function renderPOInfo(po) {
  let fields =
    infoField('PO ID', `#${po.id}`) +
    infoField('Status', badgeForStatus(po.status)) +
    infoField('Supplier', po.supplier ? po.supplier.name : 'N/A') +
    infoField('Expected Date', formatDate(po.expected_date)) +
    infoField('Placed By', po.placed_by || 'N/A') +
    infoField('Created', formatDate(po.created_at));

  if (po.notes) {
    fields += infoField('Notes', po.notes);
  }

  poInfo.innerHTML = `<div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-5">${fields}</div>`;

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
  if (!items || items.length === 0) {
    poItemsTable.innerHTML = `<tr><td colspan="5" class="text-center py-14">
      <p class="font-inter text-[13px] text-[#171E26]/45">No items</p>
    </td></tr>`;
    return;
  }

  poItemsTable.innerHTML = items.map(function (item) {
    return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
      <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${item.product ? item.product.name : 'N/A'}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${item.quantity_ordered || 0}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${item.quantity_received || 0}</td>
      <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${formatCurrency(item.cost_price)}</td>
      <td class="py-3 font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency((item.quantity_ordered || 0) * (item.cost_price || 0))}</td>
    </tr>`;
  }).join('');
}

function renderReceiveForm(items) {
  let hasPendingItems = false;
  let html = '';

  items.forEach(function (item) {
    const remaining = (item.quantity_ordered || 0) - (item.quantity_received || 0);

    if (remaining <= 0) return;

    hasPendingItems = true;

    html += `<div class="py-3.5 border-b border-[#F3F7FC] last:border-0">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-[140px] flex-1">
          <p class="font-inter text-[13px] font-semibold text-[#171E26]">${item.product ? item.product.name : 'Product'}</p>
          <p class="font-inter text-[11px] text-[#171E26]/45">Remaining: ${remaining}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <input type="number" id="receive-quantity-${item.id}" value="${remaining}" min="1" max="${remaining}"
            class="w-[76px] field-input">
          <input type="text" id="receive-batch-${item.id}" placeholder="Batch #"
            class="w-[120px] field-input">
          <input type="date" id="receive-expiry-${item.id}"
            class="w-[150px] field-input">
        </div>
      </div>
    </div>`;
  });

  if (!hasPendingItems) {
    receiveItems.innerHTML = `<div class="text-center py-8">
      <i class="ph-light ph-check-circle text-2xl text-[#1F7A44]/60 mb-1.5"></i>
      <p class="font-inter text-[13px] text-[#171E26]/40">All items have been received</p>
    </div>`;
    receiveSubmitBtn.disabled = true;
  } else {
    receiveItems.innerHTML = html;
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

receiveBtn.addEventListener('click', function () {
  receiveError.style.display = 'none';
  receiveModal.style.display = 'flex';
});

cancelBtn.addEventListener('click', async function () {
  if (!confirm('Are you sure you want to cancel this purchase order?')) return;

  try {
    await Api.post(`/staff/purchase-orders/${poId}/cancel`);
    loadPODetails();
  } catch (error) {
    alert(error.message || 'Unable to cancel purchase order.');
  }
});

closeReceiveBtn.addEventListener('click', function () {
  receiveModal.style.display = 'none';
});

cancelReceiveBtn.addEventListener('click', function () {
  receiveModal.style.display = 'none';
});

receiveModal.addEventListener('click', function (event) {
  if (event.target === receiveModal) {
    receiveModal.style.display = 'none';
  }
});

receiveForm.addEventListener('submit', async function (event) {
  event.preventDefault();
  receiveSubmitBtn.disabled = true;
  receiveError.style.display = 'none';

  const items = [];
  const po = await Api.get(`/staff/purchase-orders/${poId}`);

  po.items.forEach(function (item) {
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
      Object.keys(error.data.errors).forEach(function (key) {
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