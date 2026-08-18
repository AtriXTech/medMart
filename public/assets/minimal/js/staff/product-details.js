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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <div>
        <strong>Name:</strong> ${product.name}
      </div>
      <div>
        <strong>Generic Name:</strong> ${product.generic_name || 'N/A'}
      </div>
      <div>
        <strong>Category:</strong> ${product.category ? product.category.name : 'N/A'}
      </div>
      <div>
        <strong>Price:</strong> ${formatCurrency(product.price)}
      </div>
      <div>
        <strong>Stock:</strong> ${product.stock_quantity || 0}
      </div>
      <div>
        <strong>Barcode:</strong> ${product.barcode || 'N/A'}
      </div>
      <div>
        <strong>Requires Prescription:</strong> ${product.requires_prescription ? 'Yes' : 'No'}
      </div>
      <div>
        <strong>Status:</strong> 
        <span class="badge ${product.is_available ? 'badge-success' : 'badge-danger'}">
          ${product.is_available ? 'Available' : 'Unavailable'}
        </span>
      </div>
    </div>
  `;
}

function renderBatches(batches) {
  batchesTableBody.innerHTML = '';
  
  if (!batches || batches.length === 0) {
    batchesTableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No batches found</td></tr>';
    return;
  }
  
  batches.forEach(function(batch) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${batch.batch_number}</td>
      <td>${batch.quantity}</td>
      <td>${formatCurrency(batch.cost_price)}</td>
      <td>${formatDate(batch.expiry_date)}</td>
      <td>
        <button class="btn btn-secondary" onclick="adjustBatch(${batch.id}, ${batch.quantity})">Adjust</button>
      </td>
    `;
    batchesTableBody.appendChild(tr);
  });
}

function renderMovements(movements) {
  movementsTableBody.innerHTML = '';
  
  if (!movements || movements.length === 0) {
    movementsTableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No stock movements</td></tr>';
    return;
  }
  
  movements.forEach(function(movement) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${movement.type}</td>
      <td>${movement.quantity}</td>
      <td>${movement.reason || 'N/A'}</td>
      <td>${movement.staff || 'N/A'}</td>
      <td>${formatDate(movement.created_at)}</td>
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
    productError.style.display = 'block';
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
    batchFormError.style.display = 'block';
  } finally {
    batchSubmitBtn.disabled = false;
  }
});

loadProductDetails();