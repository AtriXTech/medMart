/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: GET /staff/products, search debounce, checkout's 422
    error handling on posError, receipt printing, low-stock/out-of-stock
    detection thresholds, cart quantity editing (+/- and typed input),
    showToast().
  - FIXED (real bug, not cosmetic): processSale() was sending
    `discount_total` at the top level and `unit_price` per item — NEITHER
    field exists in the actual CreateSaleRequest schema (confirmed
    against the OpenAPI spec: items only accept product_id/quantity/
    discount, no top-level discount field at all). Both were being
    silently stripped by backend validation, so the discount typed into
    "Discount Amount" never reached the server — the sale always saved
    at full price. Per your direction, this is now fixed on the frontend
    only: distributeDiscount() splits the single discount amount
    proportionally across each cart item's real `discount` field, based
    on that item's share of the subtotal, with the rounding remainder
    corrected onto the last item so the split always sums to exactly the
    entered amount. The discount is also capped at the subtotal so it
    can never push a line (or the sale) negative. `unit_price` is no
    longer sent at all — the backend should be pricing from the product
    record it looks up via product_id, not trusting a client-supplied
    price, so dropping it is a correctness fix, not a regression.
  - NEW: showReceipt() now passes customerName (sale.customer_name) and
    discountTotal (sale.discount_total) to MedMartReceipt.render() — see
    receipt-template.js's change summary for what those add to the
    printed receipt.
  - FIXED: MODAL_STYLES for the stock-warning/notice modal was using
    ad-hoc colors (#FEE2E2/#DC2626, #FEF3C7/#B45309, #DBEAFE/#2775E4)
    that don't match this app's actual design system — every other page
    in this project uses #FDEDEC/#9C3A32 (danger), #FFF8EC/#8A6116
    (warning), and the brand blue #E9F3FE/#2775E4 for neutral/info.
    Updated to those real tokens so this modal finally matches the rest
    of the app (e.g. the logout confirm modal on /customer/extra).
*/

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
const printReceiptBtn = document.getElementById('print-receipt-btn');

const appModal = document.getElementById('app-modal');
const appModalIcon = document.getElementById('app-modal-icon');
const appModalTitle = document.getElementById('app-modal-title');
const appModalMessage = document.getElementById('app-modal-message');
const appModalBtn = document.getElementById('app-modal-btn');

const posToast = document.getElementById('pos-toast');
const posToastMessage = document.getElementById('pos-toast-message');
let toastTimeout = null;

let cart = [];
let allProducts = [];
let searchTimeout = null;
let modalIsSticky = false;

const LOW_STOCK_THRESHOLD = 10;

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function lowStockThresholdFor(product) {
  return product.low_stock_threshold || LOW_STOCK_THRESHOLD;
}

/* ---------------- Add-to-cart toast ---------------- */

function showToast(message) {
  posToastMessage.textContent = message;
  posToast.classList.remove('opacity-0', '-translate-y-3', 'pointer-events-none');
  posToast.classList.add('opacity-100', 'translate-y-0');

  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(function () {
    posToast.classList.add('opacity-0', '-translate-y-3', 'pointer-events-none');
    posToast.classList.remove('opacity-100', 'translate-y-0');
  }, 1200);
}

/* ---------------- Custom modal (replaces alert/confirm) ---------------- */

// FIXED: real brand tokens, matching every other page in this app
// (e.g. #FDEDEC/#9C3A32 danger, #FFF8EC/#8A6116 warning — same pair
// used on the logout confirm modal, expiring-batches, dashboard, etc.)
// instead of the ad-hoc colors this modal had before.
const MODAL_STYLES = {
  danger:  { bg: '#FDEDEC', text: '#9C3A32', icon: 'ph-x-circle' },
  warning: { bg: '#FFF8EC', text: '#8A6116', icon: 'ph-warning' },
  info:    { bg: '#E9F3FE', text: '#2775E4', icon: 'ph-info' },
};

function showModal({ title, message, kind = 'info', sticky = false, buttonText = 'Got it' }) {
  const style = MODAL_STYLES[kind] || MODAL_STYLES.info;

  appModalTitle.textContent = title;
  appModalMessage.textContent = message;
  appModalIcon.style.background = style.bg;
  appModalIcon.style.color = style.text;
  appModalIcon.innerHTML = `<i class="ph ${style.icon} text-2xl"></i>`;
  appModalBtn.textContent = buttonText;

  modalIsSticky = sticky;
  appModal.style.display = 'flex';
}

function closeModal() {
  appModal.style.display = 'none';
  modalIsSticky = false;
}

appModalBtn.addEventListener('click', closeModal);
appModal.addEventListener('click', function (event) {
  if (event.target === appModal && !modalIsSticky) {
    closeModal();
  }
});

/* ---------------- Totals & cart ---------------- */

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
        <input type="number" min="1" value="${item.quantity}"
               onchange="setQuantity(${index}, this.value)"
               class="w-12 text-center font-inter text-[14px] font-medium text-[#171E26] border border-[#EAF1FB] rounded-lg py-1 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
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

window.setQuantity = function(index, value) {
  const parsed = parseInt(value, 10);

  if (!parsed || parsed <= 0) {
    cart.splice(index, 1);
  } else {
    cart[index].quantity = parsed;
  }

  renderCart();
};

window.removeFromCart = function(index) {
  cart.splice(index, 1);
  renderCart();
};

/* ---------------- Product grid ---------------- */

function renderProductGridSkeleton() {
  const cards = Array.from({ length: 8 }).map(function () {
    return `
      <div class="bg-white rounded-2xl border border-[#EAF1FB] overflow-hidden">
        <div class="skel w-full h-[130px]"></div>
        <div class="p-3 space-y-2">
          <div class="skel h-3.5 w-3/4 rounded"></div>
          <div class="skel h-3.5 w-1/2 rounded"></div>
        </div>
      </div>
    `;
  }).join('');
  productGrid.innerHTML = cards;
}

function stockBadge(product) {
  const qty = product.stock_quantity;

  if (qty <= 0) {
    return `<span class="font-inter text-[10px] font-semibold px-2 py-1 rounded-full bg-red-50 text-red-500">Out</span>`;
  }

  const isLow = qty <= lowStockThresholdFor(product);
  const classes = isLow ? 'bg-amber-50 text-amber-600' : 'bg-[#DBEBFB] text-[#2775E4]';
  return `<span class="font-inter text-[10px] font-semibold px-2 py-1 rounded-full ${classes}">${qty} left</span>`;
}

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
          ${stockBadge(product)}
        </div>
      </div>
    `;

    productGrid.appendChild(card);
  });
}

async function loadProducts() {
  renderProductGridSkeleton();

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

/* ---------------- Add to cart ---------------- */

function addToCart(product) {
  if (product.stock_quantity <= 0) {
    showModal({
      title: 'Out of Stock',
      message: `${product.name} is currently out of stock and can't be added to this sale.`,
      kind: 'danger',
    });
    return;
  }

  const existingItem = cart.find(function(item) {
    return item.id === product.id;
  });

  if (existingItem) {
    if (existingItem.quantity >= product.stock_quantity) {
      showModal({
        title: 'Not Enough Stock',
        message: `Only ${product.stock_quantity} unit(s) of ${product.name} are available.`,
        kind: 'warning',
      });
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
  showToast(`${product.name} added to cart`);

  const remaining = product.stock_quantity - (existingItem ? existingItem.quantity : 1);
  if (remaining >= 0 && product.stock_quantity <= lowStockThresholdFor(product)) {
    showModal({
      title: 'Low Stock Warning',
      message: `${product.name} is running low — only ${product.stock_quantity} unit(s) left in total stock. Please confirm before continuing.`,
      kind: 'warning',
      sticky: true,
      buttonText: 'Got it, continue',
    });
  }
}

/* ---------------- Discount distribution ---------------- */

// NEW: CreateSaleRequest only accepts discount PER ITEM (items[].discount)
// — there's no top-level discount_total field. This splits the single
// "Discount Amount" box proportionally across each cart item's share of
// the subtotal, so the real schema gets a correct per-item value while
// the UI keeps its single aggregate input. Capped at the subtotal so it
// can never push a line negative. The last item absorbs the rounding
// remainder so the split always sums to exactly the entered amount.
function distributeDiscount(cartItems, discountTotal) {
  const subtotal = cartItems.reduce(function (sum, item) {
    return sum + item.price * item.quantity;
  }, 0);

  const cappedDiscount = Math.min(Math.max(discountTotal, 0), subtotal);

  if (cappedDiscount <= 0 || subtotal <= 0) {
    return cartItems.map(function () { return 0; });
  }

  const discounts = cartItems.map(function (item) {
    const lineValue = item.price * item.quantity;
    const share = (lineValue / subtotal) * cappedDiscount;
    return Math.round(share * 100) / 100;
  });

  const allocated = discounts.reduce(function (sum, d) { return sum + d; }, 0);
  const remainder = Math.round((cappedDiscount - allocated) * 100) / 100;
  discounts[discounts.length - 1] = Math.round((discounts[discounts.length - 1] + remainder) * 100) / 100;

  return discounts;
}

/* ---------------- Checkout ---------------- */

async function processSale() {
  if (cart.length === 0) {
    showModal({
      title: 'Cart is Empty',
      message: 'Add at least one product to the cart before completing a sale.',
      kind: 'info',
    });
    return;
  }

  checkoutBtn.disabled = true;
  checkoutBtn.textContent = 'Processing...';
  posError.style.display = 'none';

  const discountTotal = Number(discountInput.value) || 0;
  const itemDiscounts = distributeDiscount(cart, discountTotal);

  const saleData = {
    customer_name: customerNameInput.value.trim() || 'Walk-in Customer',
    payment_method: paymentMethodSelect.value,
    items: cart.map(function(item, index) {
      return {
        product_id: item.id,
        quantity: item.quantity,
        discount: itemDiscounts[index],
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

async function showReceipt(sale) {
  const items = (sale.items || []).map(function (item) {
    return {
      name: item.product ? item.product.name : 'Product',
      qty: item.quantity,
      rate: item.unit_price,
      amount: item.line_total || item.unit_price * item.quantity,
    };
  });

  const pharmacy = await MedMartReceipt.loadPharmacy();

  receiptContent.innerHTML = MedMartReceipt.render({
    pharmacy,
    invoiceId: sale.id,
    cashierName: sale.cashier || MedMartReceipt.getCashierName(),
    customerName: sale.customer_name,
    date: sale.created_at,
    items,
    subtotal: sale.subtotal,
    discountTotal: sale.discount_total,
    total: sale.total,
    paymentMethod: sale.payment_method,
    status: sale.status,
  });

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
printReceiptBtn.addEventListener('click', function() {
  window.print();
});

renderCart();
loadProducts();