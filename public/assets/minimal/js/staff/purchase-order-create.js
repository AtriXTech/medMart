/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadSuppliers() (GET /staff/suppliers?per_page=100),
    searchProducts() (GET /staff/products?search=...&per_page=10),
    addItem() — including the prompt() dialog for cost price, submitOrder()
    (POST /staff/purchase-orders, same payload shape, same 422 error
    handling, same redirect to /staff/purchase-orders on success),
    window.updateItemQuantity / window.removeItem (still on window since
    rendered rows call them via inline onchange/onclick).
  - CHANGED (presentation only): renderItems() and the product search
    results list now emit Tailwind markup instead of inline style strings.
*/

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
  const total = poItemsList.reduce(function (sum, item) {
    return sum + (item.cost_price * item.quantity_ordered);
  }, 0);

  poTotalDisplay.textContent = formatCurrency(total);
}

function renderItems() {
  if (poItemsList.length === 0) {
    poItems.innerHTML = `<div class="text-center py-8">
      <i class="ph-light ph-package text-2xl text-[#171E26]/20 mb-1.5"></i>
      <p class="font-inter text-[13px] text-[#171E26]/40">No items added</p>
    </div>`;
    updateTotal();
    return;
  }

  poItems.innerHTML = poItemsList.map(function (item, index) {
    return `<div class="flex items-center justify-between gap-3 py-3 border-b border-[#F3F7FC] last:border-0">
      <div class="min-w-0 flex-1">
        <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate">${item.product_name}</p>
        <p class="font-inter text-[11px] text-[#171E26]/45">Cost: ${formatCurrency(item.cost_price)}</p>
      </div>
      <input type="number" value="${item.quantity_ordered}" min="1"
        onchange="updateItemQuantity(${index}, this.value)"
        class="w-[72px] flex-shrink-0 border border-[#DBEBFB] rounded-lg px-2 py-1.5 font-inter text-[13px] text-[#171E26] text-center focus:outline-none focus:border-[#2775E4]">
      <div class="flex items-center gap-2 flex-shrink-0 min-w-[100px] justify-end">
        <p class="font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(item.cost_price * item.quantity_ordered)}</p>
        <button onclick="removeItem(${index})" class="h-5 w-5 flex items-center justify-center rounded text-[#9C3A32] hover:bg-[#FDEDEC] text-[15px] leading-none">×</button>
      </div>
    </div>`;
  }).join('');

  updateTotal();
}

window.updateItemQuantity = function (index, quantity) {
  poItemsList[index].quantity_ordered = parseInt(quantity) || 1;
  renderItems();
};

window.removeItem = function (index) {
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
    .then(function (data) {
      const products = data.data || [];

      if (products.length === 0) {
        productResults.innerHTML = `<div class="px-4 py-6 text-center">
          <p class="font-inter text-[13px] text-[#171E26]/40">No products found</p>
        </div>`;
      } else {
        productResults.innerHTML = products.map(function (product) {
          return `<div data-product-id="${product.id}" class="po-search-result px-4 py-3 border-b border-[#F3F7FC] last:border-0 cursor-pointer hover:bg-[#F7FAFD]">
            <p class="font-inter text-[13px] font-semibold text-[#171E26]">${product.name}</p>
          </div>`;
        }).join('');

        productResults.querySelectorAll('.po-search-result').forEach(function (row) {
          row.addEventListener('click', function () {
            const id = Number(row.getAttribute('data-product-id'));
            const product = products.find(function (p) { return p.id === id; });
            if (product) addItem(product);
          });
        });
      }

      productResults.style.display = 'block';
    })
    .catch(function (error) {
      console.error('Search error:', error);
    });
}

function addItem(product) {
  const existingItem = poItemsList.find(function (item) {
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

    suppliers.forEach(function (supplier) {
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
    items: poItemsList.map(function (item) {
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
      Object.keys(error.data.errors).forEach(function (key) {
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

productSearchInput.addEventListener('input', function () {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(function () {
    searchProducts(productSearchInput.value.trim());
  }, 300);
});

poSubmitBtn.addEventListener('click', submitOrder);

loadSuppliers();
renderItems();