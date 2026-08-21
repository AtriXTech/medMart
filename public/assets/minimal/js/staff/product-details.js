const productError = document.getElementById('product-error');
const productLoading = document.getElementById('product-loading');
const productContent = document.getElementById('product-content');
const productInfo = document.getElementById('product-info');
const batchesTableBody = document.getElementById('batches-table-body');
const movementsTableBody = document.getElementById('movements-table-body');
const addBatchBtn = document.getElementById('add-batch-btn');
const batchModal = document.getElementById('batch-modal');
const batchForm = document.getElementById('batch-form');
const batchNumberInput = document.getElementById('batch-number');
const batchExpiryInput = document.getElementById('batch-expiry');
const batchQuantityInput = document.getElementById('batch-quantity');
const batchCostInput = document.getElementById('batch-cost');
const batchFormError = document.getElementById('batch-form-error');
const batchSubmitBtn = document.getElementById('batch-submit-btn');
const closeBatchModalBtn = document.getElementById('close-batch-modal-btn');
const cancelBatchModalBtn = document.getElementById('cancel-batch-modal-btn');

const productId = new URLSearchParams(window.location.search).get('id');

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

function renderProductInfo(product) {
  productInfo.innerHTML = `
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4">
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Name</p>
        <p class="font-inter text-[14px] font-semibold text-[#171E26]">${product.name}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Generic Name</p>
        <p class="font-inter text-[14px] text-[#171E26]">${product.generic_name || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Category</p>
        <p class="font-inter text-[14px] text-[#171E26]">${product.category ? product.category.name : 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Price</p>
        <p class="font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(product.price)}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Stock</p>
        <p class="font-inter text-[14px] text-[#171E26]">${product.stock_quantity || 0}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Barcode</p>
        <p class="font-inter text-[14px] text-[#171E26]">${product.barcode || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Requires Prescription</p>
        <p class="font-inter text-[14px] text-[#171E26]">${product.requires_prescription ? 'Yes' : 'No'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Status</p>
        <span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full ${product.is_available ? 'bg-[#DBEBFB] text-[#2775E4]' : 'bg-red-50 text-red-500'}">
          ${product.is_available ? 'Available' : 'Unavailable'}
        </span>
      </div>
    </div>
  `;
}

function renderBatches(batches) {
  batchesTableBody.innerHTML = '';

  if (!batches || batches.length === 0) {
    batchesTableBody.innerHTML = `
      <tr>
        <td colspan="5" class="py-12 text-center">
          <i class="ph ph-stack text-3xl text-[#171E26]/20"></i>
          <p class="font-inter text-sm text-[#171E26]/45 mt-2">No batches found</p>
        </td>
      </tr>
    `;
    return;
  }

  batches.forEach(function(batch) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition';
    tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26]">${batch.batch_number}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]">${batch.quantity}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]">${formatCurrency(batch.cost_price)}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${formatDate(batch.expiry_date)}</td>
      <td class="py-3 px-3">
        <button type="button" onclick="adjustBatch(${batch.id}, ${batch.quantity})"
                class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
          Adjust
        </button>
      </td>
    `;
    batchesTableBody.appendChild(tr);
  });
}

function renderMovements(movements) {
  movementsTableBody.innerHTML = '';

  if (!movements || movements.length === 0) {
    movementsTableBody.innerHTML = `
      <tr>
        <td colspan="5" class="py-12 text-center">
          <i class="ph ph-clock-counter-clockwise text-3xl text-[#171E26]/20"></i>
          <p class="font-inter text-sm text-[#171E26]/45 mt-2">No stock movements</p>
        </td>
      </tr>
    `;
    return;
  }

  movements.forEach(function(movement) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition';
    tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26] capitalize">${movement.type}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]">${movement.quantity}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${movement.reason || 'N/A'}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${movement.staff || 'N/A'}</td>
      <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60 whitespace-nowrap">${formatDate(movement.created_at)}</td>
    `;
    movementsTableBody.appendChild(tr);
  });
}

async function loadProductDetails() {
  if (!Auth.requireAuth()) return;
  if (!productId) {
    window.location.href = '/staff/products';
    return;
  }

  productLoading.style.display = 'block';
  productContent.style.display = 'none';
  productError.style.display = 'none';

  try {
    const product = await Api.get(`/staff/products/${productId}`);
    const batchesData = await Api.get(`/staff/products/${productId}/batches?per_page=100`);
    const movementsData = await Api.get(`/staff/products/${productId}/stock-movements?per_page=100`);

    renderProductInfo(product);
    renderBatches(batchesData.data || batchesData);
    renderMovements(movementsData.data || movementsData);

    productLoading.style.display = 'none';
    productContent.style.display = 'block';
  } catch (error) {
    productLoading.style.display = 'none';
    productError.textContent = error.message || 'Unable to load product details.';
    productError.style.display = 'flex';
  }
}

window.adjustBatch = function(id, currentQuantity) {
  const newQuantity = prompt('Enter new quantity:', currentQuantity);
  if (!newQuantity || newQuantity === currentQuantity) return;

  const reason = prompt('Reason for adjustment:', 'Manual adjustment');

  Api.patch(`/staff/products/${productId}/batches/${id}`, {
    quantity: parseInt(newQuantity),
    reason: reason || 'Manual adjustment'
  })
    .then(function() {
      loadProductDetails();
    })
    .catch(function(error) {
      alert(error.message || 'Unable to adjust batch.');
    });
};

addBatchBtn.addEventListener('click', function() {
  batchForm.reset();
  batchFormError.style.display = 'none';
  batchModal.style.display = 'flex';
});

closeBatchModalBtn.addEventListener('click', function() {
  batchModal.style.display = 'none';
});

cancelBatchModalBtn.addEventListener('click', function() {
  batchModal.style.display = 'none';
});

batchForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  batchSubmitBtn.disabled = true;
  batchFormError.style.display = 'none';

  const formData = {
    batch_number: batchNumberInput.value.trim(),
    expiry_date: batchExpiryInput.value,
    quantity: parseInt(batchQuantityInput.value),
    cost_price: Number(batchCostInput.value)
  };

  try {
    await Api.post(`/staff/products/${productId}/batches`, formData);
    batchModal.style.display = 'none';
    loadProductDetails();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function(key) {
        messages.push(...error.data.errors[key]);
      });
      batchFormError.textContent = messages.join(', ');
    } else {
      batchFormError.textContent = error.message || 'Unable to add batch.';
    }
    batchFormError.style.display = 'flex';
  } finally {
    batchSubmitBtn.disabled = false;
  }
});

loadProductDetails();