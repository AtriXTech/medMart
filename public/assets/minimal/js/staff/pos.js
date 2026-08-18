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
    cartItems.innerHTML = '<div class="empty-state">Cart is empty</div>';
    return;
  }
  
  cart.forEach(function(item, index) {
    const div = document.createElement('div');
    div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid var(--border);';
    div.innerHTML = `
      <div style="flex: 1;">
        <strong>${item.name}</strong>
        <div style="color: var(--text-muted); font-size: 12px;">${formatCurrency(item.price)} each</div>
      </div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <button class="btn btn-secondary" onclick="updateQuantity(${index}, -1)" style="padding: 4px 8px;">-</button>
        <span>${item.quantity}</span>
        <button class="btn btn-secondary" onclick="updateQuantity(${index}, 1)" style="padding: 4px 8px;">+</button>
      </div>
      <div style="min-width: 100px; text-align: right;">
        <strong>${formatCurrency(item.price * item.quantity)}</strong>
        <button class="btn btn-danger" onclick="removeFromCart(${index})" style="padding: 2px 6px; margin-left: 8px;">×</button>
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
    productGrid.innerHTML = '<div class="empty-state">No products found</div>';
    return;
  }
  
  products.forEach(function(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.style.cssText = 'background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; cursor: pointer; transition: transform 0.2s;';
    card.onmouseover = function() { card.style.transform = 'translateY(-2px)'; };
    card.onmouseout = function() { card.style.transform = 'translateY(0)'; };
    card.onclick = function() { addToCart(product); };
    
    const imageHtml = product.image_url 
      ? `<img src="${product.image_url}" alt="${product.name}" style="width: 100%; height: 150px; object-fit: cover;">`
      : `<div style="width: 100%; height: 150px; background: var(--bg); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
          <span style="font-size: 48px;">📦</span>
        </div>`;
    
    card.innerHTML = `
      ${imageHtml}
      <div style="padding: 12px;">
        <div style="font-weight: 600; margin-bottom: 4px;">${product.name}</div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span style="color: var(--primary); font-weight: 600;">${formatCurrency(product.price)}</span>
          <span class="badge ${product.stock_quantity > 0 ? 'badge-success' : 'badge-danger'}">
            ${product.stock_quantity > 0 ? 'In Stock' : 'Out'}
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
    productGrid.innerHTML = '<div class="empty-state">Unable to load products</div>';
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
    posError.style.display = 'block';
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
      <tr>
        <td>${item.product ? item.product.name : 'Product'}</td>
        <td>${item.quantity}</td>
        <td>${formatCurrency(item.unit_price)}</td>
        <td>${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
      </tr>
    `;
  });
  
  receiptContent.innerHTML = `
    <div style="text-align: center; margin-bottom: 20px;">
      <h3>MedMart Pharmacy</h3>
      <p style="margin: 4px 0;">Receipt #${sale.id}</p>
      <p style="margin: 4px 0;">Date: ${formatDate(sale.created_at)}</p>
      <p style="margin: 4px 0;">Customer: ${sale.customer_name || 'Walk-in Customer'}</p>
      <p style="margin: 4px 0;">Cashier: ${sale.cashier || 'N/A'}</p>
    </div>
    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        ${itemsHtml}
      </tbody>
    </table>
    <div style="margin-top: 20px; text-align: right;">
      <p><strong>Subtotal:</strong> ${formatCurrency(sale.subtotal)}</p>
      <p><strong>Discount:</strong> ${formatCurrency(sale.discount_total)}</p>
      <p style="font-size: 18px;"><strong>Total:</strong> ${formatCurrency(sale.total)}</p>
      <p><strong>Payment Method:</strong> ${sale.payment_method}</p>
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