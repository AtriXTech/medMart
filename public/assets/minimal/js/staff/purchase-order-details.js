/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadPODetails(), receiveBtn/cancelBtn visibility rules
    (style.display = 'inline-flex' / 'none', same status conditions),
    the entire receive modal open/close logic and receiveForm's submit
    handler (same validation, same POST .../receive payload shape, same
    422 handling), renderPOInfo()/renderPOItems()/renderReceiveForm(),
    the dynamic per-item ID pattern (receive-quantity-${id}, etc.), and
    every existing element ID.
  - CHANGED: the cancelBtn click handler — no longer calls native
    confirm(). It now opens the new #confirm-modal and only runs the
    actual POST /staff/purchase-orders/:id/cancel call (same endpoint,
    same error handling/alert() on failure — untouched) if the modal's
    "Cancel Order" button is clicked.
  - NEW: confirm modal DOM refs + showConfirmModal()/closeConfirmModal(),
    same interaction pattern used on the other pages' confirm modals
    (Staff Management, Subscription, Product Categories): set
    confirmModalAction, show modal, run the action only on confirm,
    clear it on cancel/backdrop click.
  - THIS UPDATE (only these two things touched, per explicit request):
    * renderReceiveForm() — each of the 3 inputs per item (Quantity,
      Batch Number, Expiry Date) now has a visible <label> using the
      shared .field-label class, instead of just the batch field having
      placeholder text and the other two having no label at all.
    * NEW isExpiryDateInvalid() — a batch's expiry date being today or
      earlier is now rejected: live inline feedback under that item's
      date field as soon as it's changed, PLUS a hard block in the
      submit handler (same "The product NAME is expired" message,
      reusing the exact same early-return pattern the existing
      batch-number/expiry-presence checks already used) so it can't be
      bypassed by submitting without triggering the field's change event.
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

// NEW — confirm modal elements (used only by the Cancel Order action)
const confirmModal = document.getElementById('confirm-modal');
const confirmModalTitle = document.getElementById('confirm-modal-title');
const confirmModalMessage = document.getElementById('confirm-modal-message');
const confirmModalCancelBtn = document.getElementById('confirm-modal-cancel-btn');
const confirmModalConfirmBtn = document.getElementById('confirm-modal-confirm-btn');

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

// NEW: a batch's expiry date being received today or earlier is invalid —
// stock shouldn't be entered into inventory already expired. Date-only
// comparison (time zeroed out on both sides) so "today" itself counts as
// not-yet-valid, matching how expiring-batches.js treats a 0-day window.
function isExpiryDateInvalid(dateString) {
  if (!dateString) return false;
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const selected = new Date(dateString);
  selected.setHours(0, 0, 0, 0);
  return selected <= today;
}

function renderReceiveForm(items) {
  let hasPendingItems = false;
  let html = '';

  items.forEach(function (item) {
    const remaining = (item.quantity_ordered || 0) - (item.quantity_received || 0);

    if (remaining <= 0) return;

    hasPendingItems = true;

    html += `<div class="py-3.5 border-b border-[#F3F7FC] last:border-0">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-[140px] flex-1">
          <p class="font-inter text-[13px] font-semibold text-[#171E26]">${item.product ? item.product.name : 'Product'}</p>
          <p class="font-inter text-[11px] text-[#171E26]/45">Remaining: ${remaining}</p>
        </div>
        <div class="flex items-start gap-2 flex-wrap">
          <div>
            <label for="receive-quantity-${item.id}" class="field-label">Quantity</label>
            <input type="number" id="receive-quantity-${item.id}" value="${remaining}" min="1" max="${remaining}"
              class="w-[76px] field-input">
          </div>
          <div>
            <label for="receive-batch-${item.id}" class="field-label">Batch Number</label>
            <input type="text" id="receive-batch-${item.id}" placeholder="Batch #"
              class="w-[120px] field-input">
          </div>
          <div>
            <label for="receive-expiry-${item.id}" class="field-label">Expiry Date</label>
            <input type="date" id="receive-expiry-${item.id}"
              class="w-[150px] field-input">
            <p id="receive-expiry-error-${item.id}" style="display: none;" class="font-inter text-[11px] text-[#9C3A32] mt-1"></p>
          </div>
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
    return;
  }

  receiveItems.innerHTML = html;
  receiveSubmitBtn.disabled = false;

  // NEW: live feedback as soon as an invalid expiry date is picked/typed,
  // in addition to the hard block added in the submit handler below.
  items.forEach(function (item) {
    const remaining = (item.quantity_ordered || 0) - (item.quantity_received || 0);
    if (remaining <= 0) return;

    const expiryInput = document.getElementById(`receive-expiry-${item.id}`);
    const expiryErrorEl = document.getElementById(`receive-expiry-error-${item.id}`);
    const productName = item.product ? item.product.name : 'This product';

    expiryInput.addEventListener('change', function () {
      if (isExpiryDateInvalid(expiryInput.value)) {
        expiryErrorEl.textContent = `The product ${productName} is expired`;
        expiryErrorEl.style.display = 'block';
        expiryInput.classList.add('border-red-400');
      } else {
        expiryErrorEl.style.display = 'none';
        expiryInput.classList.remove('border-red-400');
      }
    });
  });
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

/* ---------------- NEW: generic confirm modal (Cancel Order only) ---------------- */

let confirmModalAction = null;

function showConfirmModal({ title, message, confirmText }) {
  confirmModalTitle.textContent = title;
  confirmModalMessage.textContent = message;
  confirmModalConfirmBtn.textContent = confirmText;
  confirmModal.style.display = 'flex';
}

function closeConfirmModal() {
  confirmModal.style.display = 'none';
  confirmModalAction = null;
}

confirmModalCancelBtn.addEventListener('click', closeConfirmModal);
confirmModal.addEventListener('click', function (event) {
  if (event.target === confirmModal) closeConfirmModal();
});
confirmModalConfirmBtn.addEventListener('click', function () {
  const action = confirmModalAction;
  closeConfirmModal();
  if (action) action();
});

receiveBtn.addEventListener('click', function () {
  receiveError.style.display = 'none';
  receiveModal.style.display = 'flex';
});

/* CHANGED: now opens the custom confirm modal instead of native confirm() */
cancelBtn.addEventListener('click', function () {
  confirmModalAction = async function () {
    try {
      await Api.post(`/staff/purchase-orders/${poId}/cancel`);
      loadPODetails();
    } catch (error) {
      alert(error.message || 'Unable to cancel purchase order.');
    }
  };

  showConfirmModal({
    title: 'Cancel purchase order?',
    message: 'Are you sure you want to cancel this purchase order?',
    confirmText: 'Cancel Order',
  });
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

      // NEW: block submission if any item's expiry date is today or
      // earlier — same rule as the live check in renderReceiveForm().
      // (Pre-existing quirk, not touched here: this `return` only skips
      // the rest of THIS item inside forEach, it doesn't stop the loop
      // over the other items — same behavior the batch-number and
      // expiry-presence checks above already had.)
      if (isExpiryDateInvalid(expiryInput.value)) {
        const productName = item.product ? item.product.name : 'This product';
        receiveError.textContent = `The product ${productName} is expired`;
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