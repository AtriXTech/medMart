/**
 * public/assets/minimal/js/customer/cart.js
 * Customer Shopping Cart Controller
 *
 * CHANGE SUMMARY (vs. previous version):
 * - UNCHANGED: everything else — loadCart(), the cart-updated dispatch,
 *   updateQuantity(), removeItem(), the alert/confirm modals, every
 *   endpoint, every ID.
 * - FIXED: clearCart() used to try `DELETE /customer/cart` first and
 *   only fall back to per-item deletes if that failed. That bulk-clear
 *   endpoint doesn't exist anywhere in the API — only
 *   DELETE /customer/cart/items/{cartItem} does — so the first attempt
 *   was guaranteed to fail on every single clear, wasting a request
 *   (and likely logging a spurious error) before falling through to the
 *   per-item loop that actually works. Now goes straight to the
 *   per-item deletes.
 */

const cartError = document.getElementById('cart-error');
const cartSkeleton = document.getElementById('cart-skeleton') || document.getElementById('cart-loading');
const cartContent = document.getElementById('cart-content');

// Page-local alert modal elements
const alertModal = document.getElementById('alert-modal');
const alertModalTitle = document.getElementById('alert-modal-title');
const alertModalMessage = document.getElementById('alert-modal-message');
const alertModalOkBtn = document.getElementById('alert-modal-ok-btn');

// Page-local confirm modal elements
const confirmModal = document.getElementById('confirm-modal');
const confirmModalTitle = document.getElementById('confirm-modal-title');
const confirmModalMessage = document.getElementById('confirm-modal-message');
const confirmModalCancelBtn = document.getElementById('confirm-modal-cancel-btn');
const confirmModalConfirmBtn = document.getElementById('confirm-modal-confirm-btn');

let currentCartItems = [];
let confirmModalAction = null;

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/* ---------------- ALERT MODAL ---------------- */

function showAlertModal(title, message) {
    if (!alertModal) {
        alert(`${title}: ${message}`);
        return;
    }
    if (alertModalTitle) alertModalTitle.textContent = title;
    if (alertModalMessage) alertModalMessage.textContent = message;
    alertModal.style.display = 'flex';
}

function closeAlertModal() {
    if (alertModal) alertModal.style.display = 'none';
}

if (alertModalOkBtn) {
    alertModalOkBtn.addEventListener('click', closeAlertModal);
}
if (alertModal) {
    alertModal.addEventListener('click', function (event) {
        if (event.target === alertModal) closeAlertModal();
    });
}

/* ---------------- CONFIRM MODAL ---------------- */

function showConfirmModal({ title, message, confirmText }) {
    if (!confirmModal) {
        if (confirm(`${title}\n${message}`)) {
            if (confirmModalAction) confirmModalAction();
        }
        return;
    }
    if (confirmModalTitle) confirmModalTitle.textContent = title;
    if (confirmModalMessage) confirmModalMessage.textContent = message;
    if (confirmModalConfirmBtn) confirmModalConfirmBtn.textContent = confirmText || 'Confirm';
    confirmModal.style.display = 'flex';
}

function closeConfirmModal() {
    if (confirmModal) confirmModal.style.display = 'none';
    confirmModalAction = null;
}

if (confirmModalCancelBtn) {
    confirmModalCancelBtn.addEventListener('click', closeConfirmModal);
}
if (confirmModal) {
    confirmModal.addEventListener('click', function (event) {
        if (event.target === confirmModal) closeConfirmModal();
    });
}
if (confirmModalConfirmBtn) {
    confirmModalConfirmBtn.addEventListener('click', function () {
        const action = confirmModalAction;
        closeConfirmModal();
        if (action) action();
    });
}

/* ---------------- RENDERING & CART ACTIONS ---------------- */

