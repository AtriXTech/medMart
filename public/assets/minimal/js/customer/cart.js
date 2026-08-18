const cartError = document.getElementById('cart-error');
const cartLoading = document.getElementById('cart-loading');
const cartContent = document.getElementById('cart-content');

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

function renderCart(cart) {
    const items = cart.items || [];
    
    if (items.length === 0) {
        cartContent.innerHTML = `
            <div class="card">
                <div class="empty-state">Your cart is empty</div>
                <a href="/customer/products" class="btn btn-primary btn-block" style="margin-top: 16px;">Browse Products</a>
            </div>
        `;
        return;
    }
    
    let itemsHtml = '';
    items.forEach(function(item) {
        itemsHtml += `
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid var(--border);">
                <div style="flex: 1;">
                    <strong>${item.product.name}</strong>
                    ${item.product.requires_prescription 
                        ? '<span class="badge badge-warning" style="font-size: 10px; margin-left: 6px;">Rx Required</span>'
                        : ''
                    }
                    <div style="font-size: 12px; color: var(--text-muted);">${formatCurrency(item.product.price)} each</div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button class="btn btn-secondary" onclick="updateQuantity(${item.id}, ${item.quantity - 1})" style="padding: 4px 8px;">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn btn-secondary" onclick="updateQuantity(${item.id}, ${item.quantity + 1})" style="padding: 4px 8px;">+</button>
                </div>
                <div style="min-width: 80px; text-align: right;">
                    <strong>${formatCurrency(item.line_total)}</strong>
                    <button class="btn btn-danger" onclick="removeItem(${item.id})" style="padding: 2px 6px; margin-left: 8px;">×</button>
                </div>
            </div>
        `;
    });
    
    cartContent.innerHTML = `
        <div class="card">
            <p class="section-title">Cart Items</p>
            ${itemsHtml}
            <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                    <strong>Total:</strong>
                    <strong style="font-size: 20px; color: var(--primary);">${formatCurrency(cart.total)}</strong>
                </div>
                <a href="/customer/checkout" class="btn btn-primary btn-block">Proceed to Checkout</a>
            </div>
        </div>
    `;
}

async function loadCart() {
    if (!CustomerAuth.requireAuth()) return;
    
    cartLoading.style.display = 'block';
    cartContent.style.display = 'none';
    cartError.style.display = 'none';
    
    try {
        const cart = await CustomerApi.get('/customer/cart');
        renderCart(cart);
        cartLoading.style.display = 'none';
        cartContent.style.display = 'block';
    } catch (error) {
        cartLoading.style.display = 'none';
        cartError.textContent = error.message || 'Unable to load cart.';
        cartError.style.display = 'block';
    }
}

window.updateQuantity = async function(itemId, newQuantity) {
    if (newQuantity <= 0) {
        removeItem(itemId);
        return;
    }
    
    try {
        await CustomerApi.patch(`/customer/cart/items/${itemId}`, { quantity: newQuantity });
        loadCart();
    } catch (error) {
        if (error.status === 422 && error.data && error.data.errors) {
            alert(Object.values(error.data.errors).flat().join('\n'));
        } else {
            alert(error.message || 'Unable to update cart.');
        }
    }
};

window.removeItem = async function(itemId) {
    try {
        await CustomerApi.delete(`/customer/cart/items/${itemId}`);
        loadCart();
    } catch (error) {
        alert(error.message || 'Unable to remove item.');
    }
};

loadCart();