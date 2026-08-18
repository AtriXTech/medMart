const poError = document.getElementById('po-error');
const poContent = document.getElementById('po-content');
const supplierSelect = document.getElementById('po-supplier');
const expectedDateInput = document.getElementById('po-expected-date');
const notesInput = document.getElementById('po-notes');
const productSearchInput = document.getElementById('po-product-search');
const productResults = document.getElementById('po-product-results');
const poItems = document.getElementById('po-items');
const poSubmitBtn = document.getElementById('po-submit-btn');
const poTotalDisplay = document.getElementById('po-total');

let poItemsList = [];
let searchTimeout = null;

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function updateTotal() {
  const total = poItemsList.reduce(function(sum, item) {
    return sum + (item.cost_price * item.quantity_ordered);
  }, 0);
  
  poTotalDisplay.textContent = formatCurrency(total);
}

function renderItems() {
  poItems.innerHTML = '';
  
  if (poItemsList.length === 0) {
    poItems.innerHTML = '<div class="empty-state">No items added</div>';
    return;
  }
  
  poItemsList.forEach(function(item, index) {
    const div = document.createElement('div');
    div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid var(--border);';
    div.innerHTML = `
      <div style="flex: 1;">
        <strong>${item.product_name}</strong>
        <div style="color: var(--text-muted); font-size: 12px;">Cost: ${formatCurrency(item.cost_price)}</div>
      </div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <input type="number" value="${item.quantity_ordered}" min="1" 
               onchange="updateItemQuantity(${index}, this.value)" style="width: 80px;">
      </div>
      <div style="min-width: 100px; text-align: right;">
        <strong>${formatCurrency(item.cost_price * item.quantity_ordered)}</strong>
        <button class="btn btn-danger" onclick="removeItem(${index})" style="padding: 2px 6px; margin-left: 8px;">×</button>
      </div>
    `;
    poItems.appendChild(div);
  });
  
  updateTotal();
}

window.updateItemQuantity = function(index, quantity) {
  poItemsList[index].quantity_ordered = parseInt(quantity) || 1;
  renderItems();
};

window.removeItem = function(index) {
  poItemsList.splice(index, 1);
  renderItems();
};

function searchProducts(query) {
  if (!query || query.length < 2) {
    productResults.innerHTML = '';
    productResults.style.display = 'none';
    return;
  }
  
  Api.get(`/staff/products?search=${encodeURIComponent(query)}&per_page=10`)
    .then(function(data) {
      const products = data.data || [];
      
      if (products.length === 0) {
        productResults.innerHTML = '<div class="empty-state">No products found</div>';
      } else {
        productResults.innerHTML = '';
        products.forEach(function(product) {
          const div = document.createElement('div');
          div.style.cssText = 'padding: 10px; border-bottom: 1px solid var(--border); cursor: pointer;';
          div.innerHTML = `<strong>${product.name}</strong>`;
          div.onclick = function() {
            addItem(product);
          };
          productResults.appendChild(div);
        });
      }
      
      productResults.style.display = 'block';
    })
    .catch(function(error) {
      console.error('Search error:', error);
    });
}

function addItem(product) {
  const existingItem = poItemsList.find(function(item) {
    return item.product_id === product.id;
  });
  
  if (existingItem) {
    alert('Product already added to order');
    return;
  }
  
  const costPrice = prompt(`Enter cost price for ${product.name}:`, '0');
  if (!costPrice) return;
  
  poItemsList.push({
    product_id: product.id,
    product_name: product.name,
    quantity_ordered: 1,
    cost_price: Number(costPrice)
  });
  
  productResults.innerHTML = '';
  productResults.style.display = 'none';
  productSearchInput.value = '';
  renderItems();
}

async function loadSuppliers() {
  try {
    const data = await Api.get('/staff/suppliers?per_page=100');
    const suppliers = data.data || data;
    
    suppliers.forEach(function(supplier) {
      const option = document.createElement('option');
      option.value = supplier.id;
      option.textContent = supplier.name;
      supplierSelect.appendChild(option);
    });
  } catch (error) {
    console.error('Unable to load suppliers:', error);
  }
}

async function submitOrder() {
  if (!supplierSelect.value) {
    alert('Please select a supplier');
    return;
  }
  
  if (poItemsList.length === 0) {
    alert('Please add at least one item');
    return;
  }
  
  poSubmitBtn.disabled = true;
  poSubmitBtn.textContent = 'Creating...';
  poError.style.display = 'none';
  
  const orderData = {
    supplier_id: parseInt(supplierSelect.value),
    expected_date: expectedDateInput.value || null,
    notes: notesInput.value.trim(),
    items: poItemsList.map(function(item) {
      return {
        product_id: item.product_id,
        quantity_ordered: item.quantity_ordered,
        cost_price: item.cost_price
      };
    })
  };
  
  try {
    await Api.post('/staff/purchase-orders', orderData);
    window.location.href = '/staff/purchase-orders';
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
      poError.textContent = messages.join(', ');
    } else {
      poError.textContent = error.message || 'Unable to create purchase order.';
    }
    poError.style.display = 'block';
  } finally {
    poSubmitBtn.disabled = false;
    poSubmitBtn.textContent = 'Create Purchase Order';
  }
}

productSearchInput.addEventListener('input', function() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(function() {
    searchProducts(productSearchInput.value.trim());
  }, 300);
});

poSubmitBtn.addEventListener('click', submitOrder);

loadSuppliers();
renderItems();