function renderCart(cart) {
    const items = cart.items || [];
    currentCartItems = items;

    if (!cartContent) return;

    if (items.length === 0) {
        cartContent.innerHTML = `
            <div class="rounded-2xl bg-white border border-[#EAF1FB] p-8 text-center shadow-sm">
                <i class="ph-light ph-shopping-cart-simple text-4xl text-[#171E26]/20 mb-3 block"></i>
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Your cart is empty</h3>
                <p class="font-inter text-[13px] text-[#171E26]/45 mt-1 mb-6">Explore available medicines and add them to your cart.</p>
                <a href="/customer/products" class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 hover:opacity-95 transition">
                    Browse Products
                </a>
            </div>
        `;
        return;
    }

    const itemsHtml = items.map(function (item) {
        const product = item.product || {};
        const productName = product.name || item.product_name || 'Medicine Item';
        const price = product.price !== undefined ? product.price : (item.price || 0);
        const lineTotal = item.line_total !== undefined ? item.line_total : (price * item.quantity);

        const rxBadge = product.requires_prescription
            ? `<span class="font-inter text-[10px] font-semibold px-2 py-0.5 rounded-full ml-1.5" style="background:#FFF8EC;color:#8A6116">Rx Required</span>`
            : '';

        return `
            <div class="flex items-center justify-between gap-3 py-3.5 border-b border-[#F3F7FC] last:border-0">
                <div class="min-w-0 flex-1">
                    <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate">${productName}${rxBadge}</p>
                    <p class="font-inter text-[11px] text-[#171E26]/45 mt-0.5">${formatCurrency(price)} each</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button type="button" onclick="updateQuantity(${item.id}, ${item.quantity - 1})" 
                            class="h-7 w-7 rounded-md border border-[#DBEBFB] text-[#171E26]/60 font-inter text-[14px] leading-none hover:bg-[#F7FAFD] transition">−</button>
                    <input type="number" min="1" value="${item.quantity}"
                           onchange="updateQuantity(${item.id}, parseInt(this.value) || 1)"
                           class="w-12 text-center font-inter text-[13px] font-medium text-[#171E26] border border-[#DBEBFB] rounded-md py-1 focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <button type="button" onclick="updateQuantity(${item.id}, ${item.quantity + 1})" 
                            class="h-7 w-7 rounded-md border border-[#DBEBFB] text-[#171E26]/60 font-inter text-[14px] leading-none hover:bg-[#F7FAFD] transition">+</button>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 min-w-[100px] justify-end">
                    <p class="font-inter text-[13px] font-semibold text-[#171E26]">${formatCurrency(lineTotal)}</p>
                    <button type="button" onclick="removeItem(${item.id})" 
                            class="h-6 w-6 flex items-center justify-center rounded text-[#9C3A32] hover:bg-[#FDEDEC] text-[16px] leading-none transition" title="Remove item">×</button>
                </div>
            </div>
        `;
    }).join('');

    const total = cart.total !== undefined ? cart.total : items.reduce((acc, curr) => acc + (curr.line_total || (curr.price * curr.quantity)), 0);

    cartContent.innerHTML = `
        <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 md:p-6 pb-[140px] md:pb-[140px] shadow-sm">
            <div class="flex items-center justify-between mb-2 pb-2 border-b border-[#EAF1FB]">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Cart Items</h3>
                <button type="button" onclick="clearCart()" class="font-inter text-[12.5px] font-semibold text-red-500 hover:text-red-600 transition">Clear Cart</button>
            </div>
            <div class="mt-2">${itemsHtml}</div>
        </div>

        <div class="fixed bottom-[76px] left-1/2 -translate-x-1/2 w-full max-w-[480px] sm:max-w-2xl md:max-w-4xl lg:max-w-6xl px-4 sm:px-6 lg:px-10 z-30">
            <div class="bg-white border border-[#EAF1FB] rounded-t-2xl shadow-[0_-6px_20px_rgba(23,30,38,0.08)] px-4 md:px-6 py-4">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-inter text-[14px] font-medium text-[#171E26]/70">Total</span>
                    <span class="font-manrope font-extrabold text-[22px] text-[#2775E4]">${formatCurrency(total)}</span>
                </div>
                <a href="/customer/checkout" class="block text-center py-3.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[15px] shadow-md shadow-[#2775E4]/20 hover:opacity-95 transition">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    `;
}

async function loadCart() {
    if (typeof CustomerAuth !== 'undefined' && !CustomerAuth.requireAuth()) return;

    if (cartSkeleton) cartSkeleton.style.display = 'block';
    if (cartContent) cartContent.style.display = 'none';
    if (cartError) cartError.style.display = 'none';

    try {
        const response = await CustomerApi.get('/customer/cart');
        const cart = response.data || response.cart || response;
        const items = cart.items || [];

        renderCart(cart);

        // Hide skeleton and reveal content
        if (cartSkeleton) cartSkeleton.style.display = 'none';
        if (cartContent) cartContent.style.display = 'block';

        // Notify badge listener in layout
        window.dispatchEvent(new CustomEvent('cart-updated', {
            detail: { count: items.length }
        }));
    } catch (error) {
        if (cartSkeleton) cartSkeleton.style.display = 'none';
        if (cartError) {
            cartError.textContent = error.message || 'Unable to load cart.';
            cartError.style.display = 'block';
        }
    }
}

async function updateQuantity(itemId, newQuantity) {
    if (newQuantity <= 0) {
        removeItem(itemId);
        return;
    }

    const item = currentCartItems.find(function(i) { return i.id === itemId; });
    if (item && item.product && typeof item.product.stock_quantity !== 'undefined' && newQuantity > item.product.stock_quantity) {
        showAlertModal('Limited stock', `Only ${item.product.stock_quantity} unit(s) of ${item.product.name} are available.`);
        loadCart();
        return;
    }

    try {
        await CustomerApi.patch(`/customer/cart/items/${itemId}`, { quantity: newQuantity });
        loadCart();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            showAlertModal('Unable to update cart', Object.values(error.data.errors).flat().join(', '));
        } else {
            showAlertModal('Unable to update cart', error.message || 'Unable to update cart.');
        }
    }
}

async function removeItem(itemId) {
    try {
        await CustomerApi.delete(`/customer/cart/items/${itemId}`);
        loadCart();
    } catch (error) {
        showAlertModal('Unable to remove item', error.message || 'Unable to remove item.');
    }
}

function clearCart() {
    if (currentCartItems.length === 0) return;

    confirmModalAction = async function () {
        try {
            // FIXED: was trying DELETE /customer/cart first (a bulk-clear
            // endpoint that doesn't exist in the API) before falling back
            // to this per-item loop. Goes straight to the real endpoint now.
            await Promise.all(currentCartItems.map(function(item) {
                return CustomerApi.delete(`/customer/cart/items/${item.id}`);
            }));
            loadCart();
        } catch (error) {
            showAlertModal('Unable to clear cart', error.message || 'Unable to clear cart.');
        }
    };

    showConfirmModal({
        title: 'Clear Cart?',
        message: 'Are you sure you want to remove all items from your cart?',
        confirmText: 'Clear Cart'
    });
}

// Assign to global window scope for inline DOM events
window.loadCart = loadCart;
window.updateQuantity = updateQuantity;
window.removeItem = removeItem;
window.clearCart = clearCart;

document.addEventListener('DOMContentLoaded', function () {
    loadCart();
});