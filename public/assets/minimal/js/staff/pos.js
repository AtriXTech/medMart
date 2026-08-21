const posError = document.getElementById('pos-error');
const posContent = document.getElementById('pos-content');
const productSearchInput = document.getElementById('pos-product-search');
const productGrid = document.getElementById('pos-product-grid');
const cartItems = document.getElementById('pos-cart-items');
const customerNameInput = document.getElementById('pos-customer-name');
const paymentMethodSelect = document.getElementById('pos-payment-method');
const discountInput = document.getElementById('pos-discount');
const subtotalDisplay = document.getElementById('pos-subtotal');
const discountDisplay = document.getElementById('pos-discount-display');
const totalDisplay = document.getElementById('pos-total');
const checkoutBtn = document.getElementById('pos-checkout-btn');
const clearCartBtn = document.getElementById('pos-clear-cart-btn');
const receiptModal = document.getElementById('receipt-modal');
const receiptContent = document.getElementById('receipt-content');
const closeReceiptBtn = document.getElementById('close-receipt-btn');
const newSaleBtn = document.getElementById('new-sale-btn');

let cart = [];
let allProducts = [];
let searchTimeout = null;

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function updateTotals() {
  const subtotal = cart.reduce(function(sum, item) {
    return sum + (item.price * item.quantity);
  }, 0);

  const discount = Number(discountInput.value) || 0;
  const total = subtotal - discount;

  subtotalDisplay.textContent = formatCurrency(subtotal);
  discountDisplay.textContent = formatCurrency(discount);
  totalDisplay.textContent = formatCurrency(total);
}

function renderCart() {
  cartItems.innerHTML = '';

  if (cart.length === 0) {
    cartItems.innerHTML = `
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <i class="ph ph-shopping-cart-simple text-3xl text-[#171E26]/20"></i>
        <p class="font-inter text-sm text-[#171E26]/45 mt-2">Cart is empty</p>
      </div>
    `;
    updateTotals();
    return;
  }

  cart.forEach(function(item, index) {
    const div = document.createElement('div');
    div.className = 'flex items-center justify-between gap-3 py-3 border-b border-[#EAF1FB] last:border-0';
    div.innerHTML = `
      <div class="min-w-0 flex-1">
        <p class="font-inter text-[14px] font-semibold text-[#171E26] truncate">${item.name}</p>
        <p class="font-inter text-[12px] text-[#171E26]/45">${formatCurrency(item.price)} each</p>
      </div>
      <div class="flex items-center gap-1.5 flex-shrink-0">
        <button type="button" onclick="updateQuantity(${index}, -1)"
                class="h-7 w-7 flex items-center justify-center rounded-lg bg-[#F7FAFD] hover:bg-[#EAF1FB] text-[#171E26] font-semibold text-sm transition">−</button>
        <span class="font-inter text-[14px] font-medium text-[#171E26] w-5 text-center">${item.quantity}</span>
        <button type="button" onclick="updateQuantity(${index}, 1)"
                class="h-7 w-7 flex items-center justify-center rounded-lg bg-[#F7FAFD] hover:bg-[#EAF1FB] text-[#171E26] font-semibold text-sm transition">+</button>
      </div>
      <div class="flex items-center gap-2 flex-shrink-0">
        <strong class="font-inter text-[14px] font-semibold text-[#171E26] whitespace-nowrap">${formatCurrency(item.price * item.quantity)}</strong>
        <button type="button" onclick="removeFromCart(${index})" aria-label="Remove item"
                class="h-6 w-6 flex items-center justify-center rounded-md text-red-400 hover:bg-red-50 hover:text-red-500 transition">
          <i class="ph ph-x text-sm"></i>
        </button>
      </div>
    `;
    cartItems.appendChild(div);
  });

  updateTotals();
}

window.updateQuantity = function(index, change) {
  cart[index].quantity += change;

  if (cart[index].quantity <= 0) {
    cart.splice(index, 1);
  }

  renderCart();
};

window.removeFromCart = function(index) {
  cart.splice(index, 1);
  renderCart();
};

function renderProductGrid(products) {
  productGrid.innerHTML = '';

  if (!products || products.length === 0) {
    productGrid.innerHTML = `
      <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
        <i class="ph ph-package text-4xl text-[#171E26]/20"></i>
        <p class="font-inter text-sm text-[#171E26]/45 mt-3">No products found</p>
      </div>
    `;
    return;
  }

  products.forEach(function(product) {
    const inStock = product.stock_quantity > 0;

    const card = document.createElement('div');
    card.className = 'bg-white rounded-2xl border border-[#EAF1FB] overflow-hidden cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all';
    card.onclick = function() { addToCart(product); };

    const imageHtml = product.image_url
      ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-[130px] object-cover">`
      : `<div class="w-full h-[130px] bg-[#F7FAFD] flex items-center justify-center">
          <i class="ph ph-package text-3xl text-[#171E26]/20"></i>
        </div>`;

    card.innerHTML = `
      ${imageHtml}
      <div class="p-3">
        <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate">${product.name}</p>
        <div class="flex items-center justify-between mt-1.5">
          <span class="font-manrope text-[14px] font-bold text-[#2775E4]">${formatCurrency(product.price)}</span>
          <span class="font-inter text-[10px] font-semibold px-2 py-1 rounded-full ${inStock ? 'bg-[#DBEBFB] text-[#2775E4]' : 'bg-red-50 text-red-500'}">
            ${inStock ? 'In Stock' : 'Out'}
          </span>
        </div>
      </div>
    `;

    productGrid.appendChild(card);
  });
}

async function loadProducts() {
  try {
    const data = await Api.get('/staff/products?per_page=12&availability=1');
    allProducts = data.data || [];
    renderProductGrid(allProducts);
  } catch (error) {
    console.error('Unable to load products:', error);
    productGrid.innerHTML = `
      <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
        <i class="ph ph-warning-circle text-4xl text-red-300"></i>
        <p class="font-inter text-sm text-[#171E26]/45 mt-3">Unable to load products</p>
      </div>
    `;
  }
}

function searchProducts(query) {
  if (!query || query.length < 2) {
    renderProductGrid(allProducts);
    return;
  }

  const filtered = allProducts.filter(function(product) {
    return product.name.toLowerCase().includes(query.toLowerCase()) ||
           (product.barcode && product.barcode.includes(query));
  });

  renderProductGrid(filtered);
}

function addToCart(product) {
  if (product.stock_quantity <= 0) {
    alert('Product is out of stock');
    return;
  }

  const existingItem = cart.find(function(item) {
    return item.id === product.id;
  });

  if (existingItem) {
    if (existingItem.quantity >= product.stock_quantity) {
      alert('Not enough stock available');
      return;
    }
    existingItem.quantity += 1;
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      price: Number(product.price),
      quantity: 1
    });
  }

  renderCart();
}

async function processSale() {
  if (cart.length === 0) {
    alert('Cart is empty');
    return;
  }

  checkoutBtn.disabled = true;
  checkoutBtn.textContent = 'Processing...';
  posError.style.display = 'none';

  const saleData = {
    customer_name: customerNameInput.value.trim() || 'Walk-in Customer',
    payment_method: paymentMethodSelect.value,
    discount_total: Number(discountInput.value) || 0,
    items: cart.map(function(item) {
      return {
        product_id: item.id,
        quantity: item.quantity,
        unit_price: item.price
      };
    })
  };

  try {
    const result = await Api.post('/staff/sales', saleData);

    cart = [];
    customerNameInput.value = '';
    discountInput.value = '';
    renderCart();

    showReceipt(result);
    loadProducts();
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
      posError.textContent = messages.join(', ');
    } else {
      posError.textContent = error.message || 'Unable to process sale.';
    }
    posError.style.display = 'flex';
  } finally {
    checkoutBtn.disabled = false;
    checkoutBtn.textContent = 'Complete Sale';
  }
}

function showReceipt(sale) {
  const items = sale.items || [];

  let itemsHtml = '';
  items.forEach(function(item) {
    itemsHtml += `
      <tr class="border-b border-[#EAF1FB] last:border-0">
        <td class="py-2 font-inter text-[13px] text-[#171E26]">${item.product ? item.product.name : 'Product'}</td>
        <td class="py-2 font-inter text-[13px] text-[#171E26] text-center">${item.quantity}</td>
        <td class="py-2 font-inter text-[13px] text-[#171E26] text-right">${formatCurrency(item.unit_price)}</td>
        <td class="py-2 font-inter text-[13px] font-semibold text-[#171E26] text-right">${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
      </tr>
    `;
  });

  receiptContent.innerHTML = `
    <div class="text-center mb-5 pb-5 border-b border-dashed border-[#EAF1FB]">
      <h3 class="font-manrope font-extrabold text-[#171E26]">MedMart Pharmacy</h3>
      <p class="font-inter text-[13px] text-[#171E26]/60 mt-1">Receipt #${sale.id}</p>
      <p class="font-inter text-[13px] text-[#171E26]/60">Date: ${formatDate(sale.created_at)}</p>
      <p class="font-inter text-[13px] text-[#171E26]/60">Customer: ${sale.customer_name || 'Walk-in Customer'}</p>
      <p class="font-inter text-[13px] text-[#171E26]/60">Cashier: ${sale.cashier || 'N/A'}</p>
    </div>
    <table class="w-full">
      <thead>
        <tr class="border-b border-[#EAF1FB]">
          <th class="py-2 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Item</th>
          <th class="py-2 text-center font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Qty</th>
          <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Price</th>
          <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Total</th>
        </tr>
      </thead>
      <tbody>
        ${itemsHtml}
      </tbody>
    </table>
    <div class="mt-4 pt-4 border-t border-[#EAF1FB] space-y-1.5 text-right">
      <p class="font-inter text-[13px] text-[#171E26]/70">Subtotal: <strong class="text-[#171E26]">${formatCurrency(sale.subtotal)}</strong></p>
      <p class="font-inter text-[13px] text-[#171E26]/70">Discount: <strong class="text-[#171E26]">${formatCurrency(sale.discount_total)}</strong></p>
      <p class="font-manrope text-lg font-extrabold text-[#2775E4]">Total: ${formatCurrency(sale.total)}</p>
      <p class="font-inter text-[13px] text-[#171E26]/70">Payment Method: <strong class="text-[#171E26] capitalize">${sale.payment_method}</strong></p>
    </div>
  `;

  receiptModal.style.display = 'flex';
}

productSearchInput.addEventListener('input', function() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(function() {
    searchProducts(productSearchInput.value.trim());
  }, 300);
});

discountInput.addEventListener('input', updateTotals);
checkoutBtn.addEventListener('click', processSale);
clearCartBtn.addEventListener('click', function() {
  cart = [];
  renderCart();
});
closeReceiptBtn.addEventListener('click', function() {
  receiptModal.style.display = 'none';
});
newSaleBtn.addEventListener('click', function() {
  receiptModal.style.display = 'none';
});

renderCart();
loadProducts